<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', function () {
    return redirect('/summoner/Whambulance');
});

Route::get('summoner/{summonerName}', function($summonerName = 'Whambulance')
{
	return view('pages.report', ['summonerName' => $summonerName]);
});

//summoner/23486636/match/2447063750/stats
Route::get('summoner/{summonerId}/match/{matchId}/stats', 'V1\ReportController@stats');

//Add summoner matches to DB
Route::get('summoner/{summonerName}/matchlist', 'V1\MatchController@verifySummonerMatchList');

//Add summoner to DB
Route::get('summoner/{summonerId}/update', 'V1\SummonerController@postSummoner');

//Update champion list 
Route::get('staticdata/champions/update', 'V1\StaticDataController@updateChampionList');

//Test Route
Route::get('/test', 'V1\ReportController@getAllSummonerNames');