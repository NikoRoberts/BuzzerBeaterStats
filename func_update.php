<?php
include("thread.php"); // Multi-threading

function updateLeagueStats($league_id,$season_id,$next_update,$session_id,$session_username,$session_key,$session_buzz)
{
	echo '<div id="loader" style="margin:0 auto 0 auto; text-align:center;">Please Wait ... BBStats is updating this league<br>
		<img id="loader" src="/themes/'.$_SESSION['theme'].'/images/ajax-loader.gif" alt="Loading"></div>';
	flush(); //flush everything to the browser that has been loaded so far
	
	// If the league needs an update*/
	if($next_update=="now")
	{
		//if($session_id==38596) echo "<div style='margin:0 auto 0 auto; text-align:center;'>Updating...NOW</div>";
		$update_time = date("U");
		updateStandings($league_id,$season_id,$update_time,$session_id,$session_username,$session_key,$session_buzz); //update teams in league
		
		$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
                $mysqli->set_charset("utf8");
		$manager = new ThreadManager; // create thread manager
		$query = "SELECT id FROM team WHERE league='".addslashes($league_id)."' AND season='".addslashes($season_id)."'";
		$items = $mysqli->query($query) or die("Team Query Failed!");
		while ($return = mysqli_fetch_array($items))
		{
			//Update players on each team in the league
			
			//Threaded
			$manager->create_thread('updatePlayerStats', array($return['id'],$league_id,$season_id,$update_time,$session_id,$session_username,$session_key,$session_buzz));
			$manager->create_thread('updatePlayerSkills', array($return['id'],$league_id,$season_id,$update_time,$session_id,$session_username,$session_key,$session_buzz));
			
			//Unthreaded
			//echo "Unthreaded<br>";
			//updatePlayerStats($return['id'],$league_id,$season_id,$update_time,$session_id,$session_username,$session_key,$session_buzz);
			//updatePlayerSkills($return['id'],$league_id,$season_id,$update_time,$session_id,$session_username,$session_key,$session_buzz);
		}
		while ($manager->query());
                $mysqli->close();
	}
	echo '<script type="text/javascript">document.getElementById("loader").style.display="none"</script>';
}

//##########################NEW
// UPDATE LEAGUE STANDINGS
function updateStandings($league_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    
    $loggedin = updateLogin($session_id,$session_user,$session_key,$session_buzz);

    if($loggedin > 0)
    {
        $reader = new XMLReader();

        if(!$reader=updateRequest("standings","leagueid=".$league_id."&season=".$season_id,$session_id,$session_buzz))
        {
            die("ERROR (US.0): Cannot connect to BuzzerBeater<br>");
        }		
        $teamArray = array();
        $conference=0;

        while ($reader->read())
        {
            if ($reader->name=="error") print "<div class='centered'>ERROR (US.1): Login Error has occurred please report this to JudgeNik</div>";
            if ($reader->name=="bbapi") {}; // no need for information from here yet
            if ($reader->name=="standings")	{};
            if ($reader->name=="league")
            {
                    $league_level = $reader->getAttribute('level');
            }
            if(($node_name=="league") && ($reader->nodeType == XMLReader::TEXT ))
            {
                    $league_name = $reader->value;
            }
            if ($reader->name=="country") $country_id = $reader->getAttribute('id');
            if ($reader->name=="regularSeason") {}; // no need for information from here yet
            if (($reader->name=="conference") && ($reader->nodeType == XMLReader::ELEMENT ))
            {
                    $conference++; // Update conference attribute
            }
            if(($reader->name=="team") && ($reader->nodeType == XMLReader::ELEMENT ))
            {
                    $teamArray['id'] = $reader->getAttribute('id');
                    $teamArray['conference'] = $conference;

            }
            else if($reader->nodeType == XMLReader::ELEMENT)
            {
                    $node_name = $reader->name;
            }
            if ($reader->nodeType == XMLReader::TEXT)
            {
                    //print $node_name." -:- ".$reader->value."<br>";
                    $teamArray[$node_name] = $reader->value;
            }
            if(($reader->name=="team") && ($reader->nodeType == XMLReader::END_ELEMENT ))
            {
                    $query = "INSERT INTO `team`
                            SET
                            id = '".$teamArray['id']."',
                            name = '".$teamArray['teamName']."',
                            league = '".addslashes($league_id)."',
                            conference = '".$teamArray['conference']."',
                            season = '".addslashes($season_id)."',
                            wins = '".$teamArray['wins']."',
                            losses = '".$teamArray['losses']."',
                            pf = '".$teamArray['pf']."',
                            pa = '".$teamArray['pa']."'
                            ON DUPLICATE KEY UPDATE
                            name = '".$teamArray['teamName']."',
                            league = '".addslashes($league_id)."',
                            conference = '".$teamArray['conference']."',
                            season = '".addslashes($season_id)."',
                            wins = '".$teamArray['wins']."',
                            losses = '".$teamArray['losses']."',
                            pf = '".$teamArray['pf']."',
                            pa = '".$teamArray['pa']."'";

                    $items = $mysqli->query($query) or die("ERROR (US.2): Team Update Failed!");

                    $teamArray = array(); //reset the team array
            }
        }
        //update the league table
        $exists = 0; // insert if not found
        $query = "
            INSERT INTO `league`
            SET
            id = '".addslashes($league_id)."'
            ,name = '".$league_name."'
            ,countryid = '".$country_id."'
            ,level = '".$league_level."'
            ,season = '".addslashes($season_id)."'
            ,timestamp = '".$update_time."'
            ON DUPLICATE KEY UPDATE
            name = '".$league_name."'
            ,countryid = '".$country_id."'
            ,level = '".$league_level."'
            ,timestamp = '".$update_time."'";
        $items = $mysqli->query($query) or die("ERROR (US.5): League Update Failed!<pre>".$query."</pre>".mysqli_error($mysqli));

        unset($teamArray); //empty team array
        $reader->close(); //close the XML reader
    }
    else
    {
        print "ERROR (US.8): Login System has failed. Please send this error to GM-JudgeNik<BR>";
    }
    $mysqli->close();
}

