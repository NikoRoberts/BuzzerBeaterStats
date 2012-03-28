<?php
//connect to SQL database
$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
$mysqli->set_charset("utf8");

function showStats($team_id,$season_id,$league_id,$item,$order,$limit)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
	$mysqli->set_charset("utf8");
	if ( is_nan($season_id) || is_nan($league_id) || is_nan($limit) || !(($order == "ASC") || ($order == "DESC")) || (strlen($item) > 20) ) die;
	
	$normItem = array();
	$normItem['games'] = "Games Played";
	$normItem['minutes'] = "Minutes";
	$normItem['fgm'] = "Field Goals Made";
	$normItem['fga'] = "Field Goals Attempted";
	$normItem['tpm'] = "Three Points Made";
	$normItem['tpa'] = "Three Points Attempted";
	$normItem['ftm'] = "Free Throws Made";
	$normItem['fta'] = "Free Throws Attempted";
	$normItem['oreb'] = "Offensive Rebounds";
	$normItem['reb'] = "Rebounds";
	$normItem['ast'] = "Assists";
	$normItem['turn'] = "Turn-overs";
	$normItem['stl'] = "Steals";
	$normItem['blk'] = "Blocks";
	$normItem['pf'] = "Personal Fouls";
	$normItem['pts'] = "Points";
	$normItem['rating'] = "Rating";
	$normItem['salary'] = "Salary";
	$normItem['potential'] = "Potential";
	?>
    <div class="leagueTable">
    <h2>Total <? echo $normItem[$item]; ?> in Season <? echo $season_id; ?></h2>
	<table class="sortable">
	<thead>
	<tr>
		<th><a href="#">#</a></th>
		<th><a href="#">Player</a></th>
		<th><a href="#">Team</a></th>
		<th><a href="#"><? echo $normItem[$item]; ?></a></th>
        <? if (($item != 'salary') && ($item != 'potential'))
		{ ?>
			<th><a href="#"><? echo $normItem[$item]; ?> per 48mins</a></th>
			<th><a href="#"><? echo $normItem[$item]; ?> per Game</a></th>
        <? } ?>
        </tr>
	</thead>
	<tbody>
	<?
	$i = 0;
	$query = "SELECT
	*
	FROM
	player_stats PS
	INNER JOIN player PL
	ON PS.teamid = PL.team_id
	AND PS.playerid = PL.id
	INNER JOIN team TM
	ON TM.id = PS.teamid
	AND TM.season = PS.season
	WHERE
	PS.leagueid='".addslashes($league_id)."'
	AND PS.season='".addslashes($season_id)."'
	ORDER BY PS.".addslashes($item)." ".addslashes($order)."
	LIMIT ".addslashes($limit)."";
	
	$items = $mysqli->query($query) or die("Player Stats Query Failed!".$query);
        //if($_SESSION['id']==38596) echo $query;
	while ($stats = mysqli_fetch_array($items))
	{	
		$i++;
		if ($team_id == $team['id']) $textColour = "select_td";
		else $textColour = "";
		?>
		<tr>
			<td class="<? echo $textColour; ?>"><? echo str_pad((int) $i,2,"0",STR_PAD_LEFT).". "; ?></td>
			<td class="<? echo $textColour; ?>"><? echo $stats['firstname']." ".$stats['lastname']; ?></td>
			<td class="<? echo $textColour; ?>"><? echo $stats['name']; ?></td>
			<td class="<? echo $textColour; ?>" style="text-align:center"><? echo $stats[$item]; ?></td>
            <? if (($item != 'salary') && ($item != 'potential'))
			{ ?>
				<td class="<? echo $textColour; ?>" style="text-align:center"><? if ($stats['minutes']) echo number_format($stats[$item]/$stats['minutes']*48,2); else echo "0"; ?></td>
				<td class="<? echo $textColour; ?>" style="text-align:center"><? if ($stats['games']) echo number_format($stats[$item]/$stats['games'],2); else echo "0"; ?></td>
            <? } ?>

		</tr>
		<?
	}
	?>
	</tbody>
    </table>
	</div><?
        $mysqli->close();
}

