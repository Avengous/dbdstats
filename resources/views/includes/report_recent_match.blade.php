
	<div>
		@php ($stats = json_decode(App::make("App\Http\Controllers\V1\ReportController")->getRecentMatchStats($summonerName)->content()))
		<table>
		<h4>Recent Match</h4>
		{{-- json_encode($stats) --}}
			<tr>
				<td>Winner</td>
				<td>Champion</td>
				<td>Kills</td>
				<td>Deaths</td>
				<td>Assist</td>
				<td>KDA</td>
				<td>Champion Dmg</td>
			</tr>
			<tr>
				<td>
					@if ($stats->winner == 1) Victory
					@elseif ($stats->winner == 0) Defeat
					@else Remake
					@endif
				</td>
				<td>{{ $stats->championName }}</td>
				<td>{{ $stats->kills }}</td>
				<td>{{ $stats->deaths }}</td>
				<td>{{ $stats->assists }}</td>
				<td>{{ round(($stats->kills + $stats->assists)/$stats->deaths, 2) }}</td>
				<td>{{ $stats->totalDamageDealtToChampions }}</td>
			</tr>
		</table>
	</div>