// ########## UPDATE PLAYER STATS
function updatePlayerStats($team_id,$league_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    $errors = null;
    $loggedin = updateLogin($session_id,$session_user,$session_key,$session_buzz);
    $time_overall = 0.0;
    
    if($loggedin > 0)
    {
            
            $reader = new XMLReader();
            if(!$reader=updateRequest("teamstats","mode=totals&teamid=".$team_id."&season=".$season_id,$session_id,$session_buzz))
            {
                    die("ERROR (UPS.0): Cannot connect to BuzzerBeater<br>");
            }
            //Now get the player stats
            $playerArray = array();
            while ($reader->read())
            {
                if ($reader->name=="bbapi") $reader->read(); // no need for information from here yet
                if ($reader->name=="teamTotals") $reader->read(); // no need for information from here yet

                if(($reader->name=="player") && ($reader->nodeType == XMLReader::ELEMENT ))
                {
                        $playerArray['id'] = $reader->getAttribute('id');
                }
                else if(($reader->nodeType == XMLReader::ELEMENT) && ($reader->name!="totals"))
                {
                        $node_name = $reader->name;
                }
                else if ($reader->nodeType == XMLReader::TEXT )
                {
                        $playerArray[$node_name] = $reader->value;
                }
                if(($reader->name=="player") && ($reader->nodeType == XMLReader::END_ELEMENT ))
                {
                    //update database and then clear array
                    $time_start = microtime(true);

                    $query = "
                    INSERT INTO `player_stats`
                    SET
                    teamid='".addslashes($team_id)."',
                    playerid='".$playerArray['id']."',
                    season='".addslashes($season_id)."',
                    leagueid='".addslashes($league_id)."',
                    games='".$playerArray['games']."',
                    minutes='".$playerArray['minutes']."',
                    fgm='".$playerArray['fgm']."',
                    fga='".$playerArray['fga']."',
                    tpm='".$playerArray['tpm']."',
                    tpa='".$playerArray['tpa']."',
                    ftm='".$playerArray['ftm']."',
                    fta='".$playerArray['fta']."',
                    oreb='".$playerArray['oreb']."',
                    reb='".$playerArray['reb']."',
                    ast='".$playerArray['ast']."',
                    turn='".$playerArray['to']."',
                    stl='".$playerArray['stl']."',
                    blk='".$playerArray['blk']."',
                    pf='".$playerArray['pf']."',
                    pts='".$playerArray['pts']."',
                    rating='".$playerArray['rating']."'
                    ON DUPLICATE KEY UPDATE
                    games='".$playerArray['games']."',
                    minutes='".$playerArray['minutes']."',
                    fgm='".$playerArray['fgm']."',
                    fga='".$playerArray['fga']."',
                    tpm='".$playerArray['tpm']."',
                    tpa='".$playerArray['tpa']."',
                    ftm='".$playerArray['ftm']."',
                    fta='".$playerArray['fta']."',
                    oreb='".$playerArray['oreb']."',
                    reb='".$playerArray['reb']."',
                    ast='".$playerArray['ast']."',
                    turn='".$playerArray['to']."',
                    stl='".$playerArray['stl']."',
                    blk='".$playerArray['blk']."',
                    pf='".$playerArray['pf']."',
                    pts='".$playerArray['pts']."',
                    rating='".$playerArray['rating']."'";

                    //$mysqli->query($query) or die("Player Stats Update Failed! -- ".$query);

                    if($mysqli->query($query))
                    {
                        $time_end = microtime(true);
                        $time = $time_end - $time_start;
                        if($session_id=="38596")
                        {
                            $time_overall = $time_overall + $time;
                            //buzzlog("\nUpdated player (Skills) '".$playerArray['firstName']."' '".$playerArray['lastName']."', in $time seconds");
                        }
                    }
                    else
                    {
                        $errors .= "\n".$mysqli->error;
                    }
                    $playerArray = null;
                }
            }
            updateStandings($league_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz); // Make sure league is there
            unset($playerArray); //empty player array
            $reader->close(); //close the XML reader

            if($session_id=="38596")
            {
                //buzzlog("\nUpdated team '".$team_id."' Stats, in $time_overall seconds");
            }
    }
    else
    {
            print "Please check your login details.<br>";
    }
    //if($errors) buzzlog("\n".$errors);
    $mysqli->close();
}

