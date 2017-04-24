<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait Queries {
	
    protected function summonerIdByName($summonerName) {
		$query = DB::table('summoners')->select('summonerId')->where([['name', '=', $summonerName]]);
		$summonerId = $query->pluck('summonerId')[0];
		return $summonerId;
    }
	
    protected function summonerNameById($summonerId) {
		$query = DB::table('summoners')->select('name')->where([['summonerId', '=', $summonerId]]);
		$name = $query->pluck('name')[0];
		return $name;
    }
	
	protected function summonerByName($summonerName, $cols='*') {
		$query = DB::table('summoners')->select($cols)->where([['name', '=', $summonerName]]);
		return $query;
	}
	
	protected function recentMatches($summonerId, $matchCount=1) {
		$query = DB::table('match_details')->select('*')->where([['summonerId', '=', $summonerId]])->orderBy('matchCreation', 'desc')->take($matchCount);
		return $query;
	}
	
	protected function findUniqueMatchByIds($summonerId, $matchId) {
		$query = DB::table('match_details')->select('*')->where([['summonerId', '=', $summonerId], ['matchId', '=', $matchId]]);
		return $query;
	}
	
	protected function findRecordCount($table, $query) {
		$query = DB::table($table)->select('*')->where($query)->count();
	}
	
	protected function championNameById($championId) {
		$query = DB::Table('champions')->select('championName')->where([['championId', '=', $championId]])->pluck('championName')[0];
		return $query;
	}
	
	protected function totalDeathCount($summonerId) {
		return DB::Table('match_details')->select('deaths')->where([['summonerId', $summonerId]])->sum('deaths');
	}
	
	protected function sharedMatchWinRateBySummonerIds($summonerIds, $queueType) {
		$idString = "'".implode("','",$summonerIds);
		$queueString = "'".implode("','",$queueType);
		$query = DB::select(sprintf("SELECT matchId, winner FROM match_details a INNER JOIN ( SELECT matchId AS mid FROM match_details WHERE summonerId = %s ) b ON a.matchId = b.mid INNER JOIN ( SELECT matchId as countmid, count(id) AS count FROM match_details GROUP BY matchId HAVING count = 2 ) c ON a.matchId = c.countmid WHERE a.summonerId = %s AND a.queuetype IN (%s');", $summonerIds[0], $summonerIds[1], $queueString));
		return $query;
	}
	
	protected function summoners($cols='*') {
		return DB::Table('summoners')->select($cols)->get();
	}
	
	protected function championPlayedCount($summonerId, $queues, $seasons, $count) {
		$query = DB::table('match_details')
			->select(DB::raw('championId, sum(winner) as wins, COUNT(championId) as count, AVG(kills) as avgKills, AVG(deaths) as avgDeaths, AVG(assists) as avgAssists'))
				->where('summonerId', $summonerId)
				->whereIn('queueType', $queues)
				->whereIn('season', $seasons)
				->groupBy('championId')
				->orderBy('count', 'desc')
				->take($count)->get();
		return $query;
	}
	
	protected function recordExists($table, $where) {
		$count = DB::table($table)->select('*')->where($where)->count();
		if ($count == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	protected function decimated($summonerId) {
		return DB::table('summoners')->select('decimated')->where([['summonerId', $summonerId]])->get();
	}
	
	protected function tableSelectWhere($table, $select, $where) {
		return DB::table($table)->select($select)->where($where)->get();
	}
	
	protected function rolePlayedCount($summonerId, $queues, $seasons) {
		$query = DB::table('match_details')
			->select(DB::raw('lane, role, sum(winner) as wins, COUNT(winner) as totalGames, AVG(kills) as avgKills, AVG(deaths) as avgDeaths, AVG(assists) as avgAssists'))
				->where('summonerId', $summonerId)
				->whereIn('queueType', $queues)
				->whereIn('season', $seasons)
				->groupBy(['role', 'lane'])
				->orderBy('totalGames', 'desc')
				->get();
		return $query;
	}
}