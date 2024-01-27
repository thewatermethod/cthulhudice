<?php    
    /**
     * this looks for an environment file in the theme directory and loads it, 
     * parses it, and returns an array of key/value pairs.
     */

    function creatiochild_read_env() {
        $DEFAULT_ENV = array(
            'ENVIRONMENT' => 'production',
        );
    
        $env = $DEFAULT_ENV;
        $env_file = __DIR__ . '/.env';
        if ( ! file_exists( $env_file ) ) {
            return $env;
        }
        $env_file = file_get_contents( $env_file );
        $env_file = explode( "\n", $env_file );
        foreach ( $env_file as $line ) {
            $line = trim( $line );
            if ( empty( $line ) ) {
                continue;
            }
            $line = explode( '=', $line );
            $env[ $line[0] ] = $line[1];
        }
        return $env;
    }

    /**
     * this is a wrapper for error_log that only writes to the log if the 
     * environment is set to development.
     */
    function write_log( $data ) {
        $env = creatiochild_read_env();

        if ( $env['ENVIRONMENT'] !== 'development' ) {
            return;
        }
        
        if ( true === WP_DEBUG ) {
            if ( is_array( $data ) || is_object( $data ) ) {
                error_log( print_r( $data, true ) );
            } else {
                error_log( $data );
            }
        }
    }

    /***
     * this prints the browser-sync script tag to the page
     * if not in the admin
     */

    function creatiochild_print_browser_sync() {
        if ( is_admin() ) {
            return;
        }     

        echo "<script id='__bs_script__'>//<![CDATA[\n";
        echo "(function() {\n";
        echo '    try {';
        echo "       var script = document.createElement('script');\n";
        echo "       if ('async') {\n";
        echo "         script.async = true;\n";
        echo "        }\n";
        echo "        script.src = 'http://HOST:8890/browser-sync/browser-sync-client.js?v=3.0.2'.replace('HOST', location.hostname);\n";
        echo "        if (document.body) {\n";
        echo "          document.body.appendChild(script);\n";
        echo "        } else if (document.head) {\n";
        echo "          document.head.appendChild(script);\n";
        echo "        }\n";
        echo "      } catch (e) {\n";
        echo "        console.error('Browsersync: could not append script tag', e);\n";
        echo "      }\n";
        echo "    })()\n";
        echo "//]]></script>\n";
    }

    /**
     * fires function @ wp_footer that only prints the browser-sync script
     * tag if the environment is set to development.
     */
    function creatiochild_wp_footer() {
        $env = creatiochild_read_env();

        if ( $env['ENVIRONMENT'] === 'development' ) {
            creatiochild_print_browser_sync();
        }   
    }

    add_action( 'wp_footer', 'creatiochild_wp_footer' );


    /**
     * Enqueue scripts and styles.
     */
    function creatiochild_enqueue_scripts() {
        /**
         * frontend ajax requests.
         */
        wp_enqueue_script( 'dtrpg', get_stylesheet_directory_uri() . '/assets/dtrpg.js', array(), null, true );
        wp_localize_script( 'dtrpg', 'dtrpg_config',
            array(
                'affilate_id' => 463552,
                'link_source' => 'cthulhudice-com'
            )
        );
    }
    add_action( 'wp_enqueue_scripts', 'creatiochild_enqueue_scripts');

