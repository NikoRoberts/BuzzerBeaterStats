<?php include("functions.php"); ?>
<?php include("func_update.php"); ?>
<?php
if($_SESSION['defaultSeason'])
{
	$mysqli = new mysqli('DATABASE SERVER','DATABASE USERNAME','DATABASE PASSWORD','DATABASE NAME');
    $mysqli->set_charset("utf8");
    if ($_POST['forced']==1)
    {

            $query =
            "SELECT
            lpd.timestamp
            FROM
            latest_posting lpd
            WHERE
            type='league'
            AND lpd.id='".addslashes($league_id)."'
            ";
            $items = $mysqli->query($query) or die("Database Query Failed!");
            $lpd = mysqli_fetch_array($items);
            $difference = date('U') - $lpd['timestamp'];
            //only accept a forced update every minute
            if ($difference > 600)
            {
                    if($_SESSION['id']==38596) echo "Accepting forced update<br>";
                    $session_update="now"; 
            }
            else
            {
                $fail = array("somearray" => array(6 => 5, 13 => 9, "a" => 42));
                echo json_encode("fail".(10-(round($difference/60,2)))." minutes.</center>");
            }
    }
    else
    {
            $query =
            "SELECT
            lpd.timestamp
            FROM
            latest_posting lpd
            WHERE
            type='league'
            AND lpd.id='".addslashes($league_id)."'
            ";
            $items = $mysqli->query($query) or die("Database Query Failed!");
            $lpd = mysqli_fetch_array($items);
            $difference = date('U') - $lpd['timestamp'];
            if ($difference > $_SESSION['refreshTime'])
            {
                    $session_update="now"; // greater than 24 hours then refresh
                    if($_SESSION['id']==38596) echo "LEAGUE: Longer than 24 hours so updating..<br>";
            }
            else $session_update="later";
    }
    $mysqli->close();
    updateLeagueStats($league_id,$season_id,$session_update,$session_id,$session_username,$session_key,$_SESSION['buzzWebsite']); // update if logged in
}
?>
