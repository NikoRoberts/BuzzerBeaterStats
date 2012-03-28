<?php
include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
//default DB thread
$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
$mysqli->set_charset("utf8");


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

function showBestTeam($weights,$team_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    
	$i = 0;
	$player = array(array($i,"no","spare player",1,1,1,1,1,1,1,1,1,1,1,1,1,1,1));
	//$player = array(array());
	
	$query = "
	SELECT
	PL.*, TM.name AS owner
	FROM
	player PL
	INNER JOIN `team` TM
	ON PL.team_id = TM.id
	WHERE
	`update` =
	(
	SELECT
	`update`
	FROM
	player P
	WHERE P.team_id='".$team_id."'
	order by `update` DESC
	LIMIT 1)
	AND
	TM.season  = '".$_SESSION['defaultSeason']."'
	AND
	PL.team_id='".$team_id."'
	AND (jumpshot <> 0
	OR stamina <> 0)
	ORDER BY PL.salary DESC LIMIT 8
	";
        
	$items = $mysqli->query($query);
	while ($currPlayer = mysqli_fetch_array($items))
	{
		$i++;		
		array_push($player,array($i,$currPlayer['firstname'],$currPlayer['lastname'],$currPlayer['gameshape'],$currPlayer['potential'],$currPlayer['jumpshot'],$currPlayer['range'],$currPlayer['outsidedef'],$currPlayer['handling'],$currPlayer['driving'],$currPlayer['passing'],$currPlayer['insideshot'],$currPlayer['insidedef'],$currPlayer['rebound'],$currPlayer['block'],$currPlayer['stamina'],$currPlayer['freethrow'],$currPlayer['experience']));
	}
	?><!-- Start list of players -->
	<div id="xsnazzy" class="playerbox">
	<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
	<div class="xboxcontent">
	<br />
    
    <center>
    <table><tr>
    <td>
    <h3><? echo $weights; ?>'s recommended Starting team</h3><?
	include("best.php");
	?><h3>Percentage of total possible score: <?
	echo $best_overall_total; ?>%</h3>
    
    </td><td width="50px"></td>
    <td>
	<?
    showSecondBestTeam($weights,$team_id,$player[$best_overall_pg][2],$player[$best_overall_sg][2],$player[$best_overall_sf][2],$player[$best_overall_pf][2],$player[$best_overall_c][2]);
	?>
	</td>
    </tr>
    </table>
    </center>
    
	<br />
	</div>
	<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
	</div>
	<?
        $mysqli->close();
}
function showSecondBestTeam($weights,$team_id,$firstplayer,$secondplayer,$thirdplayer,$fourthplayer,$fifthplayer)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    
	$i = 0;
	$player = array(array($i,"no","spare player",1,1,1,1,1,1,1,1,1,1,1,1,1,1,1));
	//$player = array(array());
	
	$query = "
	SELECT
	PL.*, TM.name AS owner
	FROM
	player PL
	INNER JOIN `team` TM
	ON PL.team_id = TM.id
	WHERE
	`update` =
	(
	SELECT
	`update`
	FROM
	player P
	WHERE P.team_id='".$team_id."'
	order by `update` DESC
	LIMIT 1)
	AND
	TM.season  = '".$_SESSION['defaultSeason']."'
	AND
	PL.team_id='".$team_id."'
	AND PL.lastname <> '".$firstplayer."'
	AND PL.lastname <> '".$secondplayer."'
	AND PL.lastname <> '".$thirdplayer."'
	AND PL.lastname <> '".$fourthplayer."'
	AND PL.lastname <> '".$fifthplayer."'
	ORDER BY PL.salary DESC LIMIT 8
	";
        
	$items = $mysqli->query($query);
	while ($currPlayer = mysqli_fetch_array($items))
	{
		$i++;		
		array_push($player,array($i,$currPlayer['firstname'],$currPlayer['lastname'],$currPlayer['gameshape'],$currPlayer['potential'],$currPlayer['jumpshot'],$currPlayer['range'],$currPlayer['outsidedef'],$currPlayer['handling'],$currPlayer['driving'],$currPlayer['passing'],$currPlayer['insideshot'],$currPlayer['insidedef'],$currPlayer['rebound'],$currPlayer['block'],$currPlayer['stamina'],$currPlayer['freethrow'],$currPlayer['experience']));
	}

	?><h3><? echo $weights; ?>'s recommended Backup team</h3><?
	include("best.php");
	?><h3>Percentage of total possible Score: <?
    echo $best_overall_total;
	?>%</h3><?
	$mysqli->close();
}
function showPlayers($team_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    
	// Include language file for translations
	include("./lang/lang-en.php");
	
	$query = "
	SELECT
	PL.*, TM.name AS owner
	FROM
	player PL
	INNER JOIN `team` TM
	ON PL.team_id = TM.id
	WHERE
	`update` =
	(
	SELECT
	`update`
	FROM
	player P
	WHERE P.team_id='".$team_id."'
	order by `update` DESC
	LIMIT 1)
	AND
	TM.season  = '".$_SESSION['defaultSeason']."'
	AND
	PL.team_id='".$team_id."'
	AND (jumpshot <> 0
	OR stamina <> 0)
	ORDER BY PL.jersey, PL.lastname
	";
        
	$items = $mysqli->query($query);
	while ($currPlayer = mysqli_fetch_array($items))
	{
		?>
			<!-- Start list of players -->
			<div id="xsnazzy" class="playerbox">
			<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
			<div class="xboxcontent">

			<div class="playerDiv">
                <table width="100%">
                	<tr>
                    <td align="left">
                    	<img title='<? echo $country[$currPlayer['nationality']]; ?>' src='http://<? echo $_SESSION['buzzImages']; ?>/images/flags/flag_<? echo $currPlayer['nationality']; ?>.gif' />
                    </td>
                    <td align="left">
                    	<? echo $currPlayer['firstname']." ".$currPlayer['lastname']." (".$currPlayer['id'].")"; ?>
                    </td>
                    <td align="center">
                    	<? echo bestPosition($currPlayer['jumpshot'],$currPlayer['range'],$currPlayer['outsidedef'],$currPlayer['handling'],$currPlayer['driving'],$currPlayer['passing'],$currPlayer['insideshot'],$currPlayer['insidedef'],$currPlayer['rebound'],$currPlayer['block'],$currPlayer['stamina'],$currPlayer['freethrow'],$currPlayer['experience'],0);?>
                    </td>
                    <td align="right">
						<? echo selectTrainees(
                        bestPosition($currPlayer['jumpshot'],$currPlayer['range'],$currPlayer['outsidedef'],$currPlayer['handling'],$currPlayer['driving'],$currPlayer['passing'],$currPlayer['insideshot'],$currPlayer['insidedef'],$currPlayer['rebound'],$currPlayer['block'],$currPlayer['stamina'],$currPlayer['freethrow'],$currPlayer['experience'],1),
                        $currPlayer['age'],
                        $currPlayer['height'],
                        $currPlayer['potential']);
                        ?>
                    </td>
                    </tr>
                </table>
			</div>
            
            
			<div class="playerBG" style="position:relative;">
			<table cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
			<td>
                <table id="rostable">
                <tbody>
                <tr>
                <td>
                
                <div id="face_and_jersey">
                <!-- <img id='jersey_face' src="images/face.jpg" /> -->
                <? if ($currPlayer['jersey']!=100)
                {
                    echo "<img id='jersey_ball' src='http://".$_SESSION['buzzImages']."/images/number_ball.png'>";
                    if (substr($currPlayer['jersey'],1,1)>-1)
                    {
                        echo "<div id='jersey_pair'><img src='http://".$_SESSION['buzzImages']."/images/number_".substr($currPlayer['jersey'],0,1).".png'>";
                        echo "<img src='http://".$_SESSION['buzzImages']."/images/number_".substr($currPlayer['jersey'],1,1).".png'></div>";	
                    }
                    else
                    {
                        echo "<img id='jersey_0' src='http://".$_SESSION['buzzImages']."/images/number_".substr($currPlayer['jersey'],0,1).".png'>";				
                    }
                }
                
                ?>
                </div>
                </td>
                <td width="180">
                <? if ($currPlayer['injury']) echo "<i id='injury' class='lev10'>injured</i><br>"; ?>
                Owner: <? echo $currPlayer['owner']; ?><br>
                Weekly salary: <? echo "$ ".number_format($currPlayer['salary'],0,""," "); ?><br>
                DMI: <? echo $currPlayer['dmi']; ?><br>
                Age: <? echo $currPlayer['age']; ?><br>
                Height: <?
                $height = 0;
                $cms = round($currPlayer['height'] * 2.54,0);
                $feet = floor($currPlayer['height']/12);
                $inches = $currPlayer['height']%12;
                $height = $feet."'".$inches.'" / '.$cms." cm";
                echo $height;
                ?><br>
                Potential: <? echo $potential[$currPlayer['potential']]; ?><br>
                Game Shape: <? echo $ratings[$currPlayer['gameshape']]; ?><br>
                </td>
                </tr>
                </tbody>
                </table>
            </td>
			<td>
                <table id="rostable">
                <tbody>
                <tr>
                <td> Jump Shot: <? echo $ratings[$currPlayer['jumpshot']]; ?> </td>
                <td> Jump Range: <? echo $ratings[$currPlayer['range']]; ?> </td>
                </tr>
                <tr>
                <td> Outside Def.: <? echo $ratings[$currPlayer['outsidedef']]; ?> </td>
                <td> Handling: <? echo $ratings[$currPlayer['handling']]; ?> </td>
                </tr>
                <tr>
                <td> Driving: <? echo $ratings[$currPlayer['driving']]; ?> </td>
                <td> Passing: <? echo $ratings[$currPlayer['passing']]; ?></td>
                </tr>
                <tr>
                <td> Inside Shot: <? echo $ratings[$currPlayer['insideshot']]; ?> </td>
                <td> Inside Def.: <? echo $ratings[$currPlayer['insidedef']]; ?> </td>
                </tr>
                <tr>
                <td> Rebounding: <? echo $ratings[$currPlayer['rebound']]; ?> </td>
                <td> Shot Blocking: <? echo $ratings[$currPlayer['block']]; ?> </td>
                </tr>
                <tr>
                <td> Stamina: <? echo $ratings[$currPlayer['stamina']]; ?> </td>
                <td> Free Throw: <? echo $ratings[$currPlayer['freethrow']]; ?> </td>
                </tr>
                
                <tr>
                <td>&nbsp;  </td>
                <td></td>
                </tr>
                <tr>
                <td> Experience: <? echo $ratings[$currPlayer['experience']]; ?> </td>
                <td></td>
                </tr>
                </tbody>
                </table>
             </td>
             </tr>
             </tbody>
             </table>
            
            <div id="placeholder<?php echo $currPlayer['id']; ?>" style="width:170px;height:155px;border:solid #000 1px;position:absolute;top:0px;right:5px;text-align:center;"><a href="javascript:loadGraphs<?php echo $currPlayer['id']; ?>('pts')">Show Graphs</a></div>
			
            <script type="text/javascript">
			var games;
			var minutes;
			var fgm;
			var fga;
			var tpm;
			var tpa;
			var ftm;
			var fta;
			var oreb;
			var reb;
			var ast;
			var turn;
			var stl;
			var blk;
			var pf;
			var pts;
			var rating;
			
			var timer;
			var timerCheck = 0;
			var showing = 0;
			
			function loadGraphs<?php echo $currPlayer['id']; ?>(dataType)
			{
				showing = 0; //make sure showing is reset
            	<?php
				$first = true;
				
				$games = "[";
				$minutes = "[";
				$fgm = "[";
				$fga = "[";
				$tpm = "[";
				$tpa = "[";
				$ftm = "[";
				$fta = "[";
				$oreb = "[";
				$reb = "[";
				$ast = "[";
				$turn = "[";
				$stl = "[";
				$blk = "[";
				$pf = "[";
				$pts = "[";
				$rating = "[";
				
				$query2 = " SELECT * 
						FROM  `player_stats` 
						WHERE playerid =  '".$currPlayer['id']."'
				";
                                $items2 = $mysqli->query($query2);
				while ($currStats = mysqli_fetch_array($items2))
				{
					if($first)
					{
						$comma = "";
						$first = false;
					}
					else
					{
						$comma = ",";
					}
					$games .= $comma."[".$currStats['season'].",".$currStats['games']."]";
					$minutes .= $comma."[".$currStats['season'].",".$currStats['minutes']."]";
					$fgm .= $comma."[".$currStats['season'].",".$currStats['fgm']."]";
					$fga .= $comma."[".$currStats['season'].",".$currStats['fga']."]";
					$tpm .= $comma."[".$currStats['season'].",".$currStats['tpm']."]";
					$tpa .= $comma."[".$currStats['season'].",".$currStats['tpa']."]";
					
					$ftm .= $comma."[".$currStats['season'].",".$currStats['ftm']."]";
					$fta .= $comma."[".$currStats['season'].",".$currStats['fta']."]";
					$oreb .= $comma."[".$currStats['season'].",".$currStats['oreb']."]";
					$reb .= $comma."[".$currStats['season'].",".$currStats['reb']."]";
					$ast .= $comma."[".$currStats['season'].",".$currStats['ast']."]";
					$turn .= $comma."[".$currStats['season'].",".$currStats['turn']."]";
					$stl .= $comma."[".$currStats['season'].",".$currStats['stl']."]";
					$blk .= $comma."[".$currStats['season'].",".$currStats['blk']."]";
					$pf .= $comma."[".$currStats['season'].",".$currStats['pf']."]";
					$pts .= $comma."[".$currStats['season'].",".$currStats['pts']."]";
					$rating .= $comma."[".$currStats['season'].",".$currStats['rating']."]";
				}
				$games .= "]";
				$minutes .= "]";
				$fgm .= "]";
				$fga .= "]";
				$tpm .= "]";
				$tpa .= "]";
				$ftm .= "]";
				$fta .= "]";
				$oreb .= "]";
				$reb .= "]";
				$ast .= "]";
				$turn .= "]";
				$stl .= "]";
				$blk .= "]";
				$pf .= "]";
				$pts .= "]";
				$rating .= "]";
				
				echo "games = ".$games.";\n";
				echo "minutes = ".$minutes.";\n";
				echo "fgm = ".$fgm.";\n";
				echo "fga = ".$fga.";\n";
				echo "tpm = ".$tpm.";\n";
				echo "tpa = ".$tpa.";\n";
				echo "ftm = ".$ftm.";\n";
				echo "fta = ".$fta.";\n";
				echo "oreb = ".$oreb.";\n";
				echo "reb = ".$reb.";\n";
				echo "ast = ".$ast.";\n";
				echo "turn = ".$turn.";\n";
				echo "stl = ".$stl.";\n";
				echo "blk = ".$blk.";\n";
				echo "pf = ".$pf.";\n";
				echo "pts = ".$pts.";\n";
				echo "rating = ".$rating.";\n";
				
				?>
				var choice;
				var lbl;
				
				if(dataType == "pts") { choice = pts; lbl = "Points >"; }
				if(dataType == "games") { choice = games; lbl = "Games >"; }
				if(dataType == "minutes") { choice = minutes; lbl = "Minutes >"; }
				if(dataType == "fgm") { choice = fgm; lbl = "Field Goals >"; }
				if(dataType == "tpm") { choice = tpm; lbl = "3 Points >"; }
				if(dataType == "oreb") { choice = oreb; lbl = "Off Rebounds >"; }
				if(dataType == "reb") { choice = reb; lbl = "Rebounds >"; }
				if(dataType == "ast") { choice = ast; lbl = "Assists >"; }
				if(dataType == "blk") { choice = blk; lbl = "Blocks >"; }
				if(dataType == "turn") { choice = turn; lbl = "Turnovers >"; }
				if(dataType == "pf") { choice = pf; lbl = "Fouls >"; }
				if(dataType == "stl") { choice = stl; lbl = "Steals >"; }
				
				$.plot($("#placeholder<?php echo $currPlayer['id']; ?>"), [
				{
					data: choice,
					selection: { mode: "x" },
					label: lbl,
					lines: { show: true, fill: true },
					legend: { position: 'ne' },
					points: { show: true }
				}]);
				
				$('.legend',"#placeholder<?php echo $currPlayer['id']; ?>").mouseenter(function()
				{
					if(timerCheck) clearTimeout(timer);
					if(!showing)
					{
						$(this).append("<div class='addedLabel' style='background-color:#fff;position:absolute;left:170px;top:13px;width:80px;border:solid #000 1px;margin:10px'><table>");
						
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"pts\")'>Points</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"games\")'>Games</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"minutes\")'>Minutes</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"tpm\")'>3 Points</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"ast\")'>Assists</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"blk\")'>Blocks</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"stl\")'>Steals</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"oreb\")'>Off Rebounds</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"reb\")'>Rebounds</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"fgm\")'>Field Goals</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"pf\")'>Fouls</a></td></tr>");
						$(".addedLabel",this).append("<tr class='addedOption'><td class='legendLabel'><a href='javascript:loadGraphs<?php echo $currPlayer['id']; ?>(\"turn\")'>Turnovers</a></td></tr>");
						$(this).append("</table></div>");
						
						$('.addedOption').mouseenter(function()
						{
							$(".legendLabel",this).css("background-color","#bbb");
						});
						$('.addedOption').mouseleave(function()
						{
							$(".legendLabel",this).css("background-color","#fff");
						});
						showing = 1;
						
						$('.addedLabel',this).mouseleave(function()
						{
							timer = setTimeout("removeMenu<?php echo $currPlayer['id']; ?>()",1000);
							timerCheck = 1;
						});
					}					
				});
			}
			function removeMenu<?php echo $currPlayer['id']; ?>()
			{
				$('.legend > div',"#placeholder<?php echo $currPlayer['id']; ?>").css("height","22px");
				$(".addedLabel","#placeholder<?php echo $currPlayer['id']; ?>").remove();
				showing = 0;
			}
            </script>
			</div>
			</div>
			
			</div>
			<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
			
			<?
	}
	?>
