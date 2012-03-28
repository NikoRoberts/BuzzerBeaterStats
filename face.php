<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Face</title>
<style>
img {
	border: none;
}
/* Face left +82 and top +53*/
.faceCont {
	position: absolute;
	left: 10px;
	top: -5px;
	z-index: 1;
}
#eyes {
	position:absolute;
	left:21px;
	top:56px;
	z-index: 51;
}

#eyebrow {
	position:absolute;
	left:21px;
	top:50px;
	z-index: 51;
}

#mouth {
	position:absolute;
	left:24px;
	top:86px;
	z-index: 51;
}
#nose {
	position:absolute;
	left:35px;
	top:71px;
	z-index: 51;
}

#hair {
	position:absolute;
	left:7px;
	top:7px;
	z-index: 51;
}

#face {
	position:absolute;
	top:7px;
	left:6px;
	z-index: 50;
}
#controller
{
	position:absolute;
	top: 120px;
	left: 10px;
	width: 170px;
}
</style>
<script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAFYWzQeZg8qF1mal9vfnU5RSA3vwVOD-lPkO9LRJHVIacWbacwBRdjw4qdt8dDu0Cq6Dc40BfiXSK1A"></script> 
<script type="text/javascript">        
	google.load("jquery", "1.4.2");
	google.load("jqueryui", "1.8.1");
</script> 
</head>
<body>
<?php

