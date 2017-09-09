<?php
/*
Plugin Name: Mercadolibre Token
Description: Plugin para configurar Access Token de Mercadolibre.
Version: 1.0
Author: Jonathan Schell | Thecodingbear
Author URI: http://www.thecodingbear.com
License: GLP2
*/

require 'SDK/Meli/meli.php';

if(!isset($_POST['get_refresh'])){
  //add menu
  add_action('admin_menu', 'addMenu');

  function addMenu(){
    $page_title = 'Mercadolibre Token';
    $menu_title = 'Mercadolibre Token';
    $capability = 'manage_options';
    $menu_slug = 'mercadolibre_token/admin.php';

    add_menu_page($page_title, $menu_title, $capability, $menu_slug);
  }
}

$app_id = get_option('app_id');
$code = get_option('code_mercadolibre');
$redirect_url = get_option('redirect_url');
$secret_code = get_option('secret_code');

//obtengo access
get_token($app_id, $code, $redirect_url, $secret_code);

//guardo refresh
function get_token($app_id, $code, $redirect_url, $secret_code){
  $meli = new Meli($app_id, $secret_code);

  if($code):
    $oAuth = $meli->authorize($code, $redirect_url);
    $_SESSION['access_token'] = $oAuth['body']->access_token;
    if($oAuth['body']->error == 'invalid_grant'){
      get_refresh_token($app_id, $secret_code, $redirect_url, $code);
    }else {
      save_access_token($oAuth['body']->access_token);
      save_refresh_token($oAuth['body']->refresh_token);
    }
  else:
    return 'Login with MercadoLibre';
  endif;
}

function get_refresh_token($app_id, $app_secret, $redirect_url, $code){
  $meli = new Meli($app_id, $app_secret);
  $refresh_token = get_option('refresh_token');
  $url = 'https://api.mercadolibre.com/oauth/token?grant_type=refresh_token&client_id='.$app_id.'&client_secret='.$app_secret.'&refresh_token='.$refresh_token;
  $oAuth = $meli->post($url);
  save_access_token($oAuth['body']->access_token);
  save_refresh_token($oAuth['body']->refresh_token);
}

function save_refresh_token($refresh_token){
  //guardo refresh token
  if(get_option('refresh_token') !== false){
    update_option('refresh_token', $refresh_token);
  }else {
    add_option('refresh_token', $refresh_token, '', 'yes');
  }
}

function save_access_token($access_token){
  if(get_option('access_token') !== false){
    update_option('access_token', $access_token);
  }else {
    add_option('access_token', $access_token, '', 'yes');
  }
}


//client side registro script
// Register Script

function mercadolibre_scripts() {
  $plugins = get_site_url() . '/wp-content/plugins/mercadolibre_token';
	wp_register_script( 'mercadolibre-script', $plugins .'/mercadolibre_script.js', array( 'jquery' ), '1.1', true );
	wp_enqueue_script( 'mercadolibre-script' );

}
add_action( 'wp_enqueue_scripts', 'mercadolibre_scripts' );

//shortcode
function short(){
  return '<div class="data-accesstoken" data-accesstoken="'.get_option('access_token').'" style="display: none"></div>';
}
add_shortcode('mercadolibre', 'short');
?>
