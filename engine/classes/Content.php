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
					$this -> Content = $this -> createCategoryCatalog();
				break;
				case "subcatalog":
					$this -> Content = $this -> createSubCatalog();
				break;
				case "home":
					
				break;
				case "product":
					$this -> Content = $this -> createProduct();
				break;
			}
		}

		public function createCategoryCatalog()
		{
			global $MySqlI;
			$categoryId = $MySqlI -> query("SELECT `id` FROM `categories` WHERE `name` = \"" . $this -> Category . "\";") -> fetch_assoc()["id"];
			$listSubCategories = $MySqlI -> query("SELECT `subcategories`.`russian`, `subcategories`.name as name, subcategories.id FROM `subcategories` INNER JOIN `categories` ON `subcategories`.`categoryId` = `categories`.`id` WHERE `categories`.id = " . $categoryId . ";");
			while ($cursubcategory = $listSubCategories->fetch_assoc())
			{
				$WholeCatalog .= $this -> createSubCatalog($this -> Category, $cursubcategory["name"]);
			}
			return $WholeCatalog;
		}

		public function createSubCatalog($category, $subcategory) 
		{
				$subCatalogContent = file_get_contents("templates/blocks/section.html");

				$Prods = new Prods($category,$subcategory);
				$Prods -> createProdsList();
				$products = $Prods -> Content;
				
				$subCatalogContent = str_replace("[SECTIONTITLE]", $Prods -> Russian, $subCatalogContent);
				$subCatalogContent = str_replace("[SECTIONCONTENT]", $products, $subCatalogContent);
				return $subCatalogContent;
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