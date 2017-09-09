<?php
require '../Meli/meli.php';

$meli = new Meli('5331417057420749', 'vaddhc1eKq0QSfvMGpFTLXPBcROD4WiI');
/*
$params = array();

$result = $meli->get('/sites/MLU', $params);



echo '<pre>';
print_r($result);
echo '</pre>';
*/
if($_GET['code']):
  $oAuth = $meli->authorize($_GET['code'], 'https://www.thecodingbear.com/');
  $_SESSION['access_token'] = $oAuth['body']->access_token;
  var_dump($oAuth);
else:
  echo 'Login with MercadoLibre';
endif;
