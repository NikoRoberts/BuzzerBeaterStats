<?php


if ($_GET['lang'])
{
	$lang = $_GET['lang'];
	$_SESSION['lang'] = $lang;
}
else if ($_SESSION['lang']) $lang = $_SESSION['lang'];
else $lang = "en";

function googTrans($ident,$s,$lang,$q)
{
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
			curl_setopt($ch, CURLOPT_REFERER, "http://www.buzzerbeaterstats.com/");
			$body = curl_exec($ch);
			curl_close($ch);
		
			$json = json_decode($body, true);
			
			dbPut($ident,$d,str_replace("__replacemyform__",$removed,$json['responseData']['translatedText'])); //put into the database for next time
			
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
    $mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    
    $query = "SELECT COUNT(*) AS count FROM translation WHERE ident='".$ident."' AND lang='".$d."'";
    $items = $mysqli->query($query) or die("ERROR (FT.1): Database Translation Query Failed!");
    while ($return = mysqli_fetch_array($items))
    {
            if ($return['count']==0) return 0;
            else return 1;
    }
    $mysqli->close();
}
function dbGet($ident, $d)
{
    $mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    //returns the translated text
    $query = "SELECT text FROM translation WHERE ident='".$ident."' AND lang='".$d."'";
    $items = $mysqli->query($query) or die("ERROR (FT.2): Database Translation Query Failed!");
    while ($return = mysqli_fetch_array($items))
    {
            return stripslashes($return['text']);
    }
    $mysqli->close();
}
function dbPut($ident,$d,$q)
{
    // $ident (page_section_order)
    // $d (the destination language)
    // $q (the translated text)
    //
    //inserts the translated text into the database
    $mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    $query = "INSERT INTO translation VALUES
    ('".$ident."',
    '".$d."',
    '".addslashes($q)."')";
    $pass = $mysqli->query($query) or die("ERROR (FT.3): New Translation Entry Failed!<pre>".$query."</pre>".mysqli_error($mysqli));						
    $mysqli->close();
}


function newiptocountry($ip,$val="countryName")
{
    
    //$url = "http://ipinfodb.com/ip_query_country.php?ip=".$ip."&output=json";
    $apikey = "17321cc2a79df7acd97588f6a0cc3f41e2d5761e4f641e0de31037b4b42e42e4";
    $url = "http://api.ipinfodb.com/v3/ip-country/?key=".$apikey."&ip=".$ip."&format=json";
    
    // Make sure to set CURLOPT_REFERER because Google doesn't like if you leave the referrer out
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, "http://www.buzzerbeaterstats.com/");
    $body = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($body, true);
    return $json[$val];
}

function newiptocontinent($ip)
{
    //return the continent matching the IP
    return codetocontinent(newiptocountry($ip,"countryCode"));
}

