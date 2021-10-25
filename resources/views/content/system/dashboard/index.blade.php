@extends('layouts.system', [
    'wsecond_title' => 'Dashboard',
    'sidebar_menu' => 'dashboard',
    'sidebar_submenu' => null,
    'wheader' => [
        'header_title' => 'Dashboard',
        'header_breadcrumb' => [
            [
                'title' => 'Dashboard',
                'is_active' => true,
                'url' => false
            ],
        ]
    ]
])

@section('css_plugins')
    {{-- Daterange Picker --}}
    @include('layouts.partials.plugins.daterange-picker-css')
    {{-- Datatable --}}
    @include('layouts.partials.plugins.datatable-css')
@endsection
@section('css_inline')
    <style>
        .daterangepicker .drp-calendar {
            padding: 1rem;
        }
        .daterangepicker .drp-calendar .calendar-table {
            padding: unset;
        }
    </style>
@endsection

@section('content')
<section id="dashboard">
    <div class="row">
        <div class="col-12 col-lg-5 d-flex align-items-stretch">
            <div class="card tw__w-full">
                <div class="card-header">
                    <h3 class="card-title">Waktu Sekarang</h3>
                </div>
                <div class="card-body tw__flex tw__items-center tw__mx-auto">
                    <div id="time-now" class="tw__text-center">
                        <h3 class="time">HH:mm:ss</h3>
                        <span class="date">dd/mm/yyyy</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7 d-flex align-items-stretch">
            <div class="card tw__w-full">
                <div class="card-header">
                    <h3 class="card-title">Kehadiran</h3>
                </div>
                <div class="card-body">
                    @php
                        $getPaused = [];
                        $pausedAccumulation = 0;
                        if(!empty(\Auth::user()->getActiveAttendace())){
                            $getPaused = \App\Models\AttendancePause::where('attendance_id', \Auth::user()->getActiveAttendace()->id)
                                ->whereNull('end')
                                ->orderBy('created_at', 'desc')
                                ->first();
                            $pausedAccumulation = \App\Models\AttendancePause::where('attendance_id', \Auth::user()->getActiveAttendace()->id)
                                ->whereNotNull('end')
                                ->sum('duration');
                        }
                    @endphp
                    
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="tw__bg-{{ !empty(\Auth::user()->getActiveAttendace()) && !empty(\Auth::user()->getActiveAttendace()->checkout_time) ? 'blue' : 'gray' }}-200 tw__rounded tw__h-full tw__w-full tw__flex tw__items-center tw__justify-center tw__flex-col tw__p-4" id="time-work">
                                @if (!empty(\Auth::user()->getActiveAttendace()) && empty(\Auth::user()->getActiveAttendace()->checkout_time))
                                    <div id="work-time" class="text-center">
                                        <h3 class="time mb-0 tw__block">HH:mm:ss</h3>
                                        <small>(Waktu Bekerja{{ !empty($getPaused) ? ' - Timer dihentikan' : '' }})</small>
                                    </div>
                                @elseif(!empty(\Auth::user()->getActiveAttendace()->checkout_time))
                                    <span class="tw__text-center">Anda sudah melakukan check-in hari ini.</span>
                                @else
                                    <span class="tw__text-center">Anda belum melakukan check-in hari ini.</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">
                            <button type="button" class="btn d-block w-100 tw__mb-1 btn-primary" @if(!empty(\Auth::user()->getActiveAttendace())) disabled @else data-toggle="modal" data-target="#modalCheckIn" @endif>Check-in</button>

                            @if (!empty($getPaused))
                                <button type="button" class="btn d-block w-100 tw__mb-1 btn-success" @if(!empty(\Auth::user()->getActiveAttendace())) @if(empty(\Auth::user()->getActiveAttendace()->checkout_time)) data-toggle="modal" data-target="#modalPause" @else disabled @endif @endif>Lanjutkan Timer</button>
                            @else
                                <button type="button" class="btn d-block w-100 tw__mb-1 btn-info" @if(!empty(\Auth::user()->getActiveAttendace())) @if(empty(\Auth::user()->getActiveAttendace()->checkout_time)) data-toggle="modal" data-target="#modalPause" @else disabled @endif @else disabled @endif>Hentikan Timer</button>
                            @endif

                            <button type="button" class="btn d-block w-100 btn-warning" @if(empty(\Auth::user()->getActiveAttendace()) || !empty(\Auth::user()->getActiveAttendace()->checkout_time)) disabled @else @if(!empty($getPaused)) disabled @else data-toggle="modal" data-target="#modalCheckOut" @endif @endif>Check-out</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Kehadiran</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped" id="attendance-table">
                <thead>
                    <tr>
                        <th>Tanggal Kehadiran</th>
                        <th>Jam Hadir</th>
                        <th>Jam Keluar</th>
                        <th>Jumlah Aktivitas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
