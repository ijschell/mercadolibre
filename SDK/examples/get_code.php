<?php
error_reporting(0);

require '../Meli/meli.php';

$app_id = $_POST['app_id'];
$app_secret = $_POST['app_secret'];
$redirect_url = $_POST['redirect_url'];
$code = $_POST['code'];

$meli = new Meli($app_id, $app_secret);
/*
$params = array();

$result = $meli->get('/sites/MLU', $params);



echo '<pre>';
print_r($result);
echo '</pre>';
*/
if($code):
  $oAuth = $meli->authorize($code, $redirect_url);
  $_SESSION['access_token'] = $oAuth['body']->access_token;
  if($oAuth['body']->error == 'invalid_grant'){
    $object = 'invalid_grant';
  }else {
    $object = '{
      "access_soken" : "'.$oAuth['body']->access_token.'",
      "user_id" : "'.$oAuth['body']->user_id.'",
      "refresh_token" : "'.$oAuth['body']->refresh_token.'"
    }';
  }
  echo $object;
else:
  echo 'Login with MercadoLibre';
endif;
