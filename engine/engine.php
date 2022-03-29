<?php
	$MySqlI = new mysqli("localhost", "root", "", "onstore");

	class Page
	{
		function __construct($pagename, $type, $category, $subcategory, $id)
		{
			$this -> PageName = $pagename;
			$this -> Type = $type;
			$this -> Category = $category;
			$this -> SubCategory = $subcategory;
			$this -> Id = $id;
		}

		public function ConstructPage()
		{
			$pagecontent = file_get_contents("templates/page.html");

			switch ($this -> Type) {
				case "product":
					require("Engine/classes/Product.php");
				break;
				case "subcategory":
					require("Engine/classes/Prods.php");
				break;
				default:		

				break;
			}

			$sections = $this -> createSection($this -> Type);
			$pagecontent=str_replace("[THEMAINCONTENT]", $sections, $pagecontent);
			$pagecontent=str_replace("[MAINNAVCONTENT]", $this -> createMainNav($pagecontent), $pagecontent);

			$this -> PageContent = $pagecontent;
		}

		public function createMainNav($pagecontent)
		{
			global $MySqlI;
			

			$categoriesquery = $MySqlI -> query("SELECT * FROM `categories`;");
			while($category = $categoriesquery -> fetch_assoc())
			{
				$mainnavcontent .= "<li><a href=\"\">".$category["russian"]."</a>";

				$subcategoriessqlquery = <<<SQL
					SELECT subcategories.id, subcategories.name, subcategories.russian 
					FROM `categories` 
					INNER JOIN `subcategories`
					ON subcategories.categoryId = categories.id
					WHERE subcategories.categoryId =
SQL;

				$subcategoriesquery = $MySqlI -> query($subcategoriessqlquery . $category["id"]);
				if ($subcategoriesquery -> num_rows>0)
				{
					$mainnavcontent .= "<div>";
					while($subcategory = $subcategoriesquery -> fetch_assoc())
					{
						$mainnavcontent .= "<a href=\"\">".$subcategory["russian"]."</a>";
					}
					$mainnavcontent .="</div>";
				}
				$mainnavcontent .= "</li>";
			}
			
			return $mainnavcontent;
		}

		// create a Product class

		public function createProd()
		{
		
		}

		public function createSection($type, $subtitle = "") // subtitle is for page like home ("hits", "sale", etc)
		{
				$sectionContent = file_get_contents("templates/blocks/section.html");

				$Prods = new Prods($this -> SubCategory);
				$Prods -> createProdsList();	
				$products = $Prods -> content;

				$sectionContent = str_replace("[CONTENT]", $products, $sectionContent);
				return $sectionContent;
		}
		
	}
?>