function updatePlayerSkills($team_id,$league_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $errors = null;
    $loggedin = updateLogin($session_id,$session_user,$session_key,$session_buzz);
    $mysqli->set_charset("utf8");
    
    
    if($loggedin > 0)
    {
        $time_start_overall = microtime(true);
        // Now get the player details
        $reader = new XMLReader();
        if(!$reader=updateRequest("roster","teamid=".$team_id."&season=".$season_id,$session_id,$session_buzz))
        {
                die("ERROR (UPSk.0): Cannot connect to BuzzerBeater<br>");
        }

        $i = 0;
        $playerArray = array();
        $playerArray['pop_list']="";

        while ($reader->read())
        {
//			if ($reader->name=="bbapi") ; // no need for information from here yet
                if ($reader->name=="error") print "ERROR (UPSk.1): Login Error has occurred please report this to GM-JudgeNik<br>";
                if ($reader->name=="roster") "Roster as expected "; // no need for information from here yet

                if(($reader->name=="player") && ($reader->nodeType == XMLReader::ELEMENT ))
                {
                        $i = $i + 1;
                        $playerArray['id'] = $reader->getAttribute('id');
                        //print " player: ".$playerArray['id']."<br>";
                }
                else if(($reader->nodeType == XMLReader::ELEMENT) && ($reader->name!="skills"))
                {
                        //print $reader->name.":";
                        $node_name = $reader->name;
                        if(($reader->hasAttributes) && ($pop=$reader->getAttribute('pop'))) $playerArray['pop_list'] .= $node_name.":".$pop.","; // concat pops into one list
                        if($node_name=="nationality") $playerArray[$node_name] = $reader->getAttribute('id'); //nationality ID instead of the name
                }
                else if ($reader->nodeType == XMLReader::TEXT )
                {
                        //print $node_name." -:- ".$reader->value."<br>";
                        if($node_name!="nationality") $playerArray[$node_name] = mb_convert_encoding($reader->value,"UTF-8");
                }
                if(($reader->name=="player") && ($reader->nodeType == XMLReader::END_ELEMENT ))
                {

                    //update database and then clear array
                    //print "player end:".$playerArray['id']."<br>";

                    $query = "
                    INSERT INTO `player`
                    SET
                    `id` = '".$playerArray['id']."',
                    `team_id` = '".addslashes($team_id)."',
                    `firstname` = '".$playerArray['firstName']."',
                    `lastname` = '".$playerArray['lastName']."',
                    `nationality` = '".$playerArray['nationality']."',
                    `age` = '".$playerArray['age']."',
                    `height` = '".$playerArray['height']."',
                    `dmi` = '".$playerArray['dmi']."',
                    `injury` = '".$playerArray['injury']."',
                    `jersey` = '".$playerArray['jersey']."',
                    `salary` = '".$playerArray['salary']."',
                    `bestposition` = '".$playerArray['bestPosition']."',
                    `gameshape` = '".$playerArray['gameShape']."',
                    `potential` = '".$playerArray['potential']."'";

                    // now check whether the private stats are there and put them in if they are
                    if ($playerArray['jumpShot']) $query .= ",`jumpshot` = '".$playerArray['jumpShot']."'";
                    if ($playerArray['range']) $query .= ",`range` = '".$playerArray['range']."'";
                    if ($playerArray['outsideDef']) $query .= ",`outsidedef` = '".$playerArray['outsideDef']."'";
                    if ($playerArray['handling']) $query .= ",`handling` = '".$playerArray['handling']."'";
                    if ($playerArray['driving']) $query .= ",`driving` = '".$playerArray['driving']."'";
                    if ($playerArray['passing']) $query .= ",`passing` = '".$playerArray['passing']."'";
                    if ($playerArray['insideShot']) $query .= ",`insideshot` = '".$playerArray['insideShot']."'";
                    if ($playerArray['insideDef']) $query .= ",`insidedef` = '".$playerArray['insideDef']."'";
                    if ($playerArray['rebound']) $query .= ",`rebound` = '".$playerArray['rebound']."'";
                    if ($playerArray['block']) $query .= ",`block` = '".$playerArray['block']."'";
                    if ($playerArray['stamina']) $query .= ",`stamina` = '".$playerArray['stamina']."'";
                    if ($playerArray['freeThrow']) $query .= ",`freethrow` = '".$playerArray['freeThrow']."'";
                    if ($playerArray['experience']) $query .= ",`experience` = '".$playerArray['experience']."'";
                    if ($playerArray['pop_list']) $query .= ",`pop_list` = '".$playerArray['pop_list']."'";
                    $query .= ",`update` = '".$update_time."'
                        
                    ON DUPLICATE KEY UPDATE
                    
                    `age` = '".$playerArray['age']."',
                    `dmi` = '".$playerArray['dmi']."',
                    `injury` = '".$playerArray['injury']."',
                    `jersey` = '".$playerArray['jersey']."',
                    `salary` = '".$playerArray['salary']."',
                    `bestposition` = '".$playerArray['bestPosition']."',
                    `gameshape` = '".$playerArray['gameShape']."'";

                    // now check whether the private stats are there and put them in if they are
                    if ($playerArray['jumpShot']) $query .= ",`jumpshot` = '".$playerArray['jumpShot']."'";
                    if ($playerArray['range']) $query .= ",`range` = '".$playerArray['range']."'";
                    if ($playerArray['outsideDef']) $query .= ",`outsidedef` = '".$playerArray['outsideDef']."'";
                    if ($playerArray['handling']) $query .= ",`handling` = '".$playerArray['handling']."'";
                    if ($playerArray['driving']) $query .= ",`driving` = '".$playerArray['driving']."'";
                    if ($playerArray['passing']) $query .= ",`passing` = '".$playerArray['passing']."'";
                    if ($playerArray['insideShot']) $query .= ",`insideshot` = '".$playerArray['insideShot']."'";
                    if ($playerArray['insideDef']) $query .= ",`insidedef` = '".$playerArray['insideDef']."'";
                    if ($playerArray['rebound']) $query .= ",`rebound` = '".$playerArray['rebound']."'";
                    if ($playerArray['block']) $query .= ",`block` = '".$playerArray['block']."'";
                    if ($playerArray['stamina']) $query .= ",`stamina` = '".$playerArray['stamina']."'";
                    if ($playerArray['freeThrow']) $query .= ",`freethrow` = '".$playerArray['freeThrow']."'";
                    if ($playerArray['experience']) $query .= ",`experience` = '".$playerArray['experience']."'";
                    if ($playerArray['pop_list']) $query .= ",`pop_list` = '".$playerArray['pop_list']."'";

                    $query .= ",`update` = '".$update_time."'";

                    $time_start = microtime(true);
                    //$mysqli->query($query) or buzzlog("ERROR (UPSk.3): Player Update Failed! -- Query(".$query.") Error(".mysql_error().")");
                    if($mysqli->query($query))
                    {
                        $time_end = microtime(true);
                        $time = $time_end - $time_start;
                        if($session_id=="38596")
                        {
                            //buzzlog("\nUpdated player (Skills) '".$playerArray['firstName']."' '".$playerArray['lastName']."', in $time seconds");
                        }
                    }
                    else
                    {
                        $errors .= "\n".$mysqli->error;
                    }
                    $playerArray = array(); //  reset the player array
                }

        }
        updateStandings($league_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz); // Make sure league is there
        unset($playerArray); //empty player array
        $reader->close(); //close the XML reader
        updatePops($team_id); //update all of the pops that the players had

        $time_end_overall = microtime(true);
        $time_overall = $time_end_overall - $time_start_overall;
        if($session_id=="38596")
        {
            //buzzlog("\nUpdated team '".$team_id."' skills, in $time_overall seconds");
        }
    }
    //if($errors) buzzlog("\n".$errors);
    $mysqli->close();
}

