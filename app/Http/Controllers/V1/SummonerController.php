<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Traits\Queries;

class SummonerController extends Controller
{
	use Queries;
	
    public function postSummoner($id) {
		// Bug: If $summoner isn't ranked they cannot be added.
		$summoner 		= $this->riot->summoner()->info($id);
		$summonerId 	= $summoner->id;
		$name			= $summoner->name;
		$revisionDate	= $summoner->revisionDate;
		
		$league 		= $this->riot->league()->league($summoner);
		$solo			= $league[0]->entry($summonerId);
		$flex			= $league[1]->entry($summonerId);
		
		$soloTier		= $league[0]->tier;
		$soloDivision   = $solo->division;
		$soloWins		= $solo->wins;
		$soloLosses		= $solo->losses;
		$soloLP			= $solo->leaguePoints;
		
		$flexTier 		= $league[1]->tier;
		$flexDivision	= $flex->division;
		$flexWins		= $flex->wins;
		$flexLosses		= $flex->losses;
		$flexLP			= $flex->leaguePoints;
		
		$data			= [
				'summonerId' 	=> $summonerId,
				'name'			=> $name,
				'soloTier'		=> $soloTier,
				'soloDivision'	=> $soloDivision,
				'soloWins'		=> $soloWins,
				'soloLosses'	=> $soloLosses,
				'soloLeaguePoints' => $soloLP,
				'flexTier'		=> $flexTier,
				'flexDivision'	=> $flexDivision,
				'flexWins'		=> $flexWins,
				'flexLosses'	=> $flexLosses,
				'flexLeaguePoints' => $flexLP,
				'revisionDate'	=> $revisionDate];
				
		if($this->summonerExist($summonerId)) {
			DB::table('summoners')->where('summonerId', $summonerId)->update($data);
		}
		else {
			DB::table('summoners')->insert($data);
		}
	}
	
	public function summonerExist($summonerId) {
		$query = DB::table('summoners')->select('*')->where([['summonerId', '=', $summonerId]])->count();
		if ($query == 0) {
			return False;
		} else {
			return True;
		}
	}
	
	public function soloQueueRank($summonerName) {
		$record = $this->summonerByName($summonerName);
		$ranks = [
		'solo' => sprintf("%s %s %s LP", $record->pluck('soloTier')[0], $record->pluck('soloDivision')[0], $record->pluck('soloLeaguePoints')[0]),
		'flex' => sprintf("%s %s %s LP", $record->pluck('flexTier')[0], $record->pluck('flexDivision')[0], $record->pluck('flexLeaguePoints')[0])
		];
		return $ranks;
	}
	
	public function decimationCount($summonerName) {
		$record = $this->summonerByName($summonerName);
		$summonerId = $record->pluck('summonerId')[0];
		$totalDeathCount = $this->totalDeathCount($summonerId);
		$decimationCount = $totalDeathCount;
		$decimationCount += $record->pluck('soloLosses')[0];
		$decimationCount += $record->pluck('soloWins')[0];
		$decimationCount += $record->pluck('flexLosses')[0];
		$decimationCount += $record->pluck('flexWins')[0];
		return $decimationCount;
	}
}