function codetocontinent($countrycode)
{
    $pre = '{"AD":"Europe","AE":"Asia","AF":"Asia","AG":"North America","AI":"North America","AL":"Europe","AM":"Asia","AN":"North America","AO":"Africa","AQ":"Antarctica","AR":"South America","AS":"Australia","AT":"Europe","AU":"Australia","AW":"North America","AZ":"Asia","BA":"Europe","BB":"North America","BD":"Asia","BE":"Europe","BF":"Africa","BG":"Europe","BH":"Asia","BI":"Africa","BJ":"Africa","BM":"North America","BN":"Asia","BO":"South America","BR":"South America","BS":"North America","BT":"Asia","BW":"Africa","BY":"Europe","BZ":"North America","CA":"North America","CC":"Asia","CD":"Africa","CF":"Africa","CG":"Africa","CH":"Europe","CI":"Africa","CK":"Australia","CL":"South America","CM":"Africa","CN":"Asia","CO":"South America","CR":"North America","CU":"North America","CV":"Africa","CX":"Asia","CY":"Asia","CZ":"Europe","DE":"Europe","DJ":"Africa","DK":"Europe","DM":"North America","DO":"North America","DZ":"Africa","EC":"South America","EE":"Europe","EG":"Africa","EH":"Africa","ER":"Africa","ES":"Europe","ET":"Africa","FI":"Europe","FJ":"Australia","FK":"South America","FM":"Australia","FO":"Europe","FR":"Europe","GA":"Africa","GB":"Europe","GD":"North America","GE":"Asia","GF":"South America","GG":"Europe","GH":"Africa","GI":"Europe","GL":"North America","GM":"Africa","GN":"Africa","GP":"North America","GQ":"Africa","GR":"Europe","GS":"Antarctica","GT":"North America","GU":"Australia","GW":"Africa","GY":"South America","HK":"Asia","HN":"North America","HR":"Europe","HT":"North America","HU":"Europe","ID":"Asia","IE":"Europe","IL":"Asia","IM":"Europe","IN":"Asia","IO":"Asia","IQ":"Asia","IR":"Asia","IS":"Europe","IT":"Europe","JE":"Europe","JM":"North America","JO":"Asia","JP":"Asia","KE":"Africa","KG":"Asia","KH":"Asia","KI":"Australia","KM":"Africa","KN":"North America","KP":"Asia","KR":"Asia","KW":"Asia","KY":"North America","KZ":"Asia","LA":"Asia","LB":"Asia","LC":"North America","LI":"Europe","LK":"Asia","LR":"Africa","LS":"Africa","LT":"Europe","LU":"Europe","LV":"Europe","LY":"Africa","MA":"Africa","MC":"Europe","MD":"Europe","ME":"Europe","MG":"Africa","MH":"Australia","MK":"Europe","ML":"Africa","MM":"Asia","MN":"Asia","MO":"Asia","MP":"Australia","MQ":"North America","MR":"Africa","MS":"North America","MT":"Europe","MU":"Africa","MV":"Asia","MW":"Africa","MX":"North America","MY":"Asia","MZ":"Africa","NA":"Africa","NC":"Australia","NE":"Africa","NF":"Australia","NG":"Africa","NI":"North America","NL":"Europe","NO":"Europe","NP":"Asia","NR":"Australia","NU":"Australia","NZ":"Australia","OM":"Asia","PA":"North America","PE":"South America","PF":"Australia","PG":"Australia","PH":"Asia","PK":"Asia","PL":"Europe","PM":"North America","PN":"Australia","PR":"North America","PS":"Asia","PT":"Europe","PW":"Australia","PY":"South America","QA":"Asia","RE":"Africa","RO":"Europe","RS":"Europe","RU":"Europe","RW":"Africa","SA":"Asia","SB":"Australia","SC":"Africa","SD":"Africa","SE":"Europe","SG":"Asia","SH":"Africa","SI":"Europe","SJ":"Europe","SK":"Europe","SL":"Africa","SM":"Europe","SN":"Africa","SO":"Africa","SR":"South America","ST":"Africa","SV":"North America","SY":"Asia","SZ":"Africa","TC":"North America","TD":"Africa","TF":"Antarctica","TG":"Africa","TH":"Asia","TJ":"Asia","TK":"Australia","TM":"Asia","TN":"Africa","TO":"Australia","TR":"Asia","TT":"North America","TV":"Australia","TW":"Asia","TZ":"Africa","UA":"Europe","UG":"Africa","US":"North America","UY":"South America","UZ":"Asia","VC":"North America","VE":"South America","VG":"North America","VI":"North America","VN":"Asia","VU":"Australia","WF":"Australia","WS":"Australia","YE":"Asia","YT":"Africa","ZA":"Africa","ZM":"Africa","ZW":"Africa"}';
    $json = json_decode($pre,true);
    return $json[$countrycode];
}

