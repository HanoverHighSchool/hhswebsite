<?php

/* COMMENT THIS FOR FINAL COPY */
$debugMenu = true;

if ($_SERVER["REQUEST_URI"] == "/login-menu.php") {
   header("Location: nope.php");
   die();
}

require("opendb.php");

$status = loginStatus();

if ($status & $LoginStatusOK) {
   ?>
      <li class="dropdown">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            Account<b class="caret"></b>
         </a>
         <ul class="dropdown-menu">
         <?php
            if ((isset($isHomePage) && $status & $LoginStatusCanHome) ||
            (!isset($isHomePage) && $status & $LoginStatusCanEdit)) {
          ?>
            <li><a href="#">Edit This Page</a></li>
         <?php
            include("edit-support.php");
         } ?>
         <li><a href="account.php">Account Overview</a></li>
         <li><a href="account-edit.php">Account Settings</a></li>
         <li><a href="logout.php">Log Out</a></li>
         </ul>
      </li>
   <?php
} else {
   ?>
      <li class="dropdown">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            Login
         </a>
         <div class="dropdown-menu login-box">
            <form action="login.php" method="POST">
               <input type="text" class="input-small" name="username" placeholder="Username">
               <input type="password" class="input-small" name="password" placeholder="Password"><br>
               <input type="checkbox" id="remember" name="remember"> <label for="remember">Remember?</label>
               <input type="submit" class="btn" value="Log In">
            </form>
         </div>
      </li>
   <?php
}

if (isset($debugMenu)) {
?>
<li class="dropdown">
   <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      Debug<b class="caret"></b>
   </a>
   <ul class="dropdown-menu">
<?php
echo("<li><a>Permissions: $status</a></li>");
$access = ($status & $LoginStatusAccess0 ? ($status & $LoginStatusAccess1 ? ($status & $LoginStatusAccess2 ? ($status & $LoginStatusAccess3 ? 3 : 2) : 1) : 0) : -1);
echo("<li><a>Access: $access</a></li>");
echo("<li><a>Showing Warning: " . (isset($_COOKIE["hidewarning"]) ? "No" : "Yes") . "</a></li>");
?>
   </ul>
<?php } ?>