<hr />
<div>
  <table style="float: left; width: 50%; border-right: 1px solid #ddd">
    <tr>
		<td><strong>Solo Queue<strong></td>
		<td />
		<td />
		<td />
    </tr>
	<tr>
		<td>Position</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
	<tr>
		<td>Top</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
	<tr>
		<td>Jungle</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
	<tr>
		<td>Mid</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
	<tr>
		<td>ADC</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
	<tr>
		<td>Support</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
  </table>
  
  <table style="float: left; width: 50%">
    <tr>
      <td><strong>Flex Queue<strong></td>
	  <td />
	  <td />
	  <td />
    </tr>
	<tr>
		<td>Position</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
	<tr>
		<td>Top</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
	<tr>
		<td>Jungle</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
	<tr>
		<td>Mid</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
	<tr>
		<td>ADC</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
	<tr>
		<td>Support</td>
		<td>KDA</td>
		<td>Played</td>
		<td>Weight</td>
	</tr>
  </table>
  <hr />
  <table style="float: left; width: 50%">
	@php ($summoners = App::make("App\Http\Controllers\V1\ReportController")->getAllSummonerNames())
	@php ($summonerId = App::make("App\Http\Controllers\V1\ReportController")->getSummonerIdByName($summonerName))
    <tr>
		<th><strong>Duo Win Rates<strong></th>
		<th /><th /><th />
    </tr>
	<tr>
		<th>Player</th>
		<th>Wins</th>
		<th>Losses</th>
		<th>Win Rate</th>
	</tr>
	@foreach ($summoners as $summoner)
		@php ($duo = [$summoner->summonerId, $summonerId])
		@php ($stats = App::make("App\Http\Controllers\V1\ReportController")->multiSummonerWinRate($duo))
		@if ($summoner->name != $summonerName)
			<tr>
				<td>{{ $summoner->name }}</td>
				<td>{{ $stats['wins'] }}</td>
				<td>{{ $stats['losses'] }}</td>
				@if ($stats['gamesPlayed'] != 0)
					<td>{{ round($stats['wins']/$stats['gamesPlayed'], 2)*100 }}%</td>
				@else <td>N/A</td>
				@endif
			</tr>
		@endif
	@endforeach
  </table>
</div>