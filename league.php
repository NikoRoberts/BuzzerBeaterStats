<?php include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>

<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?php
if ($_SESSION['loggedin'] == true)
{
	if ($_REQUEST['highlight'])
	{
		$team_id = $_REQUEST['highlight'];
	}
	else
	{
		$team_id = $_SESSION['id'];
	}
	if ($_REQUEST['league'])
	{
		$league_id = $_REQUEST['league'];
	}
	else
	{
		$league_id = $_SESSION['leagueId'];
	}
	if ($_REQUEST['season'])
	{
		$season_id = $_REQUEST['season'];
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
	if ($_REQUEST['highlight'])
	{
		$team_id = $_REQUEST['highlight'];
	}
	else
	{
		$team_id = $_SESSION['defaultTeam']; // default team
	}
	if ($_REQUEST['league'])
	{
		$league_id = $_REQUEST['league'];
	}
	else
	{
		$league_id = $_SESSION['defaultLeague']; // default league
		echo "<br><i>Below is a random league, to demonstrate the site content</i><br>";
	}
	if ($_REQUEST['season'])
	{
		$season_id = $_REQUEST['season'];
	}
	else
	{
		$season_id = $_SESSION['defaultSeason']; // default season
	}
	$session_id='SESSION ID'; //team id
	$session_username='API USERNAME';
	$session_key='API KEY';
	?></center><?
}



	//force update button
	?>
	<div id="force_update">
	<form method="POST" name="updform" action="league.php">
	<input type="submit" value="Force Update">
	<input type="hidden" name="league" value="<? echo $league_id; ?>">
	<input type="hidden" name="season" value="<? echo $season_id; ?>">
	<input type="hidden" name="forced" value="1">
	</form>
	</div>
	<?

	//only accept this as a POST var because otherwise Google will update the league manually
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	if ($_POST['forced']==1)
	{
            
		$query =
		"SELECT
		L.timestamp
		FROM
		league L
		WHERE
		L.id='".addslashes($league_id)."'
		AND L.season='".addslashes($season_id)."'
		";
		$items = $mysqli->query($query) or die("Database Query Failed!<pre>".$query."</pre>".mysqli_error($mysqli));
		$lpd = mysqli_fetch_array($items);
		$difference = date('U') - $lpd['timestamp'];
		//only accept a forced update every minute
		if ($difference > 600)
		{
			if($_SESSION['id']==38596) echo "Accepting forced update<br>";
			$session_update="now"; 
		}
		else
		{
			echo "<center>A forced update can only be processed in ".(10-(round($difference/60,2)))." minutes.</center>";
			$session_update="later";
		}
	}
	else
	{
		$query =
		"SELECT
		L.timestamp
		FROM
		league L
		WHERE
		L.id='".addslashes($league_id)."'
		AND L.season='".addslashes($season_id)."'
		";
		$items = $mysqli->query($query) or die("Database Query Failed!<pre>".$query."</pre>".mysqli_error($mysqli));
		$lpd = mysqli_fetch_array($items);
		$difference = date('U') - $lpd['timestamp'];
		if ($difference > $_SESSION['refreshTime'])
		{
			$session_update="now"; // greater than 24 hours then refresh
			if($_SESSION['id']==38596) echo "LEAGUE: Longer than 24 hours so updating..<br>";
		}
		else $session_update="later";
	}
        $mysqli->close();
	updateLeagueStats($league_id,$season_id,$session_update,$session_id,$session_username,$session_key,$_SESSION['buzzWebsite']); // update if logged in
?>

    <?php $leagueInfo = GetLeagueInfo($league_id,$season_id); ?>
    <div id="leagueShowHide">
        <div><a href="javascript:animatedcollapse.show(['teamtotals', 'totalsalaries', 'totalpoints', 'total3pointers', 'totalsteals', 'totalrebounds', 'totalfreethrows', 'totalblocks', 'totalassists', 'totalsteals', 'totalsteals'])">Show All Sections</a></div><br/>
        <div><a href="javascript:animatedcollapse.hide(['teamtotals', 'totalsalaries', 'totalpoints', 'total3pointers', 'totalsteals', 'totalrebounds', 'totalfreethrows', 'totalblocks', 'totalassists', 'totalsteals', 'totalsteals'])">Hide All Sections</a></div>
    </div>
        
    <div class="centered">
        <h1>League Leaders from <?php echo $leagueInfo['name']; ?></h1>
        <div><a href="/world.php">World</a>-&gt;<a href="country.php?country=<?php echo $leagueInfo['countryid']; ?>"><?php echo $leagueInfo['country']; ?></a>-&gt;<a href="league.php?league=<?php echo $leagueInfo['id']; ?>&amp;season=<?php echo $season_id; ?>"><?php echo $leagueInfo['name']." (".$leagueInfo['id'].")"; ?></a></div>
        <div style="text-align:right">
            <form>
                Season: <select id="sel_season" name="season">
                    <?php
			$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
        $query="
            SELECT
            season FROM league WHERE id = 1041 GROUP BY season ORDER BY season ASC";
            $items = $mysqli->query($query) or die("Database Query Failed!<pre>".$query."</pre><br/>".mysqli_error($mysqli));
            $return = null;
            while ($return = mysqli_fetch_array($items))
            {
                ?>
                    <option <?php if($season_id==$return['season']) echo "selected"; ?>><?php echo $return['season']; ?></option>
                <?php
            }
                    ?>
                </select>
            </form>
        </div>
	<div id="statsDivs">
            <? showTeamTotals($team_id,$league_id,$season_id); ?>
            <? showTotalSalaries($team_id,$league_id,$season_id); ?>
            <? showTotalPoints($team_id,$league_id,$season_id); ?>
            <? showTotal3Pointers($team_id,$league_id,$season_id); ?>
            <? showTotalRebs($team_id,$league_id,$season_id) ?>
            <? showTotalAsts($team_id,$league_id,$season_id); ?>
            <? showTotalSteals($team_id,$league_id,$season_id); ?>
            <? showTotalBlks($team_id,$league_id,$season_id); ?>
            <? showTotalFreeThrows($team_id,$league_id,$season_id); ?>
	</div>
    </div><!-- end centered -->
<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

<?php include("footer.php"); ?>
<script type="text/javascript">
$('#sel_season').change(function()
{
    window.location = "<? echo $_SERVER['PHP_SELF']; ?>?season="+$(this).val()+"<?php if($team_id) echo "&league=".$league_id; ?>";
});
</script>
