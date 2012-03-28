<?php
include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
//include("positions.php");
$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
$mysqli->set_charset("utf8");

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
//Basic sanity checking on parameters
if (($_GET['p_country']) && (!($_GET['p_country']>0) && !($_GET['p_country']<200))) die("ERRORS IN PARAMETERS...");
if (($_GET['s_country']) && (!($_GET['s_country']>0) && !($_GET['s_country']<200))) die("ERRORS IN PARAMETERS...");
if (($_GET['sort'] != NULL) && ($_GET['sort'] != 'age') && ($_GET['sort'] != 'height') && ($_GET['sort'] != 'dmi') && ($_GET['sort'] != 'salary') && ($_GET['sort'] != 'gameshape') && ($_GET['sort'] != 'potential')) die("ERRORS IN PARAMETERS...");
if (($_GET['order'] != NULL) && ($_GET['order'] != 'asc') && ($_GET['order'] != 'desc')) die("ERRORS IN PARAMETERS...");
if (($_GET['bestposition'] != NULL) && ($_GET['bestposition'] != 'PG') && ($_GET['bestposition'] != 'SG') && ($_GET['bestposition'] != 'SF') && ($_GET['bestposition'] != 'PF') && ($_GET['bestposition'] != 'C') && ($_GET['bestposition'] != 'none')) die("ERRORS IN PARAMETERS...");
?>

<div style="text-align:center">
<h1>NT Scouter</h1>
<br/>

<form action='' method='GET'>
<div>
Country Player from: <select name='p_country'>
<?
if(!$_GET['age']) $_GET['age'] = "18";
if ($_GET['p_country']) $player_country = addslashes($_GET['p_country']);
else $player_country = rand(1,30);
if ($_GET['s_country']) $search_country = addslashes($_GET['s_country']);
else $search_country = 'any';

	$query="SELECT id,name FROM countries ORDER BY name";
	$items = $mysqli->query($query) or die("NT Scout Failed! ERR1");
	while($return = mysqli_fetch_array($items))
{
?> <option value='<? echo $return['id']; ?>'<? if ($player_country == $return['id']) echo "selected"; ?>><? echo $return['name']; ?></option><?
}
?>
</select></br>
Country Player Located: <select name='s_country'>
 <option value='any'>All Countries</option>
<?
	$query="SELECT id,name FROM countries ORDER BY name";
	$items = $mysqli->query($query) or die("NT Scout Failed! ERR2");
	while($return = mysqli_fetch_array($items))
{
?> <option value='<? echo $return['id']; ?>'<? if ($search_country == $return['id']) echo "selected"; ?>><? echo $return['name']; ?></option><?
}
?></select>
 <br>
 Sorted by: 
     <select name='sort'>
        <option value='age'<? if ($_GET['sort'] == 'age') echo "selected"; ?>>Age</option>
        <option value='height'<? if ($_GET['sort'] == 'height') echo "selected"; ?>>Height</option>
        <option value='dmi'<? if (($_GET['sort'] == 'dmi')||(!$_GET['sort'])) echo "selected"; ?>>DMI</option>
        <option value='salary'<? if ($_GET['sort'] == 'salary') echo "selected"; ?>>Salary</option>
        <option value='gameshape'<? if ($_GET['sort'] == 'gameshape') echo "selected"; ?>>Gameshape</option>
        <option value='potential'<? if ($_GET['sort'] == 'potential') echo "selected"; ?>>Potential</option>
     </select>

    <select name='order'>
        <option value='asc'<? if ($_GET['order'] == 'asc') echo "selected"; ?>>Ascending</option>
        <option value='desc'<? if (($_GET['order'] == 'desc')||(!$_GET['order'])) echo "selected"; ?>>Descending</option>
    </select>
   <br/>
   Best Position:<select name='bestposition'>
        <option value='PG'<? if ($_GET['bestposition'] == 'PG') echo "selected"; ?>>PG</option>
        <option value='SG'<? if ($_GET['bestposition'] == 'SG') echo "selected"; ?>>SG</option>
        <option value='SF'<? if ($_GET['bestposition'] == 'SF') echo "selected"; ?>>SF</option>
        <option value='PF'<? if ($_GET['bestposition'] == 'PF') echo "selected"; ?>>PF</option>
        <option value='C'<? if ($_GET['bestposition'] == 'C') echo "selected"; ?>>C</option>
        <option value='none'<? if (($_GET['bestposition'] == 'none')||(!$_GET['bestposition'])) echo "selected"; ?>>None</option>
    </select>
   <br/>
    Specific Age:<select name='age'>
    <option value='none'<? if ($_GET['age'] == 'none') echo "selected"; ?>>None</option>
    <option value='under21'<? if (($_GET['age'] == 'under21')||(!$_GET['age'])) echo "selected"; ?>>Under 21</option>
    <option value='25'<? if ($_GET['age'] == '25') echo "selected"; ?>>25</option>
    <option value='24'<? if ($_GET['age'] == '24') echo "selected"; ?>>24</option>
    <option value='23'<? if ($_GET['age'] == '23') echo "selected"; ?>>23</option>
    <option value='22'<? if ($_GET['age'] == '22') echo "selected"; ?>>22</option>
    <option value='21'<? if ($_GET['age'] == '21') echo "selected"; ?>>21</option>
    <option value='20'<? if ($_GET['age'] == '20') echo "selected"; ?>>20</option>
    <option value='19'<? if ($_GET['age'] == '19') echo "selected"; ?>>19</option>
    <option value='18'<? if ($_GET['age'] == '18') echo "selected"; ?>>18</option>
    </select>
    <br>
    Days Since Last Update: <input type="text" name="minDays" style="width:20px" value="<? if ($_GET['minDays']) echo $_GET['minDays']; else echo ""; ?>" />
    </div>
    
    <input type='submit' value='Search for Players'/>
    </form>
	<?
	