function buzzlog($entry)
{
    /*
    $myFile = "/tmp/buzzlog";
    $fh = fopen($myFile, 'a') or die("can't open file");
    $stringData = $entry;
    fwrite($fh, $stringData);
    fclose($fh);
     *
     */
}
// ##### UPDATE SKILL POPS
function updatePops($team_id)
{
    /*
	// get the latest posting week from the recorded training data
	$latest_training_week = -1;
	$query = "SELECT weekid FROM training_results WHERE teamid='".$team_id."' ORDER BY weekid DESC LIMIT 1";
	$items = $mysqli->query($query) or die("Training Results Query Failed! -- ".$query);
	$return = mysqli_fetch_array($items);
	if ($return['weekid']>0) $latest_training_week = $return['weekid'];
	
	$thisWeek = getWeek(); // get the current week from functions.php
	
	$lastWeek = $thisWeek-1; //get last week
	
	//update pop table with the data from the pop_lists
	if ($lastWeek>$latest_training_week)
	{
		$query = "SELECT pop_list, id, team_id FROM player WHERE pop_list NOT IN (' ','0') AND team_id='".$team_id."'";
		$items = $mysqli->query($query) or die("Pops Query Failed! -- ".$query);
		while($return = mysqli_fetch_array($items))
		{
			$pops = explode(",",$return['pop_list']);
			$i = 0;
			while($pops[$i])
			{
				$content = explode(":",$pops[$i]);
				$skill = $content[0];
				$value = $content[1];
				$query = "INSERT INTO training_results
					VALUES(
					'".$lastWeek."',
					'".$return['id']."',
					'".$skill."',
					'".$team_id."',
					'".$value."')";
				$mysqli->query($query) or die("New Player Pop Entry Failed! -- ".$query);
				$i++;
			}
		}
	}
    */
}

