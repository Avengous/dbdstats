<h4 style="margin: 0 0 0 0;">Ranked Pentakills</h4>
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