<?php
if ($_SERVER["REQUEST_URI"] == "/opendb.php") {
   header("Location: nope.php");
   die();
}

require("config.php");

$connection = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_data) or die("Could not connect!");

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

   if ($ret & $LoginStatusOK) {
      $query = "UPDATE `users` SET `lastlogin` = CURRENT_TIMESTAMP WHERE `username` = '$username'";
      $connection->query($query);
   }

   return $ret;
}

$globalLogin = loginStatus();

function getElement($num) {
   global $globalLogin, $connection;

   $pagename = strToLower($connection->escape_string(basename($_SERVER["PHP_SELF"], ".php")));

   $num = intVal($num);

   $query = "SELECT `value`" . ($globalLogin & $LoginStatusSeeMore ? ", `lastUpdate`, `lastUser`, `lastIP` " : " ") . "FROM `$pagename-index` WHERE `field` = $num;";

   $value = null;
   $lastUpdate = null;
   $lastUser = null;
   $lastIP = null;
   if ($statement = $connection->prepare($query)) {
      $statement->execute();
      if ($globalLogin & $LoginStatusSeeMore)
         $statement->bind_result($value, $lastUpdate, $lastUser, $lastIP);
      else
         $statement->bind_result($value);

      $result = $connection->query($query);

      if ($statement->fetch() === false)
         return array("text" => "");
      $statement->close();
   } else
      return array("text" => "");

   if ($globalLogin & $LoginStatusSeeMore)
      return array("text" => $value, "lastUpdate" => $lastUpdate, "lastUser" => $lastUser, "lastIP" => $lastIP);
   else
      return array("text" => $value);
}

function getAllElements($page = NULL) {
   if ($page == NULL)
      $page = $_SERVER["PHP_SELF"];
   global $globalLogin, $connection, $LoginStatusSeeMore;

   $pagename = strToLower($connection->escape_string(basename($page, ".php")));

   $query = "SELECT `field`, `value`" . ($globalLogin & $LoginStatusSeeMore ? ", `lastUpdate`, `lastUser`, `lastIP` " : " ") . "FROM `$pagename-index`";

   $value = null;
   $number = null;
   $lastUpdate = null;
   $lastUser = null;
   $lastIP = null;
   $return = array();
   if ($statement = $connection->prepare($query)) {
      $statement->execute();
      if ($globalLogin & $LoginStatusSeeMore)
         $statement->bind_result($number, $value, $lastUpdate, $lastUser, $lastIP);
      else
         $statement->bind_result($number, $value);

      $returns = 0;
      while ($statement->fetch()) {
         if ($globalLogin & $LoginStatusSeeMore)
            $return[$returns ++] = array("text" => $value, "number" => $number, "lastUpdate" => $lastUpdate, "lastUser" => $lastUser, "lastIP" => $lastIP);
         else
            $return[$returns ++] = array("text" => $value, "number" => $number);
      }
      $statement->close();
   } else
      return null;

   return $return;
}

function tag($num) {
   $element = getElement($num);
   return html_entity_decode($element["text"]);
}

function record() {
   global $connection;

   $ip = $_SERVER["REMOTE_ADDR"];

   $query = "SELECT `hits` FROM `hits` WHERE `ip` = '$ip'";
   $hits = 0;
   if ($statement = $connection->prepare($query)) {
      $statement->execute();
      $statement->bind_result($hits);

      if (!($statement->fetch())) {
         $query = "INSERT INTO `hits` (`hits`, `ip`, `firstHit`) VALUES (0, '$ip', CURRENT_TIMESTAMP)";
         $connection->query($query);
      }
   }

   $hits = intVal($hits) + 1;

   $query = "UPDATE `hits` SET `hits` = '$hits', `lastHit` = CURRENT_TIMESTAMP WHERE `ip` = '$ip' LIMIT 1;";
   $connection->query($query);
}

record();

?>
