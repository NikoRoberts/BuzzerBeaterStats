<?php include("header.php"); 
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>

<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?
function showTopTeams($country_id,$season_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    
    echo "Season: ".$season_id."<br>";
    $query = "
    SELECT
    t.id, t.name, l.level, sum((t.wins+1)/(l.level+2)) as score, t.wins
    FROM
    team t
    INNER JOIN league l
    ON l.id = t.league
    WHERE l.season = '".$season_id."' AND l.countryid='".$country_id."'
    AND t.season = l.season
    GROUP BY t.id
    ORDER BY sum((t.wins+1)/(l.level+2)) DESC
    LIMIT 20
    ";
    $items = $mysqli->query($query) or die("Database Query Failed!".$query);
    while ($return = mysqli_fetch_array($items))
    {
            echo "Team: ".$return['name']."  Level: ".$return['level'].", Wins:  ".$return['wins']."  Score: ".number_format($return['score'],0,""," ")."<br>";
    }
    echo "<br><br>Season: ".($season_id-1)."<br>";
    $query = "
    SELECT
    t.id, t.name, l.level, sum((t.wins+1)/(l.level+2)) as score
    FROM
    team t
    INNER JOIN league l
    ON l.id = t.league
    WHERE l.season = '".($season_id-1)."' AND l.countryid='".$country_id."'
    AND t.season = l.season
    GROUP BY t.id
    ORDER BY sum((t.wins+1)/(l.level+2)) DESC
    LIMIT 20
    ";
    $items = $mysqli->query($query) or die("Database Query Failed!".$query);
    while ($return = mysqli_fetch_array($items))
    {
            echo "Team: ".$return['name']."  Level: ".$return['level']." Score: ".number_format($return['score'],0,""," ")."<br>";
    }
    echo "<br><br>Season: ".($season_id-2)."<br>";
    $query = "
    SELECT
    t.id, t.name, l.level, sum((t.wins+1)/(l.level+2)) as score
    FROM
    team t
    INNER JOIN league l
    ON l.id = t.league
    WHERE l.season = '".($season_id-2)."' AND l.countryid='".$country_id."'
    AND t.season = l.season
    GROUP BY t.id
    ORDER BY sum((t.wins+1)/(l.level+2)) DESC
    LIMIT 20
    ";
    $items = $mysqli->query($query) or die("Database Query Failed!".$query);
    while ($return = mysqli_fetch_array($items))
    {
            echo "Team: ".$return['name']."  Level: ".$return['level']." Score: ".number_format($return['score'],2,""," ")."<br>";
    }
    echo "<br><br>Season: ".($season_id-3)."<br>";
    $query = "
    SELECT
    t.id, t.name, l.level, sum((t.wins+1)/(l.level+2)) as score
    FROM
    team t
    INNER JOIN league l
    ON l.id = t.league
    WHERE l.season = '".($season_id-3)."' AND l.countryid='".$country_id."'
    AND t.season = l.season
    GROUP BY t.id
    ORDER BY sum((t.wins+1)/(l.level+2)) DESC
    LIMIT 20
    ";
    $items = $mysqli->query($query) or die("Database Query Failed!".$query);
    while ($return = mysqli_fetch_array($items))
    {
            echo "Team: ".$return['name']."  Level: ".$return['level']." Score: ".number_format($return['score'],0,""," ")."<br>";
    }
    
    $mysqli->close();
}

if ($_SESSION['loggedin'] == true)
{
	if ($_GET['country'])
	{
		$country_id = addslashes($_GET['country']);
	}
	else
	{
		$country_id = $_SESSION['countryId'];
	}
	if ($_GET['season'])
	{
		$season_id = addslashes($_GET['season']);
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
		$country_id=addslashes($_GET['country']);
	}
	else
	{
		echo "<br><i>Below is a random country to demonstrate the site's content.</i><br>";
		$country_id=rand(1,90);
	}
	if ($_GET['season'])
	{
		$season_id=addslashes($_GET['season']);
	}
	else
	{
		$season_id=$_SESSION['defaultSeason'];
	}
	
	$session_id=session_id();
	$session_username='API USERNAME';
	$session_key='API KEY';
	
	//get name
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	$query="SELECT id, name, divisions FROM countries WHERE id='".$country_id."'";
	$items = $mysqli->query($query) or die("Country Check Failed!");
	while($return = mysqli_fetch_array($items))
	{
		$country_name=$return['name'];
	}
        $mysqli->close();
	
	?></center><?
}

showTopTeams($country_id,$season_id);

?>
</center>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

<?php include("footer.php"); ?>