function showTotalPoints($team_id,$league_id,$season_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
	$mysqli->set_charset("utf8");
	?>
	<div class="leagueTable" id=>
	<h2><a href="javascript:animatedcollapse.toggle('totalpoints')">Total Points in Season <?php echo $season_id; ?></a></h2>
	<div id="totalpoints">
	<table class="sortable">
	<thead>
	<tr>
		<th><a href="#">#</a></th>
		<th><a href="#">Player</a></th>
		<th><a href="#">Team</a></th>
		<th><a href="#">Points</a></th>
		<th><a href="#">FG%</a></th>
		<th><a href="#">Pts/48Min</a></th>
		<th><a href="#">Pts/Game</a></th>
	</tr>
	</thead>
	<tbody>
	<?
	$i = 0;
	$query = "SELECT
            PS.pts, PS.fga, PS.fgm, PS.minutes, PS.games
            ,P.firstname, P.lastname
            ,T.id AS team_id, T.name AS team_name
            FROM player_stats PS
            INNER JOIN player P
            ON PS.playerid = P.id
            AND PS.teamid = P.team_id
            INNER JOIN team T
            ON P.team_id = T.id
            AND PS.season = T.season
            WHERE
            PS.season = ".addslashes($season_id)."
            AND PS.leagueid = ".addslashes($league_id)."
            ORDER BY pts DESC
            LIMIT 20
            ";
	$items = $mysqli->query($query) or die("Player Stats Query Failed!");
	while ($results = mysqli_fetch_array($items))
	{
            if ($team_id == $results['team_id']) $textColour = "select_td";
            else $textColour = "";
            $i++;
            ?>
            <tr>
                    <td class="<? echo $textColour; ?>" ><? echo str_pad((int) $i,2,"0",STR_PAD_LEFT).". "; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $results['firstname']." ".$results['lastname']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $results['team_name']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $results['pts']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? if ($results['fga'] > 0) echo number_format(100*($results['fgm']/$results['fga']),2,"."," ")."%"; ?></td>
                    <td class="<? echo $textColour; ?>" ><? if ($results['fga'] > 0) echo number_format(($results['pts']/$results['minutes'])*48,2,"."," "); ?></td>
                    <td class="<? echo $textColour; ?>" ><? if ($results['fga'] > 0) echo number_format(($results['pts']/$results['games']),2,"."," "); ?></td>

            </tr>
            <?php
	}
	?>
	</tbody>
    </table>
    </div>
    </div><?
        $mysqli->close();
}

function showTotalSalaries($team_id,$league_id,$season_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
	$mysqli->set_charset("utf8");
	?>
    
	<div class="leagueTable">
	<h2><a href="javascript:animatedcollapse.toggle('totalsalaries')">Total Salaries in Season <?php echo $season_id; ?></a></h2>
	<div id="totalsalaries">
	<table class="sortable">
	<thead>
	<tr>
		<th  width="20" ><a href="#">#</a></th>
		<th  width="200" ><a href="#">Team</a></th>
		<th  width="80" ><a href="#">Players</a></th>
		<th  width="80" ><a href="#">Salary Tot</a></th>
		<th  width="80" ><a href="#">Average</a></th>
      	<th  width="80" ><a href="#">Std Dev.</a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	$query = "SELECT TM.id, TM.name
            , max(PL.update) as playerup
            , sum(PL.salary) as salarytot
            , count(PL.id) AS playercount
            , AVG(PL.salary) as salaryavg
            , STDDEV_SAMP(PL.salary) as salarydev
	FROM player PL
	RIGHT OUTER JOIN team TM
	ON PL.team_id = TM.id
	WHERE
	TM.league='".addslashes($league_id)."'
	AND TM.season='".addslashes($season_id)."'
	AND PL.update = (
            SELECT
            MAX(P.update) 
            FROM player P
            WHERE P.team_id =  TM.id
        )
	GROUP BY TM.id
	ORDER BY sum(PL.salary) DESC
	";
	$items = $mysqli->query($query) or die("Team Salary Summary Failed!<pre>".$query."</pre><br/>".mysqli_error($mysqli));
        //if($_SESSION['id']==38596) echo "<br/>".$query;
	while ($return = mysqli_fetch_array($items))
	{
		$top5tot = 0;
		$query2 = "";
                //if($_SESSION['id']==38596) echo "<br/>".$query2;
		/*$items2 = $mysqli->query($query2) or die("Team Salary Summary Failed!<pre>".$query2."</pre><br/>".mysqli_error($mysqli));
		while ($salaries = mysqli_fetch_array($items2))
		{
			$top5tot += $salaries['salary'];
		}*/
		if ($team_id == $return['id']) $textColour = "select_td";
		else $textColour = "";
		$i++;
		?>
		<tr>
                    <td class="<? echo $textColour; ?>" ><? echo str_pad((int) $i,2,"0",STR_PAD_LEFT).". "; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $return['name']." (".$return['id'].")"; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $return['playercount']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo "$ ".number_format($return['salarytot'],0,""," "); ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo "$ ".number_format($return['salaryavg'],0,""," "); ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo "$ ".number_format($return['salarydev'],0,""," "); ?></td>
		</tr>
	<?
	}
	?>
	</tbody>
    </table>
    </div>
    </div><?php
    $mysqli->close();
}

