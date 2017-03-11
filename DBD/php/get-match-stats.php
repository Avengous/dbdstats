<?php
if (isset($_POST)) {
    //Connection to sqlite DB, temporary until I have a button to link to personal DBs
    if (!($db = new PDO('sqlite:../data/league.db'))) {
        echo "<h2>Cannot connect to SQLite: league.db.</h2>";
        die();
    }
    else {
        $summoner = $_POST["summoner"];
        $dateFrom = $_POST["firstdate"];
        $dateTo = $_POST["seconddate"];
        
        $st = $db->prepare("SELECT champ_id FROM summoners WHERE summoner_name = '$summoner';");
        $st->execute();
        $summoner_id = $st->fetch()["champ_id"];
        
        $dateFrom = strtotime($dateFrom) * 1000;
        $dateTo = strtotime($dateTo) * 1000;
        
        $kills = array();
        $deaths = array();
        $assists = array();
        
        $avg_kills = 0;
        $avg_deaths = 0;
        $avg_assists = 0;
        $match = 0;

        //Query current list to match against API call
        $st = $db->prepare("SELECT * FROM match_details_new WHERE champ_id = $summoner_id AND timestamp >= $dateFrom AND timestamp <= $dateTo;");
        $st->execute();
        $dbmatchlist = $st->fetchAll();

        foreach($dbmatchlist as $i => $row) {
            $stats = unserialize($row["json"]);

            foreach ($stats["participantIdentities"] as $i => $stat) {
                if ($summoner_id == $stat["player"]["summonerId"]) {
                    $participant_id = $stat["participantId"] - 1;
                }
            }

            $kills[] = $stats["participants"][$participant_id]["stats"]["kills"];
            $deaths[] = $stats["participants"][$participant_id]["stats"]["deaths"];
            $assists[] = $stats["participants"][$participant_id]["stats"]["assists"];
        }

        $avg_kills = (count($kills) > 0 ? round(array_sum($kills) / count($kills), 1) : "N/A");
        $avg_deaths = (count($deaths) > 0 ? round(array_sum($deaths) / count($deaths), 1) : "N/A");
        $avg_assists = (count($assists) > 0 ? round(array_sum($assists) / count($assists), 1) : "N/A");
        
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
