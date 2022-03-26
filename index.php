<?php
	require("engine/engine.php");

	$pagename = htmlspecialchars(trim($_GET['page']));
	$type = htmlspecialchars(trim($_GET['type']));
	$category = htmlspecialchars(trim($_GET['categ']));
	$subcategory = htmlspecialchars(trim($_GET['subcateg']));
	$product = htmlspecialchars(trim($_GET['prod']));

	$Page = new Page($pagename, $type, $category, $subcategory, $product);
	$Page -> ConstructPage();

	$PageContent = $Page -> PageContent;
	print($PageContent);
?>