function updateCountries($update_time,$session_id,$session_user,$session_key,$session_buzz)
{
	
	$loggedin = updateLogin($session_id,$session_user,$session_key,$session_buzz);
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	if($loggedin > 0)
	{
		// Now get the countries
		$reader = new XMLReader();
		if(!$reader=updateRequest("countries","",$session_id,$session_buzz))
		{
			die("ERROR (UC.0): Cannot connect to BuzzerBeater<br>");
		}
		
		$countryArray = array();
		while ($reader->read())
		{
			if ($reader->name=="bbapi") $reader->read(); // no need for information from here yet
			if($reader->name=="error") echo "ERROR (UC.1): Login errors have occured, please report this to GM-JudgeNik";
			if(($reader->name=="country") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
			
				$countryArray['id'] = $reader->getAttribute('id');
				$countryArray['divisions'] = $reader->getAttribute('divisions');
				$countryArray['firstSeason'] = $reader->getAttribute('firstSeason');
				$countryArray['users'] = $reader->getAttribute('users');
			}
			else if ($reader->nodeType == XMLReader::TEXT )
			{
				$countryArray['name'] = $reader->value;
			}
			if(($reader->name=="country") && ($reader->nodeType == XMLReader::END_ELEMENT ))
			{
				//update database and then clear array
				$insert = 1; // insert trigger
                                $query = "INSERT INTO countries
                                    SET `id` = '".$countryArray['id']."',
					`divisions` = '".$countryArray['divisions']."',
					`firstSeason` = '".$countryArray['firstSeason']."',
					`users` = '".$countryArray['users']."',
					`name` = '".$countryArray['name']."'
                                        ON DUPLICATE KEY UPDATE
                                        `divisions` = '".$countryArray['divisions']."',
					`firstSeason` = '".$countryArray['firstSeason']."',
					`users` = '".$countryArray['users']."',
					`name` = '".$countryArray['name']."'";
				$items = $mysqli->query($query) or die("ERROR (UC.2): Country Query Failed! ");
				
				$countryArray = array(); //  reset the player array
			}
		}
		$query = "
		INSERT INTO `latest_posting` SET `timestamp` = '".$update_time."', `type` = 'world'
                    ON DUPLICATE KEY UPDATE
                    `timestamp` = '".$update_time."'
                    ";
                $query = "
		INSERT INTO `latest_posting`
                SET `timestamp` = '".$update_time."'
                    , `type` = 'world'
                    , `id` = '1'
                    , `season` = '1'
                    ON DUPLICATE KEY UPDATE
                    `timestamp` = '".$update_time."'
                    ";
		$mysqli->query($query);
		unset($countryArray); //empty player array
		$reader->close(); //close the XML reader
	}
        $mysqli->close();
}

function updateAllLeagues($country_id,$level_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz)
{
	$loggedin = updateLogin($session_id,$session_user,$session_key,$session_buzz);
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	if($loggedin > 0)
	{
		// Now get the leagues
		$reader = new XMLReader();
		if(!$reader=updateRequest("leagues","countryid=".$country_id."&level=".$level_id,$session_id,$session_buzz))
		{
			die("ERROR (UAL.0): Cannot connect to BuzzerBeater<br>");
		}
		
		$divArray = array();
		
		while ($reader->read())
		{
			if ($reader->name=="bbapi") $reader->read(); // no need for information from here yet
			if ($reader->name=="division") $reader->read(); 
			if(($reader->name=="league") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
				$divArray['id'] = $reader->getAttribute('id');
			}
			else if ($reader->nodeType == XMLReader::TEXT )
			{
				$divArray['name'] = addslashes($reader->value);
			}
			if(($reader->name=="league") && ($reader->nodeType == XMLReader::END_ELEMENT ))
			{
				//update database and then clear array
				
				$exists = 0; // insert trigger
				$query = "INSERT INTO `league`
					SET
					id='".$divArray['id']."',
					name='".$divArray['name']."',
					countryid='".addslashes($country_id)."',
					level='".addslashes($level_id)."',
					season='".addslashes($season_id)."'
                                            ON DUPLICATE KEY UPDATE
					name='".$divArray['name']."',
					countryid='".addslashes($country_id)."',
					level='".addslashes($level_id)."',
					season='".addslashes($season_id)."'";
				$items = $mysqli->query($query) or die("ERROR (UAL.1): League Update Failed!");
				
				$divArray = array(); //  reset the player array
			}
		}
		unset($divArray); //empty player array now
		$reader->close(); //close the XML reader
                
                $query = "
		INSERT INTO `latest_posting`
                SET `timestamp` = '".$update_time."'
                    , `type` = 'country'
                    , `id` = '".addslashes($country_id)."'
                    , `season` = '".addslashes($season_id)."'
                    ON DUPLICATE KEY UPDATE
                    `timestamp` = '".$update_time."'
                    , `season` = '".addslashes($season_id)."'
                    ";
		$mysqli->query($query);
	}
        $mysqli->close();
}