function countrytotravellrid($countryName)
{
    /* Travellr JSON reply with no text input (defaults search to "kayak") */ 
    /*
    {
            "info":
            {
                    "duration":"0.0020 seconds"
            }
            ,"document":
            {
                    "request":"Where's the best kayaking spot in tasmania?"
                    ,"topic_matches":[
                                                      {"sentence":0,"word":3,"topic":{"name":"kayaking","stemmed":"kayak"}}
                                                      ]
            }
            ,"location_matches":
            [{
                                                     "sentence":0,"confidence":127,"word":7,"words_matched":1,"matched_terms":["tasmania"]
                                                    ,"location":{"full_name":"Tasmania, Australia","question_count":40,"name":"Tasmania","loc_type":3,"id":54266,"country_code":"au"}
                                                    ,"is_best_match_for_reference":true,"alias":{"content":"tasmania","type":"travellr.model.AliasType(0)"}
            }]
    }
     */
    //get countrycode first
    $url = "http://travellr.com/services/location-finder-1.0/document.js?text=".$countryName;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, "http://www.buzzerbeaterstats.com/");
    $body = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($body, true);

    //return the continent matching the IP
    return $json["document"]["location_matches"][0]["location"]["id"];
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
				$two_letter_country_code=$ranges[$key][1];
                                break;
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
		curl_setopt($curl, CURLOPT_COOKIEJAR, "/tmp/cookies/".$_SESSION['id']);
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
		curl_setopt($curl, CURLOPT_COOKIEFILE, "/tmp/cookies/".$_SESSION['id']);
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
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	$IPaddress=$_SERVER['REMOTE_ADDR'];
	$two_letter_country_code=iptocountry($IPaddress);
	$week_id = getWeek();
	
	if($_SESSION['logged']!=1)
	{
		$query = "SELECT count(*) as count FROM user_tracking WHERE ip='".$_SERVER['REMOTE_ADDR']."' AND country='".$two_letter_country_code."' AND last= '".$week_id."'";
		$items = $mysqli->query($query) or die("Visit Check Failed!");
		$pass = mysqli_fetch_array($items);
		if($pass['count']==0)
		{
			$query = "INSERT INTO user_tracking VALUES ('".$_SERVER['REMOTE_ADDR']."','".$two_letter_country_code."','".$week_id."')";
			$pass = $mysqli->query($query) or die("Visit Insert Failed! ".$query);
		}
		$_SESSION['logged']=1; // if logged previously this week
	}
	$_SESSION['logged']=0; 
	//include("c_ip/countries.php");
	//$three_letter_country_code=$countries[ $two_letter_country_code][0];
	//$country_name=$countries[$two_letter_country_code][1];
	
	//echo $two_letter_country_code;
        $mysqli->close();
}

