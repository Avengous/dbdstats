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
						<th>Win %</th>
					</tr>
					
					@if ($league == 'SOLO')
						@php ($champions = App::make("App\Http\Controllers\V1\ReportController")->getChampionsStats($summonerId, 'SOLO', $season, 5))
					@elseif ($league == 'FLEX')
						@if (in_array($season, [4, 5]))
							@php ($champions = App::make("App\Http\Controllers\V1\ReportController")->getChampionsStats($summonerId, 'TEAM', $season, 5))
						@elseif ($season == 'All')
							@php ($champions = App::make("App\Http\Controllers\V1\ReportController")->getChampionsStats($summonerId, 'ALLTEAM', $season, 5))
						@else
							@php ($champions = App::make("App\Http\Controllers\V1\ReportController")->getChampionsStats($summonerId, 'FLEX', $season, 5))
						@endif
					@endif
					
					@foreach ($champions as $champ)
						@php ($champName = App::make("App\Http\Controllers\V1\ReportController")->championName($champ->championId))
						<tr class="highlight">
							<td style="white-space: nowrap">{{$champName}}</td>
							@if ($champ->avgDeaths == 0)
								<td style="color: red;">{{round(($champ->avgKills + $champ->avgAssists), 2)}}*</td>
							@else
								<td>{{round(($champ->avgKills + $champ->avgAssists)/$champ->avgDeaths, 2)}}</td>
							@endif
							<td>{{$champ->count}}</td>
							<td>{{round($champ->wins/$champ->count, 2)*100}}%</td>
						</tr>
					@endforeach
				</table>
			</td>
		@endforeach
	</tr>
</table>