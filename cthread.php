<?

/**
 * @author codestips
 * @copyright 2009 codestips.com
 * @authorurl http://twitter.com/codestips
 * @articleurl http://codestips.com/php-multithreading-using-curl/
 
 */
 
 
/*
Copyright (C) 2009 codestips.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.


You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

This file is to demonstrate some PHP functionality. Use it at your own risks.
*/

//add a url to the handler
function addHandle(&$curlHandle,$url)
{
	$cURL = curl_init();
	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_HEADER, 0);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_multi_add_handle($curlHandle,$cURL);
	return $cURL;
}
//execute the handle until the flag passed to function is greater then 0
function ExecHandle(&$curlHandle)
{
	$flag=null;
	do
	{
		curl_multi_exec($curlHandle,$flag);//fetch pages in parallel
	} while ($flag > 0);
}


$list[1] = "http://www.example1.com";
$list[2] = "ftp://example.com";
$list[3] = "http://www.example2.com";
$curlHandle = curl_multi_init();

for ($i = 1;$i <= 3; $i++) $curl[$i] = addHandle($curlHandle,$list[$i]);

ExecHandle($curlHandle);
for ($i = 1;$i <= 3; $i++)
{
	$text[$i] = curl_multi_getcontent ($curl[$i]);
	echo $text[$i];
}

//remove the handles
for ($i = 1;$i <= 3; $i++) curl_multi_remove_handle($curlHandle,$curl[$i]);

curl_multi_close($curlHandle);

?>