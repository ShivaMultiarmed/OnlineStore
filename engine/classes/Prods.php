<?php
	class Prods // page of prods (small products) 
	{
		function __construct($subcategory)
		{	
			$this -> SubCategory = $subcategory;
		}
		function prod($rusname, $price) // russian name from table
		{
			$prod = file_get_contents("templates/blocks/prod.html");

			$prod = str_replace("[RUSNAME]", $rusname, $prod);
			$prod = str_replace("[PRICE]", $price, $prod);

			return $prod;
		}
		function createProdsList() // create a list of prods
		{
			global $MySqlI;
			$sqlGetProds = $MySqlI -> query("SELECT * FROM `" . $this -> SubCategory . "` LIMIT 20;");
			// substitude mixers with subcategory variable
			while ($row = $sqlGetProds -> fetch_assoc())
			{
				$prods .= $this -> prod($row["russian"],$row["price"]);
			}
			$this -> Content = $prods;
		}
	}
?>