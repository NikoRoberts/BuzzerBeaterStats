<?
function ipProxyPortCheck($ip)
{
	//timeout you want to use to test
	$timeout = 0.5;
	// ports we're going to check
	$ports = array(3128,8080,80);
	// flag to be returned 0 means safe, 1 means open and unsafe
	$flag = 0;
	// loop through each of the ports we're checking
	foreach($ports as $port)
	{
		// this is the code that does the actual checking for the port
		@$fp = fsockopen($ip,$port,$errno,$errstr,$timeout);
		// test if something was returned, ie the port is open
		if(!empty($fp))
		{
			// we know the set the flag
			$flag = $port;
			// close our connection to the IP
			fclose($fp);
			// send our flag back to the calling code
			return $flag;
		}
	}
	return $flag; //return 0 if there isn't a match with the ports
}
// call our function and check the IP in there
$json = '{"port":'.ipProxyPortCheck($_REQUEST['ip']).'}';
echo $json;
?>