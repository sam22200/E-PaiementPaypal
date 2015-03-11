<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php
// Run a select query to get my letest 6 items
// Connect to the MySQL database
include "storescripts/connect_to_mysql.php";
$dynamicList = "";
$sql = mysql_query("SELECT * FROM products ORDER BY date_added DESC LIMIT 3");
$productCount = mysql_num_rows($sql); // count the output amount
if ($productCount > 0) {
	while($row = mysql_fetch_array($sql)){
             $id = $row["id"];
			 $product_name = $row["product_name"];
			 $price = $row["price"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			 $dynamicList .= '<div class="col-sm-4 col-lg-4 col-md-4">
        <div class="thumbnail">
          <img src="inventory_images/'. $id .'.jpg" alt="' . $product_name . '">
          <div class="caption">
            <h4 class="pull-right"><span class="badge" style = "font-size: 18pt;">€'. $price . '</span></h4>
            <h4><a href="product.php?id=' . $id . '">'. $product_name . '</a>
            </h4>
            <p>Voir les détails de cet article <a target="_blank" href="product.php?id='. $id . '">ici</a>.</p>
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
                    </div>
                </div>';
    }
} else {
	$dynamicList = "We have no products listed in our store yet";
}


mysql_close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//FR" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Magasin Accueil</title>
    <link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />
    <!-- Bootstrap Core CSS -->
    <link href="style/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="style/shop-homepage.css" rel="stylesheet">
  </head>

  <body>
    <div id="mainWrapper">
      <?php include_once("template_header.php");?>
      <div class="container">

        <div class="row">

          <div class="col-md-3">
            <p class="lead">FORFAIT PRAXEDO</p>
            <div class="list-group">
              <a href="#" class="list-group-item">FORFAIT 1 MOIS</a>
              <a href="#" class="list-group-item">FORFAIT 2 MOIS</a>
              <a href="#" class="list-group-item">FORFAIT 3 MOIS</a>
            </div>
          </div>

          <div class="col-md-9">

            <div class="row carousel-holder col-md-offset-2">
              <div class="col-md-9">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="item active">
                            <img class="slide-image" src="inventory_images/logo.jpg" alt="">
                        </div>
                        <div class="item">
                            <img class="slide-image" src="inventory_images/geobox.jpg" alt="">
                        </div>
                        <div class="item">
                            <img class="slide-image" src="inventory_images/Solution_multi.png" alt="">
                        </div>
                    </div>
                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                  </div>
                </div>
              </div>

              <div class="row">
                <?php echo $dynamicList ?>
              </div>
            </div>
          </div>
        </div>
      <?php include_once("template_footer.php");?>
    </div>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>