<?php
include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
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

<?
require_once('./scripts/charts/maxChart.class.php');

if($_SESSION['logged']!=1)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
	$query="SELECT SUM(last)/last AS count, country FROM user_tracking GROUP BY country";
	$items = $mysqli->query($query) or die("Visit Check Failed!");
	while($return = mysqli_fetch_array($items))
	{
		$data[$return['country']] = $return['count'];
	}
}

?>
<style>
<? include("scripts/charts/style/style.css"); ?>
</style>
<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>
<?
$mc = new maxChart($data);
$mc->displayChart('Visitors by Country',0,600,1000,true);

?>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>
<?

include("footer.php");
?>
