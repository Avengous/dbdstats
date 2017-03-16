<!DOCTYPE HTML>
<html>
	@include('includes.head')
	<body>
        <!-- Wrapper -->
        <div id="wrapper">
            <!-- Main -->
            <div id="main">
                @yield('main')
            </div>
            @include('includes.sidebar')
        </div>
	</body>
</html>