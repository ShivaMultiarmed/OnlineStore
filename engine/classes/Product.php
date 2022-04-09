<?php
	class Product
	{
		function __construct($category, $subcategory, $id){
			$this -> Category = $category;
			$this -> SubCategory = $subcategory;
			$this -> Id = $id;
		}

		function completeProduct()
		{
			global $MySqlI;

			$info = $MySqlI -> query("SELECT * FROM `" . $this -> SubCategory . "` WHERE `id` = " . $this -> Id . ";") -> fetch_assoc();
			$this -> Name = $info["name"];
			$this -> Price = $info["price"];
		}
	}
?>