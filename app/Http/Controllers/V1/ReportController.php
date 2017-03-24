<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{	
    public function stats($summonerId, $matchId) {
        $query = DB::table('match_details_new')->select('*')->where([['champ_id', '=', $summonerId], ['match_id', '=', $matchId]])->get();
        $data = unserialize(collect($query->toArray()[0])['json']);

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
		$matchlist = $this->riot->matchlist()->matchlist($summonerId)->matches;
		return $matchlist;
	}
	
	public function getMatch($matchId) {
		return $this->riot->match()->match($matchId);
	}
}