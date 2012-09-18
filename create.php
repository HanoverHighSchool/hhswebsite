<?php

if (isset($_GET["name"])) {

   require_once("opendb.php");

   $status = loginStatus();

   if (!($status & $LoginStatusCanEdit)) {
      header("Location: nope.php");
      die();
   }

   $pageName = $connection->escape_string($_GET["name"]);

   $query = "CREATE TABLE IF NOT EXISTS `$pageName-index` (`field` INT NOT NULL, `value` VARCHAR(1024) NOT NULL, `lastUpdate` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `lastUser` VARCHAR(64) NOT NULL, `lastIP` VARCHAR(32) NOT NULL) ENGINE = MYISAM;";

   if ($connection->query($query))
      copy("blank-template.php", "$pageName.php");
   else {
      header("Location: nope.php");
      die();
   }

   header("Location: $pageName.php");
} else {
      echo "<form method=\"GET\" action=\"create.php\">";
      echo "<label for=\"name\">Page Name:</label><input type=\"text\" name=\"name\" maxlength=\"16\"><br>";
      echo "<input type=\"submit\" value=\"Create Page\">";
      echo "</form>";
}
?>
