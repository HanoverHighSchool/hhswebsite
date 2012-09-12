<?php
if ($_SERVER["REQUEST_URI"] == "/opendb.php") {
   header("Location: nope.php");
   die();
}

$connection = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_data);

register_shutdown_function("exiting");

function exiting() {
   mysqli_close($connection);
}

?>