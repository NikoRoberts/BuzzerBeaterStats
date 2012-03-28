<?php include("header.php"); ?>
<? $_SESSION['returnPage'] = $_SERVER['PHP_SELF']; ?>

<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?
if ($_SESSION['loggedin'] == true)
{
	//force update buttonn
	?>
	<center><h1>Suggestions - <a href="feedback.php">Feedback</a></h1><br>
	<div id="suggest">
	<form method="POST" action="suggestion.php">
	<textarea name="suggestion" cols="70" rows="10"></textarea><br>
	<input type="submit" value="Suggest">
	<input type="hidden" name="suggestioncheck" value="1">
	</form>
	</div>
	<?
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	
	if (($_GET['delete']>0) && ($_SESSION['status']>=50))
	{
		$query = "DELETE FROM suggestions WHERE id='".addslashes($_GET['delete'])."'";
		$pass = $mysqli->query($query) or die("Suggestion Delete Failed!<br>".mysqli_error($mysqli));
	}
	
	if ($_POST['suggestioncheck']==1)
	{
		$feedback = addslashes($_POST['suggestion']);
		$feedback = str_replace("\r\n","<br>",$feedback);
		
		if ($feedback!="")
		{
			$query = "INSERT INTO suggestions VALUES ('','".$_SESSION['id']."',NOW(),'1','".$feedback."','0')";
			$pass = $mysqli->query($query) or die("New Suggestion Entry Failed!<br>".mysqli_error($mysqli));
		}
	}

	echo "<table width='590'>";
	$query1 = "SELECT F.*, user FROM suggestions F INNER JOIN user U ON U.id = F.userid ORDER BY id DESC";
	$items1 = $mysqli->query($query1) or die("Suggestion Query Failed!<br>".mysqli_error($mysqli));
	while ($post = mysqli_fetch_array($items1))
	{
		echo "<tr>";
		echo "<td><b>".$post['user']."</b></td><td align=right>".$post['timestamp'];
		if ($_SESSION['status']>=50) echo " - <a href='?delete=".$post['id']."'>[x]</a>";
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		?><td colspan='2' style='border-bottom:groove;'><? echo $post['suggestion'] ?></td><?
		echo "</tr>";
	}
	echo "</table>";

	$mysqli->close();
	?></center><?
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
