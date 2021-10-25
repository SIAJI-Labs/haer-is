<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ (!empty($wsecond_title) ? $wsecond_title.' | ' : '').($wtitle ?? env('APP_NAME')) }}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ mix('assets/plugins/fontawesome-free/css/all.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ mix('assets/adminlte/css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('assets/adminlte/css/app-custom.css') }}">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

        @yield('css_plugins')
		@yield('css_inline')
    </head>
	<body class="login-page">
                
        @yield('content')

		<!-- jQuery -->
		<script src="{{ mix('assets/plugins/jquery/jquery.js') }}"></script>
		<!-- Bootstrap 4 -->
		<script src="{{ mix('assets/plugins/bootstrap/js/bootstrap.bundle.js') }}"></script>
		<!-- AdminLTE App -->
		<script src="{{ mix('assets/adminlte/js/app.js') }}"></script>
		<!-- AdminLTE for demo purposes -->
        {{-- <script src="../../js/demo.js"></script> --}}
        
		@yield('js_plugins')
		@yield('js_inline')
	</body>
</html>
