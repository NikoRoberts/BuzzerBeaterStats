<?php include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>


	<div id="xsnazzy">
	<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
	<div class="xboxcontent" style="text-align:center">
	<p>&nbsp;</p>
	
	<a href="/weightings.php">Weightings</a> | <a href="/formatter.php">Forum Formatter</a> | <a href="/pops.php">World Pop Graph</a> | <a href="/team.php">Team View</a>
	
	<p>&nbsp;</p>
	</div>
	<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
	</div>


<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?
if ($_SESSION['loggedin'] == true)
{
	//force update button
	?>
	<center><h1>Player Information</h1><br>
	<i>Put in your player information here to get information formatted for advertising on the forums.</i><br>
	<div id="convert_player">
	<form method="POST" name="convert_player" action="formatter.php">
	<textarea name="player" cols="70" rows="30"><?
	if ($_POST['playerleft']==1)
	{
		//$player = addslashes($_POST['player']);
		$player = $_POST['player'];
		//$player = str_replace("\r\n","<br>",$player);
		$player = str_replace("mediocre","[b]mediocre[/b]",$player);
		$player = str_replace("average","[b]average[/b]",$player);
		$player = str_replace("respectable","[b]respectable[/b]",$player);
		$player = str_replace("strong","[b]strong[/b]",$player);
		$player = str_replace("proficient","[b]proficient[/b]",$player);
		$player = str_replace("prominent","[b]prominent[/b]",$player);
		$player = str_replace("prolific","[b]prolific[/b]",$player);
		$player = str_replace("sensational","[b]sensational[/b]",$player);
		$player = str_replace("tremendous","[b]tremendous[/b]",$player);
		$player = str_replace("wondrous","[b]wondrous[/b]",$player);
		$player = str_replace("marvellous","[b]marvellous[/b]",$player);
		$player = str_replace("prodigious","[b]prodigious[/b]",$player);
		$player = str_replace("stupendous","[b]stupendous[/b]",$player);
		$player = str_replace("phenomenal","[b]phenomenal[/b]",$player);
		$player = str_replace("colossal","[b]colossal[/b]",$player);
		$player = str_replace("legendary","[b]legendary[/b]",$player);
		$player = stripslashes($player);
		$player = str_replace("(","[player=",$player);
		$player = str_replace(")","]",$player);
		echo utf8_decode($player);
		echo "\r\n [i]Information formatted on BBStats[/i]";
	}
	?></textarea><br>
	<input name="submit" type="submit" value="Submit Player">
	<input type="hidden" name="playerleft" value="1">
	</form>
	</div>
	<?
}
else
{
	?>
	<center>
	Please login <a href="bblogin.php">HERE</a>.
	</center>
	<?
}
?>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

<?php include("footer.php"); ?>