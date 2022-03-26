<?php
	$MySqlI = new mysqli("localhost", "root", "", "onstore");

	class Page
	{
		function __construct($pagename, $type, $category, $subcategory, $id)
		{
			$this -> PageName = $pagename;
			$this -> Type = $type;
			$this -> Category = $category;
			$this -> subCategory = $subcategory;
			$this -> Id = $id;
		}

		public function ConstructPage()
		{
			$pagecontent = file_get_contents("templates/page.html");

			$sections = $this -> createSection();
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

		public function createSection()
		{
				$sectionContent = file_get_contents("templates/blocks/section.html");

				$products ="";

				$sectionContent = str_replace("[PRODUCTS]", $products, $sectionContent);
				return $sectionContent;
		}
		
	}
?>