
<?php 
/*Rotas relacionadas site*/

use \Hcode\Page;
use \Hcode\Model\Products;
use \Hcode\Model\Category;
use \Hcode\Model\Cart;

$app->get('/', function() {

	$products = Products::listAll();

	$page = new Page();

	$page->setTpl("index",array(
		"products" => Products::checkList($products)
	));

});


/*Listar as categorias no html de forma dinâmica*/
$app->get("/categories/:idcategory", function($idcategory){

	$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	$category = new Category();

	$category->get((int)$idcategory);

	$pagination = $category->getProductsPage($page);

	$pages = [];

	for ($i=1; $i <= $pagination['pages']; $i++) { 
		array_push($pages, [
			'link'=>'/categories/'.$category->getidcategory().'?page='.$i,
			'page'=>$i
		]);
	}

	$page = new Page();

	$page->setTpl("category", [
		'category'=>$category->getValues(),
		'products'=>$pagination["data"],
		'pages'=>$pages
	]);
});

$app->get("/products/:desurl",function($desurl){

	$product = new Products();

	$product->getFromUrl($desurl);

	$page = new Page();

	$page->setTpl("product-detail",array(
		"product" =>$product->getValues(),
		"categoires" => $product->getCategories()
	));
});

$app->get("/cart",function(){

	$cart = Cart::getFromSession();

	$page = new Page();

	$page->setTpl("cart");

});

?>