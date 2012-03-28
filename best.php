<?php
/*
	Array Values
		0 - counter
		1 - firstname
		2 - lastname
		3 - gameShape
		4 - potential
		5 - jumpShot
		6 - range
		7 - outsideDef
		8 - handling 
		9 - driving
		10 - passing 
		11 - insideShot
		12 - insideDef 
		13 - rebound
		14 - block
		15 - stamina
		16 - freeThrow 
		17 - experience
		18 - PG ranking
		19 - SG ranking
		20 - SF ranking
		21 - PF ranking
		22 - C ranking
*/
				 
/* Set weighting values by position per skill/category */
if($weights == "Trikster")
{
	$max_score = 105;
	
	$pg_gameshape_weight = 0;
	$sg_gameshape_weight = 0;
	$sf_gameshape_weight = 0;
	$pf_gameshape_weight = 0;
	$c_gameshape_weight = 0;
	
	$pg_potential_weight = 0;
	$sg_potential_weight = 0;
	$sf_potential_weight = 0;
	$pf_potential_weight = 0;
	$c_potential_weight = 0;
	
	$pg_jumpshot_weight = 0.077;
	$sg_jumpshot_weight = 0.231;
	$sf_jumpshot_weight = 0.154;
	$pf_jumpshot_weight = 0.05;
	$c_jumpshot_weight = 0;
	
	$pg_range_weight = 0.077;
	$sg_range_weight = 0.231;
	$sf_range_weight = 0.154;
	$pf_range_weight = 0;
	$c_range_weight = 0;
	
	$pg_outsideDef_weight = 0.154;
	$sg_outsideDef_weight = 0.077;
	$sf_outsideDef_weight = 0.154;
	$pf_outsideDef_weight = 0;
	$c_outsideDef_weight = 0;
	
	$pg_handling_weight = 0.231;
	$sg_handling_weight = 0.154;
	$sf_handling_weight = 0.115;
	$pf_handling_weight = 0.05;
	$c_handling_weight = 0.077;
	
	$pg_driving_weight = 0.154;
	$sg_driving_weight = 0.077;
	$sf_driving_weight = 0.115;
	$pf_driving_weight = 0.1;
	$c_driving_weight = 0;
	
	$pg_passing_weight = 0.231;
	$sg_passing_weight = 0.154;
	$sf_passing_weight = 0.115;
	$pf_passing_weight = 0.1;
	$c_passing_weight = 0.077;
	
	$pg_insideShot_weight = 0.077;
	$sg_insideShot_weight = 0.077;
	$sf_insideShot_weight = 0.077;
	$pf_insideShot_weight = 0.2;
	$c_insideShot_weight = 0.231;
	
	$pg_insideDef_weight = 0;
	$sg_insideDef_weight = 0;
	$sf_insideDef_weight = 0.077;
	$pf_insideDef_weight = 0.2;
	$c_insideDef_weight = 0.231;
	
	$pg_rebound_weight = 0;
	$sg_rebound_weight = 0;
	$sf_rebound_weight = 0.038;
	$pf_rebound_weight = 0.15;
	$c_rebound_weight = 0.231;
	
	$pg_block_weight = 0;
	$sg_block_weight = 0;
	$sf_block_weight = 0;
	$pf_block_weight = 0.15;
	$c_block_weight = 0.154;
	
	$pg_stamina_weight = 0;
	$sg_stamina_weight = 0;
	$sf_stamina_weight = 0;
	$pf_stamina_weight = 0;
	$c_stamina_weight = 0;
	
	$pg_freethrow_weight = 0;
	$sg_freethrow_weight = 0;
	$sf_freethrow_weight = 0;
	$pf_freethrow_weight = 0;
	$c_freethrow_weight = 0;
	
	$pg_experience_weight = 0;
	$sg_experience_weight = 0;
	$sf_experience_weight = 0;
	$pf_experience_weight = 0;
	$c_experience_weight = 0;
}
else if ($weights == "JudgeNik")
{
	$max_score = 455.3;

	$pg_gameshape_weight = 0;
	$sg_gameshape_weight = 0;
	$sf_gameshape_weight = 0;
	$pf_gameshape_weight = 0;
	$c_gameshape_weight = 0;
	
	$pg_potential_weight = 0;
	$sg_potential_weight = 0;
	$sf_potential_weight = 0;
	$pf_potential_weight = 0;
	$c_potential_weight = 0;
	
	$pg_jumpshot_weight = -0.5;
	$sg_jumpshot_weight = 0.2674;
	$sf_jumpshot_weight = 2.1303;
	$pf_jumpshot_weight = 0;
	$c_jumpshot_weight = -1.0;
	
	$pg_range_weight = 0;
	$sg_range_weight = 2.9143;
	$sf_range_weight = 0.7175;
	$pf_range_weight = -0.5;
	$c_range_weight = -0.5;
	
	$pg_outsideDef_weight = 0.881;
	$sg_outsideDef_weight = 2.3526;
	$sf_outsideDef_weight = 0.245;
	$pf_outsideDef_weight = -0.5;
	$c_outsideDef_weight = -0.5;
	
	$pg_handling_weight = 1.4299;
	$sg_handling_weight = 0;
	$sf_handling_weight = 0;
	$pf_handling_weight = 0;
	$c_handling_weight = 0;
	
	$pg_driving_weight = 0.604;
	$sg_driving_weight = 0;
	$sf_driving_weight = 0;
	$pf_driving_weight = 0;
	$c_driving_weight = 0;
	
	$pg_passing_weight = 3.9245;
	$sg_passing_weight = 0;
	$sf_passing_weight = 0;
	$pf_passing_weight = 0;
	$c_passing_weight = 0;
	
	$pg_insideShot_weight = -0.5;
	$sg_insideShot_weight = -0.5;
	$sf_insideShot_weight = -0.5;
	$pf_insideShot_weight = 1.4092;
	$c_insideShot_weight = 2.3544;
	
	$pg_insideDef_weight = -0.5;
	$sg_insideDef_weight = -0.5;
	$sf_insideDef_weight = 0.098;
	$pf_insideDef_weight = 1.3047;
	$c_insideDef_weight = 2.3262;
	
	$pg_rebound_weight = -0.5;
	$sg_rebound_weight = -0.5;
	$sf_rebound_weight = 0;
	$pf_rebound_weight = 0.3621;
	$c_rebound_weight = 1.3516;
	
	$pg_block_weight = 0;
	$sg_block_weight = 0;
	$sf_block_weight = 0;
	$pf_block_weight = 0.787;
	$c_block_weight = 1.2189;
	
	$pg_stamina_weight = 0.1194;
	$sg_stamina_weight = 0.1291;
	$sf_stamina_weight = 0.1411;
	$pf_stamina_weight = 0.1649;
	$c_stamina_weight = 0.1826;
	
	$pg_freethrow_weight = 0;
	$sg_freethrow_weight = 0;
	$sf_freethrow_weight = 0;
	$pf_freethrow_weight = 0;
	$c_freethrow_weight = 0;
	
	$pg_experience_weight = 0;
	$sg_experience_weight = 0;
	$sf_experience_weight = 0;
	$pf_experience_weight = 0;
	$c_experience_weight = 0;
}
else if ($weights == "JudgeNik v2")
{
	$max_score = 3601.4;
	
	$pg_gameshape_weight = 0.8594;
	$sg_gameshape_weight = 0.9235;
	$sf_gameshape_weight = 0.9105;
	$pf_gameshape_weight = 0.9363;
	$c_gameshape_weight = 0.9294;
	
	$pg_potential_weight = 0.4979;
	$sg_potential_weight = 0.4952;
	$sf_potential_weight = 0.488;
	$pf_potential_weight = 0.5255;
	$c_potential_weight = 0.5382;
	
	$pg_jumpshot_weight = 0.0839;
	$sg_jumpshot_weight = 1.3066;
	$sf_jumpshot_weight = 8.4175;
	$pf_jumpshot_weight = 0.6831;
	$c_jumpshot_weight = 0.0057;
	
	$pg_range_weight = 0.9409;
	$sg_range_weight = 18.4367;
	$sf_range_weight = 2.0493;
	$pf_range_weight = 0.2722;
	$c_range_weight = 0.2972;
	
	$pg_outsideDef_weight = 2.4133;
	$sg_outsideDef_weight = 10.5128;
	$sf_outsideDef_weight = 1.2776;
	$pf_outsideDef_weight = 0.249;
	$c_outsideDef_weight = 0.2383;
	
	$pg_handling_weight = 4.1782;
	$sg_handling_weight = 0.4449;
	$sf_handling_weight = 0.4352;
	$pf_handling_weight = 0.4202;
	$c_handling_weight = 0.4239;
	
	$pg_driving_weight = 1.8294;
	$sg_driving_weight = 0.6036;
	$sf_driving_weight = 0.5982;
	$pf_driving_weight = 0.6051;
	$c_driving_weight = 0.574;
	
	$pg_passing_weight = 50.6303;
	$sg_passing_weight = 0.6366;
	$sf_passing_weight = 0.6233;
	$pf_passing_weight = 0.5728;
	$c_passing_weight = 0.5661;
	
	$pg_insideShot_weight = 0.2114;
	$sg_insideShot_weight = 0.2188;
	$sf_insideShot_weight = 0.2204;
	$pf_insideShot_weight = 4.0926;
	$c_insideShot_weight = 10.5321;
	
	$pg_insideDef_weight = 0.159;
	$sg_insideDef_weight = 0.1588;
	$sf_insideDef_weight = 1.103;
	$pf_insideDef_weight = 3.6866;
	$c_insideDef_weight = 10.2385;
	
	$pg_rebound_weight = 0.1712;
	$sg_rebound_weight = 0.3644;
	$sf_rebound_weight = 0.9004;
	$pf_rebound_weight = 1.4363;
	$c_rebound_weight = 3.8636;
	
	$pg_block_weight = 0.5093;
	$sg_block_weight = 0.5057;
	$sf_block_weight = 0.5127;
	$pf_block_weight = 2.1969;
	$c_block_weight = 3.3833;
	
	$pg_stamina_weight = 1.1268;
	$sg_stamina_weight = 1.1378;
	$sf_stamina_weight = 1.1515;
	$pf_stamina_weight = 1.1793;
	$c_stamina_weight = 1.2004;
	
	$pg_freethrow_weight = 0.8157;
	$sg_freethrow_weight = 0.8018;
	$sf_freethrow_weight = 0.8048;
	$pf_freethrow_weight = 0.7756;
	$c_freethrow_weight = 0.7614;
	
	$pg_experience_weight = 0.8043;
	$sg_experience_weight = 0.7272;
	$sf_experience_weight = 0.7407;
	$pf_experience_weight = 0.7575;
	$c_experience_weight = 0.7738;
}