function showTotal3Pointers($team_id,$league_id,$season_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    ?>
    <div class="leagueTable">
    <h2><a href="javascript:animatedcollapse.toggle('total3pointers')">Total 3 Pointers in Season <?php echo $season_id; ?></a></h2>
    <div id="total3pointers">
	<table class="sortable">
	<thead>
	<tr>
            <th width="20"><a href="#">#</a></th>
            <th width="170"><a href="#">Player</a></th>
            <th width="170"><a href="#"><a href="#">Team</a></a></th>
            <th width="50"><a href="#">3 Point Shots</a></th>
            <th width="50"><a href="#">%</a></th>
            <th width="50"><a href="#">3P/48Min</a></th>
            <th width="70"><a href="#">3P/Game</a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
        $query = "SELECT
            PS.tpa, PS.tpm, PS.minutes, PS.games
            ,P.firstname, P.lastname
            ,T.id AS team_id, T.name AS team_name
            FROM player_stats PS
            INNER JOIN player P
            ON PS.playerid = P.id
            AND PS.teamid = P.team_id
            INNER JOIN team T
            ON P.team_id = T.id
            AND PS.season = T.season
            WHERE
            PS.season = ".addslashes($season_id)."
            AND PS.leagueid = ".addslashes($league_id)."
            ORDER BY tpm DESC
            LIMIT 20
            ";
	
        $items = $mysqli->query($query) or die("Database Query Failed!".mysqli_error($mysqli));
        //if($_SESSION['id']==38596) echo "<br/>".$query;
        while ($results = mysqli_fetch_array($items))
        {
            $i++;
            if ($team_id == $results['team_id']) $textColour = "select_td";
            else $textColour = "";
            ?>
            <tr>
                <td class="<? echo $textColour; ?>" ><? echo str_pad((int) $i,2,"0",STR_PAD_LEFT).". "; ?></td>
                <td class="<? echo $textColour; ?>" ><? echo $results['firstname']." ".$results['lastname']; ?></td>
                <td class="<? echo $textColour; ?>" ><? echo $results['team_name']; ?></td>
                <td class="<? echo $textColour; ?>" ><? echo $results['tpm']."-".$results['tpa']; ?></td>
                <td class="<? echo $textColour; ?>" ><? if ($results['tpa'] > 0) echo number_format(100*($results['tpm']/$results['tpa']),2,"."," ")."%"; ?></td>
                <td class="<? echo $textColour; ?>" ><? if ($results['tpm'] > 0) echo number_format(($results['tpm']/$results['minutes'])*48,2,"."," "); ?></td>
                <td class="<? echo $textColour; ?>" ><? if ($results['tpm'] > 0) echo number_format(($results['tpm']/$results['games']),2,"."," "); ?></td>
            </tr>
            <?php
        }
    ?>
    </tbody>
    </table>
    </div>
    </div>
    <?php
    $mysqli->close();
}

