<?php
session_start();

// Required includes
require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/php/dc_connect.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/_classes/class.database.php');
$objDB = new DB();
require_once ($_SERVER['DOCUMENT_ROOT'].'/beheer/includes/php/dc_config.php');

// Page specific includes
require_once ($_SERVER['DOCUMENT_ROOT'].'/beheer/includes/php/dc_functions.php');

$_GET = sanitize($_GET);

$strShow 	= strtolower($_GET['show']);

$strSortColumn	= $_GET["sort_column"];
$strSortOrder	= strtoupper($_GET["sort_order"]);
$strQuery		= $_GET["query"];
$strShow		= $_GET["show"];
$intOffset		= (int) $_GET["offset"];
$intLimit		= (int) $_GET["limit"];

$strSort	= ($strSortColumn != '') ? " ORDER BY `" . $strSortColumn . "` ".$strSortOrder : '';
$strWhere	.= ($strQuery != '') ? " AND (au.name LIKE '%".$strQuery."%' OR au.email LIKE '%".$strQuery."%')" : '';
$strLimit	= " LIMIT ".$intOffset.",".$intLimit;

$strSQL 	= 
		"SELECT au.id, au.name, au.email, au.username
		FROM ".DB_PREFIX."admin_users au
		WHERE 1
		".$strWhere . 
		$strSort;
$resultCount = $objDB->sqlExecute($strSQL);
$count 	= $objDB->getNumRows($resultCount);

$strSQL = $strSQL . $strLimit;
$result = $objDB->sqlExecute($strSQL);

$arrJson['totalRows'] = $count;
$i=0;

while ($objUser = $objDB->getObject($result)) {

	$arrJson['details'][$i][]	= $objUser->name;
	$arrJson['details'][$i][]	= $objUser->email;
	$arrJson['details'][$i][]	= '<a href="/beheer/dc_user_manage.php?id='.$objUser->id.'&action=edit"><span class="glyphicon glyphicon-edit"></span></a>';
	$arrJson['details'][$i][]	= '<a href="/beheer/dc_user_manage.php?id='.$objUser->id.'&action=remove"><span class="glyphicon glyphicon-remove"></span></a>';
	
	$i++;
}

header('Content-type: application/json');
echo json_encode($arrJson);

//close db
$objDB->closeDB();
?>