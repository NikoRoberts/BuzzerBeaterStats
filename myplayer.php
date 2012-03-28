<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Custom BB Player</title>
<style>

/* LEVEL STYLES from BB */
.lev1{
	font-weight:bold;
	color:#000000 !important;
}
.lev2{
	font-weight:bold;
	color:#121263 !important;
}
.lev3{
	font-weight:bold;
	color:#221385 !important;
}
.lev4{
	font-weight:bold;
	color:#30139F !important;
}
.lev5{
	font-weight:bold;
	color:#700BA2 !important;
}
.lev6{
	font-weight:bold;
	color:#910B9D !important;
}
.lev7{
	font-weight:bold;
	color:#AD0B88 !important;
}
.lev8{
	font-weight:bold;
	color:#B70B5A !important;
}
.lev9{
	font-weight:bold;
	color:#9C0B32 !important;
}
.lev10{
	font-weight:bold;
	color:#A70B00!important;
}
.lev11{
	font-weight:bold;
	color:#BD2600 !important;
}
.lev12{
	font-weight:bold;
	color:#CB3100 !important;
}
.lev13{
	font-weight:bold;
	color:#D93C00 !important;
}
.lev14{
	font-weight:bold;
	color:#DB6E04 !important;
}
.lev15{
	font-weight:bold;
	color:#E5A64B !important;
}
.lev16{
	font-weight:bold;
	color:#AC860A !important;
}
.lev17{
	font-weight:bold;
	color:#8E9800 !important;
}
.lev18{
	font-weight:bold;
	color:#498E00 !important;
}
.lev19{
	font-weight:bold;
	color:#0EAE28 !important;
}
.lev20{
	font-weight:bold;
	color:#0EB366 !important;
}

.click_inc:hover, .click_dec:hover {
	cursor:pointer;
}

.inlined {
	display:inline;
	padding: 10px;
}

</style>

<?php
if(!$_GET[id])
{
?>
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
<div id="customPlayer">
<div id="pointsused">Points Used: 70 / 125</div>

<span class="inlined">First Name: <span contentEditable="true">Steve</span></span>
<span class="inlined">Last Name: <span contentEditable="true">Jobs</span></span>
<span class="inlined">Best Position: <select name="bestpos">
<option value="pg">Point Guard</option>
<option value="sg">Shooting Guard</option>
<option value="sf">Small Forward</option>
<option value="pf">Power Forward</option>
<option value="c">Center</option>
</select>
</span>
<table cellpadding="0" cellspacing="0">
			<tbody>
			<tr>
			<td>
                <table id="rostable" style="width:250px">
                <tbody>
                <tr>
                <td>
                <ul style="list-style:none">
                <li>Owner: <span contentEditable="true">Team Name</span></li>
                <li>Weekly salary: <span contentEditable="true">$10 000</span></li>
                <li>DMI: <span contentEditable="true">10000</span></li>
                <li>Age: <span contentEditable="true">18</span></li>
                <li>Height: <span contentEditable="true">183cm/7'2"</span></li>
                <li>Potential: <img id="inc_po" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_po" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="po" class="lev11">allstar</span></li>
                <li>Game Shape: <img id="inc_gs" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_gs" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="gs" class="skill lev7">respectable</span></li>
                </ul>
                </td>
                </tr>
                </tbody>
                </table>
            </td>
			<td>
                <table id="rostable">
                <tbody>
                <tr>
                <td style="width: 200px;"> Jump Shot: <img id="inc_js" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_js" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="js" class="skill lev7">respectable</span> </td>
                <td> Jump Range: <img id="inc_jr" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_jr" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="jr" class="skill lev7">respectable</span> </td>
                </tr>
                <tr>
                <td> Outside Def.: <img id="inc_od" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_od" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="od" class="skill lev7">respectable</span> </td>
                <td> Handling: <img id="inc_h" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_h" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="h" class="skill lev7">respectable</span> </td>
                </tr>
                <tr>
                <td> Driving: <img id="inc_d" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_d" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="d" class="skill lev7">respectable</span> </td>
                <td> Passing: <img id="inc_p" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_p" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="p" class="skill lev7">respectable</span> </td>
                </tr>
                <tr>
                <td> Inside Shot: <img id="inc_is" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_is" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="is" class="skill lev7">respectable</span> </td>
                <td> Inside Def.: <img id="inc_id" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_id" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="id" class="skill lev7">respectable</span> </td>
                </tr>
                <tr>
                <td> Rebounding: <img id="inc_r" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_r" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="r" class="skill lev7">respectable</span> </td>
                <td> Shot Blocking: <img id="inc_sb" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_sb" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="sb" class="skill lev7">respectable</span> </td>
                </tr>
                <tr>
                <td> Stamina: <img id="inc_s" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_s" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="s" class="skill lev7">respectable</span> </td>
                <td> Free Throw: <img id="inc_ft" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_ft" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="ft" class="skill lev7">respectable</span> </td>
                </tr>
                
                <tr>
                <td>&nbsp;  </td>
                <td></td>
                </tr>
                <tr>
                <td> Experience: <img id="inc_e" class="click_inc" src="images/arrow_skill_inc.png" width="10" height="15"><img id="dec_e" class="click_dec" src="images/arrow_skill_dec.png" width="10" height="14"> <span id="e" class="skill lev7">respectable</span> </td>
                <td></td>
                </tr>
                </tbody>
                </table>
             </td>
             </tr>
             </tbody>
             </table>
             
             
    <script type="text/javascript">
		var lev = Array();
		lev[1] = "atrocious";
		lev[2] = "pitiful";
		lev[3] = "awful";
		lev[4] = "inept";
		lev[5] = "mediocre";
		lev[6] = "average";
		lev[7] = "respectable";
		lev[8] = "strong";
		lev[9] = "proficent";
		lev[10] = "prominent";
		lev[11] = "prolific";
		lev[12] = "sensational";
		lev[13] = "tremendous";
		lev[14] = "wondrous";
		lev[15] = "marvelous";
		lev[16] = "prodigious";
		lev[17] = "stupendous";
		lev[18] = "phenomenal";
		lev[19] = "colossal";
		lev[20] = "legendary";
		
		var pot = Array();
		pot[0] = "announcer";
		pot[1] = "bench warmer";
		pot[2] = "role player";
		pot[3] = "6th man";
		pot[4] = "starter";
		pot[5] = "star";
		pot[6] = "allstar";
		pot[7] = "perennial allstar";
		pot[8] = "superstar";
		pot[9] = "MVP";
		pot[10] = "hall of famer";
		pot[11] = "all-time great";
		
		var maxpoints = 125;
		var maxskill = 20;
		
		var maxpotential = 11;
		var maxgameshape = 9;
		var maxexperience = 13;
		var maxfreethrow = 20;
		var maxstamina = 10;
		
		var points = Array();
		points['js']=7;
		points['jr']=7;
		points['od']=7;
		points['h']=7;
		points['d']=7;
		points['p']=7;
		points['is']=7;
		points['id']=7;
		points['r']=7;
		points['sb']=7;
		
		points['s']=7;
		points['ft']=7;
		points['e']=7;
		points['po']=6;
		points['gs']=7;
		
		currpoints = 70;
		
		$('.click_inc').click(function()
		{
			if(currpoints!=maxpoints)
			{
				var temp = Array();
				temp = $(this).attr('id').split("_");
				
				var thismax = 0;
				if((temp[1]!='po')&&(temp[1]!='gs')&&(temp[1]!='e')&&(temp[1]!='s')&&(temp[1]!='ft')) thismax = maxskill;
				else if (temp[1]=='po') thismax = maxpotential;
				else if (temp[1]=='gs') thismax = maxgameshape;
				else if (temp[1]=='e') thismax = maxexperience;
				else if (temp[1]=='s') thismax = maxstamina;
				else if (temp[1]=='ft') thismax = maxfreethrow;
				//increase skills
				var spanid = "#"+temp[1];
				var level = 0;
				
				var start = 1;
				if(temp[1]=='po') start = 0;
				for(var i=start;i<=thismax;i++)
				{
					//alert("is "+lev[i]+" equal to "+$(spanid).html());
					if(temp[1]!='po')
					{
						if(lev[i]==$(spanid).html())
						{
							if(i!=thismax)
							{
								level = i+1;
								if((temp[1]!='gs')&&(temp[1]!='e')&&(temp[1]!='s')&&(temp[1]!='ft')) currpoints++;
							}
							else level = thismax;
							break;
						}
					}
					else
					{
						if(pot[i]==$(spanid).html())
						{
							if(i!=thismax)
							{
								level = i+1;
								//currpoints++;
							}
							else level = thismax;
							break;
						}
					}
				}
				//now level is set to whatever level it was previously
				if(temp[1]!='po')
				{
					$(spanid).html(lev[level]);
					$(spanid).attr('class','skill lev'+level); //replace existing classes with the ones needed for the new skill level
				}
				else
				{
					$(spanid).html(pot[level]);
					if((level+5)<12) classNum = (level+5);
					else classNum = (level+6);
					$(spanid).attr('class','skill lev'+classNum); //replace existing classes with the ones needed for the new skill level
				}
				$('#pointsused').html('Points Used: '+currpoints+' / '+maxpoints);
			}
			else $('#pointsused').css('color','red');
		});
		
		$('.click_dec').click(function()
		{
			$('#pointsused').css('color','black');
			
			var temp = Array();
			temp = $(this).attr('id').split("_");
			
			var thismax = 0;
			if((temp[1]!='po')&&(temp[1]!='gs')&&(temp[1]!='e')&&(temp[1]!='s')&&(temp[1]!='ft')) thismax = maxskill;
			else if (temp[1]=='po') thismax = maxpotential;
			else if (temp[1]=='gs') thismax = maxgameshape;
			else if (temp[1]=='e') thismax = maxexperience;
			else if (temp[1]=='s') thismax = maxstamina;
			else if (temp[1]=='ft') thismax = maxfreethrow;
			//increase skills
			var spanid = "#"+temp[1];
			var level = 0;
			
			var start = 1;
			if(temp[1]=='po') start = 0;
			for(var i=start;i<=thismax;i++)
			{
				//alert("is "+lev[i]+" equal to "+$(spanid).html());
				if(temp[1]!='po')
				{
					if(lev[i]==$(spanid).html())
					{
						if(i!=1)
						{
							level = i-1;
							if((temp[1]!='gs')&&(temp[1]!='e')&&(temp[1]!='s')&&(temp[1]!='ft')) currpoints--;
						}
						else level = 1;
						break;
					}
				}
				else
				{
					if(pot[i]==$(spanid).html())
					{
						if(i!=0)
						{
							level = i-1;
							//currpoints--;
						}
						else level = 0;
						break;
					}
				}
			}
			//now level is set to whatever level it was previously
			if(temp[1]!='po')
			{
				$(spanid).html(lev[level]);
				$(spanid).attr('class','skill lev'+level); //replace existing classes with the ones needed for the new skill level
			}
			else
			{
				$(spanid).html(pot[level]);
				if((level+5)<12) classNum = (level+5);
				else classNum = (level+6);
				$(spanid).attr('class','skill lev'+classNum); //replace existing classes with the ones needed for the new skill level
			}
			$('#pointsused').html('Points Used: '+currpoints+' / '+maxpoints);
		});
		
	</script>
</div>
<? } ?>
</body>
</html>
