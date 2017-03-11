<?php
// Right now this is only gathering match stats for Solo queue which is hard coded will eventually be able to select which queue you are gathering for, maybe leave it hard coded still deciding what I wanna do.
ini_set('max_execution_time', 18000); // Set max execution time to 5 hours
include('php-riot-api.php');
include('FileSystemCache.php');

//Connection to sqlite DB, temporary until I have a button to link to personal DBs
if (!($db = new PDO('sqlite:../data/league.db'))) {
    echo "<h2>Cannot connect to SQLite: league.db.</h2>";
    die();
}
else {
    $st = $db->prepare("SELECT * FROM summoners;");
    $st->execute();
    $summoners = $st->fetchAll();
    
    foreach ($summoners as $summoner) {
        $summoner_id = $summoner["champ_id"];
        
        //Query current list to match against API call
        $st = $db->prepare("SELECT * FROM match_details_new WHERE champ_id = $summoner_id;");
        $st->execute();
        $dbmatchlist = $st->fetchAll();
        
        ob_flush(); 
        flush();
        
        $db->beginTransaction();
        
        //Select Region for API call
        $region = new riotapi('na');
        $cache = new riotapi('na', new FileSystemCache('cache/'));

        //Run API call to php-riot-api.php to gather match history for by summoner_id
        $r = $region->getMatchHistorySolo($summoner_id);
        
        //First determine if the matchId is present in the DB, if not insert it into the details table as an entire JSON.
        try {
          foreach ($r["matches"] as $i => $match) {
              $matchexists = 0;
              
              foreach ($dbmatchlist as $a => $mid) {
                  if ($mid["match_id"] == $match["matchId"]) {
                      $matchexists = 1;
                      break;
                  }
              }
              if ($matchexists == 0) {
                  $match_det = $region->getMatch($match["matchId"]);
                  
                  $query = "INSERT INTO match_details_new VALUES (" .
                      "'" . $summoner_id . "'," .
                      "'" . $match["matchId"] . "'," .
                      "'" . serialize($match_det) . "'," .
                      "'" . $match["timestamp"] . "')";
                  $db->query($query);
                  
                  echo $summoner["summoner_name"] . " ($summoner_id) - " . $match["matchId"] . " added.<br>";
                  ob_flush(); 
                  flush();
              }
          } 
        } catch(Exception $e) {
          echo "Error: " . $e->getMessage();
        };
        
        $db->commit();
        ob_flush(); 
        flush();
    }
}

?>