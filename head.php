<?php require_once("opendb.php"); ?>
      <!-- Copyright (c) <?php echo(date("Y")); ?> Hanover High School -->
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="The website for the Hanover High School - located in Hanover, NH" />
		<meta name="author" content="Owen Versteeg, Ezekiel Elin, Glenn Smith, Caleb Winberry, Marco van Gemeren" />

		<!-- Le styles -->

		<!--<script src="assets/js/operamini.js"></script>-->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
		<script src="main.js"></script>
		<link href="bootstrap.css.gz" rel="stylesheet" />
		<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.0/js/bootstrap.min.js"></script>
		<link href="main.css" rel="stylesheet" />
		<link href="noise.css" rel="stylesheet" />

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<!-- Le fav and touch icons -->

		<link rel="stylesheet/less" type="text/css" href="bootstrap.less">
		<link rel="stylesheet/less" type="text/css" href="/assets/bootstrap/all.less.gz">
		<link rel="stylesheet/less" type="text/css" href="/assets/bootstrap/responsive.less.gz">
		<link rel="stylesheet/less" type="text/css" href="/assets/bootstrap/less/scrollbar.less">

		<script src="less-1.3.0.min.js" type="text/javascript"></script>

		<link rel="shortcut icon" href="assets/ico/favicon.ico" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png" />

      <?php

      require_once("opendb.php");
      $status = loginStatus();

      $editable = false;

      if ($status & $LoginStatusOK && ($isHomePage ? $status & $LoginStatusCanHome : $status & $LoginStatusCanEdit)) {
      ?>
      <style>
      #editor {
         display: block;
         border: 2px inset #666;
      }
      .inspect {
         display: block;
         position: absolute;
         width: 200px;
         height: 250px;
         color: #fff;
         background-color: #800;
         border: 2px solid #fff;
         border-radius: 10px;
         -moz-border-radius: 10px;
         -webkit-border-radius: 10px;
      }
      </style>
      <?php
      } ?>
      <script type="text/javascript">