function showTotalSteals($team_id,$league_id,$season_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    ?>
    <div class="leagueTable">
    <h2><a href="javascript:animatedcollapse.toggle('totalsteals')">Total Steals in Season <?php echo $season_id; ?></a></h2>
    <div id="totalsteals">
    <table class="sortable">
	<thead>
	<tr>
            <th width="20"><a href="#">#</a></th>
            <th width="170"><a href="#">Player</a></th>
            <th width="170"><a href="#">Team</a></th>
            <th width="50"><a href="#">Steals</a></th>
            <th width="50"><a href="#">Stls/48Min</a></th>
            <th width="70"><a href="#">Stls/Game</a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	$query = "SELECT
            PS.stl, PS.minutes, PS.games
            ,P.firstname, P.lastname
            ,T.id AS team_id, T.name AS team_name
            FROM player_stats PS
            INNER JOIN player P
            ON PS.playerid = P.id
            AND PS.teamid = P.team_id
            INNER JOIN team T
            ON P.team_id = T.id
            AND PS.season = T.season
            WHERE
            PS.season = ".addslashes($season_id)."
            AND PS.leagueid = ".addslashes($league_id)."
            ORDER BY tpm DESC
            LIMIT 20
            ";
	$items = $mysqli->query($query) or die("Database Query Failed!");
	while ($results = mysqli_fetch_array($items))
	{		
		
            $i++;
            if ($team_id == $results['team_id']) $textColour = "select_td";
            else $textColour = "";
            ?>
            <tr>
                    <td class="<?php echo $textColour; ?>" ><?php echo str_pad((int) $i,2,"0",STR_PAD_LEFT).". "; ?></td>
                    <td class="<?php echo $textColour; ?>" ><?php echo $results['firstname']." ".$results['lastname']; ?></td>
                    <td class="<?php echo $textColour; ?>" ><?php echo $results['team_name']; ?></td>
                    <td class="<?php echo $textColour; ?>" ><?php echo $results['stl']; ?></td>
                    <td class="<?php echo $textColour; ?>" ><?php if ($results['stl'] > 0) echo number_format(($results['stl']/$results['minutes'])*48,2,"."," "); ?></td>
                    <td class="<?php echo $textColour; ?>" ><?php if ($results['stl'] > 0) echo number_format(($results['stl']/$results['games']),2,"."," "); ?></td>
            </tr>
            <?php	
	}
	?>
    </tbody>
    </table>
    </div>
    </div>
    <?php
    $mysqli->close();
}

function showTotalRebs($team_id,$league_id,$season_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
	?>
    <div class="leagueTable">
    <h2><a href="javascript:animatedcollapse.toggle('totalrebounds')">Total Rebounds in Season <?php echo $season_id; ?></a></h2>
    <div id="totalrebounds">
    <table class="sortable">
	<thead>
	<tr>
            <th width="20"><a href="#">#</a></th>
            <th width="170"><a href="#">Player</a></th>
            <th width="170"><a href="#">Team</a></th>
            <th width="50"><a href="#">Rebounds</a></th>
            <th width="50"><a href="#">Rebs/48Min</a></th>
            <th width="70"><a href="#">Rebs/Game</a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	$query = "SELECT
            PS.reb, PS.minutes, PS.games
            ,P.firstname, P.lastname
            ,T.id AS team_id,T.name AS team_name
            FROM player_stats PS
            INNER JOIN player P
            ON PS.playerid = P.id
            AND PS.teamid = P.team_id
            INNER JOIN team T
            ON P.team_id = T.id
            AND PS.season = T.season
            WHERE
            PS.season = ".addslashes($season_id)."
            AND PS.leagueid = ".addslashes($league_id)."
            ORDER BY reb DESC
            LIMIT 20
            ";
	$items = $mysqli->query($query) or die("Database Query Failed!");
	while ($results = mysqli_fetch_array($items))
	{		
            $i++;
            if ($team_id == $results['team_id']) $textColour = "select_td";
            else $textColour = "";
            ?>
            <tr>
                    <td class="<? echo $textColour; ?>" ><? echo str_pad((int) $i,2,"0",STR_PAD_LEFT).". "; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $results['firstname']." ".$results['lastname']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $results['team_name']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $results['reb']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? if ($results['reb'] > 0) echo number_format(($results['reb']/$results['minutes'])*48,2,"."," "); ?></td>
                    <td class="<? echo $textColour; ?>" ><? if ($results['reb'] > 0) echo number_format(($results['reb']/$results['games']),2,"."," "); ?></td>
            </tr>
            <?php	
	}
	?>
	</tbody>
    </table>
    </div>
    </div>
    <?php
    $mysqli->close();
}

