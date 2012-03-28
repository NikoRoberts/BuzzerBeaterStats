<?php include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>

<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">

<div style="margin:0 auto 0 auto;">
<?php
flush();
$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
$mysqli->set_charset("utf8");
$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='1'
			ORDER BY `order` ASC";
$items = $mysqli->query($query) or die("ERROR (SC.1): Site Content Query Failed!");
while ($return = mysqli_fetch_array($items))
{ ?>
    <div style="text-align:center">
        <h2><?php
        $ident = $return['page']."_".$return['section']."_".$return['order'];
        echo googTrans($ident."_t","en",$lang,$return['title']); ?></h2>
        <?php $content = googTrans($ident."_c","en",$lang,$return['content']);
        if ($content) echo "<span style='text-style:italic;'>".$content."</span>";
        ?>
    </div>
<?php } ?>

<table cellpadding="5" cellspacing="10">
<?php
// Output main page content
$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='2'
			ORDER BY `order` ASC";
$items = $mysqli->query($query) or die("ERROR (SC.2): Site Content Query Failed! Query Failed!");
while ($return = mysqli_fetch_array($items))
{
?>
    <tr>
    <th align="right" valign="top"><?php
    $ident = $return['page']."_".$return['section']."_".$return['order'];
    echo googTrans($ident."_t","en",$lang,$return['title']); ?></th>
    <td><?php echo googTrans($ident."_c","en",$lang,$return['content']); ?></td>
    </tr>
<?php } ?>
</table>

</div>
	
<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>

<div id="news_section">

<div id="news1">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
	<table>
	<?php
	$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='3' AND `order`='1'";
	$items = $mysqli->query($query) or die("ERROR (SC.3): Site Content Query Failed!");
	while ($return = mysqli_fetch_array($items))
	{
		?>
		<tr><td colspan='2' align='center'><h2><?
        $ident = $return['page']."_".$return['section']."_".$return['order'];
		echo googTrans($ident."_t","en",$lang,$return['title']); ?></h2>
		<?php
        $content = googTrans($ident."_c","en",$lang,$return['content']);
		if ($content) echo "</td></tr><tr><td colspan=2><i>".$content."</i>"; ?>
		</td></tr>
	<?php } ?>
	
	<?php
	$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='4'
			ORDER BY `order` DESC LIMIT 5"; // output backward for latest news first and only display 5
	$items = $mysqli->query($query) or die("ERROR (SC.4): Site Content Query Failed!");
	while ($return = mysqli_fetch_array($items))
	{
		?>
		<tr>
		<td valign='top' align='right' width='60'><b><?php
        $ident = $return['page']."_".$return['section']."_".$return['order'];
		echo googTrans($ident."_t","en",$lang,$return['title']); ?></b></td>
		<td><?php echo googTrans($ident."_c","en",$lang,$return['content']); ?><hr></td>
		</tr>
	<?php } ?>
	</table>
<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

<div id="news2">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">

	<table>
	<?php
	$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='5' AND `order`='1'";
	$items = $mysqli->query($query) or die("ERROR (SC.5): Site Content Query Failed!");
	while ($return = mysqli_fetch_array($items))
	{
		?>
		<tr><td colspan='2' align='center'><h2><? 
        $ident = $return['page']."_".$return['section']."_".$return['order'];
		 echo googTrans($ident."_t","en",$lang,$return['title']); ?></h2>
		<?php
        $content = googTrans($ident."_c","en",$lang,$return['content']);
		if ($content) echo "</td></tr><tr><td colspan=2><i>".$content."</i>"; ?>
		</td></tr>
	<?php } ?>
	
	<?php
	$query = "SELECT * FROM site_content
			WHERE language='eng'
			AND page='1' AND section='6'
			ORDER BY `order` DESC LIMIT 5"; // output backward for latest news first and only display 5
	$items = $mysqli->query($query) or die("ERROR (SC.6): Site Content Query Failed!");
	while ($return = mysqli_fetch_array($items))
	{
		?>
		<tr>
		<td valign='top' align='right' width='100'><b><?  
        $ident = $return['page']."_".$return['section']."_".$return['order'];
		echo googTrans($ident."_t","en",$lang,$return['title']); ?></b></td>
		<td><?php echo googTrans($ident."_c","en",$lang,$return['content']); ?><hr></td>
		</tr>
	<?php }
        $mysqli->close();
        ?>
	</table>
<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>

<?php include("footer.php"); ?>
