<?php
	class Prods // page of prods (small products) 
	{
		function __construct($category, $subcategory)
		{	
			$this -> Category = $category;
			$this -> SubCategory = $subcategory;
		}
		function prod($id, $name, $price) // russian name from table
		{
			$prod = file_get_contents("templates/blocks/prod.html");

			$prod = str_replace("[NAME]", $name, $prod);
			$prod = str_replace("[PRICE]", $price, $prod);
			$prod = str_replace("[CATEGORY]", $this -> Category, $prod); // for image
			$prod = str_replace("[SUBCATEGORY]", $this -> SubCategory, $prod); // also for it
			$prod = str_replace("[ID]", $id, $prod); // also for it

			return $prod;
		}
		function createProdsList() // create a list of prods
		{
			global $MySqlI;
		
			$sqlGetProds = $MySqlI -> query("SELECT * FROM `" . $this -> SubCategory . "` ORDER BY `id` DESC  LIMIT 20;");
			while ($row = $sqlGetProds -> fetch_assoc())
			{
				$prods .= $this -> prod($row["id"],$row["name"],$row["price"]);
			}
			$this -> Content = $prods;
		}
	}
?>