<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Hanover High School - Home</title>
<?php
/* Make sure this is defined here, and only here! */
   $isHomePage = true;
/* Everything in the <head> except for <title> : Also contains the navbar */
   require("head.php");
?>

<!-- Start main container -->
<div id="container" class="container">
   <!-- Main hero unit -->
   <div class="hero-unit" id="hero-unit">
      <h1>Hanover High School</h1>
      <p></p>
      <p>Hanover High School is an active learning community that provides broad academic and co-curricular programs. </p>
      <p>We engage students' minds, hearts and voices so that they become educated, caring and responsible adults.</p>
      <p><a href="http://broadside.dresden.us/" class="btn btn-info btn-large">HHS Broadside &raquo;</a> <a href="https://www.google.com/calendar/embed?title=Hanover%20High&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;mode=AGENDA&amp;height=200&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=nn2509143kmbl2jbhlofvah8fs%40group.calendar.google.com&amp;color=%231B887A&amp;src=vs8lb5l1ej2cukd1l0s26atv2c%40group.calendar.google.com&amp;color=%2328754E&amp;src=680mj2kt8enp5minffopangeeg%40group.calendar.google.com&amp;color=%232952A3&amp;src=4uripfc30h2e7htmr6cn38g8bo%40group.calendar.google.com&amp;color=%23528800&amp;src=ak55rjsbcjca1b6c4g1pl98a30%40group.calendar.google.com&amp;color=%237A367A&amp;src=v5u6pb9hmmk6vdmc5sd97r67sg%40group.calendar.google.com&amp;color=%2329527A&amp;src=d4ohdnvm96jfqsrj9copecbiu8%40group.calendar.google.com&amp;color=%232F6309&amp;src=1rank92tt12gjt2q0u3cbgemmc%40group.calendar.google.com&amp;color=%2388880E&amp;src=ggb1fhi1mr66bati1ct16kvc78%40group.calendar.google.com&amp;color=%230D7813&amp;src=ubsv6hfmoe8n03oclu2r1a61hk%40group.calendar.google.com&amp;color=%235229A3&amp;src=p%23weather%40group.v.calendar.google.com&amp;color=%23AB8B00&amp;ctz=America%2FNew_York" class="btn btn-primary btn-large">School Calendar &raquo;</a> <a href="http://goo.gl/maps/kiKDE" class="btn btn-success btn-large">HHS on a Map &raquo;</a> <a href="http://www.hanoverhigh.us/resources/Handbook.pdf" class="btn btn-warning btn-large">HHS Student Handbook &raquo;</a></p>
   </div>
   <div class="row">
      <div class="span4">
         <h2>Check your grades!</h2>
         <p>Use the Portal to check your (or your student's) grades - anytime, anywhere. Just login with your school-provided username and password. </p>
      <p><a class="btn" href="http://ps.dresden.us">View grades! &raquo;</a></p>
      </div>
      <div class="span4">
         <h2>Read the HHS newspaper!</h2>
         <p>Written by students from HHS, the newspaper is always jam-packed with the latest information about new teachers, sports events, and school happenings. </p>
         <p><a class="btn" href="http://broadside.dresden.us">View the paper! &raquo;</a></p>
      </div>
      <div class="span4">
         <h2>Contribute to this website!</h2>
         <p>This new website was created by Owen Versteeg (an HHS student) and is open-source. If you have something interesting to add to it, simply head on over to GitHub and help out! </p>
         <p><a class="btn" href="https://github.com/HanoverHighSchool/hhswebsite">This website on GitHub &raquo;</a></p>
      </div>
   </div>
<?php require("foot.php"); ?>