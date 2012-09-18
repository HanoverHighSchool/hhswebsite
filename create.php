<?php
if ($_POST["name"] != "" && !(file_exists($_POST["name"].".php"))) {

   require_once("opendb.php");

   $status = loginStatus();

   if (!($status & $LoginStatusCanEdit)) {
      header("Location: nope.php");
      die();
   }

   $pageName = $connection->escape_string($_POST["name"]);
   $pageTitle = $connection->escape_string($_POST["title"]);
   $pageName = strtolower($pageName);

   $query = "CREATE TABLE IF NOT EXISTS `$pageName-index` (`field` INT NOT NULL, `value` VARCHAR(1024) NOT NULL, `lastUpdate` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `lastUser` VARCHAR(64) NOT NULL, `lastIP` VARCHAR(32) NOT NULL) ENGINE = MYISAM;";

   if ($connection->query($query)) {
      $temp = file_get_contents("blank-template.php");
      $temp = str_replace("@title@", $_POST["title"], $temp);
      file_put_contents("{$_POST['name']}.php", $temp);
   } else {
      header("Location: nope.php");
      die();
   }

   $query = "INSERT INTO `$pageName-index` SELECT * FROM `template-index`;";
   $connection->query($query);

   $query = "UPDATE `$pageName-index` SET `value` = '$title' WHERE `field` = '1'";
   $connection->query($query);

   header("Location: $pageName.php");
} else {
   if (file_exists($_GET["name"].".php")) {
      echo "The page <b>".$_GET["name"].".php</b> already exists!<br><br>";
   }
   ?>
   <form method="POST" action="create.php">
   <label for="name">File Name:</label><input type="text" name="name" maxlength="64"><br>
   <label for="title">Page Title:</label><input type="text" name="title" maxlength="1024"><br>
   <input type="submit" value="Create Page">
   </form>
   <?php
}
?>
