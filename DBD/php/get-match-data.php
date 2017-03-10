<?php
// Right now this is only gathering match stats for Solo queue which is hard coded will eventually be able to select which queue you are gathering for, maybe leave it hard coded still deciding what I wanna do.

include('php-riot-api.php');
include('FileSystemCache.php');

//Connection to sqlite DB, temporary until I have a button to link to personal DBs
if (!($db = new PDO('sqlite:../data/league.db'))) {
    echo "<h2>Cannot connect to SQLite: league.db.</h2>";
    die();
}
else {
    $person = "oliver"; //this will be a POST when I get the code to specify which table we are accessing
    
    //Query current list to match against API call
    $st = $db->prepare("SELECT * FROM match_details_" . $person . ";");
    $st->execute();
    $dbmatchlist = $st->fetchAll();
    
    ob_flush(); 
    flush();
    $db->beginTransaction();
    
    //Temporary until I allow a button to send summoner values
    $summoner_id = 22638520; //id for Oliver Phillips

    //Select Region for API call
    $test = new riotapi('na');
    $testCache = new riotapi('na', new FileSystemCache('cache/'));

    try {
        //Run API call to php-riot-api.php to gather match history for by summoner_id
        $r = $test->getMatchHistorySolo($summoner_id);
        
        //First determine if the matchId is present in the DB, if not insert it into the details table as an entire JSON.
        foreach ($r["matches"] as $i => $match) {
            $matchexists = 0;
            
            foreach ($dbmatchlist as $a => $mid) {
                if ($mid["match_id"] == $match["matchId"]) {
                    $matchexists = 1;
                    break;
                }
            }
            if ($matchexists == 0) {
                $match_det = $test->getMatch($match["matchId"]);
                
                $query = "INSERT INTO match_details_" . $person . " VALUES ('" .
                    $match["matchId"] . "','" .
                    serialize($match_det) . "','" .
                    $match["timestamp"]
                    . "')";
                $db->query($query);
            }
        }
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    };
    
    //Commit changes
    $db->commit();
    ob_flush(); 
    flush();
}

//$r = $test->getSummonerByName($summoner_name);
//$r = $test->getSummoner($summoner_id);
//$r = $test->getSummoner($summoner_id,'masteries');
//$r = $test->getSummoner($summoner_id,'runes');
//$r = $test->getSummoner($summoner_id,'name');
//$r = $test->getStats($summoner_id);
//$r = $test->getStats($summoner_id,'ranked');
//$r = $test->getTeam($summoner_id);
//$r = $test->getLeague($summoner_id);
//$r = $test->getGame($summoner_id);
//$r = $test->getChampion();

?>