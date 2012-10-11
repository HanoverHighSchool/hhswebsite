<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Hanover High School - Create Page</title>

<!-- Everything in the <head> except for <title> : Also contains the navbar-->
<?php
	require_once("opendb.php");

   $status = loginStatus();

   if (!($status & $LoginStatusCanEdit)) {
      header("Location: nope.php");
      die();
   }

   $pageName = $connection->escape_string($_POST["name"]);
   $pageTitle = $connection->escape_string($_POST["title"]);
   $pageName = strtolower($pageName);
	require("head.php");

	if ($_POST["name"] != "" && !(file_exists($_POST["name"].".php"))) {

	   	$query = "CREATE TABLE IF NOT EXISTS `$pageName-index` (`field` INT NOT NULL, `value` VARCHAR(1024) NOT NULL, `type` VARCHAR(64) NOT NULL DEFAULT 'text' AFTER `value`, `color` VARCHAR(64) NOT NULL DEFAULT '000000', `lastUpdate` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `lastUser` VARCHAR(64) NOT NULL, `lastIP` VARCHAR(32) NOT NULL) ENGINE = MYISAM;";

	   if ($connection->query($query)) {
	      $temp = file_get_contents("blank-template.php");
	      $temp = str_replace("@title@", $_POST["title"], $temp);
	      $filename = strToLower($_POST['name']);
	      file_put_contents(".php", $temp);
	   } else {
	      header("Location: nope.php");
	      die();
	   }

	   $query = "INSERT INTO `$pageName-index` SELECT * FROM `template-index`;";
	   $connection->query($query);

	   $query = "UPDATE `$pageName-index` SET `value` = '$title' WHERE `field` = '1'";
	   $connection->query($query);

      $user = $connection->escape_string($_COOKIE["username"]);
      $ip = $_SERVER["REMOTE_ADDR"];

	   $query = "UPDATE `$pageName-index` SET `lastUser` = '$user'";
	   $connection->query($query);
	   $query = "UPDATE `$pageName-index` SET `lastUpdate` = CURRENT_TIMESTAMP";
	   $connection->query($query);
	   $query = "UPDATE `$pageName-index` SET `lastIP` = '$ip'";
	   $connection->query($query);

	   header("Location: $pageName.php");
	}
?>

<!-- Start main container -->
<div id="container" class="container">

   <!-- Main hero unit -->
   <div class="hero-unit" id="hero-unit">
   	<?php
	if ($_POST["name"] != "" && !(file_exists($_POST["name"].".php"))) {
		//code to add pages is in the head for redirection purposes.
	} else {
	   if (file_exists($pageName.".php")) {
	      echo "The page <b>".$pageName.".php</b> already exists!<br><br>";
	   }
	   ?>
	   Use all lowercase for the file name!<br><br>
	   <form method="POST" action="create.php">
	   <label for="name">File Name:</label><input type="text" name="name" maxlength="64"><br>
	   <label for="title">Page Title:</label><input type="text" name="title" maxlength="1024"><br>
	   <input type="submit" class="btn" value="Create Page">
	   </form>
	   <?php
	}
	?>


   </div>
<?php require("foot.php"); ?>