// test values

/*
$player = array( array(0,"Jason","Hopper",21,21,21,21,21,21,21,21,21,21,21,21,21,21,21));

				 array_push($player,array(1,"Quyet","Duong",21,21,21,21,21,21,21,21,21,21,21,21,21,21,21),
				 array(2,"Zach","Wilcox",21,21,21,21,21,21,21,21,21,21,21,21,21,21,21),
				 array(3,"Atif","Zorno",21,21,21,21,21,21,21,21,21,21,21,21,21,21,21),
				array(5,"Smelly","Zorno",21,21,21,21,21,21,21,21,21,21,21,21,21,21,21),
				array(6,"Jumpy","Zorno",21,21,21,21,21,21,21,21,21,21,21,21,21,21,21),
				array(7,"Ranky","Zorno",21,21,21,21,21,21,21,21,3,1,1,1,1,1,1),
				 array(8,"Niko","Someone",21,21,21,21,21,21,21,21,21,21,21,21,21,21,21)
				 );
				 */

//################### insert all the player info into an array called $player

/* Initialise ranking variables to 0 */
$rank_pg = 0;
$rank_sg = 0;
$rank_sf = 0;
$rank_pf = 0;
$rank_c = 0;

//echo date("d/m/y : H:i:s", time()) ;

//echo "<h1>Each players stats and position values </h1>";

