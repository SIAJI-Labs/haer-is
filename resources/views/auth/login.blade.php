@extends('layouts.auth', [
    'wsecond_title' => 'Login'
])

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('public.index') }}"><b>{{ $wtitlle ?? env('APP_NAME') }}</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            @if(Session::get('message'))
            <!-- Content Message (Page header) -->
            <div class="alert alert-{{ Session::get('status') ?? 'info' }} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5>
                    @if(Session::get('message_icon'))
                    <i class="icon fas fa-{{ Session::get('message_icon') ?? 'info' }}"></i>
                    @endif {{ Session::get('status') ? ucwords(Session::get('status')) : 'Info' }}!</h5>
                {{ Session::get('message') }}
            </div>
            @endif
  
            <form action="{{ route('public.login') }}" method="POST" id="form-login">
                @csrf

                <div class="input-group mb-3">
                    <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email / Username" value="{{ old('email') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-7 d-flex align-items-center">
                        <div class="icheck-primary d-flex align-items-center">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember" class="mb-0 ml-2">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-5">
                        <button type="submit" class="btn btn-primary btn-block" id="btn-submit">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
        <div class="card-footer">
            <p class="mb-1">
                <a href="{{ route('public.password.request') }}">I forgot my password</a>
            </p>
        </div>
    </div>
</div>
@endsection

@section('js_inline')
<script>
    $("#form-login").submit((e) => {
        $(".form-control").removeClass('is-invalid');
        $(".form-group .invalid-feedback").remove();

        $("#btn-submit").html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        `);
    });
</script>
@endsection