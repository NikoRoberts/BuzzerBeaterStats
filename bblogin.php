<?php

function bbloginhtml()
{
	global $error;
	include("header.php"); ?>
	<div id="xsnazzy">
	<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
	<div class="xboxcontent">
	<p>&nbsp;</p>
	
	<center>
    
	<?php if ($error) echo $error."<br>"; ?>
    Please input your Buzzerbeater username and the AccessKey you have <br>
	set in your BuzzerBeater profile (this is not your password)<br>
	<form method="POST" name="logform" action="bblogin.php">
	<table>
	<tr>
	<td align="right">Username: </td><td align="left"><input name="username" type="text" value=""></td>
	</tr>
	<tr>
	<td align="right">Accesskey: </td><td align="left"><input name="accesskey" type="password" value=""></td>
	</tr>
	<tr>
	<td colspan="2" align="center"><input type="submit" value="Submit"></td>
	</tr>
	</table>
	</form>
	</center>
	
	
	<p>&nbsp;</p>
	</div>
	<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
	</div>
	
	<?php include("footer.php");
}

if($_POST['username'])
{

	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	        
	// define session variables
	session_register('id');
	session_register('loggedin');
	$_SESSION['loggedin']=false;
	session_register('bbusername');
	session_register('bbaccesskey');
	
	session_register('teamName');
	session_register('teamAbbr');
	session_register('owner');
	session_register('supporter');
	session_register('status');
	session_register('league');
	session_register('leagueId');
	session_register('leagueLevel');
	session_register('leagueName');
	session_register('country');
	session_register('countryId');
	
	$username = str_replace(' ','%20',$_POST['username']);
	
	if(!$_SESSION['returnPage']) session_register('returnPage');

	// login to BBAPI with username and accesskey
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_URL, $_SESSION['buzzWebsite']."/login.aspx?login=".$username."&code=".$_POST['accesskey']."&quickinfo=1");
	curl_setopt($curl, CURLOPT_POSTFIELDS, "");
	
	$reader = new XMLReader();
	if(!$reader->XML(curl_exec($curl),"UTF-8")) { $error = "ERROR: <i>Cannot connect to BuzzerBeater</i><br>"; bbloginhtml();}
	curl_close($curl);
	$loggedin = false;// default value
	while ($reader->read())
	{
		if($reader->nodeType == XMLReader::ELEMENT)
		{
			if($reader->name=="team")
			{
				$_SESSION['id']=$reader->getAttribute('id');
				$loggedin = true; //set to allow processing
			}
			else if($reader->name=="league")
			{
				$_SESSION['leagueId']=$reader->getAttribute('id');
			}
			else if($reader->name=="country")
			{
				$_SESSION['countryId']=$reader->getAttribute('id');
			}
			else if($reader->name=="owner")
			{
				$_SESSION['supporter']=$reader->getAttribute('supporter');
			}
			$node_name = $reader->name;
		}
		else if ($reader->nodeType == XMLReader::TEXT )
		{
			if($node_name=="teamName")
			{
				$_SESSION['teamName']=$reader->value;
			}
			else if($node_name=="shortName")
			{
				$_SESSION['teamAbbr']=$reader->value;
			}
			else if($node_name=="owner")
			{
				$_SESSION['owner']=$reader->value;
			}
			else if($node_name=="league")
			{
				$_SESSION['league']=$reader->value;
			}
			else if($node_name=="country")
			{
				$_SESSION['country']=$reader->value;
			}
		}
	}
	$reader->close();
	
	if($loggedin == true)
	{
		$_SESSION['bbusername'] = $_POST['username'];
		$_SESSION['bbaccesskey'] = $_POST['accesskey'];
		$_SESSION['loggedin'] = true;
		
		//////// HERE WE TEST FOR BBs GMs LAs and MODs to give them special colours :D (and maybe privs)
		
		$first3 = substr($_SESSION['owner'],0,3);
		$first4 = substr($_SESSION['owner'],0,4);
		
		if (($first3 == "BB-")||($_SESSION['id']==38596)) $_SESSION['status'] = 90;
		else if ($first3 == "GM-") $_SESSION['status'] = 70;
		else if ($first4 == "MOD-") $_SESSION['status'] = 50;
		else if ($first3 == "LA-") $_SESSION['status'] = 30;
		else $_SESSION['status'] = 10; // leave with basic user access - not 0 which is guest


		// Update login tracking
		$update = 0;
		$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
                $mysqli->set_charset("utf8");
		$query = "SELECT id FROM user WHERE id='".$_SESSION['id']."'";
		$items = $mysqli->query($query) or die("User Query Failed!");
		while ($return = mysqli_fetch_array($items))
		{
			if ($return['id']==$_SESSION['id']) $update=1;
		}
		if ($update==1)
		{
			$query = "UPDATE user SET timestamp='".date("U")."', user='".$_SESSION['owner']."' WHERE id='".$_SESSION['id']."'";
			$pass = $mysqli->query($query) or die("Last Login Update Failed!");
		}
		else
		{
			$query = "INSERT INTO user VALUES ('".$_SESSION['id']."' , '".$_SESSION['owner']."', '".date("U")."','','')";
			$pass = $mysqli->query($query) or die("Problems with Login have occured... $query");
		}
		
		// Show logged in page with return page link
		include("header.php");
		?>
		<div id="xsnazzy">
		<b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
		<div class="xboxcontent">
		<p>&nbsp;</p>
		
		<center>
		You have successfully Logged In <?php echo $_SESSION['owner']; ?>.<br><a href="<?php if($_SESSION['returnPage']) echo $_SESSION['returnPage']; else echo "index.php" ?>">Return</a>
		</center>
		
		<p>&nbsp;</p>
		</div>
		<b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
		</div>
		<?php
                $mysqli->close();
		include("footer.php");
	}
	else
	{
		$error = "ERROR: <i>Login information you provided is incorrect.</i><br>";
		bbloginhtml();
	}
}
else
{
	bbloginhtml();
}
?>
