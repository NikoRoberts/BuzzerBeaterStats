<?php
function positionWeightings($minSalary)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
	$i = 0;
	$pgs = 0;
	$sgs = 0;
	$sfs = 0;
	$pfs = 0;
	$cs = 0;
	
	$query = "
	SELECT
	PL.age
	,PL.height
	,PL.bestposition
	,PL.jumpshot
	,PL.range
	,PL.outsidedef
	,PL.handling
	,PL.driving
	,PL.passing
	,PL.insideshot
	,PL.insidedef
	,PL.rebound
	,PL.block
	,PL.stamina
	,PL.freethrow
	,PL.experience
	FROM
	player PL
	WHERE
	PL.stamina<>0
	AND
	PL.salary>'".addslashes($minSalary)."'
	";
	$items = $mysqli->query($query) or die("Database Query Failed!");
	while ($return = mysqli_fetch_array($items))
	{
		$i++;
		if ($return['bestposition']=="PG")
		{
			$pgs++;
			$pgheight=$pgheight+$return['height'];
			$pgjumpshot=$pgjumpshot+$return['jumpshot'];
			$pgrange=$pgrange+$return['range'];
			$pgoutsidedef=$pgoutsidedef+$return['outsidedef'];
			$pghandling=$pghandling+$return['handling'];
			$pgdriving=$pgdriving+$return['driving'];
			$pgpassing=$pgpassing+$return['passing'];
			$pginsideshot=$pginsideshot+$return['insideshot'];
			$pginsidedef=$pginsidedef+$return['insidedef'];
			$pgrebound=$pgrebound+$return['rebound'];
			$pgblock=$pgblock+$return['block'];
			$pgstamina=$pgstamina+$return['stamina'];
			$pgfreethrow=$pgfreethrow+$return['freethrow'];
			$pgexperience=$pgexperience+$return['experience'];
		}
		else if ($return['bestposition']=="SG")
		{
			$sgs++;
			$sgheight=$sgheight+$return['height'];
			$sgjumpshot=$sgjumpshot+$return['jumpshot'];
			$sgrange=$sgrange+$return['range'];
			$sgoutsidedef=$sgoutsidedef+$return['outsidedef'];
			$sghandling=$sghandling+$return['handling'];
			$sgdriving=$sgdriving+$return['driving'];
			$sgpassing=$sgpassing+$return['passing'];
			$sginsideshot=$sginsideshot+$return['insideshot'];
			$sginsidedef=$sginsidedef+$return['insidedef'];
			$sgrebound=$sgrebound+$return['rebound'];
			$sgblock=$sgblock+$return['block'];
			$sgstamina=$sgstamina+$return['stamina'];
			$sgfreethrow=$sgfreethrow+$return['freethrow'];
			$sgexperience=$sgexperience+$return['experience'];
		}
		else if ($return['bestposition']=="SF")
		{
			$sfs++;
			$sfheight=$sfheight+$return['height'];
			$sfjumpshot=$sfjumpshot+$return['jumpshot'];
			$sfrange=$sfrange+$return['range'];
			$sfoutsidedef=$sfoutsidedef+$return['outsidedef'];
			$sfhandling=$sfhandling+$return['handling'];
			$sfdriving=$sfdriving+$return['driving'];
			$sfpassing=$sfpassing+$return['passing'];
			$sfinsideshot=$sfinsideshot+$return['insideshot'];
			$sfinsidedef=$sfinsidedef+$return['insidedef'];
			$sfrebound=$sfrebound+$return['rebound'];
			$sfblock=$sfblock+$return['block'];
			$sfstamina=$sfstamina+$return['stamina'];
			$sffreethrow=$sffreethrow+$return['freethrow'];
			$sfexperience=$sfexperience+$return['experience'];
		}
		else if ($return['bestposition']=="PF")
		{
			$pfs++;
			$pfheight=$pfheight+$return['height'];
			$pfjumpshot=$pfjumpshot+$return['jumpshot'];
			$pfrange=$pfrange+$return['range'];
			$pfoutsidedef=$pfoutsidedef+$return['outsidedef'];
			$pfhandling=$pfhandling+$return['handling'];
			$pfdriving=$pfdriving+$return['driving'];
			$pfpassing=$pfpassing+$return['passing'];
			$pfinsideshot=$pfinsideshot+$return['insideshot'];
			$pfinsidedef=$pfinsidedef+$return['insidedef'];
			$pfrebound=$pfrebound+$return['rebound'];
			$pfblock=$pfblock+$return['block'];
			$pfstamina=$pfstamina+$return['stamina'];
			$pffreethrow=$pffreethrow+$return['freethrow'];
			$pfexperience=$pfexperience+$return['experience'];
		}
		else if ($return['bestposition']=="C")
		{
			$cs++;
			$cheight=$cheight+$return['height'];
			$cjumpshot=$cjumpshot+$return['jumpshot'];
			$crange=$crange+$return['range'];
			$coutsidedef=$coutsidedef+$return['outsidedef'];
			$chandling=$chandling+$return['handling'];
			$cdriving=$cdriving+$return['driving'];
			$cpassing=$cpassing+$return['passing'];
			$cinsideshot=$cinsideshot+$return['insideshot'];
			$cinsidedef=$cinsidedef+$return['insidedef'];
			$crebound=$crebound+$return['rebound'];
			$cblock=$cblock+$return['block'];
			$cstamina=$cstamina+$return['stamina'];
			$cfreethrow=$cfreethrow+$return['freethrow'];
			$cexperience=$cexperience+$return['experience'];
		}
	}

	$pgtotal=($pgjumpshot+$pgrange+$pgoutsidedef+$pghandling+$pgdriving+$pgpassing+$pginsideshot+$pginsidedef+$pgrebound+$pgblock)/$pgs;
	$sgtotal=($sgjumpshot+$sgrange+$sgoutsidedef+$sghandling+$sgdriving+$sgpassing+$sginsideshot+$sginsidedef+$sgrebound+$sgblock)/$sgs;
	$sftotal=($sfjumpshot+$sfrange+$sfoutsidedef+$sfhandling+$sfdriving+$sfpassing+$sfinsideshot+$sfinsidedef+$sfrebound+$sfblock)/$sfs;
	$pftotal=($pfjumpshot+$pfrange+$pfoutsidedef+$pfhandling+$pfdriving+$pfpassing+$pfinsideshot+$pfinsidedef+$pfrebound+$pfblock)/$pfs;
	$ctotal=($cjumpshot+$crange+$coutsidedef+$chandling+$cdriving+$cpassing+$cinsideshot+$cinsidedef+$crebound+$cblock)/$cs;
	echo "<h2>Weightings</h2>";
	echo "Based on skills from ".$i." ";
	?>
    players worldwide. <br>
    <form name='minsal' action='weightings.php' method='get'>
        Minimum Salary: 
        <select onChange='javascript:document.minsal.submit()' name='minSalary'>
            <option value='1000' <? if ($minSalary==1000) echo "selected='selected'"; ?>>$1,000</option>
            <option value='5000' <? if ($minSalary==5000) echo "selected='selected'"; ?>>$5,000</option>
            <option value='10000' <? if ($minSalary==10000) echo "selected='selected'"; ?>>$10,000</option>
            <option value='20000' <? if ($minSalary==20000) echo "selected='selected'"; ?>>$20,000</option>
            <option value='50000' <? if ($minSalary==50000) echo "selected='selected'"; ?>>$50,000</option>
            <option value='100000' <? if ($minSalary==100000) echo "selected='selected'"; ?>>$100,000</option>
        </select>
    </form>
	<?
	echo "<hr><h3>Point Guard</h3>";
	$height = 0;
	$pgheight=$pgheight/$pgs;
	$cms = round($pgheight * 2.54,0);
	$feet = floor($pgheight/12);
	$inches = $pgheight%12;
	$height = $feet."'".$inches.'" / '.$cms." cm";
	echo $height." : Average Height<br>";
	echo "<b>Main Skills (percentage of total main skills)</b><br>";
	echo number_format((($pgjumpshot/$pgs)/$pgtotal)*100,2,".",",")."% : jumpshot<br>";
	echo number_format((($pgrange/$pgs)/$pgtotal)*100,2,".",",")."% : range<br>";
	echo number_format((($pgoutsidedef/$pgs)/$pgtotal)*100,2,".",",")."% : outside def<br>";
	echo number_format((($pghandling/$pgs)/$pgtotal)*100,2,".",",")."% : handling<br>";
	echo number_format((($pgdriving/$pgs)/$pgtotal)*100,2,".",",")."% : driving<br>";
	echo number_format((($pgpassing/$pgs)/$pgtotal)*100,2,".",",")."% : passing<br>";
	echo number_format((($pginsideshot/$pgs)/$pgtotal)*100,2,".",",")."% : insideshot<br>";
	echo number_format((($pginsidedef/$pgs)/$pgtotal)*100,2,".",",")."% : insidedef<br>";
	echo number_format((($pgrebound/$pgs)/$pgtotal)*100,2,".",",")."% : rebound<br>";
	echo number_format((($pgblock/$pgs)/$pgtotal)*100,2,".",",")."% : block<br>";
	echo "<b>Secondary Skills</b><br>";
	echo number_format(($pgstamina/$pgs),2,".",",")." : Average stamina<br>";
	echo number_format(($pgfreethrow/$pgs),2,".",",")." : Average freethrow<br>";
	echo number_format(($pgexperience/$pgs),2,".",",")." : Average experience<br>";
	
	echo "<hr><h3>Shooting Guard</h3>";
	$height = 0;
	$sgheight=$sgheight/$sgs;
	$cms = round($sgheight * 2.54,0);
	$feet = floor($sgheight/12);
	$inches = $sgheight%12;
	$height = $feet."'".$inches.'" / '.$cms." cm";
	echo $height." : Average Height<br>";
	echo "<b>Main Skills (percentage of total main skills)</b><br>";
	echo number_format((($sgjumpshot/$sgs)/$sgtotal)*100,2,".",",")."% : jumpshot<br>";
	echo number_format((($sgrange/$sgs)/$sgtotal)*100,2,".",",")."% : range<br>";
	echo number_format((($sgoutsidedef/$sgs)/$sgtotal)*100,2,".",",")."% : outside def<br>";
	echo number_format((($sghandling/$sgs)/$sgtotal)*100,2,".",",")."% : handling<br>";
	echo number_format((($sgdriving/$sgs)/$sgtotal)*100,2,".",",")."% : driving<br>";
	echo number_format((($sgpassing/$sgs)/$sgtotal)*100,2,".",",")."% : passing<br>";
	echo number_format((($sginsideshot/$sgs)/$sgtotal)*100,2,".",",")."% : insideshot<br>";
	echo number_format((($sginsidedef/$sgs)/$sgtotal)*100,2,".",",")."% : insidedef<br>";
	echo number_format((($sgrebound/$sgs)/$sgtotal)*100,2,".",",")."% : rebound<br>";
	echo number_format((($sgblock/$sgs)/$sgtotal)*100,2,".",",")."% : block<br>";
	echo "<b>Secondary Skills</b><br>";
	echo number_format(($sgstamina/$sgs),2,".",",")." : Average stamina<br>";
	echo number_format(($sgfreethrow/$sgs),2,".",",")." : Average freethrow<br>";
	echo number_format(($sgexperience/$sgs),2,".",",")." : Average experience<br>";
	
	echo "<hr><h3>Small Forward</h3>";
	$height = 0;
	$sfheight=$sfheight/$sfs;
	$cms = round($sfheight * 2.54,0);
	$feet = floor($sfheight/12);
	$inches = $sfheight%12;
	$height = $feet."'".$inches.'" / '.$cms." cm";
	echo $height." : Average Height<br>";
	echo "<b>Main Skills (percentage of total main skills)</b><br>";
	echo number_format((($sfjumpshot/$sfs)/$sftotal)*100,2,".",",")."% : jumpshot<br>";
	echo number_format((($sfrange/$sfs)/$sftotal)*100,2,".",",")."% : range<br>";
	echo number_format((($sfoutsidedef/$sfs)/$sftotal)*100,2,".",",")."% : outside def<br>";
	echo number_format((($sfhandling/$sfs)/$sftotal)*100,2,".",",")."% : handling<br>";
	echo number_format((($sfdriving/$sfs)/$sftotal)*100,2,".",",")."% : driving<br>";
	echo number_format((($sfpassing/$sfs)/$sftotal)*100,2,".",",")."% : passing<br>";
	echo number_format((($sfinsideshot/$sfs)/$sftotal)*100,2,".",",")."% : insideshot<br>";
	echo number_format((($sfinsidedef/$sfs)/$sftotal)*100,2,".",",")."% : insidedef<br>";
	echo number_format((($sfrebound/$sfs)/$sftotal)*100,2,".",",")."% : rebound<br>";
	echo number_format((($sfblock/$sfs)/$sftotal)*100,2,".",",")."% : block<br>";
	echo "<b>Secondary Skills</b><br>";
	echo number_format(($sfstamina/$sfs),2,".",",")." : Average stamina<br>";
	echo number_format(($sffreethrow/$sfs),2,".",",")." : Average freethrow<br>";
	echo number_format(($sfexperience/$sfs),2,".",",")." : Average experience<br>";
	
	echo "<hr><h3>Power Forward</h3>";
	$height = 0;
	$pfheight=$pfheight/$pfs;
	$cms = round($pfheight * 2.54,0);
	$feet = floor($pfheight/12);
	$inches = $pfheight%12;
	$height = $feet."'".$inches.'" / '.$cms." cm";
	echo $height." : Average Height<br>";
	echo "<b>Main Skills (percentage of total main skills)</b><br>";
	echo number_format((($pfjumpshot/$pfs)/$pftotal)*100,2,".",",")."% : jumpshot<br>";
	echo number_format((($pfrange/$pfs)/$pftotal)*100,2,".",",")."% : range<br>";
	echo number_format((($pfoutsidedef/$pfs)/$pftotal)*100,2,".",",")."% : outside def<br>";
	echo number_format((($pfhandling/$pfs)/$pftotal)*100,2,".",",")."% : handling<br>";
	echo number_format((($pfdriving/$pfs)/$pftotal)*100,2,".",",")."% : driving<br>";
	echo number_format((($pfpassing/$pfs)/$pftotal)*100,2,".",",")."% : passing<br>";
	echo number_format((($pfinsideshot/$pfs)/$pftotal)*100,2,".",",")."% : insideshot<br>";
	echo number_format((($pfinsidedef/$pfs)/$pftotal)*100,2,".",",")."% : insidedef<br>";
	echo number_format((($pfrebound/$pfs)/$pftotal)*100,2,".",",")."% : rebound<br>";
	echo number_format((($pfblock/$pfs)/$pftotal)*100,2,".",",")."% : block<br>";
	echo "<b>Secondary Skills</b><br>";
	echo number_format(($pfstamina/$pfs),2,".",",")." : Average stamina<br>";
	echo number_format(($pffreethrow/$pfs),2,".",",")." : Average freethrow<br>";
	echo number_format(($pfexperience/$pfs),2,".",",")." : Average experience<br>";
	
	echo "<hr><h3>Centre</h3>";
	$height = 0;
	$cheight=$cheight/$cs;
	$cms = round($cheight * 2.54,0);
	$feet = floor($cheight/12);
	$inches = $cheight%12;
	$height = $feet."'".$inches.'" / '.$cms." cm";
	echo $height." : Average Height<br>";
	echo "<b>Main Skills (percentage of total main skills)</b><br>";
	echo number_format((($cjumpshot/$cs)/$ctotal)*100,2,".",",")."% : jumpshot<br>";
	echo number_format((($crange/$cs)/$ctotal)*100,2,".",",")."% : range<br>";
	echo number_format((($coutsidedef/$cs)/$ctotal)*100,2,".",",")."% : outside def<br>";
	echo number_format((($chandling/$cs)/$ctotal)*100,2,".",",")."% : handling<br>";
	echo number_format((($cdriving/$cs)/$ctotal)*100,2,".",",")."% : driving<br>";
	echo number_format((($cpassing/$cs)/$ctotal)*100,2,".",",")."% : passing<br>";
	echo number_format((($cinsideshot/$cs)/$ctotal)*100,2,".",",")."% : insideshot<br>";
	echo number_format((($cinsidedef/$cs)/$ctotal)*100,2,".",",")."% : insidedef<br>";
	echo number_format((($crebound/$cs)/$ctotal)*100,2,".",",")."% : rebound<br>";
	echo number_format((($cblock/$cs)/$ctotal)*100,2,".",",")."% : block<br>";
	echo "<b>Secondary Skills</b><br>";
	echo number_format(($cstamina/$cs),2,".",",")." : Average stamina<br>";
	echo number_format(($cfreethrow/$cs),2,".",",")." : Average freethrow<br>";
	echo number_format(($cexperience/$cs),2,".",",")." : Average experience<br>";
       $mysqli->close();
}

