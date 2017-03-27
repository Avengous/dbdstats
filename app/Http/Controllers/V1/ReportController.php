<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{	
    public function stats($summonerId, $matchId, $json = false) {
		if ($json) {
			$data = unserialize(collect($json)->toArray()[0]);
			echo $json;
		} else {
			$query = DB::table('match_details_new')->select('*')->where([['champ_id', '=', $summonerId], ['match_id', '=', $matchId]])->get();
			$data = unserialize(collect($query->toArray()[0])['json']);
		}
		
        foreach ($data['participantIdentities'] as $i => $id) {
            if ($id['player']['summonerId'] == $summonerId) {
                $stats = $data['participants'][$id['participantId']]['stats'];
				$stats['lane'] = $data['participants'][$id['participantId']]['timeline']['lane'];
				$stats['championId'] = $data['participants'][$i]['championId'];
            }
        }
		$stats['championName'] = $this->riot->champion()->championById($stats['championId'])->championStaticData->name;
		$stats['matchId'] = $data['matchId'];
        $stats['region'] = $data['region'];
        $stats['queueType'] = $data['queueType'];
        $stats['season'] = $data['season'];
        $stats['matchVersion'] = $data['matchVersion'];
        return Response($stats);
    }
	
	public function summonerIdByName($summonerName) {
		$query = DB::table('summoners')->select('champ_id')->where([['summoner_name', '=', $summonerName]]);
		$summonerId = $query->pluck('champ_id')[0];
		return $summonerId;
	}
	
	public function recentMatch($summonerId) {
        $query = DB::table('match_details_new')->select('*')->where([['champ_id', '=', $summonerId]])->latest()->pluck('match_id')[0];
		return $query;
	}
	
	public function getRecentMatchStats($summonerName){
		$summonerId = $this->summonerIdByName($summonerName);
		$matchId = $this->recentMatch($summonerId);
		return $this->stats($summonerId, $matchId);
	}
	
	public function getSummonerStatsFromMatch($summonerName, $matchId) {
		$summonerId = $this->summonerIdByName($summonerName);
		return $this->stats($summonerId, $matchId);
	}
	
	public function getMatchList($summonerName){
		$summonerId = $this->summonerIdByName($summonerName);
		$matchlist = $this->riot->matchlist()->matchlist($summonerId);
		return $matchlist;
	}
	
	public function getMatch($matchId) {
		return $this->riot->match()->match($matchId, $raw = true);
	}
	
	public function postSummonerMatches($summonerName) {
		/*
		Rate Limit:
		10 requests every 10 seconds 
		500 requests every 10 minutes

		This will limit requests to 5 per 10 seconds and 300 per 10 minutes.
		
            $table->integer('champ_id'); x 
            $table->integer('match_id'); x 
            $table->text('json');
            $table->date('timestamp');
		*/
		$summonerId = $this->summonerIdByName($summonerName);
		$matchlist = $this->getMatchList($summonerName);
		$matches = $matchlist->matches;
		$totalMatches = $matchlist->totalGames;
		
		foreach($matches as $match) {
			$matchId = $match->matchId;
			
			// Check if matchId exists in database.
			$query = DB::table('match_details_new')->select('*')->where([['champ_id', '=', $summonerId], ['match_id', '=', $matchId]])->count();
			if ($query == 0) {
				echo sprintf("[ADD] Adding match %s for summoner %s to DB"."<br>", $matchId, $summonerId);
				//$request = sprintf('https://na.api.riotgames.com/api/lol/NA/v2.2/match/%s?api_key=18d9e8e5-31d5-4ce6-a48c-9eb21e27117a', $matchId);
				//$raw_json = file_get_contents($request);
				//$json = serialize(json_decode($raw_json, true));
				//$timestamp = json_decode($raw_json, true)['matchCreation'];
				//sleep(2)
			} else {
				echo sprintf("[SKIP] Match %s exists for summoner %s"."<br>", $matchId, $summonerId);
			}
		}
		
		
	}
}