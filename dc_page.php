<?php
session_start();

// Required includes
require_once('includes/php/dc_connect.php');
require_once('_classes/class.database.php');
$objDB = new DB();
require_once('includes/php/dc_config.php');

// Page specific includes
require_once('_classes/class.cart.php');
require_once('includes/php/dc_functions.php');
require_once('libaries/Parsedown/Parsedown.php');

// Start API
require_once('libaries/Api_Inktweb/API.class.php');

// Generate page title & meta tags
$strPageTitle		= getContent('page_title');
$strMetaDescription	= getContent('page_meta_description');

// Start displaying HTML
require_once('includes/php/dc_header.php');

$intId 		= (int)$_GET['id'];

$strSQL 	= "SELECT navTitle, txt FROM ".DB_PREFIX."pages_content WHERE online = 1 AND id = '".$intId."'";
$result		= $objDB->sqlExecute($strSQL);
$objPage 	= $objDB->getObject($result);

$Parsedown 	= new Parsedown();

?>

<div class="row">
<div class="col-lg-8 col-lg-offset-2 markdown">
	<?php
	echo $Parsedown->text($objPage->txt);
	?>
</div><!-- /col -->
</div><!-- /row -->

<?php
require('includes/php/dc_footer.php');
?>