function updateTeamSchedule($team_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz)
{
	$loggedin = updateLogin($session_id,$session_user,$session_key,$session_buzz);
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	if($loggedin > 0)
	{
		// Now get the team schedules
		$reader = new XMLReader();
		if(!$reader=updateRequest("schedule","teamid=".$team_id."&season=".$season_id,$session_id,$session_buzz))
		{
			die("ERROR (UTS.0): Cannot connect to BuzzerBeater<br>");
		}
		$matchArray = array();
		$currentTeam = "";
		
		while ($reader->read())
		{
			if ($reader->name=="bbapi") $reader->read(); // no need for information from here yet
			if ($reader->name=="schedule") $reader->read(); // no need for information from here yet
			if(($reader->name=="match") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
				$matchArray['id'] = $reader->getAttribute('id');
				$matchArray['starttime'] = $reader->getAttribute('start');
				$matchArray['type'] = $reader->getAttribute('type');
			}
			else if (($reader->name=="awayTeam") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
				$matchArray["awayid"] = $reader->getAttribute('id');
				$currentTeam = "away";
			}
			else if (($reader->name=="homeTeam") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
				$matchArray["homeid"] = $reader->getAttribute('id');
				$currentTeam = "home";
			}
			else if($reader->nodeType == XMLReader::ELEMENT)
			{
				$node_name = $currentTeam.$reader->name;
			}
			if ($reader->nodeType == XMLReader::TEXT )
			{
				$matchArray[$node_name] = $reader->value;
			}
			if(($reader->name=="match") && ($reader->nodeType == XMLReader::END_ELEMENT ))
			{
				//put match details in database
				
				$query = "INSERT INTO schedule
					SET
					match_id='".$matchArray['id']."'
					,team_id='".addslashes($team_id)."'
					,season='".addslashes($season_id)."'
					,starttime='".$matchArray['starttime']."'
					,type='".$matchArray['type']."'
					,awayid='".$matchArray['awayid']."'
					,awayname='".$matchArray['awayteamName']."'
					,awayscore='".$matchArray['awayscore']."'
					,homeid='".$matchArray['homeid']."'
					,homename='".$matchArray['hometeamName']."'
					,homescore='".$matchArray['homescore']."'
                                        ON DUPLICATE KEY UPDATE
					awayscore='".$matchArray['awayscore']."'
					,homescore='".$matchArray['homescore']."'
                                        ";
				$items = $mysqli->query($query) or die("ERROR (UTS.1): Match Query Failed! -- ".$query);
				
				$matchArray = array(); //  reset the player array
			}
		}
		unset($matchArray); //empty player array
		$reader->close(); //close the XML reader
	}
        $mysqli->close();
}

