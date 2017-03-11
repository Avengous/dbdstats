<!DOCTYPE HTML>
<html>
	<head>
    <?php $summoner = ucwords(str_replace("-", " ", $_GET["summoner"])); ?>
		<title><?php echo $summoner; ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="/DBD/css/main-site.css" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="/DBD/css/font-awesome.min.css">
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body>
        <!-- Wrapper -->
        <div id="wrapper">
            
            <!-- Main -->
            <div id="main">
                <div class="inner">
                    <!-- Header -->
                    <header id="header">
                        <a class="logo"><strong><font size="+2"><?php echo $summoner; ?> </font></strong> <div id="rank">Rank Here</div></a>
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
                                <li><a href="/DBD/reports/whambulance/">Whambulance</a></li>
                                <li><a href="/DBD/reports/oliver-phillips/">Oliver Phillips</a></li>
                                <li><a href="/DBD/reports/avengous/">Avengous</a></li>
                                <li><a href="/DBD/reports/revenant/">Revenant</a></li>
                                <li><a href="/DBD/reports/krystic/">Krystic</a></li>
                               <!-- <li>
                                    <span class="opener">Submenu</span>
                                    <ul>
                                        <li><a href="#">sub1</a></li>
                                        <li><a href="#">sub2</a></li>
                                    </ul>
                                </li> -->
                            </ul>
                        </nav>
                </div>
            </div>

        </div>

        <!-- Scripts -->
        <link rel="stylesheet" href="/DBD/css/jquery-ui.css"/>
        <script type="text/javascript" src="/DBD/js/jquery.min.js"></script>
        <script type="text/javascript" src="/DBD/js/jquery.ui.js"></script>
        <script src="/DBD/js/skel.min.js"></script>
        <script src="/DBD/js/util.js"></script>
        <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
        <script src="/DBD/js/main-site.js"></script>
        <script type="text/javascript">
          var summoner = "<?php echo $summoner ?>";
        </script>
        <script type="text/javascript" src="/DBD/js/statistics.functions.js"></script>
	</body>
</html>