function showTotalFreeThrows($team_id,$league_id,$season_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    ?>
    <div class="leagueTable">
    <h2><a href="javascript:animatedcollapse.toggle('totalfreethrows')">Most Free Throws Made in Season <?php echo $season_id; ?></a></h2>
    <div id="totalfreethrows">
    <table class="sortable">
    <thead>
    <tr>
        <th width="20"><a href="#">#</a></th>
        <th width="170"><a href="#">Player</a></th>
        <th width="170"><a href="#">Team</a></th>
        <th width="50"><a href="#">Free Thows</a></th>
        <th width="50"><a href="#">%</a></th>
        <th width="50"><a href="#">FT/48Min</a></th>
        <th width="70"><a href="#">FT/Game</a></th>
    </tr>
    </thead>
    <tbody>
        <?
        $i = 0;
        $query = "SELECT
            PS.fta, PS.ftm, PS.minutes, PS.games
            ,P.firstname, P.lastname
            ,T.id AS team_id, T.name AS team_name
            FROM player_stats PS
            INNER JOIN player P
            ON PS.playerid = P.id
            AND PS.teamid = P.team_id
            INNER JOIN team T
            ON P.team_id = T.id
            AND PS.season = T.season
            WHERE
            PS.season = ".addslashes($season_id)."
            AND PS.leagueid = ".addslashes($league_id)."
            ORDER BY ftm DESC
            LIMIT 20
            ";
        $items = $mysqli->query($query) or die("Database Query Failed!");
        while ($results = mysqli_fetch_array($items))
        {

            $i++;
            if ($team_id == $results['team_id']) $textColour = "select_td";
            else $textColour = "";
            ?>
            <tr>
                <td class="<? echo $textColour; ?>" ><? echo str_pad((int) $i,2,"0",STR_PAD_LEFT).". "; ?></td>
                <td class="<? echo $textColour; ?>" ><? echo $results['firstname']." ".$results['lastname']; ?></td>
                <td class="<? echo $textColour; ?>" ><? echo $results['team_name']; ?></td>
                <td class="<? echo $textColour; ?>" ><? echo $results['ftm']."-".$results['fta']; ?></td>
                <td class="<? echo $textColour; ?>" ><? if ($results['fta'] > 0) echo number_format(100*($results['ftm']/$results['fta']),2,"."," ")."%"; ?></td>
                <td class="<? echo $textColour; ?>" ><? if ($results['ftm'] > 0) echo number_format(($results['ftm']/$results['minutes'])*48,2,"."," "); ?></td>
                <td class="<? echo $textColour; ?>" ><? if ($results['ftm'] > 0) echo number_format(($results['ftm']/$results['games']),2,"."," "); ?></td>
            </tr>
            <?
        }
        ?>
    </tbody>
    </table>
    </div>
    </div><?
    $mysqli->close();
}

