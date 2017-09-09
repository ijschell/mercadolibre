var app_id = '2513939221863852';
var app_secret = 'Yfz9pboSCtqVvWTXAXKyFhbAcCFQEhmN';
var redirect_url = 'https://www.thecodingbear.com/';
//code segá configurado una única vez
$.ajax({
  url: base+'/mercadolibre.php',
  type: 'post',
  data: {get_code: true},
}).done(function(result){
  alert(result);
  var code = result;
})
var base = './wp-content/plugins/mercadolibre_token';

var token;

var products_list = [];

function get_access_token(app_id, app_secret, redirect_url, code){

  var object = {};

  $.ajax({
    type : 'POST',
    url : base+'/SDK/examples/get_code.php',
    data: {
      app_id: app_id,
      app_secret: app_secret,
      redirect_url: redirect_url,
      code: code
    },
    success : function(result){
      if(result == 'invalid_grant'){
        //solicito refresh_token
        object = {'status' : 'Error'};
      }else {
        var obj = $.parseJSON(result);
        object = {
          'status' : 'Success',
          'access_soken' : obj['access_soken'],
          'user_id' : obj['user_id'],
          'refresh_token' : obj['refresh_token']
        };
        //guardo refresh_token
        $.ajax({
          url: base+'/mercadolibre.php',
          type: 'post',
          data: {get_refresh: true, refresh_token: object.refresh_token},
        }).done(function(result){
          alert(result);
        })
        //$.cookie('refresh_token', object.refresh_token, { expires : 1 });
      }
    }
  }).done(function(){
    if(object.status == 'Success'){
      token = object;
      //obtengo mis datos
      get_info_me(token.access_soken);
    }else {
      console.log(object);
      //get_refresh_token(app_id, app_secret, redirect_url, code);
    }

  });

}

function get_refresh_token(app_id, app_secret, redirect_url, code){
  var refresh_token = $.cookie('refresh_token');
  $.ajax({
    type: 'POST',
    url: 'https://api.mercadolibre.com/oauth/token?grant_type=refresh_token&client_id='+app_id+'&client_secret='+app_secret+'&refresh_token='+refresh_token,
    success: function(result){
      //guardo nuevo refresh_token
      $.cookie('refresh_token', result.refresh_token, { expires : 1 });
    }
  }).done(function(result){
    token = result.access_token;
    get_info_me(token);
  })

}

function get_info_me(access_token){

  $.ajax({
    url: 'https://api.mercadolibre.com/users/me?access_token='+access_token,
    method: 'GET',
    success: function(result){
      console.log(access_token);
      var id_client = result.id;
      get_items_list(id_client, token);
    }
  })

}

function get_items_list(id_client, access_token){
  $.ajax({
    url: 'https://api.mercadolibre.com/users/'+id_client+'/items/search?access_token='+access_token,
    method: 'GET',
    success: function(result){
      console.log(result);
      //guardo array de id productos
      $.each(result.results, function(k, v){
        products_list.push(v);
      });
    }
  })
}

//loader
function print_results(result, list){
  if(result == false){
    $('#productosMercadoLibre').html('No hay productos :(');
  }else if(result == true){
    $('#productosMercadoLibre').html('HAY PRODUCTOS!');
    get_products(list);
  }else if (result == 'printFull') {
    $.each(list, function(v, k){
      //producto en si
      console.log(k);
      var title = k.title;
      var currency = k.currency_id;
      var price = k.base_price;
      var thumbnail = k.thumbnail;
      var template = '<div class="prod_ml"><h2>'+title+'</h2><p class="price">'+currency+' '+price+'</p><img src="'+thumbnail+'" /></div>';
      $('#productosMercadoLibre').append(template);
    });
  }
}

function get_products(list){
  console.log(list.length);
  var lista;
  for (var i = 0; i < list.length; i++) {
    if(i == 0){
      lista = list[i];
    }else {
      lista = lista + ',' + list[i];
    }
  }

  if(lista != undefined){
    $.ajax({
      url: 'https://api.mercadolibre.com/items?ids='+lista,
      method: 'GET',
      success: function(result){
        //tengo los productos, los imprimo
        print_results('printFull', result);
      }
    })
  }

}

$(document).ready(function(){
  //solicito token e items
  alert(code);
  get_access_token(app_id, app_secret, redirect_url, code);

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
