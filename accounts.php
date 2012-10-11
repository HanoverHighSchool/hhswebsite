<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Hanover High School - Account List</title>

<!-- Everything in the <head> except for <title> : Also contains the navbar-->
<?php require("head.php"); ?>

<!-- Start main container -->
<div id="container" class="container">

<div>
<?php

require_once("opendb.php");

$query = "SELECT * FROM `users`";



?>
</div>

<?php require("foot.php"); ?>