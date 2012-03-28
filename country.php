<?php include("header.php"); 
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>

<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?

function showLeagues($country_id,$season_id)
{
	// Include language file for translations
	//include("./lang/lang-en.php");
	?>
	<div style="margin:0 auto 0 auto;">
	<?
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
    
	$level=0;
	$query = "
	SELECT
	l.level, l.id, l.name, l.timestamp
	FROM
	league l
	WHERE l.season = '".$season_id."' AND l.countryid='".$country_id."'
	ORDER BY l.level,l.id
	";
	$items = $mysqli->query($query) or die("Database Query Failed!<pre>".$query."</pre>".mysqli_error($mysqli));
	while ($return = mysqli_fetch_array($items))
	{
		
		if ($return['level'] != $level)
		{
			$level=$return['level'];
			?>
			</tbody>
			</table>
			<table class="sortable">
			
			<thead>
			<tr><td colspan=3 style="text-align:center; font-weight:bold;">Level <? echo $level; ?></td></tr>
			<tr>
			<th class="header"><a href="#">League ID</a></th>
			<th class="header"><a href="#">League Name</a></th>
            <th class="header"><a href="#">Last Update</a></th>
			</tr>
			</thead>
			<tbody>
		<? } ?>
		<tr>
		<td align="center"><a href="league.php?league=<? echo $return['id']; ?>&season=<? echo $season_id; ?>"><? echo $return['id']; ?></a></td>
		<td align="center"><a href="league.php?league=<? echo $return['id']; ?>&season=<? echo $season_id; ?>"><? echo $return['name']; ?></a></td>
        <td><?
		if ($return['timestamp']) echo date('Y-m-d', $return['timestamp']);
		else echo 'never'; ?></td>
		</tr>
		<?
	}
        $mysqli->close();
	?>
	</tbody>
	</table>
	</div>
	<?
}

if ($_SESSION['loggedin'] == true)
{
	if ($_GET['country'])
	{
		$country_id = $_GET['country'];
	}
	else
	{
		$country_id = $_SESSION['countryId'];
	}
	if ($_GET['season'])
	{
		$season_id = $_GET['season'];
	}
	else
	{
		$season_id = $_SESSION['defaultSeason'];
	}
	
	$session_id=$_SESSION['id'];
	$session_username=$_SESSION['bbusername'];
	$session_key=$_SESSION['bbaccesskey'];
}

else
{
	?>
	<center>
	Please login <a href="bblogin.php">HERE</a>.<br>
    <?
	if ($_GET['country'])
	{
		$country_id=$_GET['country'];
	}
	else
	{
		echo "<br><i>Below is a random country to demonstrate the site's content.</i><br>";
		$country_id=rand(1,90);
	}
	if ($_GET['season'])
	{
		$season_id=$_GET['season'];
	}
	else
	{
		$season_id=$_SESSION['defaultSeason'];
	}
	
	$session_id=session_id();
	$session_username='API USERNAME';
	$session_key='API KEY';
	
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	//get name
	$query="SELECT id, name, divisions FROM countries WHERE id='".$country_id."'";
	$items = $mysqli->query($query) or die("Country Check Failed!");
	while($return = mysqli_fetch_array($items))
	{
		$country_name=$return['name'];
	}
	$mysqli->close();
	?></center><?
}

echo '<div id="loader"><center>Please Wait ... BBStats is updating the leagues in this country<br>
	<img id="loader" src="/themes/'.$_SESSION['theme'].'/images/ajax-loader.gif" alt="Loading"></center></div>';
flush(); //flush everything to the browser that has been loaded so far
$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
$mysqli->set_charset("utf8");
$query =
"SELECT
timestamp
FROM
latest_posting lpd
WHERE lpd.type='country'
AND id='".$country_id."'";
$items = $mysqli->query($query) or die("Database Query Failed!<pre>".$query."</pre>".mysqli_error($mysqli));
$country = mysqli_fetch_array($items);

//$difference = date("U", strtotime(date("Y-m-d H:i:s"))) - date("U", strtotime($player['update']));
$difference = date('U') - $country['timestamp'];

$update_time=date('U');
$manager = new ThreadManager; // create thread manager
$query="SELECT id, name, divisions FROM countries WHERE id='".$country_id."'";
$items = $mysqli->query($query) or die("Country Check Failed!");
while($return = mysqli_fetch_array($items))
{
	$country_name=$return['name'];
	if($difference > 86400)
	{
		for($i=1;$i<=$return['divisions'];$i++)
		{
			//Update leagues in a country
			//$manager->create_thread('updateAllLeagues', array($return['id'],$i,$season_id,$update_time,$session_id,$session_username,$session_key,$_SESSION['buzzWebsite']));
			updateAllLeagues($return['id'],$i,$season_id,$update_time,$session_id,$session_username,$session_key,$_SESSION['buzzWebsite']);
		}
	}
}
while ($manager->query());
$mysqli->close();
echo '<script type="text/javascript">document.getElementById("loader").style.display="none"</script>';


?><center><h2>League Listing</h2>
<h3>Country: <? echo $country_name; ?></h3><?
showLeagues($country_id,$season_id);
?>
</center>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

<?php include("footer.php"); ?>