function showTotalBlks($team_id,$league_id,$season_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
	?>
    <div class="leagueTable">
    <h2><a href="javascript:animatedcollapse.toggle('totalblocks');">Total Blocks in Season <?php echo $season_id; ?></a></h2>
    <div id="totalblocks">
    <table class="sortable">
	<thead>
	<tr>
		<th width="20">#</th>
		<th width="170"><a href="">Player</a></th>
		<th width="170"><a href="">Team</a></th>
		<th width="50"><a href="">Blocks</a></th>
		<th width="50"><a href="">Blk/48Min</a></th>
		<th width="70"><a href="">Blk/Game</a></th>
	</tr>
	</thead>
	<tbody>
	<?
	$i = 0;
	$query = "SELECT
            PS.blk, PS.minutes, PS.games
            ,P.firstname, P.lastname
            ,T.id AS team_id, T.name AS team_name
            FROM player_stats PS
            INNER JOIN player P
            ON PS.playerid = P.id
            AND PS.teamid = P.team_id
            INNER JOIN team T
            ON P.team_id = T.id
            AND PS.season = T.season
            WHERE
            PS.season = ".addslashes($season_id)."
            AND PS.leagueid = ".addslashes($league_id)."
            ORDER BY blk DESC
            LIMIT 20
            ";
	$items = $mysqli->query($query) or die("Database Query Failed!");
	while ($results = mysqli_fetch_array($items))
	{		
            $i++;
            if ($team_id == $results['team_id']) $textColour = "select_td";
            else $textColour = "";
            ?>
            <tr>
                    <td class="<? echo $textColour; ?>" ><? echo str_pad((int) $i,2,"0",STR_PAD_LEFT).". "; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $results['firstname']." ".$results['lastname']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $results['team_name']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $results['blk']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? if ($results['blk'] > 0) echo number_format(($results['blk']/$results['minutes'])*48,2,"."," "); ?></td>
                    <td class="<? echo $textColour; ?>" ><? if ($results['blk'] > 0) echo number_format(($results['blk']/$results['games']),2,"."," "); ?></td>
            </tr>
            <?php
	}
	?>
        </tbody>
    </table>
    </div>
    </div><?php
    $mysqli->close();
}
function showTotalAsts($team_id,$league_id,$season_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    ?>
    <div class="leagueTable">
    <h2><a href="javascript:animatedcollapse.toggle('totalassists')">Total Assists in Season <?php echo $season_id; ?></a></h2>
    <div id="totalassists">
    <table class="sortable">
	<thead>
	<tr>
            <th width="20" ><a href="#">#</a></th>
            <th width="170" ><a href="#">Player</a></th>
            <th width="170" ><a href="#">Team</a></th>
            <th width="70" ><a href="#">Assists</a></th>
            <th width="70" ><a href="#">Ast/48Min</a></th>
            <th width="70" ><a href="#">Ast/Game</a></th>
	</tr>
	</thead>
	<tbody>
	<?
	$i = 0;
	$query = "SELECT
            PS.ast, PS.minutes, PS.games
            ,P.firstname, P.lastname
            ,T.id AS team_id, T.name AS team_name
            FROM player_stats PS
            INNER JOIN player P
            ON PS.playerid = P.id
            AND PS.teamid = P.team_id
            INNER JOIN team T
            ON P.team_id = T.id
            AND PS.season = T.season
            WHERE
            PS.season = ".addslashes($season_id)."
            AND PS.leagueid = ".addslashes($league_id)."
            ORDER BY ast DESC
            LIMIT 20
            ";
	$items = $mysqli->query($query) or die("Database Query Failed!");
	while ($results = mysqli_fetch_array($items))
	{
            $i++;
            if ($team_id == $results['team_id']) $textColour = "select_td";
            else $textColour = "";
            ?>
            <tr>
                <td class="<? echo $textColour; ?>" ><? echo str_pad((int) $i,2,"0",STR_PAD_LEFT).". "; ?></td>
                <td class="<? echo $textColour; ?>" ><? echo $results['firstname']." ".$results['lastname']; ?></td>
                <td class="<? echo $textColour; ?>" ><? echo $results['team_name']; ?></td>
                <td class="<? echo $textColour; ?>" ><? echo $results['ast']; ?></td>
                <td class="<? echo $textColour; ?>" ><? if ($results['ast'] > 0) echo number_format(($results['ast']/$results['minutes'])*48,2,"."," "); ?></td>
                <td class="<? echo $textColour; ?>" ><? if ($results['ast'] > 0) echo number_format(($results['ast']/$results['games']),2,"."," "); ?></td>
            </tr>
            <?php
	}
	?>
    </tbody>
    </table>
    </div>
	</div><?
        $mysqli->close();
}
function showTeamTotals($team_id,$league_id,$season_id)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
	?>
    <div class="leagueTable">
    <h2><a href="javascript:animatedcollapse.toggle('teamtotals')">Team Totals in Season <?php echo $season_id; ?></a></h2>
    <div id="teamtotals">
    <table class="sortable">
	<thead>
	<tr>
		<th><a href="#">#</a></th>
		<th><a href="#">Team</a></th>
		<th><a href="#">Pts</a></th>
		<th><a href="#">Rbds</a></th>
		<th><a href="#">Asts</a></th>
		<th><a href="#">Stls</a></th>
		<th><a href="#">FTs</a></th>
		<th><a href="#">Blks</a></th>
		<th><a href="#">Fouls</a></th>
		<th><a href="#">TOs</a></th>
		<th><a href="#">Ratings</a></th>
	</tr>
	</thead>
	<tbody>
	<?
	$i = 0;
	$query = "
	SELECT
	TM.pf AS pts,
	SUM(PS.reb) AS reb,
	SUM(PS.ast) AS ast,
	SUM(PS.stl) AS stl,
	SUM(PS.ftm) AS ftm,
	SUM(PS.blk) AS blk,
	SUM(PS.pf) AS pf,
	SUM(PS.turn) AS turn,
	SUM(PS.rating) AS ratingtot,
	SUM(PS.playerid) AS playertot,
	SUM(PS.rating)/COUNT(PS.playerid) AS rat,
	TM.id,
	TM.name,
	TM.league,
	TM.conference,
	TM.season,
	TM.wins,
	TM.losses
	
	FROM
	team TM
	LEFT OUTER JOIN
	player_stats PS
	ON TM.id = PS.teamid
	AND TM.season = PS.season
	WHERE TM.league = '".addslashes($league_id)."'
	AND TM.season='".addslashes($season_id)."'
	GROUP BY TM.id
	ORDER BY pts DESC
	";
	$items = $mysqli->query($query) or die("Database Query Failed!");
	while ($stats = mysqli_fetch_array($items))
	{		
            $i++;
            if ($team_id == $stats['id']) $textColour = "select_td";
            else $textColour = "";
            ?>
            <tr>
                    <td class="<? echo $textColour; ?>" ><? echo str_pad((int) $i,2,"0",STR_PAD_LEFT).". "; ?></td>

                    <td class="<? echo $textColour; ?>" ><a href="team_overview.php?season=<? echo $season_id; ?>&team=<? echo $stats['id']; ?>" rel="nofollow"><? echo $stats['name']; ?></a></td>
                    <td class="<? echo $textColour; ?>" ><? echo $stats['pts']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $stats['reb']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $stats['ast']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $stats['stl']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $stats['ftm']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $stats['blk']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $stats['pf']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo $stats['turn']; ?></td>
                    <td class="<? echo $textColour; ?>" ><? echo number_format($stats['rat'],2); ?></td>
            </tr>
            <?
	}
	?>
    </tbody>
    </table>
    </div>
	</div><?php
        $mysqli->close();
}

