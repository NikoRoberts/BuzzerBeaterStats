<?php include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];


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
        if ($_REQUEST['team'])
	{
		$team_id = $_REQUEST['team'];
	}
	else
	{
		$team_id = $_SESSION['defaultTeam'];
	}
	$session_id=$_SESSION['id'];
	$session_username=$_SESSION['bbusername'];
	$session_key=$_SESSION['bbaccesskey'];
}
?>
    <div id="xsnazzy" class="xsnazzy cornered">
    <b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
    <div class="xboxcontent" style="text-align:center">
    <p>&nbsp;</p>

    <a href="/weightings.php">Weightings</a><?php if ($_SESSION['loggedin']) { ?> | <a href="/schedule.php?season=<?php echo $season_id; ?>&amp;team=<?php echo $team_id; ?>">Schedule</a> | <a href="/team_overview.php?season=<?php echo $season_id; ?>&amp;team=<?php echo $team_id; ?>">Team Overview</a><?php } ?> | <a href="/pops.php">World Pop Graph</a> | <a href="/team.php">Team View</a>

    <p>&nbsp;</p>
    </div>
    <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
    </div>

<div id="xsnazzy" class="xsnazzy cornered" style="padding:10px;margin-top:5px;">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?php
echo '<div id="loader" style="margin:0 auto 0 auto; text-align:center;">Please Wait ... BBStats is loading this team\'s overview<br>
        <img id="loader" src="/themes/'.$_SESSION['theme'].'/images/ajax-loader.gif" alt="Loading"></div>';
flush(); //flush everything to the browser that has been loaded so far

if ($_REQUEST['team'])
{
        $team_id = intval($_REQUEST['team']);
}
else
{
        $team_id = $_SESSION['id'];
}
if ($_REQUEST['season'])
{
        $season_id = intval($_REQUEST['season']);
}
else
{
        $season_id = $_SESSION['defaultSeason'];
}
//force update button
?>
<div id="force_update">
<form method="POST" name="updform" action="team_overview.php">
<input type="submit" value="Force Update"/>
<input type="hidden" name="team" value="<?php echo $team_id; ?>"/>
<input type="hidden" name="season" value="<?php echo $season_id; ?>"/>
<input type="hidden" name="forced" value="1"/>
</form>
</div>
<?php
    $team = GetTeamInfo($team_id,$season_id);
    $league = GetLeagueInfo($team['leagueid'],$season_id);
    if($_SESSION['loggedin'])
    {
        if ($_REQUEST['forced']==1)
        {        
            updatePlayerStats($team_id, $league_id, $season_id, $update_time, $_SESSION['id'],$_SESSION['bbusername'],$_SESSION['bbaccesskey'],$_SESSION['buzzWebsite']);

            updateSchedule($team_id,$season_id,$update_time,$_SESSION['id'],$_SESSION['bbusername'],$_SESSION['bbaccesskey'],$_SESSION['buzzWebsite']);
        }
        else
        {
            $difference = date('U') - $league['timestamp'];
            if($difference>$_SESSION['refreshTime'])
            {
                updatePlayerStats($team_id, $league_id, $season_id, $update_time, $_SESSION['id'],$_SESSION['bbusername'],$_SESSION['bbaccesskey'],$_SESSION['buzzWebsite']);

                updateSchedule($team_id,$season_id,$update_time,$_SESSION['id'],$_SESSION['bbusername'],$_SESSION['bbaccesskey'],$_SESSION['buzzWebsite']);
            }
        }
    }