var tag = new Array();
var button = new Array();
var curPage = "<?php echo(strToLower($connection->escape_string(basename($_SERVER["PHP_SELF"], ".php")))); ?>";
<?php
$all = getAllElements();
for ($i = 0; $i < count($all); $i ++) {
   if ($all[$i]["type"] == "text" || $all[$i]["type"] == "")
      echo("tag[{$all[$i]['number']}] = \"" . str_replace("\n", "", str_replace("\\n", "<br>", $all[$i]["text"])) . "\";\n");
   else if ($all[$i]["type"] == "button")
      echo("button[{$all[$i]['number']}] = new Array(\"" . str_replace("\n", "", str_replace("\\n", "<br>", $all[$i]["text"])) . "\", {$all[$i]['color']});");
}
echo("var tags = " . count($all) . ";\n");
?>
      </script>
	</head>
	<body editor-enabled="false">
		<div class="navbar navbar-inverse navbar-fixed-top" editor-enabled="false">
			<div class="navbar-inner" editor-enabled="false">
				<div class="container" id="container" editor-enabled="false">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<a class="brand" href="index.php" editor-enabled="false">HHS Developer Site</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li class="active"><a href="index.php">Home</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Academics
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li><a href="art.php">Art</a></li>
									<li><a href="dresden.php">Dresden Plan</a></li>
									<li><a href="english.php">English</a></li>
									<li><a href="https://sites.google.com/a/hanovernorwichschools.org/health-class/?pli=1">Health</a></li>
									<li><a href="industrial.php">Industrial Arts</a></li>
									<li><a href="language.php">Foreign Language</a></li>
									<li><a href="mathematics.php">Mathematics</a></li>
									<li><a href="music.php">Music</a></li>
									<li><a href="sports.php">Physical Education</a></li>
									<li><a href="science.php">Science</a></li>
									<li><a href="social_studies.php">Social Studies</a></li>
									<li><a href="technology.php">Technology</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Resources
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li><a href="http://www.hanoverhigh.us/resources/Schedule.pdf">Daily Schedule</a></li>
									<li><a href="http://sites.google.com/a/hanovernorwichschools.org/portfolios-cc/home">Core Competencies</a></li>
									<li><a href="http://www.hartfordschools.net/Schools/HACTC/tabid/56/Default.aspx">Hartford Career and Tech Center</a></li>
									<li><a href="http://www.hanoverhigh.us/resources/Profile_11.pdf">HHS Profile 2011</a></li>
									<li><a href="http://www.hanoverhigh.us/resources/Parent_Survey_2011_12.pdf">Parent Survey 2011-2012</a></li>
									<li><a href="http://hhstech.org/portal/portalmovie_halfsize.mov">Portal Training Video</a></li>
									<li><a href="http://www.hanoverhigh.us/resources/Youth_At_Risk_Survey_2011.pdf">Youth at Risk Survey 2011</a></li>
									<li class="divider"></li>
									<li><a href="http://hanoverhigh.us/Hanover/activities.html">Activities</a></li>
									<li><a href="http://www.alumniclass.com/hanoverhsnh/">Alumni</a></li>
									<li><a href="/assets/pdf/Revised_HanoverDresdenTransGuide12-13.pdf">Hanover Bus Routes</a></li>
									<li><a href="/assets/pdf/RevisedNorwichDresdenTransGuide12-13.pdf">Norwich Bus Routes</a></li>
									<li><a href="http://hanoverhigh.us/Hanover/calendar.html">Calendar</a></li>
									<li><a href="https://sites.google.com/a/hanovernorwichschools.org/hhs-council/">Council</a></li>
									<li><a href="http://sites.hanovernorwichschools.org/hhs-weekly/counselors-corner-guidance-information">Counselor's Corner</a></li>
									<li><a href="http://hanoverhigh.us/Hanover/departments.html">Departments</a></li>
									<li><a href="http://mail.hanovernorwichschools.org/">Email</a></li>
									<li><a href="http://hanoverhigh.us/Hanover/faculty.html">Faculty and Staff</a></li>
									<li><a href="http://www.hhstech.org/guidance/">Guidance</a></li>
									<li><a href="http://hanoverhigh.us/Hanover/happenings.html">Happenings</a></li>
									<li><a href="https://sites.google.com/a/hanovernorwichschools.org/hhs-health-office/">Health Office</a></li>
									<li><a href="http://hanoverhigh.us/Hanover/map.html">Map</a></li>
									<li><a href="http://intensive.dresden.us/">March Intensive</a></li>
									<li><a href="http://hanoverhslibrary.weebly.com/">Media</a></li>
									<li><a href="/assets/pdf/Program_of_studies.pdf">Program of Studies (PDF)</a></li>
									<li><a href="http://www.sau70.org/spotlight/Meal_Time.htm">School Lunch</a></li>
									<li><a href="http://www.hanoverhigh.us/resources/Handbook.pdf">Student Handbook (PDF)</a></li>
									<li><a href="http://members.valley.net/~SAU70/">Superintendent</a></li>

								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Sports
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li><a href="http://www.hanoverhigh.us/Hanover/calendar.html">Calendar</a></li>
									<li><a href="http://www.usatodayhss.com/search?q=hanover%2C+nh&amp;zipCode=&amp;filters=teams">Sports Schedule</a></li>
									<li><a href="http://hanoverhigh.us/Hanover/sports.html">Sports Home</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									Other
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li><a href="http://hanoverhighcouncil.blogspot.com/">HHS Council Blog</a></li>
									<li><a href="http://hanoverhslibrary.weebly.com/">HHS Library/Media Center</a></li>
									<li><a href="http://hanoverhigh.us/Hanover/happenings.html">Happenings</a></li>
									<li><a href="http://intensive.dresden.us/">March Intensive</a></li>
									<li><a href="http://www.hhspenofiron.blogspot.com/">Pen of Iron</a></li>
									<li><a href="http://greenteam.dresden.us/">Hanover Recycles</a></li>

								</ul>
							</li>
							<!-- FFS, Stop Deleting this. If you want it gone, COMMENT IT OUT -->
							<?php include("login-menu.php"); ?>
						</ul>
					</div>
					<!--/.nav-collapse -->
				</div>
			</div>
		</div>
<?php
if (!isset($_COOKIE["hidewarning"])) {
?>
		<div style="margin-left:60px;margin-right:60px;" class="alert alert-danger" editor-enabled="false">
         <script type="text/javascript">
         function hideWarning() {
            var expire = new Date();
            expire.setDate(expire.getDate() + 3652.5); //10 years
            document.cookie = "hidewarning=1; expires=" + expire.toUTCString();
         }
         </script>
			<button type="button" class="close" data-dismiss="alert" onclick="hideWarning();" editor-enabled="false">&times;</button>
			<h4 editor-enabled="false">Warning!</h4>
			This is the dev site!
		</div>
<?php
}
?>
