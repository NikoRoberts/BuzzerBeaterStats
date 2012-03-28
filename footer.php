<!--
<?php
if($_SERVER['PHP_SELF']!="/index.php")
{
?>
    <div style="text-align:center">
    <script type="text/javascript">
    google_ad_client = "pub-3256023941811311";
    google_ad_width = 468;
    google_ad_height = 60;
    google_ad_format = "468x60_as";
    google_ad_type = "text";
    //2007-10-23: buzzerbeater
    google_ad_channel = "5167022614";
    google_color_border = "1B703A";
    google_color_bg = "003366";
    google_color_link = "FFFFFF";
    google_color_text = "78B749";
    google_color_url = "80FF00";
    google_ui_features = "rc:10";
   
    </script>
    <script type="text/javascript"
      src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
<?
}
?>


<div id="googleside">
<? if($_SESSION['theme']=="classic") { ?>
<script type="text/javascript">
google_ad_client = "pub-3256023941811311";
// 120x600, created 31/03/10
google_ad_slot = "7354897139";
google_ad_width = 120;
google_ad_height = 600;

</script>
<? }
else { ?>
<script type="text/javascript">
google_ad_client = "pub-3256023941811311";
/* 120x600, created 14/05/10 */
google_ad_slot = "3466510694";
google_ad_width = 120;
google_ad_height = 600;

</script>
<? } ?>
-->
<script type="text/javascript">
/*src="http://pagead2.googlesyndication.com/pagead/show_ads.js">*/
</script>
</div>


	<script type="text/javascript">

          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-8881866-1']);
          _gaq.push(['_setDomainName', '.buzzerbeaterstats.com']);
          _gaq.push(['_trackPageview']);
          _gaq.push(['_trackPageLoadTime']);


          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

        </script>
    <script type="text/javascript" src="/min/?f=js/jquery.min.js,js/jquery-ui-1.8.custom.min.js,js/jquery.tablesorter.js,js/slide.js,js/languageSelect.js,js/languageGoogle.js,js/animatedcollapse.js,js/jquery.corner.js,js/flot/jquery.flot.js,js/pageLoad.js&432500"></script>
   
    <script type="text/javascript">
       $("#supportClose").click(function(event) {
           event.preventDefault();
           $("#supportdialog").remove();
       });
           
    </script>
    
</div>
</div>
</body>
</html>