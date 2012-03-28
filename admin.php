<?php include("header.php"); ?>

<div id="xsnazzy">
    <b class="xtop"><b class="xb1"></b><b class="xb2"></b><b class="xb3"></b><b class="xb4"></b></b>
    <div class="xboxcontent">
    <p>&nbsp;</p>
    
   
    <?php 
    if($_SESSION['id']==38596) //admin team id (in this case only judgenik)
    {
        $Count10mins = 0;
                 $Count24 = 0;
                $thirtyCount = 0;
                $fiveCount = 0;
                $query = "SELECT timestamp FROM user";
                $items = $mysqli->query($query) or die("League Query Failed!");
                while ($return = mysqli_fetch_array($items))
                {
                        if ((date('U')-$return['timestamp'])<600)
                        {
                                $Count10mins++;
                        }
                        if ((date('U')-$return['timestamp'])<1800)
                        {
                                $thirtyCount++;
                        }
                        if ((date('U')-$return['timestamp'])<18000)
                        {
                                $fiveCount++;
                        }
                        if ((date('U')-$return['timestamp'])<86000)
                        {
                                $Count24++;
                        }
                }
                echo "
                <h2>Users Logged In</h2>
                <h3>$Count10mins in the last 10 mins</h3>
                <h3>$thirtyCount in the last 30 mins</h3>
                <h3>$fiveCount in the last 5 hours</h3>
                <h3>$Count24 in the last 24 hours</h3>";
                
                $limit = 50;
                echo "<br/>
                    <h2>Last $limit Users</h2>
                    <table><thead>
                    <th>id</th>
                    <th>username</th>
                    <th>timestamp</th>
                    <th>language</th>
                    <th>theme</th>
                    <th>Login</th>
                    <th>Password</th>
                    <th>IP</th>
                    </thead><tbody>";
                $query = "
                    SELECT U.id, U.user, U.timestamp, U.language, U.theme, L.name, TR.ip, TR.team_id
                    FROM user U
                    INNER JOIN languages L ON U.language = L.id
                    LEFT OUTER JOIN user_out_tracking TR
                    ON U.id = TR.team_id
                    GROUP BY U.id
                    ORDER BY timestamp DESC LIMIT ".$limit;
                $items = $mysqli->query($query) or die("League Query Failed!".mysqli_error($mysqli));
                while ($return = mysqli_fetch_array($items))
                {
                    echo "<tr>";
                    echo "<td>".$return['id']."</td>";
                    echo "<td>".$return['user']."(".$return['team_id'].")</td>";
                    echo "<td style='width:130px'>".date('y.m.d H:i:s',$return['timestamp'])."</td>";
                    echo "<td>".$return['name']."</td>";
                    echo "<td>".$return['theme']."</td>";
                    echo "<td>".$return['ip']."</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
                $mysqli->close();
        }
        else
        { ?>
            do you know how to log in? go do that <a href="bblogin.php">here</a>   
            <?php
        }
    ?>
<p>&nbsp;</p>
        </div>
        <b class="xbottom"><b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b></b>
</div>
<?php include("footer.php");
?>
