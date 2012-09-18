<?php

require_once("opendb.php");

$response = strtolower($connection->escape_string($_POST["response"]));

$status = loginStatus();

$refer = strToLower($connection->escape_string(basename($_SERVER["HTTP_REFERER"])));

if (!($status & $LoginStatusCanEdit)) {
	header("Location: nope.php");
	die();
}

$referphp = $refer.".php";

$query = "SELECT * FROM `special_pages` WHERE `name` = '$referphp'";
$result = $connection->query($query);
if ($result->num_rows) die("Cannot delete reserved page \"$referphp\"");

if (file_exists($refer)) {
	unlink($refer);
	echo "Deleted <b>".$refer."</b><br><br>";
} else {
	echo "File <b>".$refer."</b> does not exist.<br><br>";
}

$refer = basename($refer, ".php");

$query = "DROP TABLE `$refer-index`;";
$connection->query($query) or die("The table <b>".$refer."-index</b> does not exist");
echo "Deleted table <b>".$refer."-index</b>";

?>
