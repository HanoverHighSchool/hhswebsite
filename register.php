<?php

//Insert plaintext passwords into database (probably a bad idea...)
$plain = false;

if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"]) && isset($_POST["first"]) && isset($_POST["last"]) && isset($_POST["passkey"])) {

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
	$username = $_POST["username"];
	$password = $_POST["password"];
	$email = $_POST["email"];
	$first = $_POST["first"];
	$last = $_POST["last"];
	$access = 0;

	//Hehe, passkey
	$key = $_POST["passkey"];

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
	   die("Email already used!");
	}

	//Insert it into the table
	if ($plain)
		$query = "INSERT INTO `users` (`username`, `passhash`, `password`, `salt`, `email`, `firstname`, `lastname`, `access`, `lastlogin`) VALUES ('$username', '$passhash', '$password', '$salt', '$email', '$first', '$last', $access, CURRENT_TIMESTAMP)";
	else
		$query = "INSERT INTO `users` (`username`, `passhash`, `password`, `salt`, `email`, `firstname`, `lastname`, `access`, `lastlogin`) VALUES ('$username', '$passhash', 'Hidden!', '$salt', '$email', '$first', '$last', $access, CURRENT_TIMESTAMP)";

	//Go!
	mysqli_query($connection, $query) or die("Could not add!");
	header("Location: index.php");
} else if ($_GET["show-form"]) {
?>
<form method="POST" action="register.php">
<label for="username">Username:</label><input type="text" name="username" maxlength="64"><br>
<label for="password">Password:</label><input type="password" name="password" maxlength="64"><br>
<label for="email">Email:</label><input type="text" name="email" maxlength="128"><br>
<label for="first">First Name:</label><input type="text" name="first" maxlength="64"><br>
<label for="last">Last Name:</label><input type="text" name="last" maxlength="64"><br>
<input type="hidden" name="passkey" value="a7044860"><br>
<input type="submit" value="Register">
</form>
<?php
if ($plain === false) { ?>
Plaintext password saving is <b>OFF</b>.
<?php
}}
?>
