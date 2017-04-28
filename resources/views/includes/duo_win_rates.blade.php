<h4 style="margin: 0 0 0 0;">Ranked Duo Win Rates</h4>
<table style="border: 1px solid grey;">
	@php ($summoners = App::make("App\Http\Controllers\V1\ReportController")->getAllSummonerNames())
<tr style="border: 1px solid grey;">
	<th>Player</th>
	<th>Wins</th>
	<th>Losses</th>
	<th>Win %</th>
</tr>
	@foreach ($summoners as $summoner)
		@if ($summoner->name != $summonerName)
			@php ($duo = [$summoner->summonerId, $summonerId])
			@php ($stats = App::make("App\Http\Controllers\V1\ReportController")->getDuoStats($duo))
			@if ($stats)
				@if ($stats->wins + $stats->losses != 0)
					<tr class="highlight">
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
			@endif
		@endif
	@endforeach
</table>