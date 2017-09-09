var products_list = [];

//inicializo
jQuery(document).ready(function(){
  //preparo tags para imprimir data
  var access_token = jQuery('.data-accesstoken').attr('data-accesstoken');
  console.log(access_token);
  get_info_me(access_token);

  var myVar = setInterval(function(){ myTimer() }, 1000);

  function myTimer() {
    if(products_list.length == 0){
      print_results(false);
    }else if (products_list.length > 0) {
      print_results(true, products_list);
      myStopFunction();
    }
  }

  function myStopFunction() {
      clearInterval(myVar);
  }

});


function get_info_me(access_token){
  jQuery.ajax({
    url: 'https://api.mercadolibre.com/users/me?access_token='+access_token,
    method: 'GET',
    success: function(result){
      var id_client = result.id;
      get_items_list(id_client, access_token);
    }
  })
};

function get_items_list(id_client, access_token){
  jQuery.ajax({
    url: 'https://api.mercadolibre.com/users/'+id_client+'/items/search?access_token='+access_token,
    method: 'GET',
    success: function(result){
      //guardo array de id productos
      jQuery.each(result.results, function(k, v){
        products_list.push(v);
      });
    }
  })
};

function print_results(result, list){
  if(result == false){
    //jQuery('#productosMercadoLibre').html('No hay productos :(');
  }else if(result == true){
    jQuery('#productosMercadoLibre .loader').remove();
    get_products(list);
  }else if (result == 'printFull') {
    jQuery.each(list, function(v, k){
      //producto en si
      if(k.status == 'active'){
        console.log(k);
        var title = k.title;
        var permalink = k.permalink;
        var currency = k.currency_id;
        var price = k.base_price;
        var thumbnail = k.thumbnail;
        var template = '<div class="prod_ml"><h2><a href="'+permalink+'" target="_blank">'+title+'</a></h2><p class="price"><a href="'+permalink+'" target="_blank">'+currency+' '+price+'</a></p><a href="'+permalink+'" target="_blank"><img src="'+thumbnail+'" /></a></div>';
        jQuery('#productosMercadoLibre').append(template);
      }
    });
  }
}

function get_products(list){
  var lista;
  for (var i = 0; i < list.length; i++) {
    if(i == 0){
      lista = list[i];
    }else {
      lista = lista + ',' + list[i];
    }
  }

  if(lista != undefined){
    jQuery.ajax({
      url: 'https://api.mercadolibre.com/items?ids='+lista,
      method: 'GET',
      success: function(result){
        //tengo los productos, los imprimo
        print_results('printFull', result);
      }
    })
  }

}
