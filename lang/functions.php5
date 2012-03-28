<?php


if ($_GET['lang']) $lang = $_GET['lang'];
else $_GET['lang'] = "en";

echo $lang;

function googTrans($ident,$s,$lang,$q)
{
	if($_SESSION['id']=='38596') echo "test";
	else echo ".";
	if ($lang) $d = $lang;
	else $d = "en";
	// Basic request parameters:
	// s = source language
	// d = destination language
	// q = Text to be translated
	// Example:
	// 	echo googTrans("en",$lang,"Hello World");
	if ($s != $d)
	{
		if(dbCheck($ident,$d)==0)
		{
			$lang_pair = urlencode($s.'|'.$d);
			
			//cut out forms and encode
			if (strpos($q,"<form")>0)
			{
				$removed = substr($q,strpos($q,"<form"),strpos($q,"</form>")+7);
				$q = substr($q,0,strpos($q,"<form"))."__replacemyform__".substr($q,strpos($q,"</form>")+7,strlen($q));	
			}
			$encodedText = urlencode($q);
		
			if (strlen($encodedText)>1000) echo "ERROR: Size too big.<br>";
			
			// Google's API translator URL
			$url = "http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=".$encodedText."&langpair=".$lang_pair;
			
			// Make sure to set CURLOPT_REFERER because Google doesn't like if you leave the referrer out
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_REFERER, "http://www.bb-stats.org/");
			$body = curl_exec($ch);
			curl_close($ch);
		
			$json = json_decode($body, true);
			
			dbPut($ident,$d,$q); //put into the database for next time
			
			return str_replace("__replacemyform__",$removed,$json['responseData']['translatedText']);
		}
		else
		{
			$returnText = dbGet($ident,$d);
			
			if ($returnText) return $returnText;
			else return $q;
		}
	}
	else return $q; //original and "translate to" languages are the same
}

function dbCheck($ident,$d)
{
	//returns 1 if there is a matching ident in the DB
	//returns 0 if there is no matching ident
	
	$query = "SELECT COUNT(*) AS count FROM translation WHERE ident='".$ident."' AND lang='".$d."'";
	$items = mysql_query($query) or die("ERROR (FT.1): Database Translation Query Failed!");
	while ($return = mysql_fetch_array($items))
	{
		if ($return['count']==0) return 0;
		else return 1;
	}
}
function dbGet($ident, $d)
{
	//returns the translated text
	$query = "SELECT text FROM translation WHERE ident='".$ident."' AND lang='".$d."'";
	$items = mysql_query($query) or die("ERROR (FT.2): Database Translation Query Failed!");
	while ($return = mysql_fetch_array($items))
	{
		return $return['text'];
	}
}
function dbPut($ident,$d,$q)
{
	// $ident (page_section_order)
	// $d (the destination language)
	// $q (the translated text)
	//
	//inserts the translated text into the database
	$query = "INSERT INTO translation VALUES
	('".$ident."',
	'".$d."',
	'".$q."')";
	$pass = mysql_query($query) or die("ERROR (FT.3): New Translation Entry Failed!");						
}

function iptocountry($ip)
{
	$numbers = preg_split( "/\./", $ip);
	include("scripts/c_ip/".$numbers[0].".php");
	$code=($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);
	foreach($ranges as $key => $value)
	{
		if($key<=$code)
		{
			if($ranges[$key][0]>=$code)
			{
				$two_letter_country_code=$ranges[$key][1];break;
			}
		}
	}
	if ($two_letter_country_code=="")
	{
		$two_letter_country_code="unknown";
	}
	return $two_letter_country_code;
}


//FUNCTIONS

function getWeek()
{
	//Today's date
	$now = date("U");
	//Start date
	$start = date("U",strtotime("Sat, 11 Apr 2009 03:30:00 -0500"));
	//Difference between
	$diff = ($now-$start)/60/60/24;
	$bbweek = intval($diff/7)+1;
	return $bbweek;
}

function bblogin($username, $accesskey)
{
	global $error;
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_COOKIEJAR, "cookies/".$_SESSION['id']);
	curl_setopt($curl, CURLOPT_URL, $_SESSION['buzzWebsite']."/BBAPI/login.aspx?login=".$username."&code=".$accesskey);
	echo "<br>".$_SESSION['buzzWebsite']."/login.aspx?login=".$username."&code=".$accesskey."<br>";
	curl_setopt($curl, CURLOPT_POSTFIELDS, "");
	
	$reader = new XMLReader();
	if(!$reader->XML(curl_exec($curl)))
	{
		//Try backup server
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_COOKIEJAR, "cookies/".$_SESSION['id']);
		curl_setopt($curl, CURLOPT_URL, "bbapi.buzzerbeater.com/login.aspx?login=".$_SESSION['bbusername']."&code=".$_SESSION['bbaccesskey']);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "");
		if(!$reader->XML(curl_exec($curl)))
		{
			$error = "Cannot connect to BuzzerBeater";
			return 0; //unable to connect
		}
		else
		{
			$server = 2; // use www2 server
		}
	}
	else
	{
		$server = 1; // use www server
	}
	curl_close($curl);
	
	$loggedin = false;// default value
	while ($reader->read())
	{
		echo $reader->name;
		if($reader->name=="loggedIn")
		{
			return $server; //set to allow processing
		}
		if($reader->name="error")
		{
			return -1; //not authorised
		}
	}
	$reader->close();
}

function bbxmlrequest($server, $page, $vars)
{
	// Now we are logged in we can access the requested XML file on BB
	if ($server == 1)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_COOKIEFILE, "cookies/".$_SESSION['id']);
		curl_setopt($curl, CURLOPT_URL, $_SESSION['buzzWebsite']."/".$page.".aspx?".$vars);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "");
	}
	else
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_COOKIEFILE, "cookies/".$_SESSION['id']);
		curl_setopt($curl, CURLOPT_URL, "bbapi.buzzerbeater.com/".$page.".aspx?".$vars);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "");
	}
	
	$reader = new XMLReader();
	if(!$reader->XML(curl_exec($curl)))
	{
		return 0; // unable to access buzzerbeater
	}
	curl_close($curl);
	return $reader;
}

//ON LOAD log the IP and associated country
function log_user()
{
	mysql_pconnect("127.0.0.1","bbstatso_niko","judgement") or die("SITE ERROR:<br>Admins are looking into it.");
	mysql_select_db("bbstatso_database") or die("Unable to select database...");
	
	$IPaddress=$_SERVER['REMOTE_ADDR'];
	$two_letter_country_code=iptocountry($IPaddress);
	$week_id = getWeek();
	
	if($_SESSION['logged']!=1)
	{
		$query = "SELECT count(*) as count FROM user_tracking WHERE ip='".$_SERVER['REMOTE_ADDR']."' AND country='".$two_letter_country_code."' AND last= '".$week_id."'";
		$items = mysql_query($query) or die("Visit Check Failed!");
		$pass = mysql_fetch_array($items);
		if($pass['count']==0)
		{
			$query = "INSERT INTO user_tracking VALUES ('".$_SERVER['REMOTE_ADDR']."','".$two_letter_country_code."','".$week_id."')";
			$pass = mysql_query($query) or die("Visit Insert Failed! ".$query);
		}
		$_SESSION['logged']=1; // if logged previously this week
	}
	$_SESSION['logged']=0; 
	//include("c_ip/countries.php");
	//$three_letter_country_code=$countries[ $two_letter_country_code][0];
	//$country_name=$countries[$two_letter_country_code][1];
	
	//echo $two_letter_country_code;
}

?>