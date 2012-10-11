<?php

/* COMMENT THIS FOR FINAL COPY */
$debugMenu = true;

if ($_SERVER["REQUEST_URI"] == "/login-menu.php") {
   header("Location: /nope.php");
   die();
}

function upperFirst($str) {
   echo(strToUpper(subStr($str, 0, 1)) . subStr($str, 1));
}

require_once("opendb.php");

$status = loginStatus();

if ($status & $LoginStatusOK) {
   if ($status & $LoginStatusAccess1) {
   ?>
      <li class="dropdown" editor-enabled="false">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            Admin <b class="caret" editor-enabled="false"></b>
         </a>
         <ul class="dropdown-menu" editor-enabled="false">

         <?php
            if ((isset($isHomePage) && $status & $LoginStatusCanHome) ||
            (!isset($isHomePage) && $status & $LoginStatusCanEdit)) {
          ?>
            <li><a href="#" id="editLI" onclick="loadEdit();" editor-enabled="false">Edit This Page</a>
<script type="text/javascript">

function loadEdit() {
   var editLI = document.getElementById("editLI");
   editLI.innerHTML = "Please Wait...";
   editLI.setAttribute("onclick", "");

   var script = document.createElement("script");
   script.setAttribute("type", "text/javascript");
   script.setAttribute("src", "/assets/ckeditor/ckeditor.js");

   document.getElementsByTagName("head")[0].appendChild(script);

   script.onload = function() {
      var script1 = document.createElement("script");
      script1.setAttribute("type", "text/javascript");
      script1.setAttribute("src", "/assets/ckeditor/adapters/jquery.js");

      document.getElementsByTagName("head")[0].appendChild(script1);

      script1.onload = function() {
      var script2 = document.createElement("script");
         script2.setAttribute("type", "text/javascript");
         script2.setAttribute("src", "/edit-support.js.php");

         document.getElementsByTagName("head")[0].appendChild(script2);
      };
   };
}

</script>
            </li>
            <li class="divider" editor-enabled="false"></li>
         <?php
            }
            if ($status & $LoginStatusCanEdit) {?>
            <li><a href="/create.php" editor-enabled="false">Create Page</a>
            <?php
         if ((isset($isHomePage) && $status & $LoginStatusCanHome) ||
         (!isset($isHomePage) && $status & $LoginStatusCanEdit)) { ?>
            <li><a href="/delete.php" editor-enabled="false">Delete This Page</a></li>
            <?php } ?>
            <li><a href="/list.php" editor-enabled="false">Page Listing</a></li>
            <?php }
         if ($status & $LoginStatusAccess3) { ?>
            <li><a href="/accounts.php" editor-enabled="false">Account Listing</a></li>
         <?php }
         ?>
         </ul>
      </li>
      <?php
      }
      ?>
      <li class="dropdown" editor-enabled="false">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            Account <b class="caret" editor-enabled="false"></b>
         </a>
         <ul class="dropdown-menu">
            <!-- DO NOT DO upperFirst, it looks horrible -->
         <li class="menu-text" editor-enabled="false">Account Name: <?php echo($_COOKIE["username"])); ?></li>
         <li class="divider" editor-enabled="false"></li>
         <li><a href="/account.php">Account Overview</a></li>
         <li><a href="/account-edit.php">Account Settings</a></li>
         <li><a href="/logout.php">Log Out</a></li>
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
            <form action="/login.php" method="POST">
               <input type="text" class="input-small" name="username" placeholder="Username">
               <input type="password" class="input-small" name="password" placeholder="Password"><br>
               <input type="checkbox" id="remember" name="remember"> <label for="remember">Remember?</label>
               <input type="submit" class="btn" value="Log In">
            </form>
            <a href="register.php?show-form=true" style="padding: 0px; margin-left: -6px; margin-top:-6px;">Need an Account?</a>
            <a href="forgot.php" style="padding: 0px; margin-left: -6px; margin-top:-6px;">Forgot Password?</a>
         </div>
      </li>
   <?php
}

if (isset($debugMenu)) {
?>
<li class="dropdown">
   <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      Debug <b class="caret"></b>
   </a>
   <ul class="dropdown-menu">
<?php
echo("<li class=\"menu-text\">Permissions: $status</li>");
$access = ($status & $LoginStatusAccess0 ? ($status & $LoginStatusAccess1 ? ($status & $LoginStatusAccess2 ? ($status & $LoginStatusAccess3 ? 3 : 2) : 1) : 0) : -1);
echo("<li class=\"menu-text\">Access: $access</li>");
echo("<li class=\"menu-text\">Showing Warning: " . (isset($_COOKIE["hidewarning"]) ? "No" : "Yes") . "</li>");
?>
   </ul>
<?php } ?>
