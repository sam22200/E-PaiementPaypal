<!-- <div id="pageHeader"><table width="100%" border="0" cellspacing="0" cellpadding="12">
  <tr>
    <td width="32%"><a href="http://www.pxotestpaiement2.net16.net/index.php"><img src="http://www.pxotestpaiement2.net16.net/style/logo.jpg" alt="Logo" width="400" height="120" border="0" /></a></td>
    <td width="68%" align="right"><a href="http://www.pxotestpaiement2.net16.net/cart.php">Votre Panier</a></td>
  </tr>
  <tr>
    <td colspan="2"><a href="http://www.pxotestpaiement2.net16.net/index.php">Accueil</a> &nbsp; &middot; &nbsp; <a href="#">Produits</a> &nbsp; &middot; &nbsp; <a href="#">Aide</a> &nbsp; &middot; &nbsp; <a href="#">Contact</a></td>
    </tr>
  </table>
</div>
 -->
<?php
if(!isset($_SESSION))
    {
        session_start();
    }
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<?php
$countQte = 0;
$BadgeOutput ="";

if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
    $BadgeOutput = "";
} else {
    foreach ($_SESSION["cart_array"] as $each_item) {
        $countQte ++;
    }
    $BadgeOutput = '<span class="badge">' . $countQte . '</span>';
}
?>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">PRAXEDO</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="http://www.pxotestpaiement2.net16.net/index.php">Accueil</a>
                    </li>
                    <li>
                        <a href="http://www.pxotestpaiement2.net16.net/cart.php">Votre Panier <?php echo $BadgeOutput ?></a>
                    </li>
                    <li>
                        <a href="http://www.pxotestpaiement2.net16.net/products.php">Produits</a>
                    </li>
                    <li>
                        <a href="#">Aide</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>