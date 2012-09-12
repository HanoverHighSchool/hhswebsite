<?php
$username = $_GET["username"];
$password = $_GET["password"];
$email = $_GET["email"];
$first = $_GET["first"];
$last = $_GET["last"];
$access = $_GET["access"];

$key = $_GET["passkey"];

if ($key != "a7044860") {
	header("Location: nope.php");
}

?>