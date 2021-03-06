<?php
// Required includes
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/php/dc_connect.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_classes/class.database.php');
$objDB = new DB();
require_once ($_SERVER['DOCUMENT_ROOT'].'/beheer/includes/php/dc_config.php');

// Page specific includes
require_once ($_SERVER['DOCUMENT_ROOT'].'/beheer/includes/php/dc_functions.php');

$_POST 	= sanitize($_POST);
$_GET 	= sanitize($_GET);

$intId 		= (int) $_GET['id'];
$intCodeId 	= (int) $_GET['codeId'];
$strAction 	= $_GET['action'];

$strSQL 	= "SELECT dc_c.* FROM ".DB_PREFIX."discountcodes_codes dc_c WHERE dc_c.id = '".$intId."' ";
$result 	= $objDB->sqlExecute($strSQL);
$objCode  	= $objDB->getObject($result);

if ($_POST) {

	$strCode 					= $_POST['code'];
	$intLimit 					= (int) $_POST['limit'];

	if($intId == 0) {
		
		$strSQL = "INSERT INTO ".DB_PREFIX."discountcodes_codes (`codeId`, `code`, `limit`) VALUES (".$intCodeId.", '".$strCode."',  ".$intLimit.")";
		$result = $objDB->sqlExecute($strSQL);
		$intId = $objDB->getInsertedId();
		
	} else {
	
		$strSQL = "UPDATE ".DB_PREFIX."discountcodes_codes
				SET `codeId` = ".$intCodeId.",
				`code` = '".$strCode."',
				`limit` = ".$intLimit."
				WHERE id = '".$intId."' ";
		$result 		= $objDB->sqlExecute($strSQL);
	
	}

	if ($result === true) {
		header('Location: ?id='.$intId.'&action='.$strAction.'&succes='.urlencode('De code is bijgewerkt.'));
	}
	else {
		header('Location: ?id='.$intId.'&action='.$strAction.'&fail='.urlencode('Er is iets fout gegaan.'));
	}
	
}


require('includes/php/dc_header.php');


if (!empty($_GET['succes'])) {
	echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Gelukt!</strong> '.$_GET['succes'].'</div>';
}

if (!empty($_GET['fail'])) {
	echo '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Fout!</strong> '.$_GET['fail'].'</div>';
}

?>

<h1>Codes beheren <small><?php echo $objCode->title; ?></small></h1>

<hr />

<form role="form" class="form-horizontal" method="POST">

	<div class="form-group">
		<label for="code" class="col-sm-2 control-label">Code</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="code" name="code" placeholder="" value="<?php echo $objCode->code; ?>">
		</div><!-- /col -->
	</div><!-- /form group -->

	<div class="form-group">
		<label for="limit" class="col-sm-2 control-label">Aantal keer te gebruiken</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="limit" name="limit" value="<?php echo $objCode->limit; ?>">
			<p class="help-block">0 is oneindig</p>
		</div><!-- /col -->
	</div><!-- /form group -->

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		<button type="submit" class="btn btn-primary">Code aanpassen</button>
		</div><!-- /col -->
	</div><!-- /form group -->	

<hr />

<?php require('includes/php/dc_footer.php'); ?>