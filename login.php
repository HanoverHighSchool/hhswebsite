<?php

$previous = $_SERVER["HTTP_REFERER"];

function a_long_time() {
   return 315360000; //10 years
}

//We need our connections!
require("opendb.php");

//Get datas
$username = $_POST["username"];
$password = $_POST["password"];
$remember = $_POST["remember"];

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
} else {
   die("THERE WAS A BAD ERROR!");
}


$passhash = sha1($salt . sha1($password . $salt));

echo("User: $username<br>Salt: $salt<br>Hash: $passhash<br>Serv: $serverPass<br>");

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