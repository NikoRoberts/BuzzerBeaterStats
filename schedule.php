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
    <div id="xsnazzy">
    <b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
    <div class="xboxcontent" style="text-align:center">
    <p>&nbsp;</p>

    <a href="/weightings.php">Weightings</a><?php if ($_SESSION['loggedin']) { ?> | <a href="/schedule.php?season=<?php echo $season_id; ?>&amp;team=<?php echo $team_id; ?>">Schedule</a> | <a href="/team_overview.php?season=<?php echo $season_id; ?>&amp;team=<?php echo $team_id; ?>">Team Overview</a><?php } ?> | <a href="/pops.php">World Pop Graph</a> | <a href="/team.php">Team View</a>

    <p>&nbsp;</p>
    </div>
    <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
    </div>

<div id="xsnazzy" style="padding:10px;margin-top:5px;">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?php
echo '<div id="loader" style="margin:0 auto 0 auto; text-align:center;">Please Wait ... BBStats is loading this team\'s schedule<br>
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
<form method="POST" name="updform" action="schedule.php">
<input type="submit" value="Force Update"/>
<input type="hidden" name="team" value="<? echo $team_id; ?>"/>
<input type="hidden" name="season" value="<? echo $season_id; ?>"/>
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
        updateSchedule($team_id,$season_id,$update_time,$_SESSION['id'],$_SESSION['bbusername'],$_SESSION['bbaccesskey'],$_SESSION['buzzWebsite']);
    }
    else
    {
        $difference = date('U') - $league['timestamp'];
        if(($difference>$_SESSION['refreshTime']) || ($difference==date('U')))
        {
            updateSchedule($team_id,$season_id,$update_time,$_SESSION['id'],$_SESSION['bbusername'],$_SESSION['bbaccesskey'],$_SESSION['buzzWebsite']);
        }
    }
}
?>
<div style="margin:0 auto 0 auto">
    <div style="width:570px;margin:0 auto 0 auto;text-align:center"><h1>Schedule for <?php echo $team['name']." (".$team_id.")"; ?> in Season <?php echo $season_id; ?></h1></div>
<div style="text-align:right">
            <form>
                Season: <select id="sel_season" name="season">
                    <?php
			$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
        $query="
            SELECT
            season FROM league WHERE id = '".$league['id']."' GROUP BY season ORDER BY season ASC";
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
<table class="sortable" cellspacing="0" cellpadding="0" style="margin:0 auto 0 auto;width:650px;">
    <thead>
    <tr>
        <th width="170" valign="top" align="center"><a href="#">Away Team</a></th>
        <th width="50" valign="top" align="center"><a href="#">Score</a></th>
        <?php if($team_id) { ?><th width="30" valign="top" align="center"><a href="#">Eff.</a></th><?php } ?>
        <th width="170" valign="top" align="center"><a href="#">Home Team</a></th>
        <th width="50" valign="top" align="center"><a href="#">Score</a></th>
        <?php if($team_id) { ?><th width="30" valign="top" align="center"><a href="#">Eff.</a></th><?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
        $query="
            SELECT S.awayid ,S.awayname ,S.awayscore , S.homeid ,S.homename ,S.homescore ,S.type
            ,M.awayeffort ,M.homeeffort
            FROM schedule S
            LEFT OUTER JOIN `match` M
            ON S.match_id = M.id
            WHERE
            team_id='".addslashes($team_id)."'
            AND season='".addslashes($season_id)."'
            ORDER BY S.starttime ASC";
            $items = $mysqli->query($query) or die("Database Query Failed!<pre>".$query."</pre><br/>".mysqli_error($mysqli));
            $return = null;
            while ($return = mysqli_fetch_array($items))
            {                        
                switch($return['awayeffort'])
                {
                    case 'crunchTime':
                        $awayeffort = "CT";
                        break;
                    case 'normal':
                        $awayeffort = "N";
                        break;
                    case 'takeItEasy':
                        $awayeffort = "TIE";
                        break;
                    default:
                        $awayeffort = "";
                        break;
                }

                switch($return['homeeffort'])
                {
                    case 'crunchTime':
                        $homeeffort = "CT";
                        break;
                    case 'normal':
                        $homeeffort = "N";
                        break;
                    case 'takeItEasy':
                        $homeeffort = "TIE";
                        break;
                    default:
                        $homeeffort = "";
                        break;
                }
                
                ?>
                
                    <tr class="schedule_<?php echo str_replace(".","_",$return['type']); ?>">
                        <td valign="top" align="left"><a href="team_overview.php?team=<?php echo $return['awayid']; ?>"><? echo $return['awayname']; ?></a></td>
                        <td valign="top" align="left" class="<?php if($return['awayscore']>$return['homescore']) echo "bold"; ?>"><?php echo $return['awayscore']; ?></td>
                        <?php if ($team_id) { ?><td valign="top" align="left"><? if((strlen($awayeffort)>0) && (($_SESSION['id'] == $return['awayid'])||($_SESSION['id'] == $return['homeid']))) echo $awayeffort; ?></td><?php } ?>
                        
                        <td valign="top" align="left"><a href="team_overview.php?team=<?php echo $return['homeid']; ?>"><? echo $return['homename']; ?></a></td>
                        <td valign="top" align="left" class="<?php if($return['awayscore']<$return['homescore']) echo "bold"; ?>"><? echo $return['homescore']; ?></td>
                        <?php if ($team_id) { ?><td valign="top" align="left"><? if((strlen($homeeffort)>0) && (($_SESSION['id'] == $return['awayid'])||($_SESSION['id'] == $return['homeid']))) echo $homeeffort; ?></td><?php } ?>

                    </tr>
                    <?php
            }
            print_r($return);
            $mysqli->close();
    ?>
    </tbody>
</table>
</div>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
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
