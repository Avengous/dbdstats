<div>
	@php ($summonerId = App::make("App\Http\Controllers\V1\ReportController")->getSummonerIdByName($summonerName))
	<div><h4>Solo Champion Statistics</h4>
		@php ($seasons = [7,6,5,4, 'All'])
		<table style="margin-bottom: 0px;">
			<tr>
				@foreach ($seasons as $season)
					<th style="text-align: center; padding: 0 0  0; border: 1px solid grey;">
						<strong>Season {{$season}}</strong>
					</th>
				@endforeach
			</tr>
			<tr>
				@foreach ($seasons as $season)
					<td style="padding: 0 0 0 0; border: 1px solid grey;">
						<table style="margin: 0 0 0 0">
							<tr>
								<th>Champion</th>
								<th>KDA</th>
								<th>Played</th>
								<th>Win Rate</th>
							</tr>
							@php ($champions = App::make("App\Http\Controllers\V1\ReportController")->getChampionsStats($summonerId, 'SOLO', $season, 5))
							@foreach ($champions as $champ)
								@php ($champName = App::make("App\Http\Controllers\V1\ReportController")->championName($champ->championId))
								<tr>
									<td style="white-space: nowrap">{{$champName}}</td>
									<td>{{round(($champ->avgKills + $champ->avgAssists)/$champ->avgDeaths, 2)}}</td>
									<td>{{$champ->count}}</td>
									<td>{{round($champ->wins/$champ->count, 2)*100}}%</td>
								</tr>
							@endforeach
						</table>
					</td>
				@endforeach
			</tr>
		</table>
	</div>
	
	<hr/>
	
	<table style="float: left; width: 50%; padding: 0 0 0 0; border: 1px solid grey;">
		@php ($summoners = App::make("App\Http\Controllers\V1\ReportController")->getAllSummonerNames())
	<tr style="padding: 0 0 0 0; border: 1px solid grey;">
		<th><strong>Duo Win Rates<strong></th>
		<th/><th/><th/>
	</tr>
	<tr>
		<th>Player</th>
		<th>Wins</th>
		<th>Losses</th>
		<th>Win Rate</th>
	</tr>
		@foreach ($summoners as $summoner)
			@if ($summoner->name != $summonerName)
				@php ($duo = [$summoner->summonerId, $summonerId])
				@php ($stats = App::make("App\Http\Controllers\V1\ReportController")->getDuoStats($duo)[0])
				<tr>
					<td>{{ $summoner->name }}</td>
					<td>{{ $stats->wins }}</td>
					<td>{{ $stats->losses }}</td>
					@if ($stats->wins + $stats->losses != 0)
						<td>{{ $stats->winrate*100 }}%</td>
					@else 
						<td>N/A</td>
					@endif
				</tr>
			@endif
		@endforeach
	</table>
</div>