<?php

//connect to SQL database

include("header.php");


//Training Method Reference List
$training_methods = array(
'0' => array(
'name' => "Team Training",
'0' => "Game Shape",
'1' => "Free Throws",
'2' => "Stamina"),

'1' => array(
'name' => "Pressure",
'0' => "PG",
'1' => "PG/SG",
'2' => "PG/SG/SF"),

'2' => array(
'name' => "One on One",
'0' => "Guards",
'1' => "Forwards",
'2' => "Team"),

'3' => array(
'name' => "Outside Shooting",
'0' => "SG",
'1' => "PG/SG",
'2' => "Wingmen",
'3' => "Team"),

'4' => array(
'name' => "Jump Shot",
'0' => "Guards",
'1' => "Forwards",
'2' => "Wingmen",
'3' => "Team"),

'5' => array(
'name' => "Ball Handling",
'0' => "PG",
'1' => "PG/SG",
'2' => "PG/SG/SF"),

'6' => array(
'name' => "Passing",
'0' => "SG",
'1' => "PG/SG",
'2' => "Team"),

'7' => array(
'name' => "Shot Blocking",
'0' => "C",
'1' => "C/PF",
'2' => "C/PF/SF"),

'8' => array(
'name' => "Inside Defence",
'0' => "C",
'1' => "C/PF",
'2' => "C/PF/SF"),

'9' => array(
'name' => "Rebounding",
'0' => "C/PF",
'1' => "Team"),

'10' => array(
'name' => "Inside Scoring",
'0' => "C",
'1' => "C/PF",
'2' => "C/PF/SF")
);



function displayList()
{
	?><select name="current_setting"><?
	global $training_methods;
	$i=0;
	while($training_methods[$i])
	{
		?><optgroup label="<? echo $training_methods[$i]['name']; ?>"><?
		$k = 0;
		while($training_methods[$i][$k])
		{
			?>
			<option value="<? echo $i.".".$k ?>"><? echo $training_methods[$i][$k]; ?></option>
			<?
			$k++;
		}
		?></optgroup><?
		$i++;
	}
	?></select><?
}

function displayCurrent()
{
	?>
	<h1>Current Week Entries</h1>
	<i>Please input your team training settings for this week (<? echo getWeek(); ?>).</i><br>
	Training Setting: <form method="post" action="training.php" name="training_form">
	<? displayList(); ?>
	<input type="submit" value="Submit">
	</form>
	<?
}

function displayPrevious()
{
	?>
	<h1>Previous Weeks</h1>
	<form action="training.php" method="post">
	<select name="week">
	<?
	$i = 1;
	$thisWeek = getWeek();
        
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	while($i < $thisWeek)
	{
		echo "<option value='".$i."' onClick='javascript:week".$i."pops()'>Week ".$i."</option>";
		$pop_details = "<h2>Week ".$i." Pops</h2><table BORDER=1>";
		$prevPlayer = 0;
		$query = "
		SELECT *
		FROM training_results tr
		INNER JOIN player p
		ON tr.teamid = p.team_id
		AND tr.playerid = p.id
		WHERE tr.weekid='".$i."' AND tr.teamid='".$_SESSION['id']."'";
		$items = $mysqli->query($query) or die("Pops Query Failed! -- ".$query);
		while($return = mysqli_fetch_array($items))
		{
			if ($prevPlayer != $return['id'])
			{
				$pop_details .= "<tr><td>".$return['firstname']." ".$return['lastname']."</td><td>&nbsp;</td></tr>";
			}
			$pop_details .= "<tr><td>&nbsp;</td><td>".$return['skillid']." ".$return['value']."</td></tr>";
			$prevPlayer = $return['id'];
		}
		?>
		</table>
		<script language="javascript" type="text/javascript">
		function week<? echo $i; ?>pops()
		{
			$("pop_display").html("<? echo $pop_details; ?>");
		}
		</script>
		<?
		$i++;
	}
        $mysqli->close();
	?>
	</select>
	<? displayList(); ?>
	<input type="submit" name="submit" value="Submit">
	</form>
    <?php
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    while($i < $thisWeek)
    {
            echo "<option value='".$i."' onClick='javascript:week".$i."pops()'>Week ".$i."</option>";
            $pop_details = "<h2>Week ".$i." Pops</h2><table BORDER=1>";
            $prevPlayer = 0;
            $query = "
            SELECT *
            FROM training_results tr
            INNER JOIN player p
            ON tr.teamid = p.team_id
            AND tr.playerid = p.id
            WHERE tr.weekid='".$i."' AND tr.teamid='".$_SESSION['id']."'";
            $items = $mysqli->query($query) or die("Pops Query Failed! -- ".$query);
            while($return = mysqli_fetch_array($items))
            {
                    if ($prevPlayer != $return['id'])
                    {
                            $pop_details .= "<tr><td>".$return['firstname']." ".$return['lastname']."</td><td>&nbsp;</td></tr>";
                    }
                    $pop_details .= "<tr><td>&nbsp;</td><td>".$return['skillid']." ".$return['value']."</td></tr>";
                    $prevPlayer = $return['id'];
            }
            ?>
            </table>
            <script language="javascript" type="text/javascript">
            function week<? echo $i; ?>pops()
            {
                    $("pop_display").html("<? echo $pop_details; ?>");
            }
            </script>
            <?
            $i++;
    }
    $mysqli->close();
    ?>
<br>
<span id="pop_display"> </span>
			
			
	<?
}

?>
<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<center>
<?
updatePops($_SESSION['id']);
displayCurrent();
?>
</center>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>
<?
?>
<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<center>
<?
displayPrevious();
?>
</center>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

<?
//set training this coming week button pressed

//set a previous week training method

include("footer.php");

?>
