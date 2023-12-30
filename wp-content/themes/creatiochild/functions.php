<?php    

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

    function creatiochild_wp_footer() {
        $env = creatiochild_read_env();

        if ( $env['ENVIRONMENT'] === 'development' ) {
            creatiochild_print_browser_sync();
        }   
    }

    add_action( 'wp_footer', 'creatiochild_wp_footer' );