<?php
	header("Content-Type: text/html; charset=utf-8");

	require("Engine/Engine.php");

	require("Engine/classes/Content.php");

	require("Engine/classes/Product.php");
	require("Engine/classes/Prods.php");

	$pagename = htmlspecialchars(trim($_GET['page']));
	$type = htmlspecialchars(trim($_GET['type']));
	$category = htmlspecialchars(trim($_GET['category']));
	$subcategory = htmlspecialchars(trim($_GET['subcategory']));
	$id = htmlspecialchars(trim($_GET['id']));

	$Page = new Page($type, $category, $subcategory, $id);
	$Page -> ConstructPage();

	$PageContent = $Page -> PageContent;
	print($PageContent);
?>