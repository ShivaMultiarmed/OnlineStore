<?php
	class Content
	{
		// remove NOT ORDINARY
		function __construct($type, $category = "", $subcategory = "", $id = "", $russian = "NOT ORDINARY")
		{
			$this -> Type = $type;
			$this -> Category = $category;
			$this -> SubCategory = $subcategory;
			$this -> Russian = $russian;
			$this -> Id = $id;
		}

		public function ConstructContent()
		{
			switch ($this -> Type)
			{
				case "catalog":
					$this -> Content = $this -> createSection();
				break;
				case "home":
					
				break;
				case "product":
					$this -> Content = $this -> createProduct();
				break;
			}
		}

		public function createCatalog() 
		{
				$catalogContent = file_get_contents("templates/blocks/section.html");

				$Prods = new Prods($this -> Category,$this -> SubCategory);
				$Prods -> createProdsList();
				$products = $Prods -> Content;
				
				$catalogContent = str_replace("[SECTIONTITLE]", $this-> Russian, $catalogContent);
				$catalogContent = str_replace("[SECTIONCONTENT]", $products, $catalogContent);
				return $catalogContent;
		}

		public function createProduct()
		{
			$productContent = file_get_contents("templates/blocks/section.html");


			$product = new Product($this -> Category, $this -> SubCategory, $this -> Id);
			$product -> completeProduct();

			$productContent = str_replace("[SECTIONCONTENT]", file_get_contents("templates/blocks/product.html"), $productContent);
			
			$replacements = [
				"sectiontitle" => $product -> Name,
				"price" => $product -> Price . " rub.",
				"Category" => $this -> Category,
				"SubCategory" => $this -> SubCategory,
				"id" => $this -> Id
			];

			foreach ($replacements as $first => $second)
			{
				$productContent = str_replace("[" . strtoupper($first) . "]", $second, $productContent);
			}
			
			return $productContent;
		}
	}
?>