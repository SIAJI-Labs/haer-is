@extends('layouts.system', [
    'wsecond_title' => 'Profile',
    'sidebar_menu' => 'profile',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Profile',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => false,
                'url' => route('system.index')
            ], [
                'title' => 'Profile',
                'is_active' => true,
                'url' => null
            ],
        ]
    ]
])

@section('content')
    <div class="row">
        <div class="col-12 col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ getAvatar(\Auth::user()->name) }}" alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{ \Auth::user()->name }}</h3>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-12 col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#settings" @if(!request()->has('page')) data-toggle="tab" @endif>Pengaturan Profile</a></li>
                        <li class="nav-item"><a class="nav-link " href="#config" data-toggle="tab">Pengaturan Data</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="settings">
                            <form class="form-horizontal" method="POST" action="{{ route('system.profile.update', \Auth::user()->uuid) }}" autocomplete="off">
                                @csrf
                                @method('PUT')

                                <div class="form-group row align-items-center">
                                    <label for="input-name" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="name" class="form-control @error('name') is-invalid @enderror" id="input-name" name="name" placeholder="Nama" value="{{ \Auth::user()->name }}">
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label for="input-email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="input-email" name="email" placeholder="Email" value="{{ \Auth::user()->email }}">
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label for="input-username" class="col-sm-2 col-form-label">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="input-username" name="username" placeholder="Username" value="{{ \Auth::user()->username }}">
                                        @error('username')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label for="input-password" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" name="fakepassword" id="fakepassword"/>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="input-password" name="password" placeholder="Password">
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted">*Biarkan kosong jika tidak ingin merubah data password</small>
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label for="input-password_confirmation" class="col-sm-2 col-form-label">Konfirmasi Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="input-password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" disabled>
                                        @error('password_confirmation')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label for="input-old_password" class="col-sm-2 col-form-label">Password Lama</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control @error('old_password') is-invalid @enderror" id="input-old_password" name="old_password" placeholder="Password Lama" disabled>
                                        @error('old_password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="button" onclick="formReset()" class="btn btn-sm btn-danger">Reset</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="config">
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@section('js_inline')
    <script>
        $(document).ready((e) => {
            $("#fakepassword").hide();
        });

        $("#input-password").change((e) => {
            let val = $(e.target).val();
            console.log('Password is changed');

            if(val){
                $("#input-password_confirmation").attr('disabled', false);
                $("#input-old_password").attr('disabled', false);
            } else {
                $("#input-password_confirmation").attr('disabled', true);
                $("#input-old_password").attr('disabled', true);
            }
        })
    </script>
@endsection