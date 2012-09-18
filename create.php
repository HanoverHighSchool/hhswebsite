<?php
if ($_GET["name"] != "" && !(file_exists($_GET["name"].".php"))) {

   require_once("opendb.php");

   $status = loginStatus();

   if (!($status & $LoginStatusCanEdit)) {
      header("Location: nope.php");
      die();
   }

   $pageName = $connection->escape_string($_GET["name"]);
   $pageName = strtolower($pageName);

   $query = "CREATE TABLE IF NOT EXISTS `$pageName-index` (`field` INT NOT NULL, `value` VARCHAR(1024) NOT NULL, `lastUpdate` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `lastUser` VARCHAR(64) NOT NULL, `lastIP` VARCHAR(32) NOT NULL) ENGINE = MYISAM;";

   if ($connection->query($query))
      copy("blank-template.php", "$pageName.php");
   else {
      header("Location: nope.php");
      die();
   }

   header("Location: $pageName.php");
} else {
   if (file_exists($_GET["name"].".php")) {
      echo "The page <b>".$_GET["name"].".php</b> already exists!<br><br>";
   }
   echo "<form method=\"GET\" action=\"create.php\">";
   echo "<label for=\"name\">Page Name:</label><input type=\"text\" name=\"name\" maxlength=\"16\"><br>";
   echo "<input type=\"submit\" value=\"Create Page\">";
   echo "</form>";
}
?>
