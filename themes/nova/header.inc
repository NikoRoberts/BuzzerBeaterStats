<a href="/index.php">
	<img id="topleft" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/topleft.jpg" width="77" height="115" alt="Top Left">
	<img id="top" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/top.jpg" width="626" height="115" alt="Top">
	<img id="topright" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/topright.jpg" width="77" height="115" alt="Top Right">
</a>
<div id="bar_menu"></div>
<a href="/index.php"><img id="m_main" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/main.jpg" width="77" height="22" alt="Index"></a>
<a href="/custom.php"><img id="m_custom" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/custom.jpg" width="79" height="22" alt="Custom"></a>
<a href="/team.php"><img id="m_team" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/team.jpg" width="55" height="22" alt="Team"></a>
<a href="/league.php"><img id="m_league" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/league.jpg" width="79" height="22" alt="League"></a>
<a href="/country.php"><img id="m_country" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/country.jpg" width="92" height="22" alt="Country"></a>
<a href="/world.php"><img id="m_world" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/world.jpg" width="71" height="22" alt="World"></a>

<a href="/admin.php"><img id="m_admin" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/admin.jpg" width="73" height="24" alt="Feedback"></a>
<a href="/feedback.php"><img id="m_feedback" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/feedback.jpg" width="99" height="23" alt="Feedback"></a>
<a href="http://forum.buzzerbeaterstats.com"><img id="m_bbforums" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/bbforum.jpg" width="108" height="26" alt="Forum"></a>
<a href="/wiki/"><img id="m_bbwiki" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/bbwiki.jpg" width="89" height="26" alt="Wiki"></a>
<a href="http://www.buzzerbeaternews.com"><img id="m_bbnews" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/bbnews.jpg" width="77" height="26" alt="News"></a>

<?php
if ($_SESSION['loggedin'])
{
	?>
	<a href="/settings.php"><img id="m_settings" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/settings.jpg" width="89" height="22" alt="Settings"></a>
	<a href="/bblogout.php"><img id="loginout" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/logout.jpg" width="77" height="22" alt="Logout"></a>
	<?php
}
else
{
	?>
	<a href="/bblogin.php"><img id="loginout" src="<? echo $_SESSION['imgServ']; ?>/themes/nova/images/login.jpg" width="77" height="22" alt="Login"></a>
	<?php
}
?>