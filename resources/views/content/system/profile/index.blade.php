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

@section('css_plugins')
    {{-- Select2 --}}
    @include('layouts.partials.plugins.select2-css')
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle tw__bg-white" src="{{ getAvatar(\Auth::user()->name, \Auth::user()->avatar_style ?? 'initials') }}" alt="User profile picture">
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

                                <div class="form-group row">
                                    <label for="input-name" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="name" class="form-control @error('name') is-invalid @enderror" id="input-name" name="name" placeholder="Nama" value="{{ \Auth::user()->name }}">
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="input-email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="input-email" name="email" placeholder="Email" value="{{ \Auth::user()->email }}">
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="input-username" class="col-sm-2 col-form-label">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="input-username" name="username" placeholder="Username" value="{{ \Auth::user()->username }}">
                                        @error('username')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="input-avatar" class="col-sm-2 col-form-label">Avatar</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-12 col-md-3">
                                                <div class="img-preview mb-2">
                                                    <select class="form-control input-select2" id="input-avatar_style" name="avatar_style">
                                                        @if (config('custom.avatar'))
                                                            @foreach (config('custom.avatar') as $item)
                                                                <option value="{{ $item }}" {{ empty(\Auth::user()->avatar_style) ? ($item == 'initials' ? 'selected' : '') : ($item == \Auth::user()->avatar_style ? 'selected' : '') }}>{{ $item }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    
                                                    <img class="img-responsive tw__mt-2" id="avatar-preview" width="100%;" style="padding:.25rem;background:#eee;display:block;" src="{{ getAvatar(\Auth::user()->name, \Auth::user()->avatar_style ?? 'initials') }}">
                                                </div>
                                            </div>
                                            {{-- <div class="col-12 col-md-9">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input " name="nazhir_picture" id="input-nazhir_picture" onchange="generatePreview($(this), 'picture')">
                                                    <label class="custom-file-label" for="input-nazhir_picture">Choose file</label>
                                                    
                                                                            <small class="text-muted">Suggestion: Use a picture PNG extension</small>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
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
                                <div class="form-group row">
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
                            <form class="form-horizontal" method="POST" action="{{ route('system.profile-preference.update', \Auth::user()->uuid) }}" autocomplete="off">
                                @csrf
                                @method('PUT')

                                <div class="form-group row">
                                    <label for="input-name" class="col-sm-2 col-form-label">Lokasi</label>
                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-12 col-lg-9">
                                                <select class="form-control @error('location') is-invalid @enderror" id="input-location" name="location">
                                                    @php
                                                        $defaultLocation = \Auth::user()->userPreference()->where('key', 'location')->where('is_default', true)->first();
                                                    @endphp
                                                    @if (!empty($defaultLocation))
                                                        <option value="{{ $defaultLocation->uuid }}">{{ $defaultLocation->value }} - Default</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <button type="button" class="btn btn-sm btn-secondary w-100 h-100 tw__mt-4 lg:tw__mt-0" onclick="dataLocation()">Atur Data Lokasi</button>
                                            </div>
                                        </div>
                                        
                                        @error('location')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10 tw__mt-4 lg:tw__mt-0">
                                        <button type="button" onclick="formReset()" class="btn btn-sm btn-danger">Reset</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                    </div>
                                </div> --}}
                            </form>
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

@section('content_modal')
    {{-- Modal Location --}}
    @include('content.system.profile.partials.modal-location')
@endsection

@section('js_plugins')
    {{-- Select2 --}}
    @include('layouts.partials.plugins.select2-js')
@endsection

@section('js_inline')
    <script>
        var locationStart = 1;
        const validateLocationDefault = () => {
            $(".location-is_default").change((e) => {
                let checked = $(".location-is_default:checked").length;
                
                if(checked > 0){
                    setTimeout((e) => {
                        $(".location-is_default").each((row, data) => {
                            if(!($(data).is(':checked'))){
                                $(data).attr('disabled', true);
                            }
                        });
                    });
                } else {
                    $('.location-is_default').attr('disabled', false);
                }
            });
        }
        const addMoreLocation = () => {
            let locationContent = $("#locationContent");
            let locationAddMoreButton = $("#locationAddMore-btn");

            $(locationAddMoreButton).click((e) => {
                let template = `
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input location-is_default" type="checkbox" id="input_${locationStart}-is_default" name="location[${locationStart}][is_default]">
                                        <label for="input_${locationStart}-is_default" class="custom-control-label">Default</label>
                                    </div>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="location[${locationStart}][name]" id="input_${locationStart}-location" placeholder="Lokasi">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger btn-sm location-remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `;

                $(template).appendTo($(locationContent));
                locationStart++;
                setTimeout(() => {
                    validateLocationDefault();
                }, 0);
            });
            $(locationContent).on('click', '.location-remove', (e) => {
                const item = $(e.target).closest('.form-group');
                $(item).remove();
            });
        }

        const avatarStyle = () => {
            let baseUrl = `https://avatars.dicebear.com/api`;
            let userName = $("#input-name").val();
            let style = $("#input-avatar_style").val();

            let image = `${baseUrl}/${style}/${userName}.svg`;
            $("#avatar-preview").attr('src', image);
        }
        
        $(document).ready((e) => {
            validateLocationDefault();
            addMoreLocation();

            $("#fakepassword").hide();

            $("#input-location").select2({
                theme: 'bootstrap4',
                placeholder: 'Cari Lokasi',
                ajax: {
                    url: "{{ route('system.json.select2.user-preference.select2') }}",
                    delay: 250,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            type: 'location'
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function (data, params) {
                        var items = $.map(data.data, function(obj){
                            obj.id = obj.uuid;
                            obj.text = `${obj.value}${obj.is_default ? '- Default' : ''}`;

                            return obj;
                        });
                        params.page = params.page || 1;

                        console.log(items);
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: items,
                            pagination: {
                                more: params.page < data.last_page
                            }
                        };
                    },
                },
                templateResult: function (item) {
                    // console.log(item);
                    // No need to template the searching text
                    if (item.loading) {
                        return item.text;
                    }
                    
                    var term = select2_query.term || '';
                    var $result = markMatch(item.text, term);

                    return $result;
                },
                language: {
                    searching: function (params) {
                        // Intercept the query as it is happening
                        select2_query = params;
                        
                        // Change this to be appropriate for your application
                        return 'Searching...';
                    }
                }
            });
            $(".input-select2").select2({
                theme: 'bootstrap4',
                placeholder: 'Cari Kata kunci',
            });
        });

        // Change Avatar Style
        $("#input-name").change((e) => {
            avatarStyle();
        });
        $("#input-avatar_style").change((e) => {
            avatarStyle();
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
        });

        function dataLocation(){
            $.get("{{ route('system.profile-preference.index', \Auth::user()->uuid) }}", {'type': 'location'}, (result) => {
                let data = result.data;
                if(!(jQuery.isEmptyObject(data))){
                    locationStart = 0;
                    let locationContent = $("#locationContent");

                    $(locationContent).empty();
                    data.forEach((data, row) => {
                        let removeBtn = '';
                        if(row != 0){
                            removeBtn = `
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger btn-sm location-remove"><i class="fas fa-times"></i></button>
                                </div>
                            `;
                        }
                        let template = `
                            <div class="form-group">
                                <input type="hidden" class="form-control" name="location[${locationStart}][validate]" id="input_${locationStart}-validate" value="${data.uuid}">

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input location-is_default" type="checkbox" id="input_${locationStart}-is_default" name="location[${locationStart}][is_default]" ${data.is_default ? 'checked' : 'disabled'}>
                                                <label for="input_${locationStart}-is_default" class="custom-control-label">Default</label>
                                            </div>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" name="location[${locationStart}][name]" id="input_${locationStart}-location" placeholder="Lokasi" value="${data.value}">
                                    ${removeBtn}
                                </div>
                            </div>
                        `;

                        $(template).appendTo($(locationContent));
                        locationStart++;
                        setTimeout(() => {
                            validateLocationDefault();
                        }, 0);
                    });
                } else {
                    locationStart = 1;
                }

                setTimeout((e) => {
                    $("#modalLocation").modal('show');
                }, 0);
            });
        }
        $("#modalLocation").submit((e) => {
            ajaxAlert = false;
            e.preventDefault();

            console.log(ajaxAlert);
            $.post($(e.target).attr('action'), $(e.target).serialize(), (result) => {
                let data = result.data;
                if(!(jQuery.isEmptyObject(data.location_default))){
                    data = data.location_default;
                    var $newOption = $("<option selected='selected'></option>").val(data.uuid).text(data.value);
                    $("#input-location").append($newOption).trigger('change');
                }

                Swal.fire({
                    title: "Aksi Berhasil",
                    text: result.message,
                    icon: 'success',
                    confirmButtonText: 'Oke!',
                    reverseButtons: true,
                });
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.log("Ajax Fail");
                console.log(jqXHR);

                $.each(jqXHR.responseJSON.errors, (key, result) => {
                    let errorKey = key.split('.');
                    console.log(errorKey);
                    $(`#input_${errorKey[1]}-${errorKey[0]}`).addClass('is-invalid');
                    $(`#input_${errorKey[1]}-${errorKey[0]}`).closest('.form-group').append(`<small class="invalid-feedback d-block">${result}</small>`);
                });
            }).always(() => {
                $("#btn-submit").html('Submit').attr('disabled', false);
            });

            setTimeout((e) => {
                // ajaxAlert = true;
            }, 100);
        });
    </script>
@endsection