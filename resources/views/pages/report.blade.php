@extends('layouts.app')

@section('main')
<div class="inner">
	<!-- Header -->
	<header id="header">
		<a class="logo"><strong><font size="+2">{{$summonerName}}</font></strong> <div id="rank">Rank Here</div></a>
	</header>
	
	<!-- Section -->
	<section>
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
	</section>                   
</div>
@endsection