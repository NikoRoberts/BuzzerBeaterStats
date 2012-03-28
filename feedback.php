<?php include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>

<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?
if ($_SESSION['loggedin'] == true)
{
	//force update button
	?>
	<div style="margin:0 auto 0 auto;text-align: center;"><h1><a href="suggestion.php">Suggestions</a> - Feedback</h1><br>
	<div id="leave_feedback">
	<form method="POST" name="feedbackform" action="feedback.php">
	<textarea name="feedback" cols="70" rows="10"></textarea><br>
	<input name="submit" type="submit" value="Leave Feedback">
	<input type="hidden" name="feedbackleft" value="1">
	</form>
	</div>
	<?
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	
	if (($_GET['delete']>0) && ($_SESSION['status']>=50))
	{
		$query = "DELETE FROM feedback WHERE id='".$_GET['delete']."'";
		$pass = $mysqli->query($query) or die("Feedback Delete Failed!<br>".mysqli_error($mysqli));
	}
	
	if ($_POST['feedbackleft']==1)
	{
		$feedback = addslashes($_POST['feedback']);
		$feedback = preg_replace("!\r\n?!","<br>",$feedback);
		
		if ($feedback!="")
		{
			$query = "INSERT INTO feedback VALUES ('','".$_SESSION['id']."',NOW(),'".$feedback."')";
			$pass = $mysqli->query($query) or die("New Feedback Entry Failed!<br>".mysqli_error($mysqli));
		}
	}

	?>
            <div style="margin:0 auto 0 auto;width:590px;text-align:left;">
            <table width='590'><?php
	$query1 = "SELECT F.*, user FROM feedback F INNER JOIN user U ON U.id = F.userid ORDER BY id DESC";
	$items1 = $mysqli->query($query1) or die("Feedback Query Failed!<br>".mysqli_error($mysqli));
	while ($post = mysqli_fetch_array($items1))
	{
		echo "<tr>";
		echo "<td><b>".stripslashes($post['user'])."</b></td><td align=right>".stripslashes($post['timestamp']);
		if ($_SESSION['status']>=50) echo " - <a href='?delete=".stripslashes($post['id'])."'>[x]</a>";
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		?><td colspan='2' style='border-bottom:groove;'><? echo stripslashes($post['comment']); ?></td><?
		echo "</tr>";
	}
	?></table>
        </div></div><?
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
