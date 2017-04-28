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
						<th>Lane</th>
						<th>KDA</th>
						<th>Played</th>
						<th>Win %</th>
						<th>Weight</th>
					</tr>
					
					@if ($season == 6)
						@php ($queueType = 'DYNAMIC')
					@else
						@php ($queueType = 'SOLO')
					@endif

					
					@if ($league == 'SOLO')
						@php ($lanes = App::make("App\Http\Controllers\V1\ReportController")->getLaneStats($summonerId, $queueType, $season))
					@else
						@if (in_array($season, [4, 5]))
							@php ($lanes = App::make("App\Http\Controllers\V1\ReportController")->getLaneStats($summonerId, 'TEAM', $season))
						@elseif ($season == 'ALL')
							@php ($lanes = App::make("App\Http\Controllers\V1\ReportController")->getLaneStats($summonerId, 'ALLTEAM', $season))
						@elseif ($season == 6)
							@php ($lanes = App::make("App\Http\Controllers\V1\ReportController")->getLaneStats($summonerId, $queueType, $season))
						@else
							@php ($lanes = App::make("App\Http\Controllers\V1\ReportController")->getLaneStats($summonerId, 'FLEX', $season))
						@endif
					@endif
					
					@foreach (['TOP', 'JUNGLE', 'MIDDLE', 'ADC', 'SUPPORT'] as $lane)
						<tr class="highlight">
							<td>{{ $lane }}</td>
							@if ($lanes[$lane]->avgDeaths == 0)
								<td>{{ round(($lanes[$lane]->avgKills + $lanes[$lane]->avgAssists), 2) }}</td>
							@else
								<td>{{ round(($lanes[$lane]->avgKills + $lanes[$lane]->avgAssists)/$lanes[$lane]->avgDeaths, 2) }}</td>
							@endif
							<td>{{$lanes[$lane]->totalGames}}</td>
							@if ($lanes[$lane]->totalGames == 0)
								<td>0</td>
							@else
								<td>{{round($lanes[$lane]->wins/$lanes[$lane]->totalGames, 2)*100}}%</td>
							@endif
							<td></td>
						</tr>
					@endforeach
				</table>
			</td>
		@endforeach
	</tr>
</table>