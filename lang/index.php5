<? include("header.php5"); ?>
<? $_SESSION['returnPage'] = $_SERVER['PHP_SELF']; ?>


<style>
/* half boxes */

#news1 h1, #news1 h2, #news1 p {margin:0 1px; letter-spacing:1px;}
#news1 h1 {font-size:2.5em; color:#fff;}
#news1 h2 {font-size:2em;color:#06a; border:0;}
#news1 p {padding-bottom:0.5em;}
#news1 h2 {padding-top:0.5em;}
#news1 {
	position: absolute;
	left: 0px;
	background: transparent;
	width: 345px;
	margin:1em;
}

#news2 h1, #news2 h2, #news2 p {margin:0 1px; letter-spacing:1px;}
#news2 h1 {font-size:2.5em; color:#fff;}
#news2 h2 {font-size:2em;color:#06a; border:0;}
#news2 p {padding-bottom:0.5em;}
#news2 h2 {padding-top:0.5em;}
#news2 {
	position: absolute;
	left: 355px;
	background: transparent;
	width: 345px;
	margin:1em;
}

</style>

<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">

<center>
<?
$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='1'
			ORDER BY `order` ASC";
$items = mysql_query($query) or die("ERROR (SC.1): Site Content Query Failed!");
while ($return = mysql_fetch_array($items))
{
	?>
	<h2><?
    $ident = $return['page']."_".$return['section']."_".$return['order'];
	echo googTrans($ident,"en",$lang,$return['title']); ?></h2>
	<? $content = googTrans($ident,"en",$lang,$return['content']);
	  if ($content) echo "<i>".$content."</i>";
} ?>

<table cellpadding="5" cellspacing="10">
<?
// Output main page content
$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='2'
			ORDER BY `order` ASC";
$items = mysql_query($query) or die("ERROR (SC.2): Site Content Query Failed! Query Failed!");
while ($return = mysql_fetch_array($items))
{
?>
<tr>
<th align="right" valign="top"><?
$ident = $return['page']."_".$return['section']."_".$return['order'];
echo googTrans($ident,"en",$lang,$return['title']); ?></th>
<td><? echo googTrans($ident,"en",$lang,$return['content']); ?></td>
</tr>
<? } ?>
</table>

</center>
	
<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>

<div id="news1">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
	<table>
	<?
	$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='3' AND `order`='1'";
	$items = mysql_query($query) or die("ERROR (SC.3): Site Content Query Failed!");
	while ($return = mysql_fetch_array($items))
	{
		?>
		<tr><td colspan='2' align='center'><h2><?
        $ident = $return['page']."_".$return['section']."_".$return['order'];
		echo googTrans($ident,"en",$lang,$return['title']); ?></h2>
		<?
        $content = googTrans($ident,"en",$lang,$return['content']);
		if ($content) echo "</td></tr><tr><td colspan=2><i>".$content."</i>"; ?>
		</td></tr>
	<? } ?>
	
	<?
	$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='4'
			ORDER BY `order` DESC LIMIT 5"; // output backward for latest news first and only display 5
	$items = mysql_query($query) or die("ERROR (SC.4): Site Content Query Failed!");
	while ($return = mysql_fetch_array($items))
	{
		?>
		<tr>
		<td valign='top' align='right' width='60'><b><? 
        $ident = $return['page']."_".$return['section']."_".$return['order'];
		echo googTrans($ident,"en",$lang,$return['title']); ?></b></td>
		<td><? echo googTrans($ident,"en",$lang,$return['content']); ?><hr></td>
		</tr>
	<? } ?>
	</table>
<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

<div id="news2">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">

	<table>
	<?
	$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='5' AND `order`='1'";
	$items = mysql_query($query) or die("ERROR (SC.5): Site Content Query Failed!");
	while ($return = mysql_fetch_array($items))
	{
		?>
		<tr><td colspan='2' align='center'><h2><? 
        $ident = $return['page']."_".$return['section']."_".$return['order'];
		 echo googTrans($ident,"en",$lang,$return['title']); ?></h2>
		<?
        $content = googTrans($ident,"en",$lang,$return['content']);
		if ($content) echo "</td></tr><tr><td colspan=2><i>".$content."</i>"; ?>
		</td></tr>
	<? } ?>
	
	<?
	$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='6'
			ORDER BY `order` DESC LIMIT 5"; // output backward for latest news first and only display 5
	$items = mysql_query($query) or die("ERROR (SC.6): Site Content Query Failed!");
	while ($return = mysql_fetch_array($items))
	{
		?>
		<tr>
		<td valign='top' align='right' width='100'><b><?  
        $ident = $return['page']."_".$return['section']."_".$return['order'];
		echo googTrans($ident,"en",$lang,$return['title']); ?></b></td>
		<td><? echo googTrans($ident,"en",$lang,$return['content']); ?><hr></td>
		</tr>
	<? } ?>
	</table>
<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

</div>
</body>
</html>