<?php
	class Prods // page of prods (small products) 
	{
		$Content = ""; // all prods
		function __contruct($subcategory)
		{	
			$this -> SubCategory = $subcategory;
		}
		function prod($rusname, $price) // russian name from table
		{
			$prod = file_get_contents("templates/prod.html");

			$prod = str_replace("[RUSNAME]", $rusname, $prod);
			$prod = str_replace("[PRICE]", $price, $prod);

			return $prod;
		}
		function createProdsList($subcategory) // create a list of prods
		{
			global $MySqlI;
			$sqlGetProds = $MySqlI -> query("SELECT * FROM `" . $subcategory . "` LIMIT 20;");
			while ($row = $sqlGetProds -> fetch_assoc())
			{
				$this -> content .= prod($row["russian"],$row["price"]);
			}
		}
	}
?>