include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];	
	?>
	<div id="xsnazzy">
	<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
	<div class="xboxcontent" style="text-align:center">
	<p>&nbsp;</p>
	
	<a href="/weightings.php">Weightings</a> | <a href="/formatter.php">Forum Formatter</a> | <a href="/pops.php">World Pop Graph</a> | <a href="/team.php">Team View</a>
	
	<p>&nbsp;</p>
	</div>
	<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
	</div>

	<div id="xsnazzy">
	<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
	<div class="xboxcontent">
	<p>&nbsp;</p>
	<h2>Regression Analysis</h2>
<b>Logistic Regression Analysis based on 21 thousand player skills (Accuracy ~93%):</b><br>
<b>Odds Ratios...</b>
<table class="sortable">
<thead>
<tr><td colspan=2 align=center>Class</td></tr>
<tr><td width=100>Variable</td><td width=100>PG</td><td width=100>SG</td><td width=100>SF</td><td width=100>PF</td><td width=100>C</td></tr>
</thead>
<tbody>
<tr><td>gameshape</td><td>0.8594</td><td>0.9235</td><td>0.9105</td><td>0.9363</td><td>0.9294</td></tr>
<tr><td>potential</td><td>0.4979</td><td>0.4952</td><td>0.488</td><td>0.5255</td><td>0.5382</td></tr>
<tr><td>jumpshot</td><td>0.0839</td><td>1.3066</td><td>8.4175</td><td>0.6831</td><td>0.0057</td></tr>
<tr><td>range</td><td>0.9409</td><td>18.4367</td><td>2.0493</td><td>0.2722</td><td>0.2972</td></tr>
<tr><td>outsidedef</td><td>2.4133</td><td>10.5128</td><td>1.2776</td><td>0.249</td><td>0.2383</td></tr>
<tr><td>handling</td><td>4.1782</td><td>0.4449</td><td>0.4352</td><td>0.4202</td><td>0.4239</td></tr>
<tr><td>driving</td><td>1.8294</td><td>0.6036</td><td>0.5982</td><td>0.6051</td><td>0.574</td></tr>
<tr><td>passing</td><td>50.6303</td><td>0.6366</td><td>0.6233</td><td>0.5728</td><td>0.5661</td></tr>
<tr><td>insideshot</td><td>0.2114</td><td>0.2188</td><td>0.2204</td><td>4.0926</td><td>10.5321</td></tr>
<tr><td>insidedef</td><td>0.159</td><td>0.1588</td><td>1.103</td><td>3.6866</td><td>10.2385</td></tr>
<tr><td>rebound</td><td>0.1712</td><td>0.3644</td><td>0.9004</td><td>1.4363</td><td>3.8636</td></tr>
<tr><td>block</td><td>0.5093</td><td>0.5057</td><td>0.5127</td><td>2.1969</td><td>3.3833</td></tr>
<tr><td>stamina</td><td>1.1268</td><td>1.1378</td><td>1.1515</td><td>1.1793</td><td>1.2004</td></tr>
<tr><td>freethrow</td><td>0.8157</td><td>0.8018</td><td>0.8048</td><td>0.7756</td><td>0.7614</td></tr>
<tr><td>experience</td><td>0.8043</td><td>0.7272</td><td>0.7407</td><td>0.7575</td><td>0.7738</td></tr>
</tbody>
</table>