function updateMatchDetails($match_id,$team_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz)
{
	$loggedin = updateLogin($session_id,$session_user,$session_key,$session_buzz);
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	if($loggedin > 0)
	{
		// Now get the team schedule
		$reader = new XMLReader();
		if(!$reader=updateRequest("boxscore","matchid=".$match_id,$session_id,$session_buzz))
		{
			die("ERROR (UMD.0): Cannot connect to BuzzerBeater<br>");
		}
		$matchArray = array();
		$playerStatsArray = array();
		$currentTeam = "match";
		
		while ($reader->read())
		{
			if ($reader->name=="bbapi") $reader->read(); // no need for information from here yet
			if(($reader->name=="match") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
				$matchArray['id'] = $reader->getAttribute('id');
			}
			else if (($reader->name=="awayTeam") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
				$matchArray["awayid"] = $reader->getAttribute('id');
				$currentTeamId = $reader->getAttribute('id'); // set team id for player
				$currentTeam = "away"; // add prefix for match detail
			}
			else if (($reader->name=="homeTeam") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
				$matchArray["homeid"] = $reader->getAttribute('id');
				$currentTeamId = $reader->getAttribute('id'); // set teamid for player
				$currentTeam = "home";
			}
			if(($reader->name=="score") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
				if($currentTeam == "home") $matchArray['homepartials'] = $reader->getAttribute('partials');
				else $matchArray['awaypartials'] = $reader->getAttribute('partials');
			}
			if(($reader->name=="boxscore") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
				if ($currentTeam=="home") $home_true = 1; // for player's stats, mark whether playing at home or not
				else $home_true = 0;
				$currentTeam = ""; // remove prefix for player stats
			}
			if(($reader->name=="player") && ($reader->nodeType == XMLReader::ELEMENT ))
			{
				$playerStatsArray["id"] = $reader->getAttribute('id');
			}
			else if($reader->nodeType == XMLReader::ELEMENT)
			{
				$node_name = $currentTeam.$reader->name;
			}
			if ($reader->nodeType == XMLReader::TEXT )
			{
				if(($currentTeam=="home") || ($currentTeam=="away") || ($currentTeam=="match")) $matchArray[$node_name] = $reader->value; // if match details
				else $playerStatsArray[$node_name] = $reader->value; // else if player stats
			}
			
			if(($reader->name=="player") && ($reader->nodeType == XMLReader::END_ELEMENT ))
			{
                            /* If it is player stats we can ignore data that is duplicated because we should
                             * have everything we want already
                             */
                            $query = "
                            INSERT IGNORE INTO `match_stats`
                            SET
                                match_id='".$matchArray['id']."'
                                ,team_id='".$currentTeamId."'
                                ,player_id='".$playerStatsArray['id']."'
                                ,home='".$home_true."'
                                ,season='".addslashes($season_id)."'
                                ,pgminutes='".$playerStatsArray['PG']."'
                                ,sgminutes='".$playerStatsArray['SG']."'
                                ,sfminutes='".$playerStatsArray['SF']."'
                                ,pfminutes='".$playerStatsArray['PF']."'
                                ,cminutes='".$playerStatsArray['C']."'
                                ,fgm='".$playerStatsArray['fgm']."'
                                ,fga='".$playerStatsArray['fga']."'
                                ,tpm='".$playerStatsArray['tpm']."'
                                ,tpa='".$playerStatsArray['tpa']."'
                                ,ftm='".$playerStatsArray['ftm']."'
                                ,fta='".$playerStatsArray['fta']."'
                                ,oreb='".$playerStatsArray['oreb']."'
                                ,reb='".$playerStatsArray['reb']."'
                                ,ast='".$playerStatsArray['ast']."'
                                ,turn='".$playerStatsArray['to']."'
                                ,stl='".$playerStatsArray['stl']."'
                                ,blk='".$playerStatsArray['blk']."'
                                ,pf='".$playerStatsArray['pf']."'
                                ,pts='".$playerStatsArray['pts']."'
                                ,rating='".$playerStatsArray['rating']."'";
                               				
				$mysqli->query($query) or die("ERROR (UMD.2): Match Stats Entry Failed! <pre>".$query."</pre><br/>".mysqli_error($mysqli));
				
				$playerStatsArray = array(); //  reset the player stats array
			}
			if(($reader->name=="match") && ($reader->nodeType == XMLReader::END_ELEMENT ))
			{
                            $awayEffort = "";
                            $homeEffort = "";
                            
                            if($matchArray['awayeffort'])
                            {
                                $awayEffort = $matchArray['awayeffort'];
                                $homeEffort = calcEffort($matchArray['awayeffort'],0,$matchArray['matcheffortDelta']);
                            }
                            if($matchArray['homeeffort'])
                            {
                                $homeEffort = $matchArray['homeeffort'];
                                $awayEffort = calcEffort($matchArray['homeeffort'],1,$matchArray['matcheffortDelta']);
                            }

                                $query = "INSERT INTO `match`
                                SET
                                id='".$matchArray['id']."'
                                ,starttime='".$matchArray['matchstartTime']."'
                                ,endtime='".$matchArray['matchendTime']."'
                                ,effortDelta='".$matchArray['matcheffortDelta']."'
                                ,bleachers='".$matchArray['matchbleachers']."'
                                ,lowertier='".$matchArray['matchlowerTier']."'
                                ,courtside='".$matchArray['matchcourtside']."'
                                ,luxury='".$matchArray['matchluxury']."'
                                ,awayid='".$matchArray['awayid']."'
                                ,awayeffort='".$awayEffort."'
                                ,awaypartials='".$matchArray['awaypartials']."'
                                ,awayscore='".$matchArray['awayscore']."'
                                ,awayoffStrategy='".$matchArray['awayoffStrategy']."'
                                ,awaydefStrategy='".$matchArray['awaydefStrategy']."'
                                ,homeid='".$matchArray['homeid']."'
                                ,homeeffort='".$homeEffort."'
                                ,homepartials='".$matchArray['homepartials']."'
                                ,homescore='".$matchArray['homescore']."'
                                ,homeoffStrategy='".$matchArray['homeoffStrategy']."'
                                ,homedefStrategy='".$matchArray['homedefStrategy']."'
                                ON DUPLICATE KEY UPDATE
                                homeid='".$matchArray['homeid']."'";
                                if($awayEffort) $query .= ",awayeffort='".$awayEffort."'";
                                if($homeEffort) $query .= ",homeeffort='".$homeEffort."'";
                                
                                $mysqli->query($query) or die("ERROR (UMD.2): New Match Entry Failed! -- ".$query);

				$matchArray = array(); //  reset the match array
			}
		}
		unset($matchArray); //empty match array
                unset($playerStatsArray); //empty match stats array
		$reader->close(); //close the XML reader    
            $mysqli->close();
	}
}

