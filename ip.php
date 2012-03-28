<html>
<head>
<?php
if ($_GET['ip']) $ip = $_GET['ip'];
else $ip = $_SERVER['REMOTE_ADDR'];
?>
<script type="text/javascript" src="js/mootools-1.2.4.js"></script>
<script type="text/javascript">
	window.onload = function()
	{
		$$('[class=checkip]').addEvent('click',function()
		{
			//get the IP address from the ID property of the link
			var el = $(this);
			var ipaddress = el.get('id');
			
			//swap the link for the loading icon
			$('response'+ipaddress).set('html','<img src="images/ajax-loader.gif">');
			var jsonRequest = new Request.JSON(
			{
				url: "http://test.bb-stats.org/proxy.php"
				,onSuccess: function(data)
				{
					//replace the response HTML with whatever the response is
					if(data.port>0)
					{
						$('response'+ipaddress).set('html','The port '+data.port+' was found open');
					}
					else
					{
						$('response'+ipaddress).set('html','No suspicious ports were found open');
					}
				}
			}).get({'ip': ipaddress });
		});
	}
</script>
</head>
<body>
	<div id="response<? echo $ip; ?>"><a class="checkip" id="<? echo $ip; ?>" href="#">Check IP <? echo $ip; ?></a></div>
    Examples:<br>
    <div id="response208.101.61.52"><a class="checkip" id="208.101.61.52" href="#">Check IP 208.101.61.52</a></div>
    
</body>
</html>
