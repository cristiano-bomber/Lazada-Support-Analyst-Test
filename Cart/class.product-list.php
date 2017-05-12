<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/Config/defination.config.php');
require_once(__ROOT__.'/Product/class.product.php');

class ProductList{
	private $products = array();

	public function addProduct($product, $key = null){
		if($key == null){
			$this->products[] = $product;
		}
		if(isset($this->products[$key])){
			throw new Exception("Product with key $key already in List", 1);	
		}
		else {
			$this->products[$key] = $product;
		}
	}

	public function removeProduct($key){
		if(isset($this->products[$key])){
			unset($this->products[$key]);
		}
		else {
			throw new Exception("Invalid Product with key $key", 1);
			
		}
	}

	public function getProduct($key){
		if(isset($this->products[$key])){
			return $this->products[$key];
		}
		else {
			throw new Exception("Invalid Product with key $key", 1);
		}
	}

	public function getLengthOfList(){
		return count($this->products);
	}

	public function keyExists($key){
		return isset($this->products[$key]);
	}

	public function getAllProducts(){
		return $this->products;
	}

	// Calculate total prices of products in list
	public function getTotalPricesOfProducts(){
		$total = 0;
		foreach ($this->products as $product) {
			$total += $product->get_price() * $product->get_quantity();
		}
		return $total;
	}

	// Calculate total of location shipping fee of products in list
	public function getTotalLowestLocationShippingFeeOfProducts(){
		$total = 0;
		foreach ($this->products as $product) {
			$total += $product->get_lowest_location_shipping_fee();
		}
		return $total;
	}

	// Secion A Condition : If at least one product weight more than 1KG, despite above conditions, 
	//   10% of "Flat Rate" amount will be charged per additional KG per product.
	private function chargeOverWeightForEachProduct($flat_rate = 5){
		$charged_amount = 0;
		foreach ($this->products as $product) {
			// Unit of Weight is Gram(g), 1KG = 1,000g
			if($product->get_weight() > 1000){
				$charged_amount += (__CHARGE_RATE__) * $flat_rate * $product->get_quantity();
			}
		}
		return $charged_amount;
	}

	// Calculate Product Shipping Fee Based On Product's Weight and Following Conditions Of The Section A
	public function calculateProductShippingFeeBasedOnWeight(){
		$flat_rate = __FLAT_RATE__;
		$total_prices_of_products = $this->getTotalPricesOfProducts();
		$charged_over_weight = $this->chargeOverWeightForEachProduct($flat_rate);
		
		// Secion A Condition #1 : If at least one product weight more than 1KG, despite above conditions, 
		//   10% of "Flat Rate" amount will be charged per additional KG per product.
		if($charged_over_weight > 0)
			return ($flat_rate + $charged_over_weight);
		
		// Section A Condition #2 : If the Cart value (total value of products) > $100, the shipping fee is free.
		if($total_prices_of_products > 100)
			return 0;
		// Section A Condition : If Condition #1 & #2 is invalid, shipping fee is Flat Rate = $5.
		return $flat_rate;
	}
}
?>