<!--
<h3>Translation to English</h3>
<table class="sortable">
<thead>
<tr><td colspan=2 align=center>Class</td></tr>
<tr><td>Variable</td><td>C</td><td>PF</td><td>SF</td><td>SG</td><td>PG</td></tr>
</thead>
<tbody>
<tr><td>jumpshot</td><td>Extremely Not Important</td><td>Moderately Not Important</td><td>Extremely Important</td><td>Moderately Important</td><td>Extremely Not Important</td></tr>
<tr><td>range</td><td>Very Not Important</td>				<td>Very Not Important</td>		  <td>Quite Important</td>		<td>Extremely Important</td>	<td>A Little Important</td></tr>
<tr><td>outsidedef</td>  <td>Very Not Important</td>  <td>Very Not Important</td><td>A Little Important</td><td>Extremely Important</td><td>Quite Important</td></tr>
<tr><td>handling</td><td>Quite Not Important</td>  <td>Quite Not Important</td>  <td>Quite Not Important</td>  <td>Quite Not Important</td>	<td>Very Important</td></tr>
<tr><td>driving</td> <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td><td>Quite Important</td></tr>
<tr><td>passing</td> <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td><td>Extremely Important</td></tr>
<tr><td>insideshot</td>  <td>Extremely Important</td><td>Very Important</td>  <td>Very Not Important</td> <td>Very Not Important</td>  <td>Very Not Important</td></tr>
<tr><td>insidedef</td><td>Extremely Important</td><td>Very Important</td><td>A Little Not Important</td>  <td>Extremely Not Important</td>  <td>Extremely Not Important</td></tr>
<tr><td>rebound</td>        <td>Very Important</td>		<td>A Little Important</td><td>A Little Important</td>  <td>Quite Not Important</td>  <td>Very Not Important</td></tr>
<tr><td>block</td>          <td>Very Important</td>		<td>Quite Important</td>	<td>Quite Not Important</td><td>Quite Not Important</td><td>Quite Not Important</td></tr>
<tr><td>stamina</td>       <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td></tr>
<tr><td>freethrow</td>     <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td></tr>
<tr><td>experience</td>   <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td></tr>
<tr><td>height</td>         <td>A Little Important</td>   <td>A Little Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td>  <td>A Little Not Important</td></tr>
</tbody>
</table>
-->
	<?
	flush();
	if($_GET['minSalary']>0) $sal = $_GET['minSalary'];
	else $sal = 10000;
	positionWeightings($sal);

?>

<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>
<?

include("footer.php");


?>
