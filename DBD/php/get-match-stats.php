<?php
if (isset($_POST)) {
    //Connection to sqlite DB, temporary until I have a button to link to personal DBs
    if (!($db = new PDO('sqlite:../data/league.db'))) {
        echo "<h2>Cannot connect to SQLite: league.db.</h2>";
        die();
    }
    else {
        $dateFrom = $_POST["firstdate"];
        $dateTo = $_POST["seconddate"];
        
        $dateFrom = strtotime($dateFrom) * 1000;
        $dateTo = strtotime($dateTo) * 1000;
        
        $kills = array();
        $deaths = array();
        $assists = array();
        
        $avg_kills = 0;
        $avg_deaths = 0;
        $avg_assists = 0;

        $match = 0;

        $person = "oliver"; //this will be a POST when I get the code to specify which table we are accessing

        //Query current list to match against API call
        $st = $db->prepare("SELECT * FROM match_details_" . $person . " WHERE timestamp >= " . $dateFrom . " AND timestamp <= " . $dateTo . ";");
        $st->execute();
        $dbmatchlist = $st->fetchAll();

        //Temporary until I allow a button to send summoner values
        $summoner_id = 22638520; //id for Oliver Phillips

        foreach($dbmatchlist as $i => $row) {
            $stats = unserialize($row["json"]);

            for ($x = 0; $x <= 10; $x++) {
                if ($summoner_id == $stats["participantIdentities"][$x]["player"]["summonerId"]) {
                    $participant_id = $stats["participantIdentities"][$x]["participantId"] - 1;
                }
            }

            $kills[] = $stats["participants"][$participant_id]["stats"]["kills"];
            $deaths[] = $stats["participants"][$participant_id]["stats"]["deaths"];
            $assists[] = $stats["participants"][$participant_id]["stats"]["assists"];
        }

        $avg_kills = array_sum($kills) / count($kills);
        $avg_deaths = array_sum($deaths) / count($deaths);
        $avg_assists = array_sum($assists) / count($assists);
        
        $avg_kills = round($avg_kills, 1);
        $avg_deaths = round($avg_deaths, 1);
        $avg_assists = round($avg_assists, 1);
        
        echo json_encode(
            array(
                "avg_kills" => $avg_kills,
                "avg_deaths" => $avg_deaths,
                "avg_assists" => $avg_assists,
                "date_from" => $dateFrom
            ), true
        );
    }
}
?>