function GetLeagueInfo($id,$season)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    $query = "
	SELECT
            L.id AS id
            ,L.name AS name
            ,C.id AS countryid
            ,C.name AS country
            ,L.timestamp
	FROM
	league L
	LEFT OUTER JOIN
	countries C
	ON L.countryid = C.id
	WHERE L.id = '".addslashes($id)."'
            AND L.season = '".addslashes($season)."'
	LIMIT 1
	";
    $items = $mysqli->query($query) or die("Database Query Failed! ".mysqli_error($mysqli));
    while ($stats = mysqli_fetch_array($items))
    {
        return $stats;
    }
    $mysqli->close();
}
function GetTeamInfo($id,$season)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    $query = "
	SELECT
            T.id AS id
            ,T.name AS name
            ,L.id AS leagueid
            ,L.name AS leaguename
            ,C.id AS countryid
            ,C.name AS country
	FROM
        team T
        LEFT OUTER JOIN
	league L
        ON T.league = L.id
	LEFT OUTER JOIN
	countries C
	ON L.countryid = C.id
	WHERE T.id = '".addslashes($id)."'
            AND T.season = '".addslashes($season)."'
	LIMIT 1
	";
    $items = $mysqli->query($query) or die("Database Query Failed! ".mysql_error());
    while ($stats = mysqli_fetch_array($items))
    {
        return $stats;
    }
    $mysqli->close();
}
?>