$_SESSION['buzzImages']="www.buzzerbeater.com";

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
?>
<div class="faceCont">
	<img id="face" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/faces/<? echo $_SESSION['face_colour']; ?>/<? echo $_SESSION['face_shape']; ?>.png" width="77px" height="112px" style="border-width:0px;" alt="Face">
	<img id="eyes" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/eyes/<? echo $_SESSION['face_eyes']; ?>.png" width="47px" height="18px" style="border-width:0px;" alt="Eyes">
	<img id="eyebrow" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/eyebrow/<? echo $_SESSION['face_colour']; ?>/<? echo $_SESSION['face_eyebrow']; ?>.png" width="46px" height="9px" style="border-width:0px;" alt="Eyebrows">
	<img id="mouth" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/mouth/<? echo $_SESSION['face_mouth']; ?>.png" width="41px" height="11px" style="border-width:0px;" alt="Mouth">
	<img id="nose" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/nose/<? echo $_SESSION['face_nose']; ?>.png" width="19px" height="15px" style="border-width:0px;" alt="Nose">
	<img id="hair" src="http://<? echo $_SESSION['buzzImages']; ?>/images/faces/hair/<? echo $_SESSION['face_colour']; ?>/<? echo $_SESSION['face_hair']; ?>.png" width="76px" style="border-width:0px;" alt="Hair">
    <div id="controller">
    	<a href="javascript:skinPrev();"><img src="/images/face_arrow_left.png" width="20" height="14"></a> Skin <a href="javascript:skinNext()"><img src="/images/face_arrow_right.png" width="20" height="14"></a><br>
        <a href="javascript:headPrev()"><img src="/images/face_arrow_left.png" width="20" height="14"></a> Head <a href="javascript:headNext()"><img src="/images/face_arrow_right.png" width="20" height="14"></a><br>
        <a href="javascript:eyesPrev()"><img src="/images/face_arrow_left.png" width="20" height="14"></a> Eyes <a href="javascript:eyesNext()"><img src="/images/face_arrow_right.png" width="20" height="14"></a><br>
        <a href="javascript:eyebrowsPrev()"><img src="/images/face_arrow_left.png" width="20" height="14"></a> Eyebrow Style <a href="javascript:eyebrowsNext()"><img src="/images/face_arrow_right.png" width="20" height="14"></a><br>
        <a href="javascript:eyebrowColourPrev()"><img src="/images/face_arrow_left.png" width="20" height="14"></a> Eyebrow Colour <a href="javascript:eyebrowColourNext()"><img src="/images/face_arrow_right.png" width="20" height="14"></a><br>
        <a href="javascript:mouthPrev()"><img src="/images/face_arrow_left.png" width="20" height="14"></a> Mouth <a href="javascript:mouthNext()"><img src="/images/face_arrow_right.png" width="20" height="14"></a><br>
        <a href="javascript:nosePrev()"><img src="/images/face_arrow_left.png" width="20" height="14"></a> Nose <a href="javascript:noseNext()"><img src="/images/face_arrow_right.png" width="20" height="14"></a><br>
        <a href="javascript:hairPrev()"><img src="/images/face_arrow_left.png" width="20" height="14"></a> Hair Style <a href="javascript:hairNext()"><img src="/images/face_arrow_right.png" width="20" height="14"></a><br>
        <a href="javascript:hairColourPrev()"><img src="/images/face_arrow_left.png" width="20" height="14"></a> Hair Colour <a href="javascript:hairColourNext()"><img src="/images/face_arrow_right.png" width="20" height="14"></a>
    </div>
    <script type="text/javascript">
		var skin = <? echo $_SESSION['face_colour']; ?>;
		var head = <? echo $_SESSION['face_shape']; ?>;
		var eyes = <? echo $_SESSION['face_eyes']; ?>;
		var eyebrows = <? echo $_SESSION['face_eyebrow']; ?>;
		var eyebrowColour = <? echo $_SESSION['face_colour']; ?>;
		var mouth = <? echo $_SESSION['face_mouth']; ?>;
		var nose = <? echo $_SESSION['face_nose']; ?>;
		var hair = <? echo $_SESSION['face_hair']; ?>;
		var hairColour = <? echo $_SESSION['face_colour']; ?>;
		
		var maxskin = 4;
		var maxhead = 36;
		var maxeyes = 70;
		var maxeyebrows = 19;
		var maxeyebrowColour = 4;
		var maxmouth = 27;
		var maxnose = 24;
		var maxhair = 24;
		var maxhairColour = 4;
		
		function skinPrev()
		{
			if(skin!=1) skin--;
			else skin = maxskin;
			updateFace();
		}
		function skinNext()
		{
			if(skin!=maxskin) skin++;
			else skin = 1;
			updateFace();
		}
		function headPrev()
		{
			if(head!=1) head--;
			else head = maxhead;
			updateFace();
		}
		function headNext()
		{
			if(head!=maxhead) head++;
			else head = 1;
			updateFace();
		}
		function eyesPrev()
		{
			if(eyes!=1) eyes--;
			else eyes = maxeyes;
			updateFace();
		}
		function eyesNext()
		{
			if(eyes!=maxeyes) eyes++;
			else eyes = 1;
			updateFace();
		}
		function eyebrowsPrev()
		{
			if(eyebrows!=1) eyebrows--;
			else eyebrows = maxeyebrows;
			updateFace();
		}
		function eyebrowsNext()
		{
			if(eyebrows!=maxeyebrows) eyebrows++;
			else eyebrows = 1;
			updateFace();
		}
		function eyebrowColourPrev()
		{
			if(eyebrowColour!=1) eyebrowColour--;
			else eyebrowColour = maxeyebrowColour;
			updateFace();
		}
		function eyebrowColourNext()
		{
			if(eyebrowColour!=maxeyebrowColour) eyebrowColour++;
			else eyebrowColour = 1;
			updateFace();
		}
		function mouthPrev()
		{
			if(mouth!=1) mouth--;
			else mouth = maxmouth;
			updateFace();
		}
		function mouthNext()
		{
			if(mouth!=maxmouth) mouth++;
			else mouth = 1;
			updateFace();
		}
		function nosePrev()
		{
			if(nose!=1) nose--;
			else nose = maxnose;
			updateFace();
		}
		function noseNext()
		{
			if(nose!=maxnose) nose++;
			else nose = 1;
			updateFace();
		}
		function hairPrev()
		{
			if(hair!=1) hair--;
			else hair = maxhair;
			updateFace();
		}
		function hairNext()
		{
			if(hair!=maxhair) hair++;
			else hair = 1;
			updateFace();
		}
		function hairColourPrev()
		{
			if(hairColour!=1) hairColour--;
			else hairColour = maxhairColour;
			updateFace();
		}
		function hairColourNext()
		{
			if(hairColour!=maxhairColour) hairColour++;
			else hairColour = 1;
			updateFace();
		}
		
		function updateFace()
		{
			$('#face').attr('src','http://<? echo $_SESSION['buzzImages']; ?>/images/faces/faces/'+skin+'/'+head+'.png');
			$('#eyes').attr('src','http://<? echo $_SESSION['buzzImages']; ?>/images/faces/eyes/'+eyes+'.png');
			$('#eyebrow').attr('src','http://<? echo $_SESSION['buzzImages']; ?>/images/faces/eyebrow/'+eyebrowColour+'/'+eyebrows+'.png');
			$('#mouth').attr('src','http://<? echo $_SESSION['buzzImages']; ?>/images/faces/mouth/'+mouth+'.png');
			$('#nose').attr('src','http://<? echo $_SESSION['buzzImages']; ?>/images/faces/nose/'+nose+'.png');
			$('#hair').attr('src','http://<? echo $_SESSION['buzzImages']; ?>/images/faces/hair/'+hairColour+'/'+hair+'.png');
		}
	</script>
</div>

</body>
</html>
