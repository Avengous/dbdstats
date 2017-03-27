<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MatchController extends Controller
{
	public function verifySummonerMatchList($summonerName) {
		/*
		Rate Limit:
		10 requests every 10 seconds 
		500 requests every 10 minutes

		This will limit requests to 5 per 10 seconds and 300 per 10 minutes.
		
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