//Flush everything so far and then execute the search

echo '<div id="loader" style="margin:0 auto 0 auto; text-align:center;">
<img id="loader" src="/themes/'.$_SESSION['theme'].'/images/ajax-loader.gif" alt="Loading"><br>Searching...</div>';
flush();

?>

<table id="myTable">
<thead>
<tr>
	<th>&nbsp;</th>
    <th><a href="#">Player</a></th>
    <th>&nbsp;</th>
    <th><a href="#">Team</a></th>
    <th><a href="#">Age</a></th>
    <th><a href="#">Height</a></th>
    <th><a href="#">DMI</a></th>
    <th><a href="#">BP</a></th>
    <th style="width:50px;"><a href="#">Salary</a></th>
    <th><a href="#" title="Game Shape">Shape</a></th>
    <th><a href="#" title="Potential">Pot.</a></th>
    <th style="width:60px;"><a href="#" title="Date Last Updated">Latest Update</a></th>
</tr>
</thead>
<tbody>
<?



if ($_GET['sort']) $sort = addslashes($_GET['sort']);
else $sort = 'dmi';
if ($_GET['order']) $order = addslashes($_GET['order']);
else $order = 'DESC';

/*
if ($_GET['sort2']) $sort2 = addslashes($_GET['sort2']);
else $sort2 = 'salary';
if ($_GET['order2']) $order2 = addslashes($_GET['order2']);
else $order2 = 'ASC';*/