?>
<div style="margin:0 auto 0 auto;overflow:auto">
    <div style="width:570px;margin:0 auto 0 auto;text-align:center"><h1>Team Overview for <?php echo $team['name']." (".$team_id.")"; ?> in Season <?php echo $season_id; ?></h1></div>
    <div style="margin:0 auto 0 auto;width:650px;">
        <div style="text-align:right">
            <form>
                Season: <select id="sel_season" name="season">
                    <?php
			$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
        $query="
            SELECT
            season FROM league WHERE id IN (1,1041,1042,2,3) GROUP BY season";
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
        <div class="team_stats">
    <?php
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
        $query="
            SELECT
            SUM(PS.fga) AS fga, SUM(PS.fgm) AS fgm
            ,SUM(PS.tpa) AS tpa, SUM(PS.tpm) AS tpm
            ,SUM(PS.reb) AS reb
            ,SUM(PS.oreb) AS oreb
            ,SUM(PS.ast) AS ast
            ,SUM(PS.stl) AS stl
            ,SUM(PS.turn) AS turn
            ,SUM(PS.blk) AS blk
            ,SUM(PS.pf) AS pf
            FROM team T
            INNER JOIN player P
            ON P.team_id = T.id
            INNER JOIN player_stats PS
            ON PS.playerid = P.id
            AND PS.season = T.season
            WHERE T.id = '".addslashes($team_id)."' AND T.season = '".addslashes($season_id)."'
                GROUP BY T.id";
            $items = $mysqli->query($query) or die("Database Query Failed!<pre>".$query."</pre><br/>".mysqli_error($mysqli));
            $return = null;
            while ($return = mysqli_fetch_array($items))
            {
                ?>
                <table class="sortable" cellspacing="0" cellpadding="0" style="margin:0 auto 0 auto;width:650px;">
                    <tr><td>FG:</td><td><?php echo $return['fgm']; ?>/<?php echo $return['fga']; ?></td>
                   <td>FG%:</td><td> <?php echo number_format(100*(($return['fgm']+1)/($return['fga']+1)),2,"."," ")."%"; ?></td></tr>
                    <tr><td>3P:</td><td> <?php echo $return['tpm']; ?>/<?php echo $return['tpa']; ?></td>
                    <td>3P%:</td><td> <?php echo number_format(100*(($return['tpm']+1)/($return['tpa']+1)),2,"."," ")."%"; ?></td></tr>
                    <tr><td>Rebounds:</td><td> <?php echo $return['reb']; ?></td>
                    <td>Offensive Rebounds:</td><td> <?php echo $return['oreb']; ?></td></tr>
                    <tr><td>Assists:</td><td> <?php echo $return['ast']; ?></td>
                    <td>Steals:</td><td> <?php echo $return['stl']; ?></td></tr>
                    <tr><td>Blocks:</td><td> <?php echo $return['blk']; ?></td>
                    <td>Turnovers:</td><td> <?php echo $return['turn']; ?></td></tr>
                    <tr><td>Personal Fouls:</td><td> <?php echo $return['pf']; ?></td></tr>
                </table>
                <?php
            }
            ?>
        </div>
        <div style="float:left;width:320px;"><table class="sortable" cellspacing="0" cellpadding="0" style="margin:7px auto 5px 0px;width:320px;">
            <?php
            $query = "
                SELECT '_Home Strategies' AS name, '' AS count;
                SELECT '_&nbsp;&nbsp;Offensive' AS name, '' AS count;
            SELECT
              CONCAT('&nbsp;&nbsp;&nbsp;&nbsp;',M.homeoffStrategy) AS name
              ,COUNT(M.id)AS count
            FROM `match` M
            INNER JOIN schedule S
              ON S.match_id = M.id
            WHERE
             M.homeid='".addslashes($team_id)."'
             AND S.season = '".addslashes($season_id)."'
             GROUP BY M.homeoffStrategy; ";
            
             $query .= "
                SELECT '_&nbsp;&nbsp;Defensive' AS name, '' AS count;
            SELECT
              CONCAT('&nbsp;&nbsp;&nbsp;&nbsp;',M.homedefStrategy) AS name
              ,COUNT(M.id) AS count
            FROM `match` M
            INNER JOIN schedule S
              ON S.match_id = M.id
            WHERE
             M.homeid='".addslashes($team_id)."'
             AND S.season = '".addslashes($season_id)."'
             GROUP BY M.homedefStrategy; ";
             
             $query .= "
                SELECT '__Away Strategies' AS name, '' AS count;
                SELECT '_&nbsp;&nbsp;Offensive' AS name, '' AS count;
            SELECT
              CONCAT('&nbsp;&nbsp;&nbsp;&nbsp;',M.awayoffStrategy) AS name
              ,COUNT(M.id)AS count
            FROM `match` M
            INNER JOIN schedule S
              ON S.match_id = M.id
            WHERE
             '&nbsp;&nbsp;&nbsp;&nbsp;'+M.awayid='".addslashes($team_id)."'
             AND S.season = '".addslashes($season_id)."'
             GROUP BY M.awayoffStrategy; ";
            
             $query .= "
                SELECT '_&nbsp;&nbsp;Defensive' AS name, '' AS count;
            SELECT
              CONCAT('&nbsp;&nbsp;&nbsp;&nbsp;',M.awaydefStrategy) AS name
              ,COUNT(M.id) AS count
            FROM `match` M
            INNER JOIN schedule S
              ON S.match_id = M.id
            WHERE
             M.awayid='".addslashes($team_id)."'
             AND S.season = '".addslashes($season_id)."'
             GROUP BY M.awaydefStrategy; ";
            if ($mysqli->multi_query($query)) {
                do {
                    /* store first result set */
                    if ($result = $mysqli->store_result()) {
                        while ($row = $result->fetch_row()) {
                            if(substr($row[0],0,2)=="__") echo '</table></div><div style="display:inline;width:320px;"><table class="sortable" cellspacing="0" cellpadding="0" style="margin:7px 0px 5px auto;width:320px;">';
                            ?>
                                
                            <tr class="<?php if(substr($row[0],0,1)=="_") echo "trhead"; else echo "tr"; ?>"><td><?php echo str_replace("_","",$row[0]); ?></td><td><?php echo $row[1]; ?></td></tr>
                            
                            <?php
                        }
                        $result->free();
                    }
                    /* print divider 
                    if ($mysqli->more_results()) {
                        printf("-----------------\n");
                    }*/
                } while ($mysqli->next_result());
            } ?>
            </table></div>
            <?php
            $mysqli->close();
    ?>
       </div>
    </div>
</div>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>
</div>
</div>

<?php
echo '<script type="text/javascript">document.getElementById("loader").style.display="none"</script>';
include("footer.php");
?>
<script type="text/javascript">
$('#sel_season').change(function()
{
    window.location = "<? echo $_SERVER['PHP_SELF']; ?>?season="+$(this).val()+"<?php if($team_id) echo "&team=".$team_id; ?>";
});
</script>
