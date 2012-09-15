<?php

$previous = $_SERVER["HTTP_REFERER"];

function a_long_time() {
   return 315360000; //10 years
}

echo("--start debug--<br><br>");
echo(0);

//We need our connections!
require("opendb.php");

//Get datas
$username = $_POST["username"];
$password = $_POST["password"];
$remember = $_POST["remember"];

echo(1);

$username = strToLower($connection->escape_string($username));
$password = $connection->escape_string($password);

$query = "SELECT `passhash`, `salt` FROM `users` WHERE `username` = '$username'";

$serverPass = "";
$salt = "";

if ($statement = $connection->prepare($query)) {
   $statement->execute();

   $statement->bind_result($serverPass, $salt);

   $result = $connection->query($query);

   if ($statement->fetch() === false)
      die("Account does not exist!");
}

echo(2);

$passhash = sha1($salt . sha1($password . $salt));

echo(3);

echo("<br><br>--end debug--<br><br>");

if ($passhash !== $serverPass)
   die("Invalid password!");

if ($remember) {
   setCookie("username", $username, time() + a_long_time(), "/");
   setCookie("session", $passhash, time() + a_long_time(), "/");
} else {
   setCookie("username", $username, NULL, "/");
   setCookie("session", $passhash, NULL, "/");
}

echo(4);

header("Location: $previous")

?>