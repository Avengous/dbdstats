<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StaticDataController extends Controller
{
    public function updateChampionList() {
		$champions = $this->riot->champion()->all();
		foreach ($champions as $champion) {
			$championId = $champion->championStaticData->id;
			$championName = $champion->championStaticData->name;
			$countOfChampion = DB::table('champions')->select('*')->where(['championId', '=', $championId])->count();
			if ($countOfChampion < 1) {
				DB::table('champions')->insert(['championId' => $championId, 'championName' => $championName]);
			}
		}
	}
}
