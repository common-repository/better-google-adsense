<?php
/**
 * Plugin Name:       Better Google Adsense
 * Description:       Display Google Adsense Auto Ads. Easily customise where ads are displayed.
 * Version:           1.0.4
 * Author:            Morgan Hvidt
 * Author URI:        https://morganhvidt.com/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 $better_google_adsense_section_start = get_option('better_google_adsense_section_start');
 // Start Adsense if enabled and exists
 if (  $better_google_adsense_section_start && in_array('on' , $better_google_adsense_section_start)) {
  include( plugin_dir_path( __FILE__ ) . 'includes/functions.php');
}

  		add_action( 'admin_menu', 'better_google_adsense_create_settings'  );
  		add_action( 'admin_init', 'better_google_adsense_setup_sections', 1 ); // piorty 1 keep on top
  		add_action( 'admin_init', 'better_google_adsense_setup_fields' );


  	 function better_google_adsense_create_settings() {
  		$page_title = 'Better Google Adsense';
  		$menu_title = 'Better Adsense';
  		$capability = 'manage_options';
  		$slug = 'better-google-adsense';
  		$callback =  'better_google_adsense_page_callback';
  		$icon = 'dashicons-chart-area';
  		$position = 100;
  		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
  	}
  	 function better_google_adsense_page_callback() { ?>
  		<div class="wrap">
  			<h1>Better Google Adsense</h1>
  			<?php settings_errors(); ?>
          	<?php do_action( 'better_google_adsense_above_page'); ?>
       <div class="better-goolge-adsense-main-section">
       <form method="POST" action="options.php">
  				<?php
            do_action( 'better_google_adsense_above_settings');
  				  settings_fields( 'better_google_adsense_callback' );
  					do_settings_sections( 'better_google_adsense_callback' );
            do_action( 'better_google_adsense_below_settings');
           ?>

           <?php
  					submit_button();
  				?>
  			</form>
       </div>
       <?php
       do_action( 'better_google_adsense_below_page'); ?>
     </div>
 <?php
   }
  	 function better_google_adsense_setup_sections() {
     add_settings_section( 'better_google_adsense_section_start', 'Enable Ads', array(), 'better_google_adsense_callback' );
     add_settings_section( 'better_google_adsense_section_code', 'Google Adsense', array(), 'better_google_adsense_callback' );
     add_settings_section( 'better_google_adsense_section_display', 'Display on', array(), 'better_google_adsense_callback' );

  	}
  	 function better_google_adsense_setup_fields() {

      // Get all post categories and save as ID
       $output_categories = array();

       $categories = get_categories();
         foreach($categories as $category) {
            $output_categories[$category->cat_ID] = $category->name;
       }

      // Get all pages as , save ID
      $output_pages = array();

       $page_ids = get_all_page_ids();

       foreach($page_ids as $page) {

           $output_pages[$page] = get_the_title($page);
       }


  		$fields = array(

 			array(
 				'label' => 'Enable (Master Switch)',
 				'id' => 'better_google_adsense_section_start',
 				'type' => 'checkbox',
 				'section' => 'better_google_adsense_section_start',
 				'options' => array(
 					'on' => 'Start Adsense',
 				),
 			),

       array(
         'label' => 'Your Adsense Code',
         'id' => 'better_google_adsense_code',
         'type' => 'textarea',
         'placeholder' => '<script>The whole script tag with your Code</script>',
         'section' => 'better_google_adsense_section_code',
       ),
       array(
         'label' => 'Display on entire site',
         'id' => 'better_google_adsense_entire_site',
         'type' => 'checkbox',
         'section' => 'better_google_adsense_section_display',
         'options' => array(
           'on' => 'Entire site',
         ),
       ),

       array(
         'label' => 'Display on Post Categories ',
         'id' => 'better_google_adsense_post_categories',
         'desc' => 'Display on default post types',
         'type' => 'multiselect',
         'section' => 'better_google_adsense_section_display',
         'options' => $output_categories,
       ),
       array(
         'label' => 'Display on pages ',
         'id' => 'better_google_adsense_pages',
         'desc' => 'Display on pages',
         'type' => 'multiselect',
         'section' => 'better_google_adsense_section_display',
         'options' => $output_pages,
       ),
       array(
         'label' => 'Display on Custom Post Type',
         'id' => 'better_google_adsense_custom_post_type',
         'type' => 'textfield',
         'desc' => 'Slug, name (singular) or ID of your custom post type',
         'placeholder' => 'download',
         'section' => 'better_google_adsense_section_display',
       ),
  		);
  		foreach( $fields as $field ){
  			add_settings_field( $field['id'], $field['label'], 'better_google_adsense_field_callback', 'better_google_adsense_callback', $field['section'], $field );
  			register_setting( 'better_google_adsense_callback', $field['id'] );
  		}

  	}
  function better_google_adsense_field_callback( $field ) {

    	$value = get_option( $field['id'] );

 		switch ( $field['type'] ) {
 				case 'radio':
        case 'select':
        case 'textarea':
printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>',
  $field['id'],
  $field['placeholder'],
  $value
  );
  break;
  case 'multiselect':
        if( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {
          $attr = '';
          $options = '';
          foreach( $field['options'] as $key => $label ) {
            // Fix for PHP notice array_search
            if (is_array($value) || is_object($value)) {
                $selectcheck = selected(true, in_array($key, $value), false);
              }
              else {
                  $selectcheck = selected($value, $key, false);
              }
            $options.= sprintf('<option value="%s" %s>%s</option>',
              $key,
               $selectcheck,
              $label
            );
          }
          if( $field['type'] === 'multiselect' ){
            $attr = ' multiple="multiple" ';
          }
          printf( '<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>',
            $field['id'],
            $attr,
            $options
          );
        }
        break;
 				case 'checkbox':
 					if( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {
 						$options_markup = '';
 						$iterator = 0;

 						foreach( $field['options'] as $key => $label ) {
               // checks if the value is in array. it was throwing a error because second value of array search was a string, when no checkbox was selected.
               if (is_array($value) || is_object($value)) {
                   $checkboxcheck = checked($value[array_search($key, $value, true)], $key, false);
                 }
                 else {
                   $checkboxcheck = checked($value, $key, false);
                 }
 							$iterator++;
 							$options_markup.= sprintf('<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>',
 							$field['id'],
 							$field['type'],
 							$key,

 							$checkboxcheck,
 							$label,
 							$iterator
 							);
 							}
 							printf( '<fieldset>%s</fieldset>',
 							$options_markup
 							);
 					}
 					break;
 			default:
 				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
 					$field['id'],
 					$field['type'],
 					$field['placeholder'],
 					$value
 				);
 		}
  if (isset($field['desc'])) {
 			printf( '<p class="description">%s </p>', $field['desc'] );
    }
 }
    function better_google_adsense_enqueue_scripts(){

  	if(isset( $_GET['page'] ) && $_GET['page'] == 'better-google-adsense' ) {

       wp_enqueue_style('better-google-adsense-css', plugins_url('includes/better-google-adsense.css',__FILE__ ));
       wp_enqueue_script('better-google-adsense-js', plugins_url('includes/better-google-adsense.js',__FILE__ ));
     }
     else {
        wp_dequeue_script( 'better-google-adsense-js' );
     }
    }

    add_action('admin_enqueue_scripts', 'better_google_adsense_enqueue_scripts');

     function better_google_adsense_admin_box_one() {
       echo '<div class="better-goolge-adsense-upgrade-pro">
       <h2>Getting started</h2>
       <ol>
       <li>Sign into your <a href="https://www.google.com.au/adsense/start/" target="_blank">Adsense account</a></li>
       <li>Set up a new Auto Ad under <strong>My ads</strong>.</li>
       <li>Copy and paste your <strong>Auto Ad</strong> code into the plugin.</li>
       <li>Wait, Your ads may time a little time to show up.</li>
       </ol>
       <p> <a href="https://morganhvidt.com/adsense-auto-ads-on-wordpress/" target="_blank">Full Guide here </a></p>
       <p>Leave a <a href="https://profiles.wordpress.org/morganhvidt#content-plugins" target="_blank">review</a></p>
       </div>';
     }
     add_action('better_google_adsense_below_page', 'better_google_adsense_admin_box_one');

     function better_google_adsense_top_info() {

       echo "<p>
       Better Google Adsense is developed to work for <a href='https://adsense.googleblog.com/2018/02/introducing-adsense-auto-ads.html' target='_blank'>Auto Ads by Google Adsense. </a>
       </p>";
     }
     add_action('better_google_adsense_above_page', 'better_google_adsense_top_info');


register_uninstall_hook(__FILE__, 'better_google_adsense_uninstall');

function better_google_adsense_uninstall() {
  delete_option('better_google_adsense_section_start');
  delete_option('better_google_adsense_code');
  delete_option('better_google_adsense_post_categories');
  delete_option('better_google_adsense_pages');
  delete_option('better_google_adsense_custom_post_type');
}
