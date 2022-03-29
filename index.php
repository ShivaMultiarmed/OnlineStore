<?php
	require("Engine/Engine.php");

	$pagename = htmlspecialchars(trim($_GET['page']));
	$type = htmlspecialchars(trim($_GET['type']));
	$category = htmlspecialchars(trim($_GET['category']));
	$subcategory = htmlspecialchars(trim($_GET['subcategory']));
	$product = htmlspecialchars(trim($_GET['prod']));

	$Page = new Page($pagename, $type, $category, $subcategory, $product);
	$Page -> ConstructPage();

	$PageContent = $Page -> PageContent;
	print($PageContent);
?>