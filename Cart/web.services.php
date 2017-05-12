<?php
	
	/*	Request: CartID, ItemIDs and Location, etc.
		Response format should be something like below.
			"CartID":Cart12334,
			"SFData": [
				{"ItemID":Item1,"Location":PostalCode1,"ShippingFee":0},
				{"ItemID":Item2,"Location":PostalCode2,"ShippingFee":5},
				{"ItemID":Item3,"Location":...,"ShippingFee":...}]
	*/

	
	require_once(dirname(__FILE__).'/class.product-list.php');
	
	# Get the url parameters
	//if(isset($_GET['cartid']) && isset($_GET['location']) && isset($_GET['itemids'])){
	if(isset($_SERVER['QUERY_STRING'])){
		# Parse the variables
		parse_str($_SERVER['QUERY_STRING'], $request);
		$cart_id = strval($request['cartid']);
		$delivery_location = intval($request['location']);
		$item_ids = $request['itemids'];
		$amount_each_ids = $request['amounteachid'];
		# Connect to DB
		$connection = mysql_connect('127.0.0.1', 'root', 'Undecim@=10') or die('Can not connect to the Server');
		mysql_select_db('lazada_test', $connection) or die('Can not select the Database');

		# Select the Product with ids in $item_ids and each lowest location shipping fee
		/*
			SET @DeliveryLocation=710000;
			SET @BoughtProductIds:='1,2,3,4,5,6,7';
			SELECT p.Id, p.Name as ProdName, p.Price as Price, p.Weight as Weight, 
				s.Name as SupName, s.PostalCode, @DeliveryLocation as DeliveryLocation, lsf.ShippingFee
			FROM `Location-Shipping-Fee` lsf
			JOIN `Supplier` s ON s.PostalCode = lsf.SourceLocation
			    JOIN `Supplier-Product` sp ON sp.SupplierId = s.Id 
			    AND FIND_IN_SET(sp.ProductId, @BoughtProductIds)
			    JOIN `Product` p ON p.Id = sp.ProductId
			WHERE lsf.DestinationLocation = @DeliveryLocation 
			AND lsf.ShippingFee = 
			    (SELECT MIN(lsf1.ShippingFee) as ShippingFee
			       FROM `Location-Shipping-Fee` AS lsf1
			       WHERE lsf1.DestinationLocation = @DeliveryLocation
			     	AND lsf1.SourceLocation = s.PostalCode
			       )
			GROUP by p.Id

		*/
		
		$query = 'select p.Id, p.Name as ProdName, p.Price as Price, p.Weight as Weight, p.Quantity as Quantity,
				s.Name as SupName, s.PostalCode, '.$delivery_location.' as DeliveryLocation, lsf.ShippingFee
			FROM `Location-Shipping-Fee` lsf
			JOIN `Supplier` s ON s.PostalCode = lsf.SourceLocation
			    JOIN `Supplier-Product` sp ON sp.SupplierId = s.Id 
			    AND FIND_IN_SET(sp.ProductId, "'.implode(',', $item_ids).'")
			    JOIN `Product` p ON p.Id = sp.ProductId
			WHERE lsf.DestinationLocation = '.$delivery_location.'
			AND lsf.ShippingFee = 
			    (SELECT MIN(lsf1.ShippingFee) as ShippingFee
			       FROM `Location-Shipping-Fee` AS lsf1
			       WHERE lsf1.DestinationLocation = '.$delivery_location.'
			     	AND lsf1.SourceLocation = s.PostalCode
			       )
			GROUP by p.Id ';

		$results = mysql_query($query, $connection) or die('Can not select Products');

		/* create array of the records */
		$products = new ProductList();
		$products_in_cart = array();
		
		if(mysql_num_rows($results)) {
			//while($item = mysql_fetch_assoc($results)) {
			while($item = mysql_fetch_array($results,MYSQL_ASSOC)) {
				//$products_in_cart[] = $item;
				
				$product = new Product();
				$product->set_id($item['Id']);
				$product->set_name($item['ProdName']);
				$product->set_price($item['Price']);
				$product->set_weight($item['Weight']);
				$product->set_lowest_location_shipping_fee($item['ShippingFee']);
				$product->set_quantity($amount_each_ids[$product->get_id() - 1]);
				$product->set_source_location($item['PostalCode']);
				$products->addProduct($product, $product->get_id());
			}
		}

		/* Calculate total Price of Cart */
		$total_origin_products_price = $products->getTotalPricesOfProducts();
		$total_charged_fee_products = $products->calculateProductShippingFeeBasedOnWeight();
		$total_location_shipping_fee = $products->getTotalLowestLocationShippingFeeOfProducts();
		$total_cart_price = $total_origin_products_price + $total_charged_fee_products + $total_location_shipping_fee;
		$products_in_cart = $products->getAllProducts();

		/* Encode all into json*/
		header('Content-type: application/json');
		echo json_encode(array('cart_id'=>$cart_id,'products'=>$products_in_cart, 
			'total_shipping_fee'=>$total_charged_fee_products,'total_cart_price'=>$total_cart_price));

		/* disconnect from the db */
		@mysql_close($connection);
	}
?>