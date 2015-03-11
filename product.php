<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php
// Check to see the URL variable is set and that it exists in the database
if (isset($_GET['id'])) {
	// Connect to the MySQL database
    include "storescripts/connect_to_mysql.php";
	$id = preg_replace('#[^0-9]#i', '', $_GET['id']);
	// Use this var to check to see if this ID exists, if yes then get the product
	// details, if no then exit this script and give message why
	$sql = mysql_query("SELECT * FROM products WHERE id='$id' LIMIT 1");
	$productCount = mysql_num_rows($sql); // count the output amount
    if ($productCount > 0) {
		// get all the product details
		while($row = mysql_fetch_array($sql)){
			 $product_name = $row["product_name"];
			 $price = $row["price"];
			 $details = $row["details"];
			 $category = $row["category"];
			 $subcategory = $row["subcategory"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
         }

	} else {
		echo "That item does not exist.";
	    exit();
	}

} else {
	echo "Data to render this page is missing.";
	exit();
}
mysql_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $product_name; ?></title>
    <link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />
    <!-- Bootstrap Core CSS -->
    <link href="style/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="style/shop-homepage.css" rel="stylesheet">
  </head>

  <body>
    <div id="mainWrapper">

      <?php include_once("template_header.php");?>

      <div id="pageContent">
        <div class="container">
          <div class="row">

              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 lg-offset-3 md-offset-3 sm-offset-3 xs-offset-3">
                    <div class="thumbnail">
                        <img src="inventory_images/<?php echo $id ?>.jpg" alt="<?php echo $product_name ?>">
                        <a href="inventory_images/<?php echo $id; ?>.jpg">Voir image Taille réelle</a></td>
                        <div class="caption">
                            <h4 class="pull-right"></span><span class="badge" style = "font-size: 18pt;">€<?php echo $price ?></span></h4>
                            </span><span class="badge" style = "font-size: 18pt;"><?php echo "$category" . " - " . "$subcategory"; ?></span>
                            <br />
                            <br />
                            <h4><a href="product.php?id=<?php echo $id ?>"><?php echo $details ?></a>
                            </h4>
                            <p>Voir les détails de cet article <a target="_blank" href="product.php?id=<?php echo $id ?>">ici</a>.</p>
                        </div>
                        <div class="ratings">
                            <p class="pull-right">15 reviews</p>
                            <p>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                            </p>
                        </div>
                        <form id="form1" name="form1" method="post" action="cart.php">
                          <input type="hidden" name="pid" id="pid" value="<?php echo $id; ?>" />
                          <input class="btn btn-primary" type="submit" name="button" id="button" value="Ajouter au Panier" />
                        </form>
                        </td>
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