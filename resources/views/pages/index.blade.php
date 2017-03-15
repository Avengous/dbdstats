<!DOCTYPE HTML>
<html>
	<head>
		<title>DBD| {{$summonerName}}</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="{{ asset('css/main-site.css') }}" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
	</head>
	<body>
        <!-- Wrapper -->
        <div id="wrapper">
            
            <!-- Main -->
            <div id="main">
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

                    <!-- Section -->
                    <section>
                        <header class="major">
                        </header>
                        <div class="posts">
                            <article>
                            </article>
                            <article>
                            </article>
                            <article>
                            </article>
                            <article>
                            </article>
                            <article>
                            </article>
                            <article>
                            </article>
                        </div>
                    </section>
                      
                </div>
            </div>

            <!-- Sidebar -->
            <div id="sidebar">
                <div class="inner">

                    <!-- Search -->
                        <section id="header" class="alt">
                        </section>

                    <!-- Menu -->
                        <nav id="menu">
                            <header class="major">
                                <h2>Menu</h2>
                            </header>
                            <ul>
                                <li><a href="/summoner/Whambulance">Whambulance</a></li>
                                <li><a href="/summoner/Oliver Phillips">Oliver Phillips</a></li>
                                <li><a href="/summoner/Avengous">Avengous</a></li>
                                <li><a href="/summoner/Revenant/">Revenant</a></li>
                                <li><a href="/summoner/Krystic">Krystic</a></li>
                            </ul>
                        </nav>
                </div>
            </div>

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
        <script type="text/javascript" src="{{ asset('js/statistics.functions.js') }}"></script>
	</body>
</html>