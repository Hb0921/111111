<?php
session_start();




 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include 'DBController.php';
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Meta, title -->
		<meta charset="utf-8">		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Bootstrap, a sleek, intuitive, and powerful mobile first front-end framework for faster and easier web development.">
		<meta name="keywords" content="HTML, CSS, JS, JavaScript, framework, bootstrap, front-end, frontend, web development">
		<meta name="author" content="WowThemesNet">	
		<title></title>
		
		<!-- Google Fonts -->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:100,200,300,400,400italic,500,600,700,700,800,900italic&amp;subset=latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese' rel='stylesheet' type='text/css'/>
		<link href='http://fonts.googleapis.com/css?family=ABeeZee:100,200,300,400,400italic,500,600,700,700,800,900italic&amp;subset=latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese' rel='stylesheet' type='text/css'/>
		
		<!-- CSS -->
		<link rel='stylesheet' href='css/bootstrap.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/shortcodes.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/font-awesome.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/animate.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='style.css' type='text/css' media='all'/>
		<link rel='stylesheet' href='css/skins/orange.css' type='text/css' media='all'/>
		
		<!-- Jquery -->
		<script type='text/javascript' src='js/jquery.js'></script>
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="js/html5shiv.min.js"></script>
		  <script src="js/respond.min.js"></script>
		<![endif]-->

		<!-- Favicons -->
		<link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-precomposed.png">
		<link rel="icon" href="img/favicon.ico">
	</head>
<body>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="default.css" rel="stylesheet" type="text/css" media="all" />
<link href="style.css" rel="stylesheet" type="text/css" media="all" />

<link href="style2.css" rel="stylesheet" type="text/css">

<link rel="stylesheet"
    href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


<!-- BEGIN MENU
================================================== -->	
<div id="navigation" class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.html">
			<img src="img/logo.png" alt="">
			</a>
		</div>
		<div class="navbar-collapse collapse pull-right" id="mainMenu">
			<ul id="menu-main-menu" class="nav navbar-nav">
				<li><a href="index1.html">Home</a></li>
				<li><a href="aboutus.html">About us</a></li>
				<li><a href="careers.html">Careers</a></li>
				<li><a href="orderline.php">Order line</a></li>
				<li><a href="contactus.html">Contact us</a></li>
				<li><a href="register.php">Register</a></li>
				
				
				</ul>
				</li>
			</ul>
		</div>
	</div>
</div>



<div class="wrapper">
<a href="logout.php" class="open-button pull-right"><i class="fa fa-plus"></i> Sign Out of Your Account</a>
<div id="shopping-cart">
<div class="txt-heading">Shopping Cart</div>

<a id="btnEmpty" href="orderonline.php?action=empty">Empty Cart</a>
<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	
<table class="tbl-cart" cellpadding="10" cellspacing="1">
<tbody>
<tr>
<th style="text-align:left;">Name</th>
<th style="text-align:left;">Code</th>n
<th style="text-align:right;" width="5%">Quantity</th>
<th style="text-align:right;" width="10%">Unit Price</th>
<th style="text-align:right;" width="10%">Price</th>
<th style="text-align:center;" width="5%">Remove</th>
</tr>	
<?php		
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		?>
				<tr>
				<td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
				<td><?php echo $item["code"]; ?></td>
				<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
				<td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
				<td style="text-align:center;"><a href="orderonline.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
				</tr>
				<?php
				$total_quantity += $item["quantity"];
				$total_price += ($item["price"]*$item["quantity"]);
		}
		?>

<tr>
<td colspan="2" align="right">Total:</td>
<td align="right"><?php echo $total_quantity; ?></td>
<td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
<td></td>
</tr>
</tbody>
</table>		
  <?php
} else {
?>
<div class="no-records">Your Cart is Empty</div>
<?php 
}
?>
</div>

<div id="product-grid">
	<div class="txt-heading">Products</div>
	<?php
	$product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
	
		<div class="image">
			
			<div class="image"><img src="<?php echo $product_array[$key]["image"]; ?>"/>
			<button class="quick_look" data-id="<?php echo $product_array[$key]["id"] ; ?>">Quick Look</button>
			</div>
			<form method="post" action="orderonline.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
			<div class="product-tile-footer">
			<div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
			<div class="product-price"><?php echo "$".$product_array[$key]["price"]; ?></div>
			<div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
			</div>
			
			</form>
			
		</div>
	<?php
		}
	}
	
	?>
</div>
</div>	

    <div id="demo-modal"></div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
    $(".quick_look").on("click", function() {
        var product_id = $(this).data("id");
        	var options = {
        			modal: true,
        			height: 'auto',
        			width:'70%'
        		};
        	$('#demo-modal').load('get-product-info.php?id='+product_id).dialog(options).dialog('open');
    });

    $(document).ready(function() {
        	$(".image").hover(function() {
                $(this).children(".quick_look").show();
            },function() {
            	   $(this).children(".quick_look").hide();
            });
    });
    </script>


    <!-- BEGIN FOOTER
================================================== -->
<section class="nowidgetbottom">
    <p id="back-top">
        <a href="#top"><span><i class="fa fa-chevron-up"></i></span></a>
    </p>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <img src="img/logowhite.png" alt="">
                <ul class="social-icons" style="margin-top:7px;margin-bottom:5px;">
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                    <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                </ul>
                 <a target="_blank" href="http://www.mobanwang.com" title="网页模板">网页模板</a>
            </div>
        </div>
    </div>
    </section>
    <!-- END FOOTER
    ================================================== -->
        
    
    <script type='text/javascript' src='js/bootstrap.js'></script>
    <script type='text/javascript' src='js/easing.js'></script>
    <script type='text/javascript' src='js/smoothscroll.js'></script>
    <script type='text/javascript' src='js/wow.js'></script>
    <script type='text/javascript' src='js/appear.js'></script>
    <script type='text/javascript' src='js/fitvids.js'></script>
    <script type='text/javascript' src='js/common.js'></script>
    <script type='text/javascript' src='js/flexslider.js'></script>
    <script type='text/javascript' src='js/countto.js'></script>
    <script type='text/javascript' src='js/jquery.cycle.all.min.js'></script>
    <script type='text/javascript' src='js/carousel.js'></script>
    <script type='text/javascript' src='js/carousel-anything.js'></script>
    <script type='text/javascript' src='js/carousel-testimonials.js'></script>
    <script type='text/javascript' src='js/isotope.js'></script>
    <script type='text/javascript' src='js/carousel-blog.js'></script>
    <script type='text/javascript' src='js/parallax.js'></script>
    <script type='text/javascript' src='js/validate.js'></script>
    </body>
    </html>