<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Hanover High School - Delete Page</title>

<!-- Everything in the <head> except for <title> : Also contains the navbar-->
<?php 
	require_once("opendb.php");

	$response = strtolower($connection->escape_string($_POST["response"]));

	$status = loginStatus();

	$refer = strToLower($connection->escape_string(basename($_SERVER["HTTP_REFERER"])));
	
	$page = $_POST["name"];
	if (!($status & $LoginStatusCanEdit)) {
		header("Location: nope.php");
		die();
	}

	require("head.php"); 
?>


<!-- Start main container -->
<div id="container" class="container">

   	<!-- Main hero unit -->
   	<div class="hero-unit" id="hero-unit">
		<?php
		if ($page != "") {
			$query = "SELECT * FROM `special_pages` WHERE `name` = '$page'";
			$result = $connection->query($query);
			if ($result->num_rows) die("Cannot delete reserved page \"$page\"");

			if (file_exists($page)) {
				unlink($page);
				echo "Deleted <b>".$page."</b><br><br>";
			} else {
				echo "File <b>".$page."</b> does not exist.<br><br>";
			}

			$page = basename($page, ".php");

			$query = "DROP TABLE `$page-index`;";
			$connection->query($query) or die("The table <b>".$page."-index</b> does not exist");
			echo "Deleted table <b>".$page."-index</b>";
		} else {
			if ($refer == "dev.hanoverhs.org") {

			} elseif ($refer == "") {
				echo "Enter the page you wish to delete below<br><br>";
			} elseif ($refer != "") {
				echo "To delete the page you were just viewing, enter <b>".$refer."</b> below.<br><br>";
			}
			echo "<form method=\"POST\" action=\"delete.php\">";
			echo "<label for=\"name\">File Name:</label><input type=\"text\" name=\"name\" maxlength=\"1024\"><br>";
			echo "<input type=\"submit\" value=\"Delete Page\">";
			echo "</form>";
		}


		?>
   </div>
   <div class="row">

   </div>

<?php require("foot.php"); ?>