function calcEffort($effort,$isHome,$delta)
{
    $arr = array("crunchTime" => 2, "normal" => 1,"takeItEasy" => 0);
    if($isHome)
    {        
        $result = array_search(($arr[$effort]-$delta),$arr);
        return $result;
    }
    else
    {
        $result = array_search(($arr[$effort]+$delta),$arr);
        return $result;
    }
}
//######### Update Countries and their leagues
function updateCountriesAndLeagues($season_id,$session_id,$session_user,$session_key)
{
	$manager = new ThreadManager; // create thread manager
	updateCountries($session_id,$session_user,$session_key);
        
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
        $mysqli->set_charset("utf8");
	$query="SELECT id, divisions FROM countries";
	$items = $mysqli->query($query) or die("ERROR (UCAL.0): Country Check Failed!");
	while($return = mysqli_fetch_array($items))
	{
		for($i=1;$i<=$return['divisions'];$i++)
		{
			$manager->create_thread('updateAllLeagues', array($return['id'],$i,$season_id,$session_id,$session_user,$session_key));
			//updateAllLeagues($return['id'],$i,$season_id,$session_id,$session_user,$session_key);
		}
	}
	while ($manager->query());
        $mysqli->close();
}

function updateSchedule($team_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz)
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    
    $manager = new ThreadManager; // create thread manager
    $update_time = date('U');
    
    updateTeamSchedule($team_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz);
    $query="
        SELECT match_id
        FROM schedule
        WHERE
        team_id='".$team_id."'
        AND season='".$season_id."'";
    $items = $mysqli->query($query) or die("Database Query Failed!<pre>".$query."</pre><br/>".mysqli_error($mysqli));
    while ($return = mysqli_fetch_array($items))
    {
        // for each item in the schedule
        
        $manager->create_thread('updateMatchDetails', array($return['match_id'],$team_id,$season_id,$update_time,$session_id,$session_user,$session_key,$session_buzz));
        //updateMatchDetails($return['match_id'],$team_id,$season_id,$update_time,$_SESSION['id'],$_SESSION['bbusername'],$_SESSION['bbaccesskey'],$_SESSION['buzzWebsite']);
    }
    while ($manager->query()); // execute the threads
    $mysqli->close();
}

// ##### LOGIN TO BBAPI

function updateLogin($session_id,$session_user,$session_key, $session_buzz)
{
	$session_user = str_replace(' ','%20',$session_user); // replace spaces in username
	$server = 0;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_COOKIEJAR, "/tmp/cookies/".$session_id);
	curl_setopt($curl, CURLOPT_URL, $session_buzz."/login.aspx?login=".$session_user."&code=".$session_key);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "");
	        
	$reader = new XMLReader();
	if(!$reader->XML(curl_exec($curl)))
	{
		print "ERROR (ULogin.0): Cannot connect to BuzzerBeater<br>";
	}
	else
	{
		$server = 1;
	}
	
	curl_close($curl);
	
	$loggedin = false;// default value
	while ($reader->read())
	{
		if($reader->name=="loggedIn")
		{
			$loggedin = true; //set to allow processing
		}
	}
	$reader->close();
	
	if ($loggedin) return $server;
	else return 0;
}


// ##### REQUEST FILE FROM BBAPI
function updateRequest($request_page,$request_vars,$session_id,$session_buzz)
{
	// Now get the team player details
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIEFILE, "/tmp/cookies/".$session_id);
	curl_setopt($curl, CURLOPT_URL, $session_buzz."/".$request_page.".aspx?".$request_vars);
	curl_setopt($curl, CURLOPT_POSTFIELDS, "");
	
	//if($session_id="38596") echo $session_buzz."/".$request_page.".aspx?".$request_vars;
	
	$reader = new XMLReader();
	if(!$reader->XML(curl_exec($curl)))
	{
		print "ERROR (UReq.0): Cannot connect to BuzzerBeater<br>";
		curl_close($curl);
		return 0;
	}
	else
	{
		curl_close($curl);
                                
		return $reader;
	}
}
function _convert($content)
{ 
    if(!mb_check_encoding($content, 'UTF-8') 
        OR !($content === mb_convert_encoding(mb_convert_encoding($content, 'UTF-32', 'UTF-8' ), 'UTF-8', 'UTF-32'))) { 

        $content = mb_convert_encoding($content, 'UTF-8'); 

        if (mb_check_encoding($content, 'UTF-8')) { 
            // log('Converted to UTF-8'); 
        } else { 
            // log('Could not converted to UTF-8'); 
        } 
    } 
    return $content; 
} 
?>
