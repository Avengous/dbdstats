
	<div>
		@php ($matches = App::make("App\Http\Controllers\V1\ReportController")->recentMatchDetails($summonerName))
		<table>
		<h4>Recent Matches</h4>
		
			<tr>
				<th>Winner</th>
				<th>Queue</th>
				<th>Lane</th>
				<th>Level</th>
				<th>Champion</th>
				<th>K|D|A</th>
				<th>KDA</th>
				<th>KP</th>
				<th>CS</th>
				<th>CS Diff@10</th>
				<th>CS Diff@20</th>
				<th>Gold/Share</th>
				<th>Dmg/Share</th>
				<th>Link</th>
			</tr>
			@foreach ($matches as $match)
			@php ($csDiffPerMinDeltas = unserialize($match->csDiffPerMinDeltas))
			@php ($role = App::make("App\Http\Controllers\V1\ReportController")->defineMatchRole($match->role, $match->lane))
			@php ($championName = App::make("App\Http\Controllers\V1\ReportController")->championName($match->championId))
			<tr>
				<td>
				@if ($match->winner == 0) Defeat
				@elseif ($match->winner == 1) Victory
				@else Remake
				@endif
				</td>
				<td>
				@if ($match->queueType == 'TEAM_BUILDER_RANKED_SOLO') Solo
				@else $match->queueType
				@endif
				</td>
				<td>{{ $role }}</td>
				<td>{{ $match->champLevel }}</td>
				<td>{{ $championName }}</td>
				<td>
				{{ $match->kills }}/{{ $match->deaths }}/{{ $match->assists }}</td>
				<td>{{ round(($match->kills+$match->assists)/$match->deaths, 2)}}</td>
				<td>{{ $match->pctKillParticipation *100}}%</td>
				<td>{{ $match->minionsKilled + $match->neutralMinionsKilled }}</td>
				<td>{{ $csDiffPerMinDeltas['zeroToTen'] }}</td>
				<td>{{ $csDiffPerMinDeltas['tenToTwenty'] }}</td>
				<td>{{ $match->goldEarned }} {{ $match->pctTeamGoldShare * 100}}%</td>
				<td>{{ $match->totalDamageDealtToChampions }} {{ $match->pctTeamDamageDealtToChampions * 100 }}%</td>
				<td><a href='https://'>Click</a></td>
			</tr>
			@endforeach
		</table>
	</div>