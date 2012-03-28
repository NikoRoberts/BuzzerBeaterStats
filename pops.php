<?php include("header.php");
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
<div class="xboxcontent" style="text-align:center">
<div id="loader"><div style="text-align:center">Please Wait ... BBStats is loading the page<br>
<img src="/themes/<? echo $_SESSION['theme']; ?>/images/ajax-loader.gif" alt="Loading"></div><br></div>
<? flush(); ?>
<script type="text/javascript">
        
			// Load the Visualization API and the piechart package.
			//google.load('visualization', '1', {'packages':['motionchart']});
			google.load('visualization',  '1', {'packages': ['linechart'], 'language' : '<? if($_SESSION['lang']) echo $_SESSION['lang']; else echo 'en'; ?>'})
			
			// Set a callback to run when the Google Visualization API is loaded.
			google.setOnLoadCallback(drawChart);
			
			// Callback that creates and populates a data table, 
			// instantiates the pie chart, passes in the data and
			// draws it.
			function drawChart() {
			
			// Create our data table.
			var data = new google.visualization.DataTable();
			data.addColumn('number', 'Blocking');
			data.addColumn('number', 'Driving');
			data.addColumn('number', 'Free Throws');
			data.addColumn('number', 'Handling');
			data.addColumn('number', 'Inside Defence');
			data.addColumn('number', 'Inside Shot');
			data.addColumn('number', 'Jump Shot');
			data.addColumn('number', 'Outside Defence');
			data.addColumn('number', 'Passing');
			data.addColumn('number', 'Jump Range');
			data.addColumn('number', 'Rebounding');
			data.addColumn('number', 'Stamina');
			data.addRows([
<?
	$first = 1;
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	$query = "SELECT * FROM vw_pops;";
	$items = $mysqli->query($query) or die("ERROR (PQ.1): Pop Query Failed!");
	while ($return = mysqli_fetch_array($items))
	{
		if(($return['skillid']!="stamina")&&($return['skillid']!="block"))
		{
			?>,<? echo $return['popCount'];
		}
		else if($return['skillid']=="block")
		{
			if($first == 1) $first = 0;
			else echo ",";
			?>
			[<? echo $return['popCount'];
		}
		else if($return['skillid']=="stamina")
		{ //close weeks results
			//$first=1;
			?>,<? echo $return['popCount']; ?>]<?
		}
	}
        $mysli->close();
        ?>
	
	]);
	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
	chart.draw(data, {width: 650, height: 500, smoothLine: true, titleX: 'Week', titleY: 'Pops', logScale: true, is3D: true, title: 'Skill Pops / Total Pops for each week'});
	}
	</script>
    <h1>Pops Each Week</h1>
	<div id="chart_div"></div><br>
<p>&nbsp;</p>
</div>
<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
<script type="text/javascript">document.getElementById("loader").style.display="none"</script>
<?php include("footer.php"); ?>
