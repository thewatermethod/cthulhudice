/**
 * this is a little function that will add the affiliate code to all drivethrurpg.com links
 * it will also add a link source so you can track where the link came from
 */

document.addEventListener("DOMContentLoaded", function () {
  const { affilate_id, link_source } = dtrpg_config;

  // get all links with drivethrurpg.com domain
  var links = document.querySelectorAll('a[href*="drivethrurpg.com"]');
  // loop through each link and add affiliate code
  for (var i = 0; i < links.length; i++) {
    // get link
    var link = links[i];
    // convert link to URL object
    const url = new URL(link.href);
    // get URLSearchParams object
    const params = new URLSearchParams(url.search);
    // add affiliate code
    params.set("affiliate_id", affilate_id);
    // add link source
    params.set("source", link_source);
    // update URL search params
    url.search = params.toString();
    // update link href
    link.href = url.toString();
  }
});