//echo "<ol>";

// Calculate ratings for each position for each player
for ($row = 0; $row < sizeof($player) ; $row++)
{
	//echo "<li><b>The row number $row</b>";
	//echo "<ul>";

	for ($col = 0; $col < 18; $col++)
	{
		//echo "<li>".$player[$row][$col]."</li>";
		switch($col) {
		case 3: // gameshape
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_gameshape_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_gameshape_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_gameshape_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_gameshape_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_gameshape_weight);
			break;
		case 4: // potential
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_potential_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_potential_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_potential_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_potential_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_potential_weight);
			break;
		case 5: // jumpshot
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_jumpshot_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_jumpshot_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_jumpshot_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_jumpshot_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_jumpshot_weight);
			break;
		case 6: // range
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_range_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_range_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_range_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_range_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_range_weight);
			break;
		case 7: // outsideDef
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_outsideDef_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_outsideDef_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_outsideDef_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_outsideDef_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_outsideDef_weight);
			break;
		case 8: // handling
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_handling_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_handling_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_handling_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_handling_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_handling_weight);
			break;
		case 9: // driving
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_driving_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_driving_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_driving_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_driving_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_driving_weight);
			break;
		case 10: // passing
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_passing_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_passing_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_passing_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_passing_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_passing_weight);
			break;
		case 11: // insideShot
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_insideShot_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_insideShot_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_insideShot_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_insideShot_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_insideShot_weight);
			break;
		case 12: // insideDef
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_insideDef_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_insideDef_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_insideDef_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_insideDef_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_insideDef_weight);
			break;
		case 13: // rebound
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_rebound_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_rebound_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_rebound_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_rebound_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_rebound_weight);
			break;
		case 14: // block
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_block_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_block_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_block_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_block_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_block_weight);
			break;
		case 15: // stamina
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_stamina_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_stamina_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_stamina_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_stamina_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_stamina_weight);
			break;
		case 16: // freeThrow
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_freeThrow_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_freeThrow_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_freeThrow_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_freeThrow_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_freeThrow_weight);
			break;
		case 17: // experience
			$rank_pg = $rank_pg + ($player[$row][$col] * $pg_experience_weight);
			$rank_sg = $rank_sg + ($player[$row][$col] * $sg_experience_weight);
			$rank_sf = $rank_sf + ($player[$row][$col] * $sf_experience_weight);
			$rank_pf = $rank_pf + ($player[$row][$col] * $pf_experience_weight);
			$rank_c  = $rank_c  + ($player[$row][$col] * $c_experience_weight);
			break;
		}

	}
	
	$player[$row][18] = round($rank_pg,1);
	$player[$row][19] = round($rank_sg,1);
	$player[$row][20] = round($rank_sf,1);
	$player[$row][21] = round($rank_pf,1);
	$player[$row][22] = round($rank_c,1);
	
	// reset for next player
	$rank_pg = 0;
	$rank_sg = 0;
	$rank_sf = 0;
	$rank_pf = 0;
	$rank_c = 0;
}

