<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Simple Cart</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    
  </head>
  <body>
    <div id="cart-container">
      <h3>My Shopping Cart</h3>
      <div id="sub-products-container">
        
      </div>
      <div id="summary-cart-container"></div>
    </div>
    
    <script type="text/javascript">
      $(document).ready(function(){
        var itemids = [1,2,3,4,5,6,7];
        var amountOfEachItem = [1,1,1,1,1,1,1];
        $.ajax({
          url: 'web.services.php',
          type: 'get',
          data: {'cartid': '112233', 'location': '700000', 
                 'itemids': itemids, 'amounteachid': amountOfEachItem},
          dataType: 'json',
          success: function(data) {
            if(data!=null) {
              
              var content = '';
              
              content += '<div class="product-header-container">';
              content += '  <div class="product-header-info"></div>';
              content += '  <div class="product-header-price">ITEM PRICE</div>';
              content += '  <div class="product-header-quantity">QUANTITY</div>';
              content += '</div>';
              $.each(data["products"],function(i, obj){
                content += '<div id="product-item-'+ obj.id +'" class="product-info-container">';
                content += '  <div class="product-info">';
                content += '    <div class="product-name">' + obj.name + '</div>';
                content += '    <div class="product-location"> Delivered From: ';
                content += '      <div id="product-delivery-from-' + obj.id + '" class="product-delivery-from">' + obj.source_location + '</div>';
                content += '    </div>';
                content += '  </div>'
                content += '  <div class="product-info-price">';
                content += '    <div class="product-price">Price: ' + '<b>' + obj.price + '</b>' + '</div>';
                content += '  </div>';
                content += '  <div class="product-info-quantity">';
                content += '    <div class="product-quantity">';
                content += '      <select id="product-quantity-' + obj.id +'" class="product-quantity-selector" onchange="ChangeProductQuantity()">';
                for (i=1;i<=10;i++){
                    content += '<option val=' + i + '>' + i + '</option>';
                }
                content += '      </select>';
                content += '    </div>';
                content += '  </div>';
                content += '</div>';
              });
              $('#sub-products-container').html(content);

              content = '';
              content += '<div>Order Summary</div>';
              content += '<div class="delivery-address">';
              content += '  <select class="delivery-address-selector" name="delivery-address" onchange="SelectDeliveryAddress()">';
              content += '    <option value="700000">7000000</option>';
              content += '    <option value="710000">7100000</option>';
              content += '    <option value="720000">7200000</option>';
              content += '  </select>';
              content += '</div>';
              content += '<div class="total-shipping-fee">';
              content += '  Total Shipping Fee : <span id="total-shipping-fee">' + data['total_shipping_fee'] +'</span> USD';
              content += '</div>';
              content += '<div class="total-payment-amount">';
              content += '  <b>Total </b><span>(Total payment amount): </span>';
              content += '  <b id="total-cart-payment">' + data['total_cart_price'] + '</b>  <b>USD</b>';
              content += '</div>';
              content += '<div class="checkout-container">';
              content += '  <input id="checkout-cart" type="button" value="checkout" title="checkout" />';
              content += '</div>';
              $('#summary-cart-container').html(content);
            }
          },
          error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
          }
        }); // end ajax call
      });

      function UpdateCart(){
        var deliveryLocation = $('select.delivery-address-selector').val();
        var itemids = [1,2,3,4,5,6,7];
        var amountOfEachItem = [];
        $('select.product-quantity-selector').each(function(){
          var quantity = $(this).val();
          amountOfEachItem.push(quantity);
        });
        $.ajax({
          url: 'web.services.php',
          type: 'get',
          data: {'cartid': '112233', 'location': deliveryLocation, 
                 'itemids': itemids, 'amounteachid': amountOfEachItem},
          dataType: 'json',
          success: function(data) {
            if(data!=null) {
              $('span#total-shipping-fee').html(data['total_shipping_fee']);
              $('b#total-cart-payment').html(data['total_cart_price']);
              $.each(data["products"],function(i, obj){
                $('#product-delivery-from-'+obj.id).html(obj.source_location);
              });
            }
          },
          error: function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
          }
        });
      }

      function SelectDeliveryAddress(){
        UpdateCart();
      }

      function ChangeProductQuantity(){
        UpdateCart();
      }
      
    </script>
  </body>
</html>