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
			$this -> Russian = "Товары";
			$this -> Id = $id;
		}

		public function ConstructPage()
		{
			$pagecontent = file_get_contents("templates/page.html");

			$sections = $this -> createSection($this -> Type, $this -> SubCategory, $this-> Russian);
			$pagecontent=str_replace("[SECTIONS]", $sections, $pagecontent);
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

		public function createSection($type, $subcategory = "", $russian = "") 
		// subcategory is also may be used as subtitle in pages without Product catalog
		// russian is alias for subtitle
		{
				$sectionContent = file_get_contents("templates/blocks/section.html");

				$Prods = new Prods($subcategory);
				$Prods -> createProdsList();
				$products = $Prods -> Content;
				
				$sectionContent = str_replace("[SECTIONTITLE]", "Products", $sectionContent);
				$sectionContent = str_replace("[SECTIONCONTENT]", $products, $sectionContent);
				return $sectionContent;
		}
		
	}
?>