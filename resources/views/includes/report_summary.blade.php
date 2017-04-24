<div>
	@php ($summonerId = App::make("App\Http\Controllers\V1\ReportController")->getSummonerIdByName($summonerName))
	<div><h4 style="margin: 0 0 0 0;">Solo Champion Statistics</h4>
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
	</div>
	
	<br>
	<div style="width:100%">
		<div style="float: left; width: 49%; ">
			<h4 style="margin: 0 0 0 0;">Duo Win Rates</h4>
			<table style="border: 1px solid grey;">
				@php ($summoners = App::make("App\Http\Controllers\V1\ReportController")->getAllSummonerNames())
			<tr style="border: 1px solid grey;">
				<th>Player</th>
				<th>Wins</th>
				<th>Losses</th>
				<th>Win Rate</th>
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
		</div>
	
		<div style="float: right; width: 49%; ">
			<h4 style="margin: 0 0 0 0;">Pentakills</h4>
			<table style="border: 1px solid grey;">
				@php ($pentas = App::make("App\Http\Controllers\V1\ReportController")->getMultiKills($summonerName))
			<tr style="border: 1px solid grey;">
				<th>Date</th>
				<th>Lane</th>
				<th>Level</th>
				<th>Champion</th>
				<th>K|D|A</th>
				<th>Dmg/Share</th>
				
			</tr>
				@foreach ($pentas as $penta)
					@php ($championName = App::make("App\Http\Controllers\V1\ReportController")->championName($penta->championId))
					@php ($role = App::make("App\Http\Controllers\V1\ReportController")->defineMatchRole($penta->role, $penta->lane))
					<tr class="highlight">
						<td>{{ date_create(date('r', $penta->matchCreation/1000))->format('m-d-y') }}</td>
						<td>{{ $role }}</td>
						<td>{{ $penta->champLevel }}</td>
						<td>{{ $championName }}</td>
						<td>{{ $penta->kills }}/{{ $penta->deaths }}/{{ $penta->assists }}</td>
						<td>{{ $penta->totalDamageDealtToChampions }} {{ $penta->pctTeamDamageDealtToChampions * 100 }}%</td>
					</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>