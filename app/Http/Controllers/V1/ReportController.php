<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Queries;

class ReportController extends Controller
{	
	use Queries;
	
    public function matchDetails($summonerId, $matchId) {
		$record = $this->findUniqueMatchByIds($summonerId, $matchId);
        return Response($record);
    }
	
	public function getRecentMatchStats($summonerName){
		$summonerId = $this->summonerIdByName($summonerName);
		$matchId = $this->recentMatch($summonerId);
		return $this->matchDetails($summonerId, $matchId);
	}
	
	public function getSummonerStatsFromMatch($summonerName, $matchId) {
		$summonerId = $this->summonerIdByName($summonerName);
		return $this->matchDetails($summonerId, $matchId);
	}
}