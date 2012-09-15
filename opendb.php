<?php
if ($_SERVER["REQUEST_URI"] == "/opendb.php") {
   header("Location: nope.php");
   die();
}

require("config.php");

$connection = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_data) or die("Could not connect!");

register_shutdown_function("exiting");

function exiting() {
   mysqli_close($connection);
}

$LoginStatusOK = 1 << 0;         //Login is successful
$LoginStatusUserPass = 1 << 1;   //Incorrect username/password
$LoginStatusBadCreds = 1 << 2;   //Username/password missing
$LoginStatusAccess0 = 1 << 3;    //Normal access
$LoginStatusAccess1 = 1 << 4;    //Editor access
$LoginStatusAccess2 = 1 << 5;    //Homepage editor access
$LoginStatusAccess3 = 1 << 6;    //Administrator access
$LoginStatusCanEdit = 1 << 7;    //Can edit pages
$LoginStatusCanHome = 1 << 8;    //Can edit home page
$LoginStatusSeeMore = 1 << 9;    //Allows for viewing of last IP + more

function loginStatus($username = NULL, $passhash = NULL) {
   global $connection, $LoginStatusOK, $LoginStatusUserPass, $LoginStatusBadCreds, $LoginStatusAccess0, $LoginStatusAccess1, $LoginStatusAccess2, $LoginStatusAccess3, $LoginStatusCanEdit, $LoginStatusCanHome, $LoginStatusSeeMore;
   $ret = 0;
   if ($username == NULL || $passhash == NULL) {
      if (!isset($_COOKIE["username"]) || !isset($_COOKIE["session"]))
         $ret = $ret | $LoginStatusBadCreds;
      else {
         $username = $_COOKIE["username"];
         $passhash = $_COOKIE["session"];
      }
   }

   //Escaping required
   $username = strToLower($connection->escape_string($username));
   $password = $connection->escape_string($passhash);

   $query = "SELECT `passhash`, `access` FROM `users` WHERE `username` = '$username'";

   $access = 0;
   $serverPass = "";

   if ($statement = $connection->prepare($query)) {
      $statement->execute();
      $statement->bind_result($serverPass, $access);
      $result = $connection->query($query);

      if ($statement->fetch() === false)
         $ret = $ret | $LoginStatusUserPass;
   }

   $access = intVal($access);

   if ($passhash !== $serverPass)
      $ret = $ret | $LoginStatusUserPass;
   else
      $ret = $ret | $LoginStatusOK;

   switch ($access) {
   case 0:
      $ret = $ret | $LoginStatusAccess0;
      break;
   case 1:
      $ret = $ret | $LoginStatusAccess0;
      $ret = $ret | $LoginStatusAccess1;
      $ret = $ret | $LoginStatusCanEdit;
      break;
   case 2:
      $ret = $ret | $LoginStatusAccess0;
      $ret = $ret | $LoginStatusAccess1;
      $ret = $ret | $LoginStatusAccess2;
      $ret = $ret | $LoginStatusCanEdit;
      $ret = $ret | $LoginStatusCanHome;
      break;
   case 3:
      $ret = $ret | $LoginStatusAccess0;
      $ret = $ret | $LoginStatusAccess1;
      $ret = $ret | $LoginStatusAccess2;
      $ret = $ret | $LoginStatusAccess3;
      $ret = $ret | $LoginStatusCanEdit;
      $ret = $ret | $LoginStatusCanHome;
      $ret = $ret | $LoginStatusSeeMore;
      break;
   }

   return $ret;
}

?>