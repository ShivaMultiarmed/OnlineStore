<?php
	$MySqlI = new mysqli("localhost", "root", "", "onstore");

	class Page
	{
		// MainContent is content in the center
		// PageContent is whole html code
		function __construct($type, $category = "", $subcategory = "", $id = "")
		{
			global $MySqlI;
			$this -> Type = $type;
			$this -> Category = $category;
			$this -> SubCategory = $subcategory;
			// russian is title in russian 
			// also used as subtitle in pages without Product catalog
			$this -> Russian = $MySqlI -> query("SELECT `russian` FROM `subcategories` WHERE `name` = \"" . $subcategory . "\";") -> fetch_assoc() ["russian"];
			$this -> Id = $id;
			
			$this -> MainContent = new Content($type, $category, $subcategory, $id, $this -> Russian);
		}

		public function ConstructPage()
		{
			$pagecontent = file_get_contents("templates/page.html");

			$this -> MainContent -> ConstructContent();
			$this -> HtmlMainContent = $this -> MainContent -> Content;
			
			$pagecontent=str_replace("[CONTENTTYPE]", $this -> Type . "Content", $pagecontent);
			$pagecontent=str_replace("[HTMLMAINCONTENT]", $this -> HtmlMainContent, $pagecontent);
			$pagecontent=str_replace("[MAINNAVCONTENT]", $this -> createMainNav($pagecontent), $pagecontent);

			$this -> PageContent = $pagecontent;
		}

		public function createMainNav($pagecontent)
		{
			global $MySqlI;
			

			$categoriesquery = $MySqlI -> query("SELECT * FROM `categories`;");
			while($category = $categoriesquery -> fetch_assoc())
			{
				$mainnavcontent .= "<li><a href=\"?category=" . $category["id"] . "\">".$category["russian"]."</a>";

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
						$mainnavcontent .= "<a href=\"/?category=" . $category["name"] . "&subcategory=" . $subcategory["name"] . "\">".$subcategory["russian"]."</a>";
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

		
		
	}
?>