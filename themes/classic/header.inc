<a href="/index.php">
	<img id="topleft" style="border:none" src="/themes/classic/images/topleft.jpg" alt="Top Left">
	<img id="top" style="border:none" src="/themes/classic/images/top.jpg" alt="Top">
</a>
<a href="/team.php"><img src="/themes/classic/images/team.jpg" width="99" height="22" id="team" style="border:none" alt="Team"></a>
<a href="/league.php"><img src="/themes/classic/images/league.jpg" width="98" height="22" id="league" style="border:none" alt="League"></a>
<a href="/country.php"><img src="/themes/classic/images/country.jpg" width="98" height="22" id="country" style="border:none" alt="Country"></a>
<a href="/world.php"><img src="/themes/classic/images/world.jpg" width="98" height="22" id="world" style="border:none" alt="World"></a>
<a href="/custom.php"><img src="/themes/classic/images/custom.jpg" width="99" height="22" id="custom" style="border:none" alt="Custom"></a>
<img src="/themes/classic/images/space.jpg" width="87" height="22" id="space" style="border:none" alt="" />
<a href="/feedback.php"><img src="/themes/classic/images/feedback.jpg" width="103" height="43" id="feedback" style="border:none" alt="Feedback"></a>
<a href="/forum/"><img src="/themes/classic/images/ntforums.jpg" width="103" height="29" id="ntforums" style="border:none" alt="Forum"></a>
<a href="/wiki/"><img src="/themes/classic/images/bbwiki.jpg" width="103" height="43" id="bbwiki" style="border:none" alt="Wiki"></a>
<?php
if ($_SESSION['loggedin'])
{
	?>
	<a href="/bblogout.php"><img src="/themes/classic/images/logout.jpg" width="75" height="22" id="loginout" style="border:none" alt="Logout"></a>
	<?php
}
else
{
	?>
	<a href="/bblogin.php"><img src="/themes/classic/images/login.jpg" width="75" height="22" id="loginout" style="border:none" alt="Login"></a>
	<?php
}
?>