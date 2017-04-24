
	<div>
		@php ($matches = App::make("App\Http\Controllers\V1\ReportController")->recentMatchDetails($summonerName))
		<table style="padding: 0 0 0 0; border: 1px solid grey;">
		<h4 style="margin: 0 0 0 0;">Recent Matches</h4>
		
			<tr style="border: 1px solid grey;">
				<th style="width:100px; max-width:100px; display:inline-block;">Date</th>
				<th>Duration</th>
				<th>Winner</th>
				<th>Queue</th>
				<th>Lane</th>
				<th>Level</th>
				<th>Champion</th>
				<th>K|D|A</th>
				<th>KDA</th>
				<th>KP</th>
				<th>DP</th>
				<th>CS</th>
				<th>CS Diff@10</th>
				<th>CS Diff@20</th>
				<th>Gold / Share</th>
				<th>Dmg / Share</th>
				<th>DmgTaken / Share</th>
				<th>Details</th>
			</tr>
			@foreach ($matches as $match)
				@php ($csDiffPerMinDeltas = unserialize($match->csDiffPerMinDeltas))
				@php ($role = App::make("App\Http\Controllers\V1\ReportController")->defineMatchRole($match->role, $match->lane))
				@php ($championName = App::make("App\Http\Controllers\V1\ReportController")->championName($match->championId))
				@php ($matchDuration = App::make("App\Http\Controllers\V1\ReportController")->formatSeconds($match->matchDuration))
				
				@if (empty($csDiffPerMinDeltas['zeroToTen']))
					@php ($zeroToTen = 0)
				@else
					@php ($zeroToTen = round($csDiffPerMinDeltas['zeroToTen'], 2))
				@endif
				
				@if (empty($csDiffPerMinDeltas['tenToTwenty']))
					@php ($tenToTwenty = 0)
				@else
					@php ($tenToTwenty = round($csDiffPerMinDeltas['tenToTwenty'], 2))
				@endif
				<tr class="highlight">
					<td>{{ date_create(date('r', $match->matchCreation/1000))->format('m-d-y') }}</td>
					<td>{{ $matchDuration }}</td>
					<td>
						@if ($match->winner == 0)
							Defeat
						@elseif ($match->winner == 1)
							Victory
						@else
							Remake
						@endif
					</td>
					<td>
						@if ($match->queueType == 'TEAM_BUILDER_RANKED_SOLO')
							Solo
						@elseif ($match->queueType == 'RANKED_FLEX_SR')
							Flex
						@endif
					</td>
					<td>{{ $role }}</td>
					<td>{{ $match->champLevel }}</td>
					<td>{{ $championName }}</td>
					<td>
						{{ $match->kills }}/{{ $match->deaths }}/{{ $match->assists }}
					</td>
					@if ($match->deaths == 0)
						<td style="color: red;">{{$match->kills+$match->assists}}*</td>
					@else 
						<td>{{ round(($match->kills+$match->assists)/$match->deaths, 2)}}</td>
					@endif
					<td>{{ $match->pctKillParticipation * 100 }}%</td>
					<td>{{ $match->pctTeamDeaths*100 }}%</td>
					<td>{{ $match->minionsKilled + $match->neutralMinionsKilled }}</td>
					<td>{{ $zeroToTen or 'N/A' }}</td>
					<td>{{ $tenToTwenty or 'N/A' }}</td>
					<td>{{ $match->goldEarned }} {{ $match->pctTeamGoldShare * 100 }}%</td>
					<td>{{ $match->totalDamageDealtToChampions }} {{ $match->pctTeamDamageDealtToChampions * 100 }}%</td>
					<td>{{ $match->totalDamageTaken }} {{ $match->pctTeamDamageTaken * 100 }}%</td>
					<td><a href={{ sprintf("/summoner/%s/%s/details",$summonerName,$match->matchId) }}>Click</a></td>
				</tr>
			@endforeach
		</table>
	</div>