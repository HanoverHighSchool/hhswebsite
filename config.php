<?php
if ($_SERVER["REQUEST_URI"] == "/config.php") {
   header("Location: nope.php");
   die();
}
$mysql