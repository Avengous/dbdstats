@extends('layouts.app')

@section('main')
<div class="inner">
	<!-- Header -->
	<header id="header">
		@php ($summonerRank = App::make("App\Http\Controllers\V1\SummonerController")->soloQueueRank($summonerName))
		<a class="logo">
			<strong><font size="+2">{{$summonerName}}</font></strong> 
			<!-- <div id="rank">FLEX: {{ $summonerRank['flex'] }}</div> -->
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
			SOLO {{ $summonerRank['solo'] }}
		</div>
		<div style="float:right;">
			FLEX {{ $summonerRank['flex'] }}
		</div>
		
		@if ($summonerName == 'Whambulance')
			<div style="text-align: center;">
				@php ($decimatedCount = App::make("App\Http\Controllers\V1\SummonerController")->decimationCount($summonerName))
				DECIMATED: {{ $decimatedCount }} 
			</div>
		@endif
	</div>
	
	<!-- Section -->
	<section>
		@include('includes.report_recent_match')
		<!--
		<header class="major">
			<h2>Summary</h2>
		</header>
		
		<div id="date-info">
			<p>Date from: <input type="text" id="datepicker" value="Click To Choose Date" size="22"></p>
			<p>Date to: <input type="text" id="datepicker2" value="Click To Choose Date" size="22"></p>
			<button class="button getTickets">Show Results</button>
		</div>
		<div class="summary">
			<div class="circle" id="summary_kills"></div>
			<div class="circle" id="summary_deaths"></div>
			<div class="circle" id="summary_assists"></div>
		</div>
		-->
		@include('includes.report_summary')
		
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
<!--<script type="text/javascript" src="{{ asset('js/statistics.functions.js') }}"></script>-->
@endsection