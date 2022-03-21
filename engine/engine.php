<?php
	class Page
	{
		function __construct($pagename, $type, $category, $id)
		{
			$this -> PageName = $pagename;
			$this -> Type = $type;
			$this -> Category = $category;
			$this -> Id = $id;
		}

		public function ConstructPage()
		{
			$pagecontent = file_get_contents("templates/page.html");
			$sections = createSection();
			$pagecontent=str_replace("[SECTIONS]", $sections, $pagecontent);
		}
		public function createSection()
		{
				$sectionContent = get_file_contents("templates/blocks/section.html");



				$sectionContent = str_replace("[PRODUCTS]", $products, $sectionContent);
				return $sectionContent;
		}
	}
?>