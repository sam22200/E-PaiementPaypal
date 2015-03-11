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
$sql = mysql_query("SELECT * FROM products ORDER BY date_added DESC LIMIT 6");
$productCount = mysql_num_rows($sql); // count the output amount
if ($productCount > 0) {
	while($row = mysql_fetch_array($sql)){
             $id = $row["id"];
			 $product_name = $row["product_name"];
			 $price = $row["price"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			 $dynamicList .= '<table width="100%" border="0" cellspacing="0" cellpadding="6">
        <tr>
          <td width="17%" valign="top"><a href="product.php?id=' . $id . '"><img style="border:#666 1px solid;" src="inventory_images/' . $id . '.jpg" alt="' . $product_name . '" width="250" height="150" border="1" /></a></td>
          <td width="83%" valign="top">' . $product_name . '<br />
            €' . $price . '<br />
            <a href="product.php?id=' . $id . '">Voir les détails</a></td>
        </tr>
      </table>';
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
</head>
<body>
<div align="center" id="mainWrapper">
  <?php include_once("template_header.php");?>
  <div id="pageContent">
  <table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td width="32%" valign="top"><h3>SITE DE TEST POUR LE E-PAIEMENT BOOTSTRAP</h3>
      <p>Ce site Web est un site de test pour le paiement en ligne. Il permet de simuler l'achat de forfait Praxedo. Cliquez sur un produit et séléctionnez : Ajoutez au Panier <br />
        <a href="http://www.praxedo.com" target="_blank">www.praxedo.com</a> </p>
      <p>La seule plateforme de paiement pour l'instant est Paypal.<br />
        <br />
        La méthode ici est celle de la redirection</p></td>
    <td width="35%" valign="top"><h3>FORFAITS PRAXEDO</h3>
      <p><?php echo $dynamicList; ?><br />
        </p>
      <p><br />
      </p></td>
    <td width="33%" valign="top"><h3>Note</h3>
      <p>Ce site devra être comptabile avec un mobile et s'adapter au format de l'écran. Pour l'instant les inscriptions en base fonctionnent (avec les IPN) et un système d'administration est en place.</p></td>
  </tr>
</table>

  </div>
  <?php include_once("template_footer.php");?>
</div>
</body>
</html>