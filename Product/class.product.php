<?php
	/*
		Product : 
			Id : Integer
			Name : Text
			Price : Double
			Weight : Double (1 unit is Gram(g))
			Quantity : Integer
			shipping_fee : Double, calculating based on Flat Rate, and Weight, Price of Product
			lowest_location_shipping_fee : Double, calculating based on Location/PostalCode of Product's Supplier where on
	*/
	class Product{
		var $id;
		var $name;
		var $price;
		var $weight;
		var $quantity;
		var $shipping_fee;
		var $lowest_location_shipping_fee;
		var $source_location;

		function set_id($new_id){
			$this->id = $new_id;
		}
		function get_id(){
			return $this->id;
		}

		function set_name($new_name){
			$this->name = $new_name;
		}
		function get_name(){
			return $this->name;
		}

		function set_price($new_price){
			$this->price = $new_price;
		}
		function get_price(){
			return $this->price;
		}

		function set_weight($new_weight){
			$this->weight = $new_weight;
		}
		function get_weight(){
			return $this->weight;
		}

		function set_quantity($new_quantity){
			$this->quantity = $new_quantity;
		}
		function get_quantity(){
			return $this->quantity;
		}

		function set_shipping_fee($new_shipping_fee){
			$this->shipping_fee = $new_shipping_fee;
		}
		function get_shipping_fee(){
			return $this->shipping_fee;
		}

		function set_lowest_location_shipping_fee($new_lowest_location_shipping_fee){
			$this->lowest_location_shipping_fee = $new_lowest_location_shipping_fee;
		}
		function get_lowest_location_shipping_fee(){
			return $this->lowest_location_shipping_fee;
		}

		function set_source_location($new_source_location){
			$this->source_location = $new_source_location;
		}
		function get_source_location(){
			return $this->source_location;
		}
	}
?>