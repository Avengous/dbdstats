<div>
	@php ($summonerId = App::make("App\Http\Controllers\V1\ReportController")->getSummonerIdByName($summonerName))
	@php ($seasons = [7,6,5,4, 'All'])
	<div>
		<h4 style="margin: 0 0 0 0;">Solo Champion Win Rates</h4>
		@php ($league = 'SOLO')
		@include('includes.champion_win_rates')
	</div>
	<br>
	<div>
		<h4 style="margin: 0 0 0 0;">Flex/Team Champion Win Rates</h4>
		@php ($league = 'FLEX')
		@include('includes.champion_win_rates')
	</div>
	<br>
	<div>
		<h4 style="margin: 0 0 0 0;">Solo Lane Win Rates</h4>
		@php ($league = 'SOLO')
		@include('includes.lane_win_rates')
	</div>
	<div>
		<h4 style="margin: 0 0 0 0;">Flex/Team Lane Win Rates</h4>
		@php ($league = 'FLEX')
		@include('includes.lane_win_rates')
	</div>
	<br>
	<div style="width:100%">
		<div style="float: left; width: 49%; ">
			@include('includes.duo_win_rates')
		</div>
	
		<div style="float: right; width: 49%; ">
			@include('includes.ranked_pentakills')
		</div>
	</div>
</div>