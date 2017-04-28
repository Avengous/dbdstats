@extends('layouts.app')

@section('main')
<div class="inner">
	<!-- Header -->
	<header id="header">
		@php ($summonerRank = App::make("App\Http\Controllers\V1\SummonerController")->soloQueueRank($summonerName))
		<a class="logo">
			<strong><font size="+2">{{$summonerName}}</font></strong> 
		</a>
		<div style="float: right; width: 50px;">
			<a href="{{action('V1\MatchController@verifySummonerMatchList', ['summonerName' => $summonerName, 'allMatches' => true])}}">
				<button style="float: right; margin-left: 5px;" type="button">Validate</button>
			</a>
			<a href="{{action('V1\MatchController@verifySummonerMatchList', ['summonerName' => $summonerName])}}">
				<button style="float: right" type="button">Update</button>
			</a>
		</div>

	</header>

	<div id="rank" style="width:100%; display: inline-block;">
		<div style="float:left;">
			@if ($summonerName == 'Møchi')
				SOLO POTATO I 420LP
			@else
				SOLO {{ $summonerRank['solo'] }}
			@endif
		</div>
		
		<div style="float:right;">
			@if ($summonerName == 'Møchi')
				FLEX POTATO I 420LP
			@else
				FLEX {{ $summonerRank['flex'] }}
			@endif
		</div>
		
		@if ($summonerName == 'Whambulance')
			<div style="text-align: center;">
				@php ($decimatedCount = App::make("App\Http\Controllers\V1\SummonerController")->decimationCount($summonerName))
				DECIMATED: {{ $decimatedCount }} 
				<a href="{{action('V1\SummonerController@increaseDecimatedCount', ['summonerName' => $summonerName])}}">
					+
				</a>
			</div>
		@endif
	</div>
	
	<!-- Section -->
	<section>
		<div style="width: 100%; overflow: auto;">
			@include('includes.report_recent_match')
			@include('includes.report_summary')
		</div>
	</section>       	
</div>

<!-- Scripts -->
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}"/>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/skel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/util.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/main-site.js') }}"></script>
<script type="text/javascript">
  var summoner = "{{$summonerName}}";
</script>
@endsection