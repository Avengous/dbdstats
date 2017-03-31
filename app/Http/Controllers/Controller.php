<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use LeagueWrap\Api;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	public $riot;

	public function __construct() {
		// Disable query log as large queries utilize too much memory.
		DB::disableQueryLog();
		
		// LeagueWrap API Object
		$this->riot = new Api('18d9e8e5-31d5-4ce6-a48c-9eb21e27117a');
		$this->riot->attachStaticData();
		
		// Uncomment in non-windows environments
		//$this->riot->remember(3600);
		//$this->riot->limit(10, 10);
		//$this->riot->limit(500, 600);
	}
}
