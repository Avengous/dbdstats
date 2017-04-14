<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Queries;

class MatchController extends Controller
{
	use Queries;
	
	public function getMatchList($summonerName, $beginIndex=null, $endIndex=null){
		$summonerId = $this->summonerIdByName($summonerName);
		$matchlist = $this->riot->matchlist()->matchlist($summonerId, null, null, null, $beginIndex, $endIndex, null, null);
		return $matchlist;
	}
	
	public function getMatch($matchId, $timeline=false) {
		return $this->riot->match()->match($matchId, $timeline);
	}
	
	public function verifySummonerMatchList($summonerName, $allMatches=false, $auto=false) {
		set_time_limit(36000);
		$summonerId = $this->summonerIdByName($summonerName);
		if ($allMatches) {
			$matchlist = $this->getMatchList($summonerName);
		} else {
			
			$matchlist = $this->getMatchList($summonerName, 0, 50);
		}
		$matches = $matchlist->matches;
		$totalMatches = $matchlist->totalGames;
		$totalSkipped = 0;
		$totalAdded = 0;
		$responseMsg = "";
		foreach($matches as $match) {
			$data = [];
			$data['matchId'] = $match->matchId;
			
			if (!$this->matchExists($data['matchId'], $summonerId)) {
				$responseMsg.=sprintf("[ADD] Adding match %s for summoner %s to DB"."<br>", $data['matchId'], $summonerId);
				$matchDetails = $this->getMatch($data['matchId']);
				$teamDmgToChamps = 0;
				$teamDmgTaken = 0;
				$teamGold = 0;
				$teamKills = 0;
				$teamDeaths = 0;
				$data['summonerId'] = $summonerId;
				$data['matchVersion'] = $matchDetails->matchVersion;
				$data['platformId'] = $matchDetails->platformId;
				$data['mapId'] = $matchDetails->mapId;
				$data['matchCreation'] = $matchDetails->matchCreation;
				$data['matchMode'] = $matchDetails->matchMode;
				$data['matchDuration'] = $matchDetails->matchDuration;
				$data['season'] = $matchDetails->season;
				$data['queueType'] = $matchDetails->queueType;
				$data['matchType'] = $matchDetails->matchType;

				// Finds participantId for $summonerId
				foreach($matchDetails->participantIdentities as $identity) {
					if ($identity->player['summonerId'] == $data['summonerId']) {
						$participantId = $identity->participantId;
						$data['matchHistoryUri'] = $identity->player['matchHistoryUri'];
						$data['teamId'] = $matchDetails->participant($participantId)->teamId;
					}
				}
				
				// Finds sum of team stats for participants on the same team as $summonerId
				foreach($matchDetails->participantIdentities as $identity) {
					if ($matchDetails->participant($identity->participantId)->teamId == $data['teamId']) {
						$teamDmgToChamps += $matchDetails->participant($identity->participantId)->stats->totalDamageDealtToChampions;
						$teamDmgTaken += $matchDetails->participant($identity->participantId)->stats->totalDamageTaken;
						$teamGold += $matchDetails->participant($identity->participantId)->stats->goldEarned;
						$teamKills += $matchDetails->participant($identity->participantId)->stats->kills;
						$teamDeaths += $matchDetails->participant($identity->participantId)->stats->deaths;
					}
				}
				
				$data['winner'] = $matchDetails->participant($participantId)->stats->winner;
				// Sets remake as 2 when matchDuration <= 300 seconds
				if ($data['matchDuration'] <= 300) {
					$data['winner'] = 2;
				}
				
				$data['championId'] = $matchDetails->participant($participantId)->championId;
				$data['wardsKilled'] = $matchDetails->participant($participantId)->stats->wardsKilled;
				$data['wardsPlaced'] = $matchDetails->participant($participantId)->stats->wardsPlaced;
				$data['largestMultiKill'] = $matchDetails->participant($participantId)->stats->largestMultiKill;
				$data['champLevel'] = $matchDetails->participant($participantId)->stats->champLevel;
				$data['goldEarned'] = $matchDetails->participant($participantId)->stats->goldEarned;
				$data['minionsKilled'] = $matchDetails->participant($participantId)->stats->minionsKilled;
				$data['neutralMinionsKilled'] = $matchDetails->participant($participantId)->stats->neutralMinionsKilled;
				$data['kills'] = $matchDetails->participant($participantId)->stats->kills;
				$data['deaths'] = $matchDetails->participant($participantId)->stats->deaths;
				$data['assists'] = $matchDetails->participant($participantId)->stats->assists;
				$data['totalDamageDealtToChampions'] = $matchDetails->participant($participantId)->stats->totalDamageDealtToChampions;
				$data['totalDamageTaken'] = $matchDetails->participant($participantId)->stats->totalDamageTaken;
				$data['csDiffPerMinDeltas'] = serialize($matchDetails->participant($participantId)->timeline->csDiffPerMinDeltas);
				$data['role'] = $matchDetails->participant($participantId)->timeline->role;
				$data['lane'] = $matchDetails->participant($participantId)->timeline->lane;
				
				if ($teamDmgToChamps == 0) {
					$data['pctTeamDamageDealtToChampions'] = 0;
				} else {
					$data['pctTeamDamageDealtToChampions'] = round($data['totalDamageDealtToChampions']/$teamDmgToChamps, 2);
				}
				
				if ($teamDmgTaken == 0) {
					$data['pctTeamDamageTaken'] = 0;
				} else {
					$data['pctTeamDamageTaken'] = round($data['totalDamageTaken']/$teamDmgTaken, 2);
				}
				
				$data['pctTeamGoldShare'] = round($data['goldEarned']/$teamGold, 2);
				
				if ($teamKills == 0) {
					$data['pctKillParticipation'] = 0;
				} else {
					$data['pctKillParticipation'] = round(($data['kills']+$data['assists'])/$teamKills, 2);
				}
				
				if ($teamDeaths == 0) {
					$data['pctTeamDeaths'] = 0;
				} else {
					$data['pctTeamDeaths'] = round($data['deaths']/$teamDeaths, 2);
				}

				DB::table('match_details')->insert($data);
				$totalAdded++;
				sleep(2.25);
			} else {
				$totalSkipped++;
			}
		}
		$responseMsg.=sprintf("[COMPLETE] Matches Skipped: %s, Added: %s"."<br>", $totalSkipped, $totalAdded);
		$this->multiSummonerWinRate($summonerId);
		if !($auto){
			return redirect()->back()->with('message', $responseMsg);
		}
	}
	
