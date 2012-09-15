<?php
setCookie("username", "Q", time() - 3600, "/");
setCookie("session", "Q", time() - 3600, "/");

header("Location: index.php");
?>