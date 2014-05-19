<?php
/*
Plugin Name: Promotions: Facebook Integration
Plugin URI: http://bozuko.com
Description: This plugin improves the UI for Wordpress SEO by Yoast
Version: 1.0.0
Author: Bozuko
Author URI: http://bozuko.com
License: Proprietary
*/

add_action('promotions/plugins/load', function()
{
  define('PROMOTIONS_FACEBOOK_DIR', dirname(__FILE__));
  define('PROMOTIONS_FACEBOOK_URI', plugins_url('/', __FILE__));
  
  require_once(PROMOTIONS_FACEBOOK_DIR.'/vendor/autoload.php');
  
  Snap_Loader::register( 'PromotionsFacebook', PROMOTIONS_FACEBOOK_DIR . '/lib' );
  Snap::inst('PromotionsFacebook_Plugin');
}, 100);