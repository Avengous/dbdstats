<!DOCTYPE HTML>
<html>
	<head>
		@include('includes.sidebar')
	</head>
	<body>
        <!-- Wrapper -->
        <div id="wrapper">
            <!-- Main -->
            <div id="main">
                @yield('main')
            </div>
            @include('includes.header')
        </div>
	</body>
	
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
	<script type="text/javascript" src="{{ asset('js/statistics.functions.js') }}"></script>
	
</html>