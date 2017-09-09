<?php
//guardo app id
if(isset($_POST['app_id'])){
  if(strlen($_POST['app_id']) > 0){
    $data = $_POST['app_id'];
    if(get_option('app_id') !== false){
      update_option('app_id', $data);
    }else {
      add_option('app_id', $data, '', 'yes');
    }
  }
}

//guardo code
if(isset($_POST['code_mercadolibre'])){
  if(strlen($_POST['code_mercadolibre']) > 0){
    $data = $_POST['code_mercadolibre'];
    if(get_option('code_mercadolibre') !== false){
      update_option('code_mercadolibre', $data);
    }else {
      add_option('code_mercadolibre', $data, '', 'yes');
    }
  }
}

//guardo redirect
if(isset($_POST['redirect_url'])){
  if(strlen($_POST['redirect_url']) > 0){
    $data = $_POST['redirect_url'];
    if(get_option('redirect_url') !== false){
      update_option('redirect_url', $data);
    }else {
      add_option('redirect_url', $data, '', 'yes');
    }
  }
}

//guardo secret code
if(isset($_POST['secret_code'])){
  if(strlen($_POST['secret_code']) > 0){
    $data = $_POST['secret_code'];
    if(get_option('secret_code') !== false){
      update_option('secret_code', $data);
    }else {
      add_option('secret_code', $data, '', 'yes');
    }
  }
}



?>
<link rel="stylesheet" href="../wp-content/plugins/testplugin/css/admin.css">
<div id="admin">
  <div class="wrapper">
    <p>Solicite su Code con la Api de ML en <a href="https://auth.mercadolibre.com.uy/authorization?response_type=code&client_id=<?php echo get_option('app_id'); ?>">https://auth.mercadolibre.com.uy/authorization?response_type=code&client_id=<?php echo get_option('app_id'); ?></a></p>
    <p>Imprimir este shortcode en la apertura del body: 'echo do_shortcode("[mercadolibre]")'</p>
    <p>Imprima el siguiente código donde quieras que aparezcan los productos:</p>
    <p>
      <textarea rows="8" cols="80">
        <div id="productosMercadoLibre">
          <img src="<?php echo get_site_url()?>/wp-content/plugins/mercadolibre_token/loading.gif" class="loader" alt="">
        </div>
      </textarea>
    </p>
    <form action="" method="post">
      App ID:<br />
      <input type="text" name="app_id" value="">
      <?php echo get_option('app_id');?>
      <br />
      Secret Code:<br />
      <input type="text" name="secret_code" value="">
      <?php echo get_option('secret_code');?>
      <br />
      Guardar Code<br />
      <input type="text" name="code_mercadolibre" value="">
      <?php echo get_option('code_mercadolibre');?>
      <br />
      Guardar Redirect<br />
      <input type="text" name="redirect_url" value="">
      <?php echo get_option('redirect_url');?>
      <br />
      <input type="submit" name="" value="Guardar">
      <br />
      token: <?php echo get_option('access_token')?>
      <br />
      refresh: <?php echo get_option('refresh_token')?>
    </form>
  </div>
</div>
