<?php
	define('__ROOT__', dirname(dirname(__FILE__)));
	require_once(__ROOT__.'/Product/class.product.php');
	require_once(dirname(__FILE__).'/class.product-list.php');
	class Cart {
		var $cart_id;
		ProductList $products;

		public function set_cart_id($new_cart_id){
			$this->cart_id = $new_cart_id;
		}
		public function get_cart_id(){
			return $this->cart_id;
		}

		public function set_products($new_products){
			$this->products = $new_products;
		}
		public function get_products(){
			return $this->products;
		}
	}
?>