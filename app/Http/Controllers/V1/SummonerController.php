<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SummonerController extends Controller
{
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
				
		//BUG Overwrites every entry in database. GEE GEE
		if($this->summonerExist($summonerId)) {
			DB::table('summoners')->update($data);
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
}