@endsection

@section('content_modal')
    {{-- Checkin Modal --}}
    <form class="modal fade" id="modalCheckIn" action="{{ route('system.attendance.store') }}" method="POST">
        @csrf

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Check-in Kehadiran</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="input-checkin_date" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-date" id="input-checkin_date" name="date" placeholder="Tanggal Kehadiran" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="input-checkin_time" class="col-sm-3 col-form-label">Jam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-time" id="input-checkin_time" name="time" placeholder="Jam Kehadiran">
                        </div>
                    </div>

                    <div class="card mb-0">
                        <div class="card-header">
                            <h3 class="card-title">Aktivitas</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover table-striped tw__w-full" id="existingActivity-task">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="tw__text-center">Aktivitas Sebelumnya</th>
                                    </tr>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Progress (0 - 100)</th>
                                        <th>Judul</th>
                                    </tr>
                                </thead>
                            </table>

                            <table class="table table-bordered table-hover table-striped tw__mt-4" id="activity-task">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="tw__text-center">Aktivitas Baru</th>
                                    </tr>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Progress (0 - 100)</th>
                                        <th>Judul</th>
                                    </tr>
                                </thead>
                                <tbody id="activityContent">
                                    <tr>
                                        <td class="align-middle tw__text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="input_0-included" name="task[0][include]" checked="" onclick="return false;">
                                                <label for="input_0-included" class="custom-control-label"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" min="0" max="100" step="1" class="form-control acitivty-progress" name="task[0][progress]" id="input_0-progress" placeholder="Progress Aktivitas">
                                        </td>
                                        <td>
                                            <input type="text" name="task[0][name]" class="form-control" id="input_0-name" placeholder="Judul Aktivitas">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-sm btn-primary" id="activityAddMore-btn"><i class="fas fa-plus mr-2"></i>Tambah Lainnya</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </form>
    <!-- /.modal -->

    {{-- Checkout Modal --}}
    <form class="modal fade" id="modalCheckOut" action="{{ route('system.attendance.update', (!empty(\Auth::user()->getActiveAttendace()) ? \Auth::user()->getActiveAttendace()->uuid : 0)) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Check-out Kehadiran</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="tw__mb-4 tw__bg-blue-100 tw__text-blue-700 tw__px-4 tw__py-3 tw__rounded tw__relative" role="alert">
                        <strong class="tw__font-bold">Data Kehadiran!</strong>
                        <span class="tw__block">Anda melakukan check-in kehadiran pada <u>{{ !empty(\Auth::user()->getActiveAttendace()) ? \Auth::user()->getActiveAttendace()->checkin_time : '[waktu check-in]' }}</u></span>
                    </div>

                    <div class="form-group row">
                        <label for="input-checkout_date" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-date" id="input-checkout_date" name="date" placeholder="Tanggal Kepulangan" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="input-checkout_time" class="col-sm-3 col-form-label">Jam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-time" id="input-checkout_time" name="time" placeholder="Jam Kepulangan">
                        </div>
                    </div>

                    <div class="card mb-0">
                        <div class="card-header">
                            <h3 class="card-title">Aktivitas</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover table-striped" id="activity-task_checkout">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="tw__text-center">Daftar Aktivitas</th>
                                    </tr>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Progress (0 - 100)</th>
                                        <th>Judul</th>
                                    </tr>
                                </thead>
                                <tbody id="activityContentCheckout">
                                    @php
                                        $activityCheckoutStart = 0;
                                    @endphp
                                    @if (\Auth::user()->getActiveAttendace())
                                        @foreach (\Auth::user()->getActiveAttendace()->attendanceTask as $key =>  $item)
                                            <tr>
                                                <td class="align-middle tw__text-center">
                                                    <input type="hidden" name="task[{{ $key }}][validate]" value="{{ $item->id }}" readonly>

                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="input_{{ $key }}-checkout_included" name="task[{{ $key }}][include]" checked="" onclick="return false;">
                                                        <label for="input_0-checkout_included" class="custom-control-label"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" min="0" max="100" step="1" class="form-control acitivty-progress" name="task[{{ $key }}][progress]" id="input_{{ $key }}-checkout_progress" value="{{ $item->progress_start }}" placeholder="Progress Aktivitas">
                                                </td>
                                                <td>
                                                    <input type="text" name="task[{{ $key }}][name]" class="form-control" id="input_{{ $key }}-checkout_name" value="{{ $item->task->name }}" placeholder="Judul Aktivitas" readonly>
                                                </td>
                                            </tr>

                                            @php
                                                $activityCheckoutStart++;
                                            @endphp
                                        @endforeach
                                    @else
                                        @php
                                            $activityCheckoutStart = 1;
                                        @endphp
                                        <tr>
                                            <td class="align-middle tw__text-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="input_0-checkout_included" name="task[0][include]" checked="" onclick="return false;">
                                                    <label for="input_0-checkout_included" class="custom-control-label"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" min="0" max="100" step="1" class="form-control acitivty-progress" name="task[0][progress]" id="input_0-checkout_progress" placeholder="Progress Aktivitas">
                                            </td>
                                            <td>
                                                <input type="text" name="task[0][name]" class="form-control" id="input_0-checkout_name" placeholder="Judul Aktivitas">
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-sm btn-primary" id="activityAddMoreCheckout-btn"><i class="fas fa-plus mr-2"></i>Tambah Lainnya</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </form>
    <!-- /.modal -->

    {{-- Pause Modal --}}
    <form class="modal fade" id="modalPause" action="{{ route('system.attendance.update', (!empty(\Auth::user()->getActiveAttendace()) ? \Auth::user()->getActiveAttendace()->uuid : 0)) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="type" value="pause" readonly>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Jeda Aktivitas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (!empty($getPaused))
                        <div class="tw__mb-4 tw__bg-indigo-100 tw__text-indigo-700 tw__px-4 tw__py-3 tw__rounded tw__relative" role="alert">
                            <strong class="tw__font-bold">Timer Dihentikan sementara!</strong>
                            <span class="tw__block">Sepertinya anda melakukan pause pada timer aktivitas pada <u>{{ $getPaused->start }}</u> dengan alasan <u>{{ $getPaused->notes }}</u>. Mohon untuk melanjutkan timer ketika urusan anda sudah selesai</span>
                        </div>

                        <input type="hidden" name="pause_id" value="{{ $getPaused->id }}" readonly>
                    @endif

                    <div class="form-group row">
                        <label for="input-checkout_time" class="col-sm-3 col-form-label">Jam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-time" id="input-pause_time" name="time" placeholder="Jam">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="input-reason" class="col-sm-3 col-form-label">Alasan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="input-reason" name="reason" placeholder="Alasan" value="{{ !empty($getPaused) ? $getPaused->notes : '' }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </form>
    <!-- /.modal -->
@endsection

@section('js_plugins')
    {{-- Daterange Picker --}}
    @include('layouts.partials.plugins.daterange-picker-js')
    {{-- Datatable --}}
    @include('layouts.partials.plugins.datatable-js')
@endsection

@section('js_inline')
    <script>
        const validateProgressInput = () => {
            $(".acitivty-progress").change((e) => {
                // Validate Value
                if($(e.target).val() > 100){
                    $(e.target).val(100);
                } else if($(e.target).val() < 0){
                    $(e.target).val(0);
                }
            });
        }
        const addMoreActivity = () => {
            let activityStart = 1;
            let activityContent = $("#activityContent");
            let activityAddMoreBtn = $("#activityAddMore-btn");

            $(activityAddMoreBtn).click((e) => {
                let template = `
                    <tr>
                        <td class="align-middle tw__text-center">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="input_${activityStart}-included" name="task[${activityStart}][include]" checked="" onclick="return false;">
                                <label for="input_${activityStart}-included" class="custom-control-label"></label>
                            </div>
                        </td>
                        <td>
                            <input type="number" min="0" max="100" step="1" class="form-control acitivty-progress" name="task[${activityStart}][progress]" id="input_${activityStart}-progress" placeholder="Progress Aktivitas">
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" name="task[${activityStart}][name]" class="form-control" id="input_${activityStart}-name" placeholder="Judul Aktivitas">

                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger btn-sm activity-remove"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;

                $(template).appendTo($(activityContent));
                activityStart++;
                setTimeout(() => {
                    validateProgressInput();
                }, 0);
            });
            $(activityContent).on('click', '.activity-remove', (e) => {
                const item = $(e.target).closest('tr');
                $(item).remove();
            });
        }
        const addMoreActivityCheckout = () => {
            let activityCheckoutStart = "{{ $activityCheckoutStart }}";
            let activityCheckoutContent = $("#activityContentCheckout");
            let activityCheckoutAddMoreBtn = $("#activityAddMoreCheckout-btn");

            $(activityCheckoutAddMoreBtn).click((e) => {
                let template = `
                    <tr>
                        <td class="align-middle tw__text-center">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="input_${activityCheckoutStart}-included" name="task[${activityCheckoutStart}][include]" checked="" onclick="return false;">
                                <label for="input_${activityCheckoutStart}-included" class="custom-control-label"></label>
                            </div>
                        </td>
                        <td>
                            <input type="number" min="0" max="100" step="1" class="form-control acitivty-progress" name="task[${activityCheckoutStart}][progress]" id="input_${activityCheckoutStart}-progress" placeholder="Progress Aktivitas">
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" name="task[${activityCheckoutStart}][name]" class="form-control" id="input_${activityCheckoutStart}-name" placeholder="Judul Aktivitas">

                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger btn-sm activity-remove"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;

                $(template).appendTo($(activityContentCheckout));
                activityCheckoutStart++;
                setTimeout(() => {
                    validateProgressInput();
                }, 0);
            });
            $(activityCheckoutContent).on('click', '.activity-remove', (e) => {
                const item = $(e.target).closest('tr');
                $(item).remove();
            });
        }

        $(document).ready((e) => {
            displayTime();
            // Validate
            validateProgressInput();

            // Add More Button
            addMoreActivity();
            addMoreActivityCheckout();

            $('.input-date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
            });
            $('.input-time').daterangepicker({
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                startDate: moment().format('HH:mm'),
                timePickerIncrement: 1,
                locale: {
                    format: 'HH:mm'
                }
            }).on('show.daterangepicker', function (ev, picker) {
                picker.container.find(".calendar-table").hide();
            });

            $("#existingActivity-task").DataTable({
                order: [1, 'desc'],
                responsive: true,
                processing: true,
                serverSide: true,
                mark: true,
                ajax: {
                    url: "{{ route('system.json.datatable.task.all') }}",
                    type: "GET",
                    data: function(d){
                        d.filter_unfinished = true;
                    }
                },
                rowId: function(a) {
                    return `row-${a.id}`;
                },
                success: (result) => {
                    // console.log(result);
                },
                columns: [
                    { "data": "uuid" },
                    { "data": "progress" },
                    { "data": "name" },
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child',
                },
                columnDefs: [
                    {
                        "targets": "_all",
                        "className": "align-middle"
                    }, {
                        "targets": 0,
                        "checkboxes": {
                            "selectRow": true,
                            "selectAllPages": true,
                        },
                        "orderable": false,
                    }, 
                ]
            });
            $("#attendance-table").DataTable({
                order: [0, 'desc'],
                responsive: true,
                processing: true,
                serverSide: true,
                mark: true,
                ajax: {
                    url: "{{ route('system.json.datatable.attendance.all') }}",
                    type: "GET",
                },
                rowId: function(a) {
                    return `row-${a.id}`;
                },
                success: (result) => {
                    // console.log(result);
                },
                columns: [
                    { "data": "date" },
                    { "data": "checkin_time" },
                    { "data": "checkout_time" },
                    { "data": null },
                    { "data": null },
                ],
                columnDefs: [
                    {
                        "targets": "_all",
                        "className": "align-middle"
                    }, {
                        "targets": 0,
                        "render": (row, type, data) => {
                            return `
                                <span>${moment(row).format('Do MMMM YYYY')}</span>
                            `;
                        }
                    }, {
                        "targets": 1,
                        "render": (row, type, data) => {
                            // let template = whatsappFormat();
                            let formatedData = {
                                'name': data.user.name,
                                'date': moment(data.date).format('DD/MM/YYYY'),
                                'time': data.checkin_time,
                                'type': 'check-in'
                            };
                            let formatedTask = [];
                            if(!(jQuery.isEmptyObject(data.attendance_task))){
                                (data.attendance_task).forEach((data, row) => {
                                    formatedTask.push({
                                        'name': data.task.name,
                                        'progress': data.progress_start
                                    });
                                });
                            }
                            console.log(formatedData);
                            console.log(formatedTask);
                            let formatedWhatsapp = whatsappFormat(formatedData, formatedTask);

                            return `
                                <span>${row} WIB</span>
                                <br/>
                                <a href="https://wa.me/?text=${formatedWhatsapp}" target="_blank" class="tw__inline-flex tw__border-0 tw__text-sm tw__items-center tw__h-8 tw__px-2 tw__text-green-100 tw__transition-colors tw__duration-150 tw__bg-green-500 tw__rounded focus:tw__shadow-outline hover:tw__bg-green-600">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    <span>Share</span>
                                </a>
                            `;
                        }
                    }, {
                        "targets": 2,
                        "render": (row, type, data) => {
                            if(row == null){
                                return `-`;
                            }

                            // let template = whatsappFormat();
                            let formatedData = {
                                'name': data.user.name,
                                'date': moment(data.date).format('DD/MM/YYYY'),
                                'time': data.checkout_time,
                                'type': 'check-out'
                            };
                            let formatedTask = [];
                            if(!(jQuery.isEmptyObject(data.attendance_task))){
                                (data.attendance_task).forEach((data, row) => {
                                    formatedTask.push({
                                        'name': data.task.name,
                                        'progress': data.progress_end
                                    });
                                });
                            }
                            console.log(formatedData);
                            console.log(formatedTask);
                            let formatedWhatsapp = whatsappFormat(formatedData, formatedTask);

                            return `
                                <span>${row} WIB</span>
                                <br/>
                                <a href="https://wa.me/?text=${formatedWhatsapp}" target="_blank" class="tw__inline-flex tw__border-0 tw__text-sm tw__items-center tw__h-8 tw__px-2 tw__text-green-100 tw__transition-colors tw__duration-150 tw__bg-green-500 tw__rounded focus:tw__shadow-outline hover:tw__bg-green-600">
                                    <i class="fab fa-whatsapp mr-2"></i>
                                    <span>Share</span>
                                </a>
                            `;
                        }
                    }, {
                        "targets": 3,
                        "searchable": false,
                        "orderable": false,
                        "render": (row, type, data) => {
                            return `
                                <span class="tw__inline-flex tw__items-center tw__justify-center tw__p-1 tw__text-xs tw__font-bold tw__leading-none tw__text-blue-100 tw__bg-blue-400 tw__rounded-full">
                                    <span class="tw__inline-flex tw__items-center tw__justify-center tw__p-1 tw__text-xs tw__leading-none tw__text-blue-400 tw__bg-blue-100 tw__rounded-full tw__mr-1">${data.attendance_task_count}</span>
                                    <span class="tw__mr-1">Task</span>
                                </span>
                                <span class="tw__inline-flex tw__items-center tw__justify-center tw__p-1 tw__text-xs tw__font-bold tw__leading-none tw__text-red-100 tw__bg-indigo-400 tw__rounded-full">
                                    <span class="tw__inline-flex tw__items-center tw__justify-center tw__p-1 tw__text-xs tw__leading-none tw__text-indigo-400 tw__bg-indigo-100 tw__rounded-full tw__mr-1">${data.attendance_pause_count}</span>
                                    <span class="tw__mr-1">Pause</span>
                                </span>
                            `;
                        }
                    }, {
                        "targets": 4,
                        "searchable": false,
                        "orderable": false,
                        "render": (row, type, data) => {
                            return `-`;
                        }
                    }
                ]
            });
        });

        function displayTime()
        {
            // let myTime = setTimeout(displayTimeNow(), 1000);

            var refresh = 1000; // Refresh rate in milli seconds
            mytime = setInterval(() => {
                let data = displayTimeNow();

                $("#time-now .time").text(data.time);
                $("#time-now .date").text(data.date);
            }, refresh);
        }
        $("#modalCheckIn").submit((e) => {
            e.preventDefault();
            let targetUrl = $(e.target).attr('action');

            let selectedRow = $("#existingActivity-task").DataTable().column(0).checkboxes.selected();
            let selectedId = [];
            $.each(selectedRow, (index, data) => {
                selectedId.push(data);
            });

            $.post(targetUrl, ($(e.target).serialize())+`&validate=${selectedId}`, (result) => {
                // location.reload();
                console.log(result);
            });
        });
        $('#modalCheckIn').on('hidden.bs.modal', function (e) {
            $("#activityContent").empty();
            $(`
                <tr>
                    <td class="align-middle tw__text-center">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="input_0-included" name="task[0][include]" checked="" onclick="return false;">
                            <label for="input_0-included" class="custom-control-label"></label>
                        </div>
                    </td>
                    <td>
                        <input type="number" min="0" max="100" step="1" class="form-control acitivty-progress" name="task[0][progress]" id="input_0-progress" placeholder="Progress Aktivitas">
                    </td>
                    <td>
                        <input type="text" name="task[0][name]" class="form-control" id="input_0-name" placeholder="Judul Aktivitas">
                    </td>
                </tr>
            `).appendTo($("#activityContent"));
        });

        $("#modalCheckOut").submit((e) => {
            e.preventDefault();
            let targetUrl = $(e.target).attr('action');

            $.post(targetUrl, $(e.target).serialize(), (result) => {
                location.reload();
            });
        });

        $("#modalPause").submit((e) => {
            e.preventDefault();
            let targetUrl = $(e.target).attr('action');

            $.post(targetUrl, $(e.target).serialize(), (result) => {
                location.reload();
            });
        });
    </script>

    @if(!empty(\Auth::user()->getActiveAttendace()) && empty(\Auth::user()->getActiveAttendace()->checkout_time))
        <script>
            function displayWorkTime()
            {
                let extraTime = "{{ $pausedAccumulation }}";
                let parse = Date.parse("{{ date('m/d/Y H:i:s', strtotime(\Auth::user()->getActiveAttendace()->date.' '.\Auth::user()->getActiveAttendace()->checkin_time)) }}");
                let startWork = new Date(parse + ({{ $pausedAccumulation }} * 60000));
                @if(!empty($getPaused))
                    let parsePaused = Date.parse("{{ date('m/d/Y H:i:s', strtotime(\Auth::user()->getActiveAttendace()->date.' '.$getPaused->start)) }}");
                    let now = new Date(parsePaused);
                @else
                    let now = new Date();
                @endif

                // get total seconds between the times
                var delta = Math.abs(now - startWork) / 1000;
                // calculate (and subtract) whole days
                var days = Math.floor(delta / 86400);
                delta -= days * 86400;
                // calculate (and subtract) whole hours
                var hours = Math.floor(delta / 3600) % 24;
                delta -= hours * 3600;
                // calculate (and subtract) whole minutes
                var minutes = Math.floor(delta / 60) % 60;
                delta -= minutes * 60;
                // what's left is seconds
                var seconds = (delta % 60).toFixed();  // in theory the modulus is not required

                // Format Date Diff
                let formatedHours = hours.toString();
                formatedHours = formatedHours.length == 1 ? 0+formatedHours : formatedHours;
                let formatedMinutes = minutes.toString();
                formatedMinutes = formatedMinutes.length == 1 ? 0+formatedMinutes : formatedMinutes;
                let formatedSeconds = seconds.toString();
                formatedSeconds = formatedSeconds.length == 1 ? 0+formatedSeconds : formatedSeconds;

                $("#work-time .time").text(`${formatedHours}:${formatedMinutes}:${formatedSeconds}`);
            }

            $(document).ready((e) => {
                setInterval(() => {
                    displayWorkTime();
                }, 1000);
            });
        </script>
    @endif
@endsection