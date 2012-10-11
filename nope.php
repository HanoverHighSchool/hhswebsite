<?php
//   header("Location: index.php");
//Do some fancy tracking stuff here for hackers
?>
<html>
<head>
<style>
iframe {
   width: 100%;
   height: 100%;
}
* {
   padding: 0px;
   margin: 0px;
}
nope {
   width: 100%;
   height: 100%;
}
haha {
   color: #fff;
   position: absolute;
   top: 50%;
   left: 50%;
   width: 200px;
   height: 80px;
   margin-left: -100px;
   margin-top: -40px;
   font-size: 64px;
}
</style>
<title>Nope!</title>
</head>
<body>
<iframe src="http://www.youtube.com/watch_popup?v=<?php

$v[0] = "itvJybdcYbI";
$v[1] = "oHg5SJYRHA0";
$v[2] = "gvdf5n-zI14";

echo($v[rand(0, 2)]);

?>&vq=medium&autoplay=1&frameborder=0&fullscreen=1" frameborder="0" autoplay allowfullscreen></iframe>
<nope><haha>Nope!</haha></nope>
<script type="text/javascript">
function D() {
   var haha = document.getElementsByTagName("haha")[0];
   var c = "0123456789abcdef";
   var cc = "#";
   for (var i = 0; i < 6; i ++) {
      var p = Math.floor(Math.random() * 16);
      cc = cc + c.substring(p, p + 1);
   }
   haha.style.color = cc;
   setTimeout("D();", 100);
}

D();
</script>
</body>
</html>