function getCountryCode($countryName)
{
	if(strtolower("AFGHANISTAN")==strtolower($countryName)) return "AF";
	else if(strtolower("�LAND ISLANDS")==strtolower($countryName)) return "AX";
	else if(strtolower("ALBANIA")==strtolower($countryName)) return "AL";
	else if(strtolower("ALGERIA")==strtolower($countryName)) return "DZ";
	else if(strtolower("AMERICAN SAMOA")==strtolower($countryName)) return "AS";
	else if(strtolower("ANDORRA")==strtolower($countryName)) return "AD";
	else if(strtolower("ANGOLA")==strtolower($countryName)) return "AO";
	else if(strtolower("ANGUILLA")==strtolower($countryName)) return "AI";
	else if(strtolower("ANTARCTICA")==strtolower($countryName)) return "AQ";
	else if(strtolower("ANTIGUA AND BARBUDA")==strtolower($countryName)) return "AG";
	else if(strtolower("ARGENTINA")==strtolower($countryName)) return "AR";
	else if(strtolower("ARMENIA")==strtolower($countryName)) return "AM";
	else if(strtolower("ARUBA")==strtolower($countryName)) return "AW";
	else if(strtolower("AUSTRALIA")==strtolower($countryName)) return "AU";
	else if(strtolower("AUSTRIA")==strtolower($countryName)) return "AT";
	else if(strtolower("AZERBAIJAN")==strtolower($countryName)) return "AZ";
	else if(strtolower("BAHAMAS")==strtolower($countryName)) return "BS";
	else if(strtolower("BAHRAIN")==strtolower($countryName)) return "BH";
	else if(strtolower("BANGLADESH")==strtolower($countryName)) return "BD";
	else if(strtolower("BARBADOS")==strtolower($countryName)) return "BB";
	else if(strtolower("BELARUS")==strtolower($countryName)) return "BY";
	else if(strtolower("BELGIUM")==strtolower($countryName)) return "BE";
	else if(strtolower("BELIZE")==strtolower($countryName)) return "BZ";
	else if(strtolower("BENIN")==strtolower($countryName)) return "BJ";
	else if(strtolower("BERMUDA")==strtolower($countryName)) return "BM";
	else if(strtolower("BHUTAN")==strtolower($countryName)) return "BT";
	else if(strtolower("BOLIVIA, PLURINATIONAL STATE OF")==strtolower($countryName)) return "BO";
	else if(strtolower("BOSNIA AND HERZEGOVINA")==strtolower($countryName)) return "BA";
	else if(strtolower("BOTSWANA")==strtolower($countryName)) return "BW";
	else if(strtolower("BOUVET ISLAND")==strtolower($countryName)) return "BV";
	else if(strtolower("BRAZIL")==strtolower($countryName)) return "BR";
	else if(strtolower("BRITISH INDIAN OCEAN TERRITORY")==strtolower($countryName)) return "IO";
	else if(strtolower("BRUNEI DARUSSALAM")==strtolower($countryName)) return "BN";
	else if(strtolower("BULGARIA")==strtolower($countryName)) return "BG";
	else if(strtolower("BURKINA FASO")==strtolower($countryName)) return "BF";
	else if(strtolower("BURUNDI")==strtolower($countryName)) return "BI";
	else if(strtolower("CAMBODIA")==strtolower($countryName)) return "KH";
	else if(strtolower("CAMEROON")==strtolower($countryName)) return "CM";
	else if(strtolower("CANADA")==strtolower($countryName)) return "CA";
	else if(strtolower("CAPE VERDE")==strtolower($countryName)) return "CV";
	else if(strtolower("CAYMAN ISLANDS")==strtolower($countryName)) return "KY";
	else if(strtolower("CENTRAL AFRICAN REPUBLIC")==strtolower($countryName)) return "CF";
	else if(strtolower("CHAD")==strtolower($countryName)) return "TD";
	else if(strtolower("CHILE")==strtolower($countryName)) return "CL";
	else if(strtolower("CHINA")==strtolower($countryName)) return "CN";
	else if(strtolower("CHRISTMAS ISLAND")==strtolower($countryName)) return "CX";
	else if(strtolower("COCOS (KEELING) ISLANDS")==strtolower($countryName)) return "CC";
	else if(strtolower("COLOMBIA")==strtolower($countryName)) return "CO";
	else if(strtolower("COMOROS")==strtolower($countryName)) return "KM";
	else if(strtolower("CONGO")==strtolower($countryName)) return "CG";
	else if(strtolower("CONGO, THE DEMOCRATIC REPUBLIC OF THE")==strtolower($countryName)) return "CD";
	else if(strtolower("COOK ISLANDS")==strtolower($countryName)) return "CK";
	else if(strtolower("COSTA RICA")==strtolower($countryName)) return "CR";
	else if(strtolower("C�TE D'IVOIRE")==strtolower($countryName)) return "CI";
	else if(strtolower("CROATIA")==strtolower($countryName)) return "HR";
	else if(strtolower("CUBA")==strtolower($countryName)) return "CU";
	else if(strtolower("CYPRUS")==strtolower($countryName)) return "CY";
	else if(strtolower("CZECH REPUBLIC")==strtolower($countryName)) return "CZ";
	else if(strtolower("DENMARK")==strtolower($countryName)) return "DK";
	else if(strtolower("DJIBOUTI")==strtolower($countryName)) return "DJ";
	else if(strtolower("DOMINICA")==strtolower($countryName)) return "DM";
	else if(strtolower("DOMINICAN REPUBLIC")==strtolower($countryName)) return "DO";
	else if(strtolower("ECUADOR")==strtolower($countryName)) return "EC";
	else if(strtolower("EGYPT")==strtolower($countryName)) return "EG";
	else if(strtolower("EL SALVADOR")==strtolower($countryName)) return "SV";
	else if(strtolower("EQUATORIAL GUINEA")==strtolower($countryName)) return "GQ";
	else if(strtolower("ERITREA")==strtolower($countryName)) return "ER";
	else if(strtolower("ESTONIA")==strtolower($countryName)) return "EE";
	else if(strtolower("ETHIOPIA")==strtolower($countryName)) return "ET";
	else if(strtolower("FALKLAND ISLANDS (MALVINAS)")==strtolower($countryName)) return "FK";
	else if(strtolower("FAROE ISLANDS")==strtolower($countryName)) return "FO";
	else if(strtolower("FIJI")==strtolower($countryName)) return "FJ";
	else if(strtolower("FINLAND")==strtolower($countryName)) return "FI";
	else if(strtolower("FRANCE")==strtolower($countryName)) return "FR";
	else if(strtolower("FRENCH GUIANA")==strtolower($countryName)) return "GF";
	else if(strtolower("FRENCH POLYNESIA")==strtolower($countryName)) return "PF";
	else if(strtolower("FRENCH SOUTHERN TERRITORIES")==strtolower($countryName)) return "TF";
	else if(strtolower("GABON")==strtolower($countryName)) return "GA";
	else if(strtolower("GAMBIA")==strtolower($countryName)) return "GM";
	else if(strtolower("GEORGIA")==strtolower($countryName)) return "GE";
	else if(strtolower("GERMANY")==strtolower($countryName)) return "DE";
	else if(strtolower("GHANA")==strtolower($countryName)) return "GH";
	else if(strtolower("GIBRALTAR")==strtolower($countryName)) return "GI";
	else if(strtolower("GREECE")==strtolower($countryName)) return "GR";
	else if(strtolower("GREENLAND")==strtolower($countryName)) return "GL";
	else if(strtolower("GRENADA")==strtolower($countryName)) return "GD";
	else if(strtolower("GUADELOUPE")==strtolower($countryName)) return "GP";
	else if(strtolower("GUAM")==strtolower($countryName)) return "GU";
	else if(strtolower("GUATEMALA")==strtolower($countryName)) return "GT";
	else if(strtolower("GUERNSEY")==strtolower($countryName)) return "GG";
	else if(strtolower("GUINEA")==strtolower($countryName)) return "GN";
	else if(strtolower("GUINEA-BISSAU")==strtolower($countryName)) return "GW";
	else if(strtolower("GUYANA")==strtolower($countryName)) return "GY";
	else if(strtolower("HAITI")==strtolower($countryName)) return "HT";
	else if(strtolower("HEARD ISLAND AND MCDONALD ISLANDS")==strtolower($countryName)) return "HM";
	else if(strtolower("HOLY SEE (VATICAN CITY STATE)")==strtolower($countryName)) return "VA";
	else if(strtolower("HONDURAS")==strtolower($countryName)) return "HN";
	else if(strtolower("HONG KONG")==strtolower($countryName)) return "HK";
	else if(strtolower("HUNGARY")==strtolower($countryName)) return "HU";
	else if(strtolower("ICELAND")==strtolower($countryName)) return "IS";
	else if(strtolower("INDIA")==strtolower($countryName)) return "IN";
	else if(strtolower("INDONESIA")==strtolower($countryName)) return "ID";
	else if(strtolower("IRAN, ISLAMIC REPUBLIC OF")==strtolower($countryName)) return "IR";
	else if(strtolower("IRAQ")==strtolower($countryName)) return "IQ";
	else if(strtolower("IRELAND")==strtolower($countryName)) return "IE";
	else if(strtolower("ISLE OF MAN")==strtolower($countryName)) return "IM";
	else if(strtolower("ISRAEL")==strtolower($countryName)) return "IL";
	else if(strtolower("ITALY")==strtolower($countryName)) return "IT";
	else if(strtolower("JAMAICA")==strtolower($countryName)) return "JM";
	else if(strtolower("JAPAN")==strtolower($countryName)) return "JP";
	else if(strtolower("JERSEY")==strtolower($countryName)) return "JE";
	else if(strtolower("JORDAN")==strtolower($countryName)) return "JO";
	else if(strtolower("KAZAKHSTAN")==strtolower($countryName)) return "KZ";
	else if(strtolower("KENYA")==strtolower($countryName)) return "KE";
	else if(strtolower("KIRIBATI")==strtolower($countryName)) return "KI";
	else if(strtolower("KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF")==strtolower($countryName)) return "KP";
	else if(strtolower("KOREA, REPUBLIC OF")==strtolower($countryName)) return "KR";
	else if(strtolower("KUWAIT")==strtolower($countryName)) return "KW";
	else if(strtolower("KYRGYZSTAN")==strtolower($countryName)) return "KG";
	else if(strtolower("LAO PEOPLE'S DEMOCRATIC REPUBLIC")==strtolower($countryName)) return "LA";
	else if(strtolower("LATVIA")==strtolower($countryName)) return "LV";
	else if(strtolower("LEBANON")==strtolower($countryName)) return "LB";
	else if(strtolower("LESOTHO")==strtolower($countryName)) return "LS";
	else if(strtolower("LIBERIA")==strtolower($countryName)) return "LR";
	else if(strtolower("LIBYAN ARAB JAMAHIRIYA")==strtolower($countryName)) return "LY";
	else if(strtolower("LIECHTENSTEIN")==strtolower($countryName)) return "LI";
	else if(strtolower("LITHUANIA")==strtolower($countryName)) return "LT";
	else if(strtolower("LUXEMBOURG")==strtolower($countryName)) return "LU";
	else if(strtolower("MACAO")==strtolower($countryName)) return "MO";
	else if(strtolower("MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF")==strtolower($countryName)) return "MK";
	else if(strtolower("MADAGASCAR")==strtolower($countryName)) return "MG";
	else if(strtolower("MALAWI")==strtolower($countryName)) return "MW";
	else if(strtolower("MALAYSIA")==strtolower($countryName)) return "MY";
	else if(strtolower("MALDIVES")==strtolower($countryName)) return "MV";
	else if(strtolower("MALI")==strtolower($countryName)) return "ML";
	else if(strtolower("MALTA")==strtolower($countryName)) return "MT";
	else if(strtolower("MARSHALL ISLANDS")==strtolower($countryName)) return "MH";
	else if(strtolower("MARTINIQUE")==strtolower($countryName)) return "MQ";
	else if(strtolower("MAURITANIA")==strtolower($countryName)) return "MR";
	else if(strtolower("MAURITIUS")==strtolower($countryName)) return "MU";
	else if(strtolower("MAYOTTE")==strtolower($countryName)) return "YT";
	else if(strtolower("MEXICO")==strtolower($countryName)) return "MX";
	else if(strtolower("MICRONESIA, FEDERATED STATES OF")==strtolower($countryName)) return "FM";
	else if(strtolower("MOLDOVA, REPUBLIC OF")==strtolower($countryName)) return "MD";
	else if(strtolower("MONACO")==strtolower($countryName)) return "MC";
	else if(strtolower("MONGOLIA")==strtolower($countryName)) return "MN";
	else if(strtolower("MONTENEGRO")==strtolower($countryName)) return "ME";
	else if(strtolower("MONTSERRAT")==strtolower($countryName)) return "MS";
	else if(strtolower("MOROCCO")==strtolower($countryName)) return "MA";
	else if(strtolower("MOZAMBIQUE")==strtolower($countryName)) return "MZ";
	else if(strtolower("MYANMAR")==strtolower($countryName)) return "MM";
	else if(strtolower("NAMIBIA")==strtolower($countryName)) return "NA";
	else if(strtolower("NAURU")==strtolower($countryName)) return "NR";
	else if(strtolower("NEPAL")==strtolower($countryName)) return "NP";
	else if(strtolower("NETHERLANDS")==strtolower($countryName)) return "NL";
	else if(strtolower("NETHERLANDS ANTILLES")==strtolower($countryName)) return "AN";
	else if(strtolower("NEW CALEDONIA")==strtolower($countryName)) return "NC";
	else if(strtolower("NEW ZEALAND")==strtolower($countryName)) return "NZ";
	else if(strtolower("NICARAGUA")==strtolower($countryName)) return "NI";
	else if(strtolower("NIGER")==strtolower($countryName)) return "NE";
	else if(strtolower("NIGERIA")==strtolower($countryName)) return "NG";
	else if(strtolower("NIUE")==strtolower($countryName)) return "NU";
	else if(strtolower("NORFOLK ISLAND")==strtolower($countryName)) return "NF";
	else if(strtolower("NORTHERN MARIANA ISLANDS")==strtolower($countryName)) return "MP";
	else if(strtolower("NORWAY")==strtolower($countryName)) return "NO";
	else if(strtolower("OMAN")==strtolower($countryName)) return "OM";
	else if(strtolower("PAKISTAN")==strtolower($countryName)) return "PK";
	else if(strtolower("PALAU")==strtolower($countryName)) return "PW";
	else if(strtolower("PALESTINIAN TERRITORY, OCCUPIED")==strtolower($countryName)) return "PS";
	else if(strtolower("PANAMA")==strtolower($countryName)) return "PA";
	else if(strtolower("PAPUA NEW GUINEA")==strtolower($countryName)) return "PG";
	else if(strtolower("PARAGUAY")==strtolower($countryName)) return "PY";
	else if(strtolower("PERU")==strtolower($countryName)) return "PE";
	else if(strtolower("PHILIPPINES")==strtolower($countryName)) return "PH";
	else if(strtolower("PITCAIRN")==strtolower($countryName)) return "PN";
	else if(strtolower("POLAND")==strtolower($countryName)) return "PL";
	else if(strtolower("PORTUGAL")==strtolower($countryName)) return "PT";
	else if(strtolower("PUERTO RICO")==strtolower($countryName)) return "PR";
	else if(strtolower("QATAR")==strtolower($countryName)) return "QA";
	else if(strtolower("R�UNION")==strtolower($countryName)) return "RE";
	else if(strtolower("ROMANIA")==strtolower($countryName)) return "RO";
	else if(strtolower("RUSSIAN FEDERATION")==strtolower($countryName)) return "RU";
	else if(strtolower("RWANDA")==strtolower($countryName)) return "RW";
	else if(strtolower("SAINT BARTH�LEMY")==strtolower($countryName)) return "BL";
	else if(strtolower("SAINT HELENA")==strtolower($countryName)) return "SH";
	else if(strtolower("SAINT KITTS AND NEVIS")==strtolower($countryName)) return "KN";
	else if(strtolower("SAINT LUCIA")==strtolower($countryName)) return "LC";
	else if(strtolower("SAINT MARTIN")==strtolower($countryName)) return "MF";
	else if(strtolower("SAINT PIERRE AND MIQUELON")==strtolower($countryName)) return "PM";
	else if(strtolower("SAINT VINCENT AND THE GRENADINES")==strtolower($countryName)) return "VC";
	else if(strtolower("SAMOA")==strtolower($countryName)) return "WS";
	else if(strtolower("SAN MARINO")==strtolower($countryName)) return "SM";
	else if(strtolower("SAO TOME AND PRINCIPE")==strtolower($countryName)) return "ST";
	else if(strtolower("SAUDI ARABIA")==strtolower($countryName)) return "SA";
	else if(strtolower("SENEGAL")==strtolower($countryName)) return "SN";
	else if(strtolower("SERBIA")==strtolower($countryName)) return "RS";
	else if(strtolower("SEYCHELLES")==strtolower($countryName)) return "SC";
	else if(strtolower("SIERRA LEONE")==strtolower($countryName)) return "SL";
	else if(strtolower("SINGAPORE")==strtolower($countryName)) return "SG";
	else if(strtolower("SLOVAKIA")==strtolower($countryName)) return "SK";
	else if(strtolower("SLOVENIA")==strtolower($countryName)) return "SI";
	else if(strtolower("SOLOMON ISLANDS")==strtolower($countryName)) return "SB";
	else if(strtolower("SOMALIA")==strtolower($countryName)) return "SO";
	else if(strtolower("SOUTH AFRICA")==strtolower($countryName)) return "ZA";
	else if(strtolower("SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS")==strtolower($countryName)) return "GS";
	else if(strtolower("SPAIN")==strtolower($countryName)) return "ES";
	else if(strtolower("SRI LANKA")==strtolower($countryName)) return "LK";
	else if(strtolower("SUDAN")==strtolower($countryName)) return "SD";
	else if(strtolower("SURINAME")==strtolower($countryName)) return "SR";
	else if(strtolower("SVALBARD AND JAN MAYEN")==strtolower($countryName)) return "SJ";
	else if(strtolower("SWAZILAND")==strtolower($countryName)) return "SZ";
	else if(strtolower("SWEDEN")==strtolower($countryName)) return "SE";
	else if(strtolower("SWITZERLAND")==strtolower($countryName)) return "CH";
	else if(strtolower("SYRIAN ARAB REPUBLIC")==strtolower($countryName)) return "SY";
	else if(strtolower("TAIWAN, PROVINCE OF CHINA")==strtolower($countryName)) return "TW";
	else if(strtolower("TAJIKISTAN")==strtolower($countryName)) return "TJ";
	else if(strtolower("TANZANIA, UNITED REPUBLIC OF")==strtolower($countryName)) return "TZ";
	else if(strtolower("THAILAND")==strtolower($countryName)) return "TH";
	else if(strtolower("TIMOR-LESTE")==strtolower($countryName)) return "TL";
	else if(strtolower("TOGO")==strtolower($countryName)) return "TG";
	else if(strtolower("TOKELAU")==strtolower($countryName)) return "TK";
	else if(strtolower("TONGA")==strtolower($countryName)) return "TO";
	else if(strtolower("TRINIDAD AND TOBAGO")==strtolower($countryName)) return "TT";
	else if(strtolower("TUNISIA")==strtolower($countryName)) return "TN";
	else if(strtolower("TURKEY")==strtolower($countryName)) return "TR";
	else if(strtolower("TURKMENISTAN")==strtolower($countryName)) return "TM";
	else if(strtolower("TURKS AND CAICOS ISLANDS")==strtolower($countryName)) return "TC";
	else if(strtolower("TUVALU")==strtolower($countryName)) return "TV";
	else if(strtolower("UGANDA")==strtolower($countryName)) return "UG";
	else if(strtolower("UKRAINE")==strtolower($countryName)) return "UA";
	else if(strtolower("UNITED ARAB EMIRATES")==strtolower($countryName)) return "AE";
	else if(strtolower("UNITED KINGDOM")==strtolower($countryName)) return "GB";
	else if(strtolower("UNITED STATES")==strtolower($countryName)) return "US";
	else if(strtolower("UNITED STATES MINOR OUTLYING ISLANDS")==strtolower($countryName)) return "UM";
	else if(strtolower("URUGUAY")==strtolower($countryName)) return "UY";
	else if(strtolower("UZBEKISTAN")==strtolower($countryName)) return "UZ";
	else if(strtolower("VANUATU")==strtolower($countryName)) return "VU";
	else if(strtolower("VENEZUELA, BOLIVARIAN REPUBLIC OF")==strtolower($countryName)) return "VE";
	else if(strtolower("VIET NAM")==strtolower($countryName)) return "VN";
	else if(strtolower("VIRGIN ISLANDS, BRITISH")==strtolower($countryName)) return "VG";
	else if(strtolower("VIRGIN ISLANDS, U.S.")==strtolower($countryName)) return "VI";
	else if(strtolower("WALLIS AND FUTUNA")==strtolower($countryName)) return "WF";
	else if(strtolower("WESTERN SAHARA")==strtolower($countryName)) return "EH";
	else if(strtolower("YEMEN")==strtolower($countryName)) return "YE";
	else if(strtolower("ZAMBIA")==strtolower($countryName)) return "ZM";
	else if(strtolower("ZIMBABWE")==strtolower($countryName)) return "ZW";
}
?>