<?php
    $mysqli->close();
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
<?php

include("func_player.php");

if ($_SESSION['loggedin'])
{
	$updatenow=0;
	$query =
	"SELECT
	`update`,
	jumpshot
	FROM
	player P
	WHERE P.team_id='".$_SESSION['id']."'
	order by `update` DESC
	LIMIT 1";
	$items = $mysqli->query($query);
	$player = mysqli_fetch_array($items);

	//$difference = date("U", strtotime(date("Y-m-d H:i:s"))) - date("U", strtotime($player['update']));
	$difference = date('U') - $player['update'];
	$jumpshot = $player['jumpshot'];
	unset($player);
	?>
	<div id="xsnazzy" class="playerbox">
	<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
	<div class="xboxcontent">
	<p>&nbsp;</p>
	
	<center><h1><? echo googTrans("2_1_1_t","en",$lang,"Your Team"); ?></h1></center>
	<?
	
	// if latest update was < 1/2 day ago show force update
	//if ($difference > 360)
	if ($difference > $_SESSION['refreshTime'])
	{
		//force update button ?>	
		<div id="force_update">
		<form method="POST" name="updform" action="team.php">
		<input type="submit" value="Force Update">
		<input type="hidden" name="forcedcheck" value="1">
		</form>
		</div>
		<?
	}
	?><p><?
	//echo googTrans($identifier, $original_lang ,$destination_lang ,$text);
    echo googTrans("2_1_1_c","en",$lang,"This page now shows your Roster with a few extras. The top 2 sections show some cool team suggestions using calculations based on your players skills and the other players in your team. These are meant to be viewed as a guide for new players or a confirmation that the way you play is the right one.");
	?></p><p><?
    echo googTrans("2_1_2_c","en",$lang,"The differences between these lineups are because of the differences in weights that the creators have chosen.");
	?></p><p><?
    echo googTrans("2_1_3_c","en",$lang,"The BBStats Best Position is also calculated base on the weights created by JudgeNik");
	?></p><p><?
    echo googTrans("2_1_4_c","en",$lang,"The Value of Training rating is also rated in the same way but with age and potential added into the calculations.");
	?></p><p><?
    echo googTrans("2_1_5_c","en",$lang,"I hope you like it,<br/>JudgeNik");
    
     
    ?>
    </p>
	<p>&nbsp;</p>
	</div>
	<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
	</div>
	<?
		
	// if forced to update or roster is older than 3 days
	// DISABLED NOW TO STOP SALARIES PROBLEM
	
	if ($_REQUEST['forcedcheck'] || $difference > $_SESSION['refreshTime'] || $jumpshot == 0) 
	{
		$_SESSION['update']="now";
		updatePlayerSkills($_SESSION['id'],$_SESSION['leagueId'],$_SESSION['defaultSeason'],date('U'),$_SESSION['id'],$_SESSION['bbusername'],$_SESSION['bbaccesskey'],$_SESSION['buzzWebsite']);
                updatePlayerStats($_SESSION['id'],$_SESSION['leagueId'],$_SESSION['defaultSeason'],date('U'),$_SESSION['id'],$_SESSION['bbusername'],$_SESSION['bbaccesskey'],$_SESSION['buzzWebsite']);
        }        
	
	// then show players
	showBestTeam("Trikster",$_SESSION['id']);
	showBestTeam("JudgeNik v2",$_SESSION['id']);
	showPlayers($_SESSION['id']);
}
else
{
	?>
	<div id="xsnazzy" class="playerbox">
	<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
	<div class="xboxcontent">
	<p>&nbsp;</p>
	<center>
	Please login <a href="bblogin.php">HERE</a>.<br>
    <br>
    <i>You cannot see this page without logging in</i>
	</center>
	
	<p>&nbsp;</p>
	</div>
	<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
	</div>
    
    <div id="xsnazzy" class="playerbox">
	<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
	<div class="xboxcontent">
	<p>&nbsp;</p>
	
	<center><h1>Your Team</h1></center>
    <p>&nbsp;</p>
	</div>
	<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
	</div>
    
	<?
	//showPlayers(99999999);
}
$mysqli->close();
include("footer.php");
?>
