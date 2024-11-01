<?php
/*
Plugin Name: WPHobby Ajax Search for WooCommerce
Plugin URI: http://wphobby.com
Description: Generate Woocommerce Ajax Search.
Version: 1.0.0
Author: wphobby
Author URI: https://wphobby.com/downloads/wphobby-ajax-search-for-woocommerce/
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit;
} // Exit if accessed directly

// Load plugin text domian
load_plugin_textdomain( 'wphobby-woo-ajax-search', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

$wp_upload_dir = wp_upload_dir();
// Set constants
define('WHWAS_DIR', plugin_dir_path(__FILE__));
define('WHWAS_URL', plugin_dir_url(__FILE__));
define('WHWAS_OPTIONS', 'whwas_general_data');
define('WHWAS_VERSION', '1.0.0');


if( ! function_exists( 'whwas_install_woocommerce_admin_notice' ) ) {
   /**
    * Display an admin notice if woocommerce is deactivated
    *
    * @since 1.0.0
    * @return void
    * @use admin_notices hooks
    */
   function whwas_install_woocommerce_admin_notice() { ?>
      <div class="error">
         <p><?php esc_html_e( 'WooCommerce Ajax Search is enabled but not effective. It requires WooCommerce in order to work.', 'wphobby-woo-ajax-search' ); ?></p>
      </div>
      <?php
   }
}

if( ! function_exists( 'whwas_install' ) ){
   function whwas_install() {

      if ( ! function_exists( 'WC' ) ) {
         add_action( 'admin_notices', 'whwas_install_woocommerce_admin_notice' );
      }else{
         // Include files
         require_once('includes/whwas_init.php');
         require_once('includes/whwas_element.php');
         include_once('includes/whwas_search.php' );
         include_once('includes/whwas_widget.php' );
         require_once('includes/whwas_admin.php');

         // Initalize this plugin
         $WHWAS = new WHWAS();
         // When admin active this plugin
         register_activation_hook(__FILE__, array(&$WHWAS, 'activate'));
         // When admin deactive this plugin
         register_deactivation_hook(__FILE__, array(&$WHWAS, 'deactivate'));

         // Run the plugins initialization method
         add_action('init', array(&$WHWAS, 'initialize'));

      }
   }
}

add_action( 'plugins_loaded', 'whwas_install', 11 );
?>