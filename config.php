<?php
if ($_SERVER["REQUEST_URI"] == "/config.php") {
   header("Location: nope.php");
   die();
}

$mysql_user = "hhswebsite";
$mysql_pass = "517R3ae9qN4K";
$mysql_host = "205.178.146.107";
$mysql_data = "hhsdb";

?>