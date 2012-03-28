<?php
function showCountries($session_buzz)
{
	// Include language file for translations
	//include("./lang/lang-en.php");
	?>
	<center>
	<table class="sortable">
	<thead>
	<tr>
        <th class="header"><a href="#">#</a></td>
        <th class="header">&nbsp;</td>
        <th class="header" align="center"><a href="#">Name</a></td>
        <th class="header" align="center"><a href="#">Divisions</a></td>
        <th class="header" align="center"><a href="#">First Season</a></td>
        <th class="header" align="center"><a href="#">Users</a></td>
	</tr>
	</thead>
	<tbody>
	<?
	$i=0;//counter for row
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	$query = "
	SELECT
	c.id, c.name, c.divisions, c.firstSeason, c.users
	FROM
	countries c
	ORDER BY id ASC
	";
	$items = $mysqli->query($query);
	while ($return = mysqli_fetch_array($items))
	{
		?>
		<tr>
		<td align="center"><a href="country.php?country=<? echo $return['id']; ?>" rel="nofollow"><? echo $return['id']; ?></a></td>
		<td><a href="country.php?country=<? echo $return['id']; ?>" rel="nofollow"><img border=0 title='<? echo $return['name']; ?>' src='http://<? echo $_SESSION['buzzImages']; ?>/images/flags/flag_<? echo $return['id']; ?>.gif' /></a></td>
		<td><a href="country.php?country=<? echo $return['id']; ?>" rel="nofollow"><?
		if ($return['translated']) echo $return['translated'];
		else echo $return['name']; ?></a></td>
		<td align="center"><? echo $return['divisions']; ?></td>
		<td align="center"><? echo $return['firstSeason']; ?></td>
		<td align="center"><? echo $return['users']; ?></td>
		</tr>
		<?
	}
	?>
	</tbody>
	</table>
	</center>
	<?
        $mysqli->close();
}


include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];

?>
<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent" style="text-align:center">
<p>&nbsp;</p>

<a href="/ntscout.php">NT Scouter</a> | <a href="/visitors.php">Visitor Breakdown</a> | <a href="/world.php">World View</a>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>


<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?

	if ($_SESSION['id']==38596){
            echo "UPDATING COUNTRIES.";
            updateCountries($update_time,$_SESSION['id'],$_SESSION['bbusername'],$_SESSION['bbaccesskey'],$_SESSION['buzzWebsite']);
        }

	?>
	<center><h1>Countries of the World</h1></center>
	<?
	
	showCountries($_SESSION['buzzWebsite']);


?>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

<?php include("footer.php"); ?>
