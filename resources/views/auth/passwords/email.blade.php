@extends('layouts.adm.auth', [
    'wsecond_title' => 'Forgot Password',
])

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('public.index') }}">{{ $wtitle ?? 'SIABAS' }}</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Forgot Password</p>

            @if(Session::get('message'))
                <div class="alert alert-{{ Session::get('status') ?? 'warning' }} alert-dismissible fade show" role="alert">
                    <span>{{ Session::get('message') }}</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('adm.password.email') }}" method="post">
                @csrf

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <a href="{{ route('adm.login') }}" class="text-secondary">Back to Login</a>
                    </div>
                    <div class="flex-fill">
                        <button type="submit" class="btn btn-primary btn-block">Send Password Reset Link</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
@endsection