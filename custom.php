<?php include("header.php"); ?>
<? $_SESSION['returnPage'] = $_SERVER['PHP_SELF']; ?>

<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?

if ($_SESSION['loggedin'] == true)
{

	if ($_GET['highlight'])
	{
		$team_id = $_GET['highlight'];
	}
	else
	{
		$team_id = $_SESSION['id'];
	}
	if ($_POST['league'])
	{
		$league_id = $_POST['league'];
	}
	else
	{
		$league_id = $_SESSION['leagueId'];
	}
	if ($_POST['season_id'])
	{
		$season_id = $_POST['season_id'];
	}
	else
	{
		$season_id = $_SESSION['defaultSeason'];
	}

	?>
	<center>
	<form method="post" name="showstat">
	<table>
	<tr>
	<th>Season:</th><th>Order By:</th><th>High/Low</th><th>How Many?</th><th>&nbsp;</th>
	</tr>
	<tr>
		<td>
		<input type="hidden" name="test" value="1">
		<select name="season_id">
		<?
		for ($i=$_SESSION['defaultSeason'];$i>9;$i--)
		{
			?>
			<option value=<?
			echo "\"".$i."\" ";
			if ($i==$_POST['season_id']) echo "selected";
			?>>Season <? echo $i; ?></option>
			<?
		}
		?>
		</select></td>
		<td>
		<select name="item">		
		<option value="games" <? if ($_POST['item']=="games") echo "selected"; ?>>Games Played</option>
		<option value="minutes" <? if ($_POST['item']=="minutes") echo "selected"; ?>>Minutes</option>
		<option value="fgm" <? if ($_POST['item']=="fgm") echo "selected"; ?>>Field Goals Made</option>
		<option value="fga" <? if ($_POST['item']=="fga") echo "selected"; ?>>Field Goals Attempted</option>
		<option value="tpm" <? if ($_POST['item']=="tpm") echo "selected"; ?>>Three Points Made</option>
		<option value="tpa" <? if ($_POST['item']=="tpa") echo "selected"; ?>>Three Points Attempted</option>
		<option value="ftm" <? if ($_POST['item']=="ftm") echo "selected"; ?>>Free Throws Made</option>
		<option value="fta" <? if ($_POST['item']=="fta") echo "selected"; ?>>Free Throws Attempted</option>
		<option value="oreb" <? if ($_POST['item']=="oreb") echo "selected"; ?>>Offensive Rebounds</option>
		<option value="reb" <? if ($_POST['item']=="reb") echo "selected"; ?>>Rebounds</option>
		<option value="ast" <? if ($_POST['item']=="ast") echo "selected"; ?>>Assists</option>
		<option value="turn" <? if ($_POST['item']=="turn") echo "selected"; ?>>Turn-overs</option>
		<option value="stl" <? if ($_POST['item']=="stl") echo "selected"; ?>>Steals</option>
		<option value="blk" <? if ($_POST['item']=="blk") echo "selected"; ?>>Blocks</option>
		<option value="pf" <? if ($_POST['item']=="pf") echo "selected"; ?>>Personal Fouls</option>
		<option value="pts" <? if ((!$_POST['item'])||($_POST['item']=="pts")) echo "selected"; ?>>Points</option>
        <option value="salary" <? if ($_POST['item']=="salary") echo "selected"; ?>>Salary</option>
        <option value="potential" <? if ($_POST['item']=="potential") echo "selected"; ?>>Potential</option>
		<option value="rating" <? if ($_POST['item']=="rating") echo "selected"; ?>>Rating</option>
		</select>
		</td>
		<td>
		<select name="order">
		<option value="DESC" <? if ($_POST['order']=="DESC") echo "selected"; ?>>Highest</option>
		<option value="ASC" <? if ($_POST['order']=="ASC") echo "selected"; ?>>Lowest</option>
		</select>
		</td>
		<td>
		<input name="limit" type="text" value="<? if ($_POST['limit']) echo $_POST['limit']; else echo "20";?>" /></td>
		<td>
		<input name="submit" type="submit" value="Get Stats" /></td>
		</tr>
		</table>
	</form>
	</center>
<?
	if ($_POST['test'])
	{
		//showStats($season_id,$league_id,$item,$order,$limit)
		showStats($team_id,$season_id,$league_id,$_POST['item'],$_POST['order'],$_POST['limit']);
	}
}
else
{
	?>
	<center>
	Please login <a href="bblogin.php">HERE</a>.
	</center>
	<?
}
?>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

<?php include("footer.php"); ?>