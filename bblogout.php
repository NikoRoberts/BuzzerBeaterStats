<?
include("header.php"); 
	$flag = 0;
	if ($_SESSION['loggedin']==true) $flag=1;
	$_SESSION['loggedin'] = false;
	$_SESSION['id']=NULL;
	$_SESSION['season']=NULL;
	$_SESSION['leagueId']=NULL;
	$_SESSION['countryId']=NULL;
	session_unregister('id');
	session_unregister('bbusername');
	session_unregister('bbaccesskey');
	
	session_unregister('teamName');
	session_unregister('teamAbbr');
	session_unregister('owner');
	session_unregister('supporter');
	session_unregister('league');
	session_unregister('leagueId');
	session_unregister('leagueLevel');
	session_unregister('leagueName');
	session_unregister('country');
	session_unregister('countryId');


?>
<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>
<center>
<?

if ($flag=1)
{
	?>
	You have successfully Logged Out.<br>
    Click <a href="bblogin.php">HERE</a> to login again.
	<?
}
else
{
	?>
	Please login <a href="bblogin.php">HERE</a>.
	<?
}

?>
</center>
<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>
<? 
include("footer.php"); 
?>
