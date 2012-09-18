<?php
require("opendb.php");

$tags = $_POST["tags"];
$taglist = "";
for ($i = 1; $i < $tags + 1; $i ++) {
   $taglist .= " {$_POST['tag'][$i]}";
}

if ($globalLogin & $LoginStatusCanEdit) {
   $isHome = $_POST["page"] == "index";
   echo("\nPushing Update to {$_POST['page']}");
   echo("\n Is home: $isHome");

   $table = $isHome ? "index" : strToLower($connection->escape_string(basename($_POST["page"], ".php")));

   if ($isHome && !($globalLogin & $LoginStatusCanHome))
      die("Nope!");

   $query = "CREATE TABLE IF NOT EXISTS `$table-index` (`field` INT NOT NULL, `value` VARCHAR(1024) NOT NULL, `lastUpdate` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `lastUser` VARCHAR(64) NOT NULL, `lastIP` VARCHAR(32) NOT NULL) ENGINE = MYISAM;";

   $connection->query($query); //We don't really care about the output

   $elements = getAllElements($table);
   $change = array();

   for ($i = 0; $i < count($elements); $i ++) {
      $element = $elements[$i];
      $val = addslashes($element["text"]);
      $new = $_POST["tag"][$i];
      if ($val !== $new) {
         //echo("Value for tag $i mismatch!\n");
         $user = $connection->escape_string($_COOKIE["username"]);
         $ip = $_SERVER["REMOTE_ADDR"];
         $new = $connection->escape_string($new);
         //echo("Update Specs: $user $ip\n");
         $query = "UPDATE `$table-index` SET `value` = '$new', `lastUser` = '$user', `lastIP` = '$ip' WHERE `field` = " . ($i + 1) . " LIMIT 1;";
         //echo($query . "\n");
         if ($connection->query($query))
            echo("Update good Updated tag " . ($i + 1) . "!\n");
      }
   }
   if ($tags > count($elements)) {
      echo("We have " . ($tags - count($elements)) . " extra tag(s) to add!");
      for ($i = count($elements); $i < $tags; $i ++) {
         $user = $connection->escape_string($_COOKIE["username"]);
         $ip = $_SERVER["REMOTE_ADDR"];
         $new = $connection->escape_string(htmlentities($_POST["tag"][$i]));
         $query = "INSERT INTO `$table-index` (`field`, `value`, `lastUser`, `lastIP`) VALUES (" . ($i + 1) . ", '$new', '$user', '$ip')";
         if ($connection->query($query))
            echo("Added tag " . ($i + 1) . "!");
      }
   }
} else
   die("Nope!");

?>
