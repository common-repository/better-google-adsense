<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Start Adsense if enable

function better_google_adsense_front_end(){

$code = get_option('better_google_adsense_code');

$post_categories = get_option('better_google_adsense_post_categories' );

$post_type_custom = get_option('better_google_adsense_custom_post_type' );

$pages = get_option('better_google_adsense_pages' );

$entire_site = get_option('better_google_adsense_entire_site' );

if ( ( $post_categories && in_category( $post_categories) ) || ( $post_type_custom && is_singular( $post_type_custom ) ) || ($pages && is_page( $pages ) ) ){
  echo $code;
} else if ( $entire_site && in_array('on' , $entire_site)) {
  echo $code;
}
else {
 //.. nothing
}

}
add_action ('wp_footer', 'better_google_adsense_front_end');
