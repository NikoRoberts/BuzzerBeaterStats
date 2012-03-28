<?php
 session_start();
 header('Content-Type: text/html; charset=UTF-8');
 mb_internal_encoding( 'UTF-8' ); //Set the internal encoding of PHP to UTF-8
 ?>
<?php include("functions.php"); ?>
<?php include("func_stats.php"); ?>
<?php include("func_update.php"); ?>
<?php
// Defaults for the walkable site
if(!$_SESSION['defaultSeason']) $_SESSION['defaultSeason']=18;
if(!$_SESSION['defaultTeam']) $_SESSION['defaultTeam']=22807;
if(!$_SESSION['defaultLeague']) $_SESSION['defaultLeague']= rand(1,1050);
if(!$_SESSION['refreshTime']) $_SESSION['refreshTime'] = 86000; //24 hours
if(!$_SESSION['bbusername']) $_SESSION['bbusername'] = "API USERNAME";
if(!$_SESSION['bbaccesskey']) $_SESSION['bbaccesskey'] = "API KEY";
if(!$_SESSION['buzzWebsite']) $_SESSION['buzzWebsite']="bbapi.buzzerbeater.com";
if(!$_SESSION['buzzImages']) $_SESSION['buzzImages']="www.buzzerbeater.com";
if(!$_SESSION['imgServ']) $_SESSION['imgServ']="http://dev.buzzerbeaterstats.com";

if(($_SESSION['id'] && !$_SESSION['theme'] && !$_SESSION['lang']) || ($_SERVER['PHP_SELF']=="/settings.php"))
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    //The initial User settings have not been loaded
    $query = "SELECT T.folder, L.abbrev FROM user U INNER JOIN languages L ON L.id = U.language INNER JOIN themes T ON T.id = U.theme WHERE U.id = '".addslashes($_SESSION['id'])."' LIMIT 1";
    $items = $mysqli->query($query) or die("Database Query Failed!");
    while ($return = mysqli_fetch_array($items))
    {
            $_SESSION['lang'] = $return['abbrev'];
            $_SESSION['theme'] = $return['folder']; 
    }
    $mysqli->close();
}

if(!$_SESSION['theme']) $_SESSION['theme'] = "nova"; // set the default theme
if(!$_SESSION['lang']) $_SESSION['lang'] = "en"; // set the default theme

if($_GET['lang'])
{
	$_SESSION['lang'] = $_GET['lang'];
}
else if($_SESSION['lang'] && !($_GET['lang']))
{
	$_GET['lang'] = $_SESSION['lang'];
}

if($_GET['theme']) $_SESSION['theme'] = addslashes($_GET['theme']);

