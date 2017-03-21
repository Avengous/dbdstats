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

Route::get('summoner/{summonerId}/match/all', function ($summonerId) {
	return App\Report::bysummonerid($summonerId)->get()->toArray();
});

Route::get('summoner/{summonerId}/match/earliest', function ($summonerId) {
	$matches = App\Report::bysummonerid($summonerId)->get()->toArray();
	return end($matches);
});

Route::get('summoner/{summonerId}/match/recent', function ($summonerId) {
	return App\Report::bysummonerid($summonerId)->get()->toArray()[0];
});

Route::get('summoner/{summonerId}/match/{matchId}', function ($summonerId, $matchId) {
	return App\Report::findmatch($summonerId, $matchId)->get()->toArray()[0];
});