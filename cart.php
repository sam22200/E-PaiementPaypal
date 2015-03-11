<?php
session_start(); // Start session first thing in script
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
// Connect to the MySQL database
include "storescripts/connect_to_mysql.php";
?>
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 1 (if user attempts to add something to the cart from the product page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
	$wasFound = false;
	$i = 0;
	// If the cart session variable is not set or cart array is empty
	if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
	    // RUN IF THE CART IS EMPTY OR NOT SET
		$_SESSION["cart_array"] = array(0 => array("item_id" => $pid, "quantity" => 1));
	} else {
		// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
		foreach ($_SESSION["cart_array"] as $each_item) {
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $pid) {
					  // That item is in cart already so let's adjust its quantity using array_splice()
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $each_item['quantity'] + 1)));
					  $wasFound = true;
				  } // close if condition
		      } // close while loop
	       } // close foreach loop
		   if ($wasFound == false) {
			   array_push($_SESSION["cart_array"], array("item_id" => $pid, "quantity" => 1));
		   }
	}
	header("location: cart.php");
    exit();
}
?>
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 2 (if user chooses to empty their shopping cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_GET['cmd']) && $_GET['cmd'] == "emptycart") {
    unset($_SESSION["cart_array"]);
}
?>
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 3 (if user chooses to adjust item quantity)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['item_to_adjust']) && $_POST['item_to_adjust'] != "") {
    // execute some code
	$item_to_adjust = $_POST['item_to_adjust'];
	$quantity = $_POST['quantity'];
	$quantity = preg_replace('#[^0-9]#i', '', $quantity); // filter everything but numbers
	if ($quantity >= 100) { $quantity = 99; }
	if ($quantity < 1) { $quantity = 1; }
	if ($quantity == "") { $quantity = 1; }
	$i = 0;
	foreach ($_SESSION["cart_array"] as $each_item) {
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $item_to_adjust) {
					  // That item is in cart already so let's adjust its quantity using array_splice()
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity)));
				  } // close if condition
		      } // close while loop
	} // close foreach loop
}
?>
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 4 (if user wants to remove an item from cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != "") {
    // Access the array and run code to remove that array index
 	$key_to_remove = $_POST['index_to_remove'];
	if (count($_SESSION["cart_array"]) <= 1) {
		unset($_SESSION["cart_array"]);
	} else {
		unset($_SESSION["cart_array"]["$key_to_remove"]);
		sort($_SESSION["cart_array"]);
	}
}
?>
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 5  (render the cart for the user to view on the page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$cartOutput = "";
$cartOutputB = "";
$cartTotal = "";
$pp_checkout_btn = '';
$product_id_array = '';
if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
    $cartOutput = "<h2 align='center'>Your shopping cart is empty</h2>";
} else {
	// Start PayPal Checkout Button
	$pp_checkout_btn .= '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_cart">
    <input type="hidden" name="upload" value="1">
    <input type="hidden" name="business" value="PXO_SELL_1@seller.fr">';
	// Start the For Each loop
	$i = 0;
    foreach ($_SESSION["cart_array"] as $each_item) {
		$item_id = $each_item['item_id'];
		$sql = mysql_query("SELECT * FROM products WHERE id='$item_id' LIMIT 1");
		while ($row = mysql_fetch_array($sql)) {
			$product_name = $row["product_name"];
			$price = $row["price"];
			$details = $row["details"];
		}
		$pricetotal = $price * $each_item['quantity'];
		$cartTotal = $pricetotal + $cartTotal;
		setlocale(LC_ALL, "fr_FR");
        $pricetotal = money_format("%10.2n", $pricetotal);
		// Dynamic Checkout Btn Assembly
		$x = $i + 1;
		$pp_checkout_btn .= '<input type="hidden" name="item_name_' . $x . '" value="' . $product_name . '">
        <input type="hidden" name="amount_' . $x . '" value="' . $price . '">
        <input type="hidden" name="quantity_' . $x . '" value="' . $each_item['quantity'] . '">  ';
		// Create the product array variable
		$product_id_array .= "$item_id-".$each_item['quantity'].",";
		// Dynamic table row assembly
		$cartOutput .= "<tr>";
		$cartOutput .= '<td><a href="product.php?id=' . $item_id . '">' . $product_name . '</a><br /><img src="inventory_images/' . $item_id . '.jpg" alt="' . $product_name. '" width="125" height="75" border="1" /></td>';
		$cartOutput .= '<td>' . $details . '</td>';
		$cartOutput .= '<td>€' . $price . '</td>';
		$cartOutput .= '<td><form action="cart.php" method="post">
		<input name="quantity" type="text" value="' . $each_item['quantity'] . '" size="1" maxlength="2" />
		<input name="adjustBtn' . $item_id . '" type="submit" value="change" />
		<input name="item_to_adjust" type="hidden" value="' . $item_id . '" />
		</form></td>';
		//$cartOutput .= '<td>' . $each_item['quantity'] . '</td>';
		$cartOutput .= '<td>' . $pricetotal . '</td>';
		$cartOutput .= '<td><form action="cart.php" method="post"><input name="deleteBtn' . $item_id . '" type="submit" value="X" /><input name="index_to_remove" type="hidden" value="' . $i . '" /></form></td>';
		$cartOutput .= '</tr>';

    $cartOutputB .= '<div class="row" style="border:1px solid #ddd;">';
//    $cartOutputB .= '<div class="jumbotron">';
    $cartOutputB .= '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 lg-offset-1">';
    $cartOutputB .= '<img class="img-responsive img-thumbnail" src="inventory_images/'. $item_id . '.jpg" alt="'. $product_name . '>';
    $cartOutputB .= '</div>';
    $cartOutputB .= '</div>';
    $cartOutputB .= '</div>';

		$i++;
    }
	setlocale(LC_ALL, "fr_FR");
    $cartTotal = money_format("%10.2n", $cartTotal);
	$cartTotal = "<div style='font-size:18px; margin-top:12px;' align='right'>Cart Total : ".$cartTotal."</div>";
    // Finish the Paypal Checkout Btn
	$pp_checkout_btn .= '<input type="hidden" name="custom" value="' . $product_id_array . '">
	<input type="hidden" name="notify_url" value="http://www.pxotestpaiement2.net16.net/storescripts/my_ipn.php">
	<input type="hidden" name="return" value="http://www.pxotestpaiement2.net16.net/checkout_complete.html">
	<input type="hidden" name="rm" value="2">
	<input type="hidden" name="cbt" value="Return to The Store">
	<input type="hidden" name="cancel_return" value="http://www.pxotestpaiement2.net16.net/paypal_cancel.html">
	<input type="hidden" name="lc" value="FR">
	<input type="hidden" name="currency_code" value="EUR">
	<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
	</form>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//FR" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Votre panier</title>
    <link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />
    <!-- Bootstrap Core CSS -->
    <link href="style/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>
    <div id="mainWrapper">

      <?php include_once("template_header.php");?>

      <div id="pageContent">
        <div id="cart-attributes">

            <div class="container">

              <div class="row">

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 lg-offset-1  text-center"><span class="label label-default" style = "font-size: 18pt;">PRODUIT</span>
                  </div>
                  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1  text-center"><span class="label label-default" style = "font-size: 18pt;">PRIX</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  text-center"><span class="label label-default" style = "font-size: 18pt;">QUANTITE</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  text-center"><span class="label label-default" style = "font-size: 18pt;">TOTAL</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 lg-offset-1  text-center"><span class="label label-default" style = "font-size: 18pt;">RETIRER</span>
              </div>

            </div>
        </div>

        <div id="cart-elements">
            <div class="container">

              <div class="row" style="border:1px solid #ddd;">

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 lg-offset-1  text-center"><img class="img-responsive img-thumbnail img-center center-block" src="inventory_images/413.jpg">
                  </div>
                  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center"></span><span class="badge" style = "font-size: 18pt;">50€</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  text-center"></span><span class="badge" style = "font-size: 18pt;">2</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center"></span><span class="badge" style = "font-size: 18pt;">100€</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 lg-offset-1  text-center"><button type="button" class="btn btn-danger">X</button>
                  </div>
              </div>

            </div>


            <div class="container">

              <div class="row" style="border:1px solid #ddd;">

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 lg-offset-1  text-center"><img class="img-responsive img-thumbnail" src="inventory_images/414.jpg">
                  </div>
                  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1  text-center"></span><span class="badge" style = "font-size: 18pt;">150€</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  text-center"></span><span class="badge" style = "font-size: 18pt;">2</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  text-center"></span><span class="badge" style = "font-size: 18pt;">300€</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 lg-offset-1  text-center"><button type="button" class="btn btn-danger">X</button>
                  </div>
              </div>

            </div>

            <div class="container">

              <div class="row" style="border:1px solid #ddd;">

                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 lg-offset-1  text-center"><img class="img-responsive img-thumbnail" src="inventory_images/415.jpg">
                  </div>
                  <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1  text-center"></span><span class="badge" style = "font-size: 18pt;">150€</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  text-center"></span><span class="badge" style = "font-size: 18pt;">1</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  text-center"></span><span class="badge" style = "font-size: 18pt;">150€</span>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 lg-offset-1  text-center"><button type="button" class="btn btn-danger">X</button>
                  </div>
              </div>

            </div>
        </div>

      </div>

      <?php include_once("template_footer.php");?>
      <!-- jQuery -->
      <script src="js/jquery.js"></script>

      <!-- Bootstrap Core JavaScript -->
      <script src="js/bootstrap.min.js"></script>
    </div>
  </body>

</html>