if ($search_country!='any')
{
	$query="
	SELECT PL.id,PL.firstname,PL.lastname,TM.name AS team_name,PL.team_id,PL.nationality
	,PL.age,PL.height,PL.dmi,PL.salary,PL.gameshape,PL.potential,PL.update, PL.bestposition,TM.league,LE.countryid,CO2.name as PCName, CO.name as TCName
	FROM countries CO
	INNER JOIN league LE
	ON CO.id = LE.countryid
	AND CO.id = '".$search_country."'
	AND LE.season = ".$_SESSION['defaultSeason']."
	INNER JOIN team TM
	ON LE.id = TM.league
	AND TM.season = ".$_SESSION['defaultSeason']."
	INNER JOIN player PL
	ON PL.team_id = TM.id
	INNER JOIN countries CO2
	ON PL.nationality = CO2.id
	WHERE
	PL.nationality ='".$player_country."'
	";
        
        if ($_GET['age']=='under21')
        {
            $query .= "
            AND (PL.age=18 OR PL.age=19 OR PL.age=20 OR PL.age=21)";
        }
	else if ($_GET['age']!='none') $age = intval($_GET['age']);
        if($age>0)
        {
            $query .= "
            AND PL.age = '".addslashes($age)."'";
        }
        
	if ($_GET['minDays']>0) $mindays = $_GET['minDays'];
	
        if($mindays)
        {
            $histDate = date('U')-(intval($mindays)*60*60*24);

            $query .= "
            AND PL.update > '".$histDate."'";
        }
        if(($_GET['bestposition']!="none")&&(strlen($_GET['bestposition'])>0))
        {
            $query .= "
            AND bestposition='".addslashes($_GET['bestposition'])."'";
        }
	$query .= "
	ORDER BY PL.".addslashes($sort)." ".addslashes($order)."
	LIMIT 100
	";
	
	if($_SESSION['id']=="38596") echo "search in one country: ".$search_country." : <pre>".$query."</pre>";
}
else
{
	//if searching in any country
	$query="
	SELECT PL.id,PL.firstname,PL.lastname,TM.name AS team_name,PL.team_id,PL.nationality
	,PL.age,PL.height,PL.dmi,PL.salary,PL.gameshape,PL.potential, PL.bestposition ,PL.update,TM.league
	FROM
	player PL
	INNER JOIN team TM
	ON PL.team_id = TM.id
	WHERE
	TM.season = '".$_SESSION['defaultSeason']."'
	AND PL.nationality ='".$player_country."'";
	if ($_GET['age']=='under21')
        {
            $query .= "
            AND (PL.age=18 OR PL.age=19 OR PL.age=20 OR PL.age=21)";
        }
	else if ($_GET['age']!='none') $age = intval($_GET['age']);
        if($age>0)
        {
            $query .= "
            AND PL.age = '".addslashes($age)."'";
        }
	if ($_GET['minDays']>0)
	{
		$histDate = date('U')-(intval($_GET['minDays'])*60*60*24);
		
		$query=$query."
		AND PL.update > '".$histDate."'";
	}
        if(($_GET['bestposition']!="none")&&(strlen($_GET['bestposition'])>0))
        {
            $query .= "
            AND bestposition='".addslashes($_GET['bestposition'])."'";
        }
	$query=$query."
	ORDER BY PL.".addslashes($sort)." ".addslashes($order)."
	LIMIT 100
	";
	if($_SESSION['id']=="38596") echo "searching all countries : <pre>".$query."</pre>";
}
	$items = $mysqli->query($query) or die("NT Scout Failed! ERR4");
	while($return = mysqli_fetch_array($items))
	{ ?>
    	<tr>
        	<td>
            	<img title="<? echo $return['PCName']; ?>" src="http://<? echo $_SESSION['buzzImages']; ?>/images/flags/flag_<? echo $return['nationality']; ?>.gif" alt="<? echo $return['PCName']; ?>">
			</td>
			<td>
            	<a href="http://www.buzzerbeaterstats.com/out.php?url=http://www.buzzerbeater.com/player/<? echo $return['id']; ?>/overview.aspx"><? echo $return['firstname']." ".$return['lastname']." (".$return['id'].")"; ?></a>
            </td>
			<td>
            	<?php if($return['TCName']) { ?><img title="<? echo $return['TCName']; ?>" src="http://<? echo $_SESSION['buzzImages']; ?>/images/flags/flag_<? echo $return['countryid']; ?>.gif" alt="<? echo $return['TCName']; ?>"><?php } ?>
			</td>
			<td>
            	<a title='in league: <? echo $return['league']; ?>' href='http://www.buzzerbeaterstats.com/out.php?url=http://www.buzzerbeater.com/team/<? echo $return['team_id']; ?>/overview.aspx'><? echo $return['team_name']." (".$return['team_id'].")";?></a>
			</td>
			<td>
				<? echo $return['age']; ?>
			</td>
            <td>
                <?
                $height = '';
                $cms = round($return['height'] * 2.54,0);
                $feet = floor($return['height']/12);
                $inches = $return['height']%12;
                $height = $feet."'".$inches.'"'; // / '.$cms." cm";
                echo $height;
                ?>
            </td>
			<td><? echo $return['dmi']; ?></td>
                        <td><? echo $return['bestposition']; ?></td>
			<td style="width:50px;">$<? echo number_format($return['salary'],0,""," "); ?></td>
			<td><? echo $return['gameshape']; ?></td>
			<td><? echo $return['potential']; ?></td>
            <?
			$latest_up = $return['update'];
			$max = 1728000; //20 days
			if ((date('U') - $latest_up) > $max)
			{
			?>
            <td style="font-style:italic">
				<a href="/league.php?league=<? echo $return['league']; ?>" title="Click to go and Update League"><? echo date('Y-m-d', $latest_up); ?></a>
            </td>
            <? }
			else {
			?>
			<td>
				<? echo date('Y-m-d', $latest_up); ?>
            </td>
            <?
			} ?>
            </tr>
		<?
        }
        if($_SESSION['id']==38596)
        {
            $query = "SHOW STATUS;";
            $items = $mysqli->query($query);
            echo "<table>";
            while($return = mysqli_fetch_array($items))
            {
                echo "<tr><td>".$return[0]."</td><td>".$return[1]."</td>";
            }
            echo "</table>";
        }
		?>
		</tbody>
</table>
</div>
<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>
<?
echo '<script type="text/javascript">document.getElementById("loader").style.display="none"</script>';

include("footer.php");
$mysqli->close();
?>