if(!$_SESSION['faceset'])
{
	$_SESSION['face_colour'] = rand(1,4);
	$_SESSION['face_shape'] = rand(1,36);
	$_SESSION['face_eyes'] = rand(1,70);
	$_SESSION['face_eyebrow'] = rand(1,19);
	$_SESSION['face_mouth'] = rand(1,27);
	$_SESSION['face_nose'] = rand(1,24);
	$_SESSION['face_hair'] = rand(1,24);
	$_SESSION['faceset'] = true;
}
	
    /* Setup extra stuff to add to the title */
    $page = $_SERVER['PHP_SELF'];
    if ($page == "/index.php") $titleextra = " - main page";
    else if ($page == "/team.php") $titleextra = " - team page";
    else if ($page == "/league.php") $titleextra = " - league page";
    else if ($page == "/bblogin.php") $titleextra = " - login page";
    else if ($page == "/bblogout.php") $titleextra = " - logout page";
    else if ($page == "/world.php") $titleextra = " - world page";
    else if ($page == "/country.php") $titleextra = " - country league list";
    else if ($page == "/ntscout.php") $titleextra = " - national team scout tool";
    else if ($page == "/weightings.php") $titleextra = " - skills position weightings";
    else if ($page == "/visitors.php") $titleextra = " - visitor stats";
    else if ($page == "/pops.php") $titleextra = " - recorded skill pops";
    else if ($page == "/settings.php") $titleextra = " - settings";
    else
    {
            $titleextra = " - ".substr($page, 1, strlen($page)-5);
    }
    
    /* Load in the custom CSS for each page if it exists */
    $page_css = "";
    $filename = 'themes/'.$_SESSION['theme'].'/page.'.str_replace("/","",str_replace(".php","",$page)).'.css';
    if (file_exists($filename))
    {
        $page_css = ",".$filename;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>BuzzerBeaterStats <?php echo $titleextra; ?></title>
<meta http-equiv="Content-Type" charset="utf-8" />
<meta name="google-site-verification" content="k5hrM7r1Q520YCSbwBxiemmatx1WoXqRrz0u6XSuaLc" />
<meta name="description" content="BuzzerBeaterStats.com is a free to use statistics site for the online basketball management game BuzzerBeater.com">

<?php if ($_SERVER['HTTP_USER_AGENT'] != "")
{
	//Browsers without Agent Identity does get to see any of the header
	?>
	<script type="text/javascript">
	<!--
	<?php include("themes/".$_SESSION['theme']."/preload.inc"); ?>
	google = new Image(51,15);
	google.src="http://www.google.com/uds/css/small-logo.png";
	shape= new Image(77,112);
	shape.src="http://<?php echo $_SESSION['buzzImages']; ?>/images/faces/faces/<?php echo $_SESSION['face_colour']; ?>/<?php echo $_SESSION['face_shape']; ?>.png";
	eyes= new Image(46,18);
	eyes.src="http://<?php echo $_SESSION['buzzImages']; ?>/images/faces/eyes/<?php echo $_SESSION['face_eyes']; ?>.png";
	eyebrow= new Image(49,9);
	eyebrow.src="http://<?php echo $_SESSION['buzzImages']; ?>/images/faces/eyebrow/<?php echo $_SESSION['face_colour']; ?>/<?php echo $_SESSION['face_eyebrow']; ?>.png";
	mouth= new Image(41,11);
	mouth.src="http://<?php echo $_SESSION['buzzImages']; ?>/images/faces/mouth/<?php echo $_SESSION['face_mouth']; ?>.png";
	nose= new Image(19,15);
	nose.src="http://<?php echo $_SESSION['buzzImages']; ?>/images/faces/nose/<?php echo $_SESSION['face_nose']; ?>.png";
	hair= new Image(76,53);
	hair.src="http://<?php echo $_SESSION['buzzImages']; ?>/images/faces/hair/<?php echo $_SESSION['face_colour']; ?>/<?php echo $_SESSION['face_hair']; ?>.png";
	//-->
	</script>
	
        <link rel="stylesheet" type="text/css" href="/min/?f=themes/<? echo $_SESSION['theme']; ?>/styles.css<?php echo $page_css; ?>&amp;32000">
        <script type="text/javascript">
        document.write('<script type="text/javascript" src="http://www.google.com/jsapi"><\/script>'); //non blocking technique
        </script>
    	
	<?php
} 
flush(); 
?>

    <!-- New Jquery TableSorter used in NTScout -->
    <script type="text/javascript">
        function myPageLoad() {
	        $("#myTable").tablesorter();
            $(".sortable").tablesorter();
			$(".cornered").corner();
			<?php include("themes/".$_SESSION['theme']."/javascript.inc"); ?>
        };
    </script>
</head>

<body>

<div>
<?php include("themes/".$_SESSION['theme']."/header.inc"); ?>
</div>

<div id="topFace" class="faceCont">
	<img class="face" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/faces/<? echo $_SESSION['face_colour']; ?>/<? echo $_SESSION['face_shape']; ?>.png" width="77px" height="112px" style="border-width:0px;" alt="Face">
	<img class="eyes" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/eyes/<? echo $_SESSION['face_eyes']; ?>.png" width="47px" height="18px" style="border-width:0px;" alt="Eyes">
	<img class="eyebrow" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/eyebrow/<? echo $_SESSION['face_colour']; ?>/<? echo $_SESSION['face_eyebrow']; ?>.png" width="46px" height="9px" style="border-width:0px;" alt="Eyebrows">
	<img class="mouth" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/mouth/<? echo $_SESSION['face_mouth']; ?>.png" width="41px" height="11px" style="border-width:0px;" alt="Mouth">
	<img class="nose" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/nose/<? echo $_SESSION['face_nose']; ?>.png" width="19px" height="15px" style="border-width:0px;" alt="Nose">
	<img class="hair" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/hair/<? echo $_SESSION['face_colour']; ?>/<? echo $_SESSION['face_hair']; ?>.png" width="76px" height="57px" style="border-width:0px;" alt="Hair">
</div> 

<div id="main">
