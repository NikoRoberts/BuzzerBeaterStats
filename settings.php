<?php include("header.php");
$_SESSION['returnPage'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>

<?php
if ($_SESSION['loggedin'] == true)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
	$pass1 = false;
	$pass2 = false;
	$errors = "";
	
	//Update if asked to
	if ($_POST['settingscheck']==1)
	{
		$lang = addslashes($_POST['lang']);
		$theme = addslashes($_POST['theme']);
		
		if ($lang!="")
		{
			$query = "INSERT INTO user SET id = '".addslashes($_SESSION['id'])."',language=(SELECT id FROM languages WHERE abbrev='".addslashes($lang)."') ON DUPLICATE KEY UPDATE language=(SELECT id FROM languages WHERE abbrev='".addslashes($lang)."') ";
			$pass1 = $mysqli->query($query);
			if(!$pass1) $errors = "Language Update Failed! Maybe just try using a language selected from the dropdown menu.<br>".mysqli_error($mysqli)."<br>";
		}
		if ($theme>0)
		{
			$query = "INSERT INTO user SET id = '".addslashes($_SESSION['id'])."' , theme='".addslashes($theme)."' ON DUPLICATE KEY UPDATE theme='".addslashes($theme)."'";
			$pass2 = $mysqli->query($query);
			if(!$pass2) $errors = "Theme Update Failed!<pre>".$query."</pre>".mysqli_error($mysqli);
		}
	}
        $mysqli->close();
}
?>

<div id="xsnazzy">
<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
<div class="xboxcontent">
<p>&nbsp;</p>

<?php
if ($_SESSION['loggedin'] == true)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
	if ($_POST['settingscheck']==1)
	{
		if($pass1 && $pass2) echo '<div style="position:absolute;top:20px;left:20px;">* Settings Updated</div>';
		else echo $errors;
	}	
	?>
	<div style="text-align:center"><h1>Settings</h1>
	<div id="settings" style="text-align:center">
	<form id="settingsForm" method="POST" action="settings.php">
	Theme: 
    <select id="themeSel" name="theme">
    <?
	$query = "SELECT U.theme, T.id, T.name FROM themes T LEFT JOIN user U ON U.theme = T.id AND U.id = '".$_SESSION['id']."'";
	$items = $mysqli->query($query) or die("Database Query Failed!");
	while ($return = mysqli_fetch_array($items))
	{
		echo '<option value="'.$return['id'].'"';
		if($return['theme']==$return['id']) echo ' selected="selected"';
		echo '>'.$return['name'].'</option>';
	}
	?>
    </select><br>
     <sub>* You can change the theme temporarily by adding "?theme=nova" or "?theme=classic" to the address bar</sub>
     <br>
    
    <!-- languages -->
    <div id="languages">
        <dl class="dropdown" id="langSelect">
            <dt><a href="#">
            <span><?
				$query = "SELECT C.id, L.name, L.abbrev
						FROM user U
						INNER JOIN languages L ON U.language = L.id
						INNER JOIN countries C ON C.langCode = L.abbrev
						WHERE U.id =  '".$_SESSION['id']."'
						ORDER BY C.users DESC 
						LIMIT 1";
				$items = $mysqli->query($query) or die("Database Query Failed!");
				while ($return = mysqli_fetch_array($items))
				{
					echo '<img class="flag" src="http://'.$_SESSION['buzzImages'].'/images/flags/flag_'.$return['id'].'.gif" width="29px" height="20px" alt=""> '.str_replace('_',' ',ucfirst(strtolower($return['name']))).'<span class="value">'.$return['langCode'].'</span>';
				}
			?></span>
            </a></dt>
           	<dd>
                <ul style="text-align:left;">
                <? 
				$query = "SELECT DISTINCT C.langCode,C.id, L.name,SUM(C.users) FROM countries C INNER JOIN languages L ON C.langCode = L.abbrev GROUP BY C.langCode ORDER BY SUM(C.users) DESC LIMIT 11";
				$items = $mysqli->query($query) or die("Database Query Failed!");
				while ($return = mysqli_fetch_array($items))
				{
					echo '<li><a href="#"><img class="flag" src="http://'.$_SESSION['buzzImages'].'/images/flags/flag_'.$return['id'].'.gif" width="29px" height="20px" alt=""> '.str_replace('_',' ',ucfirst(strtolower($return['name']))).'<span class="value">'.$return['langCode'].'</span></a></li>';
				}
                ?>
                </ul>
            </dd>
        </dl>
    </div>
    <div><input type="text" value="" id="result" style="display:none;" name="lang"></div><a href="javascript:$('#result').css('display','inline');">Enter Other Language Code</a> --
    <a href="http://code.google.com/apis/ajaxlanguage/documentation/reference.html">(See possible codes here)</a>
    <div id="powered"><div id="poweredby" class="cornered"></div></div>

	<input type="submit" value="Save Settings">
	<input type="hidden" name="settingscheck" value="1">
	</form>
	</div>
	</div><?
        $mysqli->close();
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

<script type="text/javascript">
$(document).ready(function(){
    $('#themeSel').change(function()
    {
        $.post('settings.php', $("#settingsForm").serialize(), function(data)
        {
            $("#settingsForm").submit();
        });
    });
});
</script>