$combination_count = 1;
$best_overall_pg = 0;
$best_overall_sg = 0;
$best_overall_sf = 0;
$best_overall_pf = 0;
$best_overall_c = 0;
$best_overall_total = 0;

// # of players
$roster_size = sizeof($player);

// spin through until we get to 4th last player, i.e. last 5 players of roster
for ($p1 = 0; $p1 < $roster_size-4 ; $p1++)
{
	// 2nd player, start from second player in roster
	for ($p2 = 1; $p2 < $roster_size-3 ; $p2++)
	{
		if ($p1 != $p2) {
			// 3rd player, start from third player in roster
			for ($p3 = 2; $p3 < $roster_size-2 ; $p3++)
			{
				if ($p1 != $p3 and $p2 != $p3) {
					// 4th player, start from third player in roster
					for ($p4 = 3; $p4 < $roster_size-1 ; $p4++)
					{
						if ($p1 != $p4 and $p2 != $p4 and $p3 != $p4) {
							// 5th player, start from third player in roster
							for ($p5 = 4; $p5 < $roster_size ; $p5++)
							{
								if ($p1 != $p5 and $p2 != $p5 and $p3 != $p5 and $p4 != $p5) {
								
									$combination_count = $combination_count + 1;
									
									// Insert calculation code here
									$best_combination_total = 0;
									
									// The Fun bit .... finding out the best combination
									for ($r1 = 18; $r1 < 23; $r1++)
									{
										for ($r2 = 18; $r2 < 23; $r2++)
										{
											if ($r1 != $r2) {
												for ($r3 = 18; $r3 < 23; $r3++)
												{
													if ($r1 != $r3 and $r2 != $r3) {
														for ($r4 = 18; $r4 < 23; $r4++)
														{
															if ($r1 != $r4 and $r2 != $r4 and $r3 != $r4) {
																for ($r5 = 18; $r5 < 23; $r5++)
																{
																	if ($r1 != $r5 and $r2 != $r5 and $r3 != $r5 and $r4 != $r5)
																	{
																		$rank_total = $player[$p1][$r1] + $player[$p2][$r2] + $player[$p3][$r3] + $player[$p4][$r4] + $player[$p5][$r5];
																		//echo "<p>Rank Total = ".$rank_total.".</p>";
																		if ($rank_total > $best_combination_total) {
																			$best_combination_total = $rank_total;			// save total score for later compatison
																			// Save which player is at which position for the best score
																			switch ($r1) {
																				case 18 : $best_combination_pg = $p1; 
																							break;
																				case 19 : $best_combination_sg = $p1; 
																							break;
																				case 20 : $best_combination_sf = $p1; 
																							break;
																				case 21 : $best_combination_pf = $p1; 
																							break;
																				case 22 : $best_combination_c = $p1; 
																							break;
																			}
																			switch ($r2) {
																				case 18 : $best_combination_pg = $p2; 
																							break;
																				case 19 : $best_combination_sg = $p2; 
																							break;
																				case 20 : $best_combination_sf = $p2; 
																							break;
																				case 21 : $best_combination_pf = $p2; 
																							break;
																				case 22 : $best_combination_c = $p2; 
																							break;
																			}
																			switch ($r3) {
																				case 18 : $best_combination_pg = $p3; 
																							break;
																				case 19 : $best_combination_sg = $p3; 
																							break;
																				case 20 : $best_combination_sf = $p3; 
																							break;
																				case 21 : $best_combination_pf = $p3; 
																							break;
																				case 22 : $best_combination_c = $p3; 
																							break;
																			}
																			switch ($r4) {
																				case 18 : $best_combination_pg = $p4; 
																							break;
																				case 19 : $best_combination_sg = $p4; 
																							break;
																				case 20 : $best_combination_sf = $p4; 
																							break;
																				case 21 : $best_combination_pf = $p4; 
																							break;
																				case 22 : $best_combination_c = $p4; 
																							break;
																			}
																			switch ($r5) {
																				case 18 : $best_combination_pg = $p5; 
																							break;
																				case 19 : $best_combination_sg = $p5; 
																							break;
																				case 20 : $best_combination_sf = $p5; 
																							break;
																				case 21 : $best_combination_pf = $p5; 
																							break;
																				case 22 : $best_combination_c = $p5; 
																							break;
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
									// end calculation code
								}
								// Is this the best overall total???
								if ($best_combination_total > $best_overall_total)
								{
									$best_overall_total = $best_combination_total;
									$best_overall_pg = $best_combination_pg;
									$best_overall_sg = $best_combination_sg;
									$best_overall_sf = $best_combination_sf;
									$best_overall_pf = $best_combination_pf;
									$best_overall_c = $best_combination_c;
								}
							}
						}
					}
				}
			}
		}
	}
}

									echo "<p>PG: ".$player[$best_overall_pg][1]." ".$player[$best_overall_pg][2]."</p>";
									echo "<p>SG: ".$player[$best_overall_sg][1]." ".$player[$best_overall_sg][2]."</p>";
									echo "<p>SF: ".$player[$best_overall_sf][1]." ".$player[$best_overall_sf][2]."</p>";
									echo "<p>PF: ".$player[$best_overall_pf][1]." ".$player[$best_overall_pf][2]."</p>";
									echo "<p>C: ".$player[$best_overall_c][1]." ".$player[$best_overall_c][2]."</p>";
									if ($_SESSION['id']==38596)
									{
										echo $best_overall_total;
										echo "  -- -- ".$max_score;
									}
									$best_overall_total = round($best_overall_total/$max_score,4)*100;
?>

