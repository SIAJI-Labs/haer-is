<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ (!empty($wsecond_title) ? $wsecond_title.' | ' : '').($wtitle ?? env('APP_NAME')) }}</title>

        <meta name="description" content="{{ $wdesc ?? 'Just a Skeleton of Website' }}">
        @if(!empty($wfavicon))
        <link rel="shortcut icon" href="{{ asset('images'.'/'.$wfavicon) }}">
        @endif
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <!-- Meta -->
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ (isset($wsecond_title) && !empty($wsecond_title) ? $wsecond_title.' - ' : '').($wtitle ?? 'SIABAS') }}">
        <meta property="og:description" content="{{ isset($wdesc) && !empty($wdesc) ? $wdesc : 'Just a Skeleton of Website' }}">
        @if(isset($wfavicon) && !empty($wfavicon))
        <meta property="og:image" content="{{ asset('images'.'/'.$wfavicon) }}">
        @endif

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="{{ (isset($wsecond_title) && !empty($wsecond_title) ? $wsecond_title.' - ' : '').($wtitle ?? 'SIABAS') }}">
        <meta property="twitter:description" content="{{ isset($wdesc) && !empty($wdesc) ? $wdesc : 'Just a Skeleton of Website' }}">
        @if(isset($wfavicon) && !empty($wfavicon))
        <meta property="twitter:image" content="{{ asset('images'.'/'.$wfavicon) }}">
        @endif

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ mix('assets/plugins/fontawesome-free/css/all.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Main Style -->
        <link rel="stylesheet" href="{{ mix('assets/adminlte/css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('assets/adminlte/css/app-custom.css') }}">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <!-- Tailwind - Without Base -->
        <link href="{{ mix('assets/css/minimal-tailwind.css') }}" rel="stylesheet">
        <!-- Sweetalert2 -->
        <link href="{{ mix('assets/plugins/sweetalert2/css/sweetalert2.css') }}" rel="stylesheet">
        <link href="{{ mix('assets/plugins/sweetalert2/css/bootstrap-4.css') }}" rel="stylesheet">

        {{-- OverlayScrollbar --}}
        @include('layouts.partials.plugins.overlayscrollbar-css')

        @yield('css_plugins')
		@yield('css_inline')
    </head>
	<body class="hold-transition sidebar-mini layout-fixed {{ $wbody_class ?? '' }}">
		<!-- Site wrapper -->
		<div class="wrapper">
			@include('layouts.partials.navbar')
			@include('layouts.partials.sidebar')

            <!-- Preloader -->
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__wobble" src="{{ getAvatar(($wtitle ?? env('APP_NAME')), 'gridy') }}" alt="Preloader" height="60" width="60">
            </div>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
                <!-- Ajax Alert -->
                <div id="ajax-alert">
                    <div class="alert alert-progress alert-info fade show mb-0" role="alert" id="progress-alert" style="display:none;border:unset;border-radius:0;">
                        <div class="d-flex align-items-center">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="ml-2">Mohon bersabar, ada proses yang sedang berjalan...</span>
                        </div>
                    </div>
                </div>

				@if(isset($wheader) && isset($wheader))
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>{{ $wheader['header_title'] }}</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    @foreach($wheader['header_breadcrumb'] as $br)
                                    <li class="breadcrumb-item {{ $br['is_active'] ? 'active' : '' }}">
                                        @if($br['is_active'])
                                        {{ $br['title'] }}
                                        @else
                                        <a href="{{ $br['url'] }}">{{ $br['title'] }}</a>
                                        @endif
                                    </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>
				@endif
				
				@if(Session::get('message'))
                <!-- Content Message (Page header) -->
                <div class="container-fluid">
                    <section class="px-2">
                        <div class="alert alert-{{ Session::get('status') ?? 'info' }} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5>
                                @if(Session::get('message_icon'))
                                <i class="icon fas fa-{{ Session::get('message_icon') ?? 'info' }}"></i>
                                @endif {{ Session::get('status') ? ucwords(Session::get('status')) : 'Info' }}!</h5>
                            {{ Session::get('message') }}
                        </div>
                    </section>
                </div>
                @endif

				<!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </section>
                <!-- /.content -->
			</div>
			<!-- /.content-wrapper -->

			@include('layouts.partials.footer')
		</div>
		<!-- ./wrapper -->

        @yield('content_modal')
        
        {{-- Logout Form --}}
        <form id="logout-form" action="{{ route('public.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- jQuery -->
        <script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>
        <!-- AdminLTE App -->
        {{-- <script src="{{ asset('assets/adminlte/js/admin-lte.js') }}"></script> --}}
        <script src="{{ mix('assets/adminlte/js/app.js') }}"></script>
        {{-- <script src="{{ mix('assets/adminlte/js/app-custom.js') }}"></script> --}}
        <script src="{{ mix('assets/adminlte/js/function.js') }}"></script>
        <script src="{{ mix('assets/js/s.js') }}"></script>
        <!-- Moment JS -->
        <script src="{{ mix('assets/plugins/moment/dist/moment.js') }}"></script>

        <!-- Sweetalert2 -->
        <script src="{{ mix('assets/plugins/sweetalert2/js/sweetalert2.js') }}"></script>
        {{-- OverlayScrollbar --}}
        @include('layouts.partials.plugins.overlayscrollbar-js')
        
        <script>
            var ajax_timer = null;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: () => {
                    // show loading dialog // works
                    if(ajax_timer){
                        clearTimeout(ajax_timer)
                        ajax_timer = null;
                    }
                    
                    if($('.ajax-toast').length < 1){
                        ajax_timer = setTimeout((e) => {
                            $(document).Toasts('create', {
                                class: 'bg-warning ajax-toast m-3', 
                                title: 'Processing',
                                close: false,
                                body: 'Mohon bersabar, ada proses yang sedang berjalan...'
                            });
                        }, 750);
                    }
                    // console.log(ajax_timer);
                },
                complete: (xhr, stat) => {
                    // hide dialog // works
                    $('.ajax-toast').fadeOut('300', (e) => {
                        setTimeout((e) => {
                            $('.ajax-toast').remove();
                        }, 0);
                    });
                    if(ajax_timer){
                        clearTimeout(ajax_timer)
                        ajax_timer = null;
                    }
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    // console.log("Ajax Fail Global");
                    // console.log(jqXHR);

                    Swal.fire({
                        title: "Ada sesuatu yang bermasalah",
                        text: "Mohon hubungi admin jika error ini terjadi berulang!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Segarkan Halaman!',
                        cancelButtonText: 'Tutup!',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.value) {
                            Swal.fire({
                                title: "Halaman ini akan disegarkan!",
                                text: "Semua data yang belum disimpan akan diabaikan!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Segarkan Halaman!',
                                cancelButtonText: 'Tutup!',
                                reverseButtons: true,
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            });
                        }
                    });
                },
            });
            
            function resend_link(){
                console.log("Resend Link is running...");
            }
            function formReset(){
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Semua data yang belum tersimpan akan diabaikan, dan tidak ada perubahan yang akan disimpan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, jalankan!',
                    cancelButtonText: 'Tidak, batalkan!',
                    reverseButtons: true,
                }).then((result) => {
                    // console.log(result);
                    if(result.value){
                        location.reload();
                    }
                });
            }

            $(document).ready((e) => {
                let sidebarLink = $("ul.sidebar-menu li.nav-item");
                sidebarLink.each((row, data) => {
                    let link = $(data).find('a');
                    let pathLink = link.attr('href').replace(/^.*\/\/[^\/]+/, '');

                    if(pathLink != 'javascript:void(0)'){
                        $(link).attr('href', pathLink);
                    }
                });
            });
        </script>
        @yield('js_plugins')
		@yield('js_inline')
	</body>
</html>