	public function matchExists($matchId, $summonerId) {
		$query = DB::table('match_details')->select('*')->where([['summonerId', '=', $summonerId], ['matchId', '=', $matchId]])->count();
		if ($query == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	public function multiSummonerWinRate($summonerId, $queueType=['TEAM_BUILDER_RANKED_SOLO', 'RANKED_SOLO_5x5', 'TEAM_BUILDER_DRAFT_RANKED_5x5']) {
		$summoners = $this->summoners(['summonerId']);
		foreach ($summoners as $summoner) {
			if ($summoner->summonerId != $summonerId) {
				$data = [];
				$data['duoId'] = $summonerId + $summoner->summonerId;
				$data['summonerIds'] = serialize([$summonerId, $summoner->summonerId]);
				$data['wins'] = 0;
				$data['losses'] = 0;
				$matches = $this->sharedMatchWinRateBySummonerIds([$summonerId, $summoner->summonerId], $queueType);
				foreach ($matches as $match) {
					if ($match->winner == 0) {
						$data['losses']++;
					} elseif ($match->winner == 1) {
						$data['wins']++;
					}
				}
				$data['winrate'] = round($data['wins']/($data['wins'] + $data['losses']), 2);
				if($this->recordExists('duo_stats', [['duoId', '=', $data['duoId']]])) {
					DB::table('duo_stats')->where('duoId', $data['duoId'])->update($data);
				}
				else {
					DB::table('duo_stats')->insert($data);
				}	
			}
		}
	}
}
