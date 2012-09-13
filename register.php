<?php

//We need to open the connection
require("opendb.php");

//Quick function for random strings
function str_rand($length = 8, $chars = "abcdefghijklmnopqrstuvwxyz\n\r\t ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-=_+[]{}\\|!@#$%^&*()`~<>,./?\'\";:") {
   $ret = "";

   //Just append
   for ($i = 0; $i < $length; $i ++)
      $ret .= substr($chars, rand(0, strlen($chars)), 1);
   return $ret;
}

//Get data (so very insecure)
$username = $_GET["username"];
$password = $_GET["password"];
$email = $_GET["email"];
$first = $_GET["first"];
$last = $_GET["last"];
$access = $_GET["access"];

//Hehe, passkey
$key = $_GET["passkey"];

//Random value!
if ($key != "a7044860") {
	header("Location: nope.php");
	die();
}

//Generate a salt
$salt = str_rand(16);

//Get these values and ESCAPE THEM (very important)
$username = strToLower($connection->escape_string($username));
$password = $connection->escape_string($password);
//SHA1+Salt the password
$passhash = sha1($salt . sha1($password . $salt));
$email = strToLower($connection->escape_string($email));
$first = $connection->escape_string($first);
$last = $connection->escape_string($last);
$access = intVal($access); //Quick hack to convert to int

//Check if the account exists
$query = "SELECT `username` FROM `users` WHERE `username` LIKE '$username'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result)) {
   die("Account already exists!");
}

//Check if the email exists
$query = "SELECT `email` FROM `users` WHERE `email` LIKE '$email'";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result)) {
   die("Email already exists!");
}

//Insert it into the table
$query = "INSERT INTO `users` (`username`, `passhash`, `password`, `salt`, `email`, `firstname`, `lastname`, `access`, `lastlogin`) VALUES ('$username', '$passhash', '$password', '$salt', '$email', '$first', '$last', $access, CURRENT_TIMESTAMP)";

//Go!
mysqli_query($connection, $query) or die("Could not add!");

?>