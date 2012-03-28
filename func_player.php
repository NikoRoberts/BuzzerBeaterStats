<?


function selectTrainees($rating,$age,$height,$potential)
{
	$trainability = 1;
	
	$position = substr($rating,0,2);
	$value = substr($rating,3);
	
	//BASIC TRAINABILITY
	$trainability = ($value/$age)*$potential;
	$trainability = $trainability*((20-$age/2));
	//HEIGHT
	$height_bonus = 500;
	if (($position == "ct") && ($height > 84)) $trainability = $trainability+$height_bonus;
	if (($position == "pf") && ($height > 82)) $trainability = $trainability+$height_bonus;
	if (($position == "sf") && ($height < 81) && ($height > 79)) $trainability = $trainability+$height_bonus;
	if (($position == "sg") && ($height < 75)) $trainability = $trainability+$height_bonus;
	if (($position == "pg") && ($height < 73)) $trainability = $trainability+$height_bonus;

	//YOUNG PLAYERS
	if($age<=25) $trainability = $trainability+$age*$potential*1.1;
	if($age<=23) $trainability = $trainability+$age*$potential*1.3;
	if($age<=21) $trainability = $trainability+$age*$potential*1.6;
	if($age<=19) $trainability = $trainability+$age*$potential*1.9;
	if($age==18) $trainability = $trainability+$age*$potential*2.5;
	//ROUND TO INTEGER
	$trainability = round($trainability,0);
	
	if ($trainability>13000) return "Value of Training: Extremely High";
	else if ($trainability>11000) return "Value of Training: Very High";
	else if ($trainability>10000) return "Value of Training: High";
	else if ($trainability>8500) return "Value of Training: Good";
	else if ($trainability>5000) return "Value of Training: Average";
	else if ($trainability>3000) return "Value of Training: Low";
	else if ($trainability>2000) return "Value of Training: Very Low";
	else return "Value of Training: Almost None";
}
	
function analyseTeam()
{
	$currPlayer['gameShape'];
}

function bestPosition($jumpshot,$range,$outsidedef,$handling,$driving,$passing,$insideshot,$insidedef,$rebound,$block,$stamina,$freethrow,$experience,$numtest)
{
	$pgweight = array(); //42
	$pgweight['jumpshot'] = 60;
	$pgweight['range'] = 70;
	$pgweight['outsidedef'] = 70;
	$pgweight['handling'] = 40;
	$pgweight['driving'] = 40;
	$pgweight['passing'] = 100;
	$pgweight['insideshot'] = 30;
	$pgweight['insidedef'] = 20;
	$pgweight['rebound'] = 10;
	$pgweight['block'] = 10;
	$pgweight['stamina'] = 0;
	$pgweight['freethrow'] = 0;
	$pgweight['experience'] = 0;
$sgweight = array(); //42
	$sgweight['jumpshot'] = 100;
	$sgweight['range'] = 40;
	$sgweight['outsidedef'] = 70;
	$sgweight['handling'] = 40;
	$sgweight['driving'] = 40;
	$sgweight['passing'] = 50;
	$sgweight['insideshot'] = 40;
	$sgweight['insidedef'] = 40;
	$sgweight['rebound'] = 10;
	$sgweight['block'] = 10;
	$sgweight['stamina'] = 0;
	$sgweight['freethrow'] = 0;
	$sgweight['experience'] = 0;
$sfweight = array();
	$sfweight['jumpshot'] = 60;
	$sfweight['range'] = 30;
	$sfweight['outsidedef'] = 60;
	$sfweight['handling'] = 30;
	$sfweight['driving'] = 30;
	$sfweight['passing'] = 40;
	$sfweight['insideshot'] = 80;
	$sfweight['insidedef'] = 70;
	$sfweight['rebound'] = 60;
	$sfweight['block'] = 40;
	$sfweight['stamina'] = 0;
	$sfweight['freethrow'] = 0;
	$sfweight['experience'] = 0;
$pfweight = array(); //42
	$pfweight['jumpshot'] = 20;
	$pfweight['range'] = 20;
	$pfweight['outsidedef'] = 20;
	$pfweight['handling'] = 30;
	$pfweight['driving'] = 30;
	$pfweight['passing'] = 30;
	$pfweight['insideshot'] = 70;
	$pfweight['insidedef'] = 100;
	$pfweight['rebound'] = 100;
	$pfweight['block'] = 70;
	$pfweight['stamina'] = 0;
	$pfweight['freethrow'] = 0;
	$pfweight['experience'] = 0;
$ctweight = array(); //42
	$ctweight['jumpshot'] = 10;
	$ctweight['range'] = 10;
	$ctweight['outsidedef'] = 10;
	$ctweight['handling'] = 30;
	$ctweight['driving'] = 30;
	$ctweight['passing'] = 30;
	$ctweight['insideshot'] = 70;
	$ctweight['insidedef'] = 100;
	$ctweight['rebound'] = 100;
	$ctweight['block'] = 90;
	$ctweight['stamina'] = 0;
	$ctweight['freethrow'] = 0;
	$ctweight['experience'] = 0;
	
	
	$pointguard =
	$jumpshot * $pgweight['jumpshot'] +
	$range * $pgweight['range'] +
	$outsidedef * $pgweight['outsidedef'] +
	$handling * $pgweight['handling'] +
	$driving * $pgweight['driving'] +
	$passing * $pgweight['passing'] +
	$insideshot * $pgweight['insideshot'] +
	$insidedef * $pgweight['insidedef'] +
	$rebound * $pgweight['rebound'] +
	$block * $pgweight['block'] +
	$stamina * $pgweight['stamina'] +
	$freethrow * $pgweight['freethrow'] +
	$experience * $pgweight['experience'];
	
	$shootingguard =
	$jumpshot * $sgweight['jumpshot'] +
	$range * $sgweight['range'] +
	$outsidedef * $sgweight['outsidedef'] +
	$handling * $sgweight['handling'] +
	$driving * $sgweight['driving'] +
	$passing * $sgweight['passing'] +
	$insideshot * $sgweight['insideshot'] +
	$insidedef * $sgweight['insidedef'] +
	$rebound * $sgweight['rebound'] +
	$block * $sgweight['block'] +
	$stamina * $sgweight['stamina'] +
	$freethrow * $sgweight['freethrow'] +
	$experience * $sgweight['experience'];
	
	$smallforward =
	$jumpshot * $sfweight['jumpshot'] +
	$range * $sfweight['range'] +
	$outsidedef * $sfweight['outsidedef'] +
	$handling * $sfweight['handling'] +
	$driving * $sfweight['driving'] +
	$passing * $sfweight['passing'] +
	$insideshot * $sfweight['insideshot'] +
	$insidedef * $sfweight['insidedef'] +
	$rebound * $sfweight['rebound'] +
	$block * $sfweight['block'] +
	$stamina * $sfweight['stamina'] +
	$freethrow * $sfweight['freethrow'] +
	$experience * $sfweight['experience'];
	
	$powerforward =
	$jumpshot * $pfweight['jumpshot'] +
	$range * $pfweight['range'] +
	$outsidedef * $pfweight['outsidedef'] +
	$handling * $pfweight['handling'] +
	$driving * $pfweight['driving'] +
	$passing * $pfweight['passing'] +
	$insideshot * $pfweight['insideshot'] +
	$insidedef * $pfweight['insidedef'] +
	$rebound * $pfweight['rebound'] +
	$block * $pfweight['block'] +
	$stamina * $pfweight['stamina'] +
	$freethrow * $pfweight['freethrow'] +
	$experience * $pfweight['experience'];
	
	$centre =
	$jumpshot * $ctweight['jumpshot'] +
	$range * $ctweight['range'] +
	$outsidedef * $ctweight['outsidedef'] +
	$handling * $ctweight['handling'] +
	$driving * $ctweight['driving'] +
	$passing * $ctweight['passing'] +
	$insideshot * $ctweight['insideshot'] +
	$insidedef * $ctweight['insidedef'] +
	$rebound * $ctweight['rebound'] +
	$block * $ctweight['block'] +
	$stamina * $ctweight['stamina'] +
	$freethrow * $ctweight['freethrow'] +
	$experience * $ctweight['experience'];
	
	
	// NEW WE FIGURE OUT WHICH POSITION IS THE BEST
	$sortedlist = array();
	$sortedlist[0] = $pointguard;
	$sortedlist[1] = $shootingguard;
	$sortedlist[2] = $smallforward;
	$sortedlist[3] = $powerforward;
	$sortedlist[4] = $centre;
	
	
	natsort($sortedlist);
	$sortedlist = array_reverse($sortedlist);
	
	if ($numtest == 0)
	{
		//Primary position
		if ($sortedlist[0] == $pointguard) $return = "BBStats Best Position: Point Guard";
		else if ($sortedlist[0] == $shootingguard) $return = "BBStats Best Position: Shooting Guard";
		else if ($sortedlist[0] == $smallforward) $return = "BBStats Best Position: Small Forward";
		else if ($sortedlist[0] == $powerforward) $return = "BBStats Best Position: Power Forward";
		else if ($sortedlist[0] == $centre) $return = "BBStats Best Position: Center";
		//Secondary position
		if ($sortedlist[1] == $pointguard) $return .= " (PG)";
		else if ($sortedlist[1] == $shootingguard) $return .= " (SG)";
		else if ($sortedlist[1] == $smallforward) $return .= " (SF)";
		else if ($sortedlist[1] == $powerforward) $return .= " (PF)";
		else if ($sortedlist[1] == $centre) $return .= " (C)";
		return $return;
	}
	else
	{
		if ($sortedlist[0] == $pointguard) return "pg.".$pointguard;
		else if ($sortedlist[0] == $shootingguard) return "sg.".$shootingguard;
		else if ($sortedlist[0] == $smallforward) return "sf.".$smallforward;
		else if ($sortedlist[0] == $powerforward) return "pf.".$powerforward;
		else if ($sortedlist[0] == $centre) return "ct.".$centre;
	}
}


?>