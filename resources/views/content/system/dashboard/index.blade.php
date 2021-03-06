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
    {{-- Select2 --}}
    @include('layouts.partials.plugins.select2-css')
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
        <div class="col-12 col-lg-3 d-flex align-items-stretch">
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

        @php
            $activeAttendance = \Auth::user()->getActiveAttendace();
        @endphp
        <div class="col-12 col-lg-9 d-flex align-items-stretch">
            <div class="card tw__w-full">
                <div class="card-header">
                    <h3 class="card-title">Kehadiran{{ !empty($activeAttendance) ? ' - '.date("d/m/Y", strtotime($activeAttendance->date)) : '' }}</h3>
                </div>
                <div class="card-body">
                    @php
                        $getPaused = [];
                        $pausedAccumulation = 0;
                        if(!empty($activeAttendance)){
                            $getPaused = \App\Models\AttendancePause::where('attendance_id', $activeAttendance->id)
                                ->whereNull('end')
                                ->orderBy('created_at', 'desc')
                                ->first();
                            $pausedAccumulation = \App\Models\AttendancePause::where('attendance_id', $activeAttendance->id)
                                ->whereNotNull('end')
                                ->sum('duration');
                        }
                    @endphp
                    
                    <div class="row">
                        <div class="col-12 col-lg-5 tw__mb-4 lg:tw__mb-0">
                            <div class="tw__bg-{{ !empty($activeAttendance) && !empty($activeAttendance->checkout_time) ? 'blue' : (!empty($getPaused) ? 'yellow' : 'gray') }}-200 tw__rounded tw__h-full tw__w-full tw__flex tw__items-center tw__justify-center tw__flex-col tw__p-4" id="time-work">
                                @if (!empty($activeAttendance) && empty($activeAttendance->checkout_time))
                                    <div id="work-time" class="text-center">
                                        @if (!empty($getPaused))
                                            <h3 class="time mb-0 tw__block" data-time="{{ date('m/d/Y H:i:s', strtotime($activeAttendance->date.' '.$getPaused->start)) }}">HH:mm:ss</h3>
                                            <small>Waktu dihentikan sejak <u><span class="time-pause">HH:mm</span> WIB</u></small>
                                        @else
                                            <h3 class="time mb-0 tw__block" data-time="{{ date('m/d/Y H:i:s', strtotime($activeAttendance->date.' '.$activeAttendance->checkin_time.' +'.$pausedAccumulation.' minutes')) }}">HH:mm:ss</h3>
                                            <small>Waktu Bekerja</small>
                                        @endif
                                        <br/>
                                        <small>Check-in dilakukan pada <u>{{ $activeAttendance->checkin_time }} WIB</u></small>
                                    </div>
                                @elseif(!empty($activeAttendance->checkout_time))
                                    <span class="tw__text-center">Anda sudah melakukan check-in hari ini.</span>
                                @else
                                    <span class="tw__text-center">Anda belum melakukan check-in hari ini.</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-lg-7">
                            <button type="button" class="btn d-block w-100 tw__mb-1 btn-primary" @if(!empty($activeAttendance)) disabled @else data-toggle="modal" data-target="#modalCheckIn" @endif>Check-in</button>

                            @if (!empty($getPaused))
                                <button type="button" class="btn d-block w-100 tw__mb-1 btn-success tw__text-white" @if(!empty($activeAttendance)) @if(empty($activeAttendance->checkout_time)) data-toggle="modal" data-target="#modalPause" @else disabled @endif @endif>Lanjutkan Timer</button>
                            @else
                                <button type="button" class="btn d-block w-100 tw__mb-1 btn-info tw__text-white" @if(!empty($activeAttendance)) @if(empty($activeAttendance->checkout_time)) data-toggle="modal" data-target="#modalPause" @else disabled @endif @else disabled @endif>Hentikan Timer</button>
                            @endif

                            <button type="button" class="btn d-block w-100 btn-warning tw__text-white" @if(empty($activeAttendance) || !empty($activeAttendance->checkout_time)) disabled @else @if(!empty($getPaused)) disabled @else onclick="checkOut('{{ $activeAttendance->uuid }}')" @endif @endif>Check-out</button>
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
            <div class="card">
                <div class="card-header tw__flex tw__items-center">
                    <h3 class="card-title">Filter</h3>

                    <button class="btn btn-sm btn-secondary tw__ml-auto" id="filter-now" disabled>Periode Sekarang</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-6 form-group">
                            <label>Tahun</label>
                            <select class="form-control" id="input_filter-year">
                                @for ($i = date("Y"); $i >= date("Y", strtotime('2015-01-01')); $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            <label>Bulan</label>
                            <select class="form-control" id="input_filter-month">
                                @for ($i = 12; $i >= date("m", strtotime(date("Y").'-01-01')); $i--)
                                    <option value="{{ $i }}" id="input_filter-option_month_{{ $i }}">{{ dateFormat(date("Y-m-d", strtotime(date("Y").'-'.$i.'-'.date("d"))), 'months') }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>

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
        <div class="card-footer">
            <button class="btn btn-secondary btn-sm" onclick="refreshData($(this))"><i class="fas fa-sync-alt mr-2"></i>Refresh Data</button>
        </div>
    </div>
</section>
@endsection

@section('content_modal')
    {{-- Checkin Modal --}}
    @include('content.system.dashboard.partials.check-in.modal')

    {{-- Checkout Modal --}}
    @include('content.system.dashboard.partials.check-out.modal')

    {{-- New Task Modal --}}
    @include('content.system.dashboard.partials.new-task.modal')

    {{-- Pause Modal --}}
    @include('content.system.dashboard.partials.modal-pause')

    {{-- Detail Modal --}}
    @include('content.system.dashboard.partials.modal-detail')
@endsection

@section('js_plugins')
    {{-- Daterange Picker --}}
    @include('layouts.partials.plugins.daterange-picker-js')
    {{-- Datatable --}}
    @include('layouts.partials.plugins.datatable-js')
    {{-- Select2 --}}
    @include('layouts.partials.plugins.select2-js')
@endsection

@section('js_inline')
    {{-- Check-in --}}
    @include('content.system.dashboard.partials.check-in.function-js')
    {{-- Check-out --}}
    @include('content.system.dashboard.partials.check-out.function-js')
    {{-- New Task --}}
    @include('content.system.dashboard.partials.new-task.function-js')

    <script>
        const validateProgressInput = () => {
            $(".activity-progress").change((e) => {
                // Validate Value
                if($(e.target).val() > 100){
                    $(e.target).val(100);
                } else if($(e.target).val() < 0){
                    $(e.target).val(0);
                }
            });
        }
        const validateNewProgressInput = () => {
            $(".newActivity-progress").change((e) => {
                // Validate Value
                if($(e.target).val() > 100){
                    $(e.target).val(100);
                } else if($(e.target).val() < 0){
                    $(e.target).val(0);
                }
            });
        }
        const filterAttendanceDateTable = () => {
            let currYear = "{{ date('Y') }}";
            let selectedYear = $("#input_filter-year").val();

            if(currYear == selectedYear){
                let currMonth = "{{ date('m') }}";
                $("#input_filter-month").val("{{ date('m') }}").change();
                for(let i = (parseInt(currMonth) + 1); i <= 12; i++){
                    $(`#input_filter-option_month_${i}`).prop('disabled', true);
                }
            } else {
                $("#filter-now").attr('disabled', false);

                for(let i = 1; i <= 12; i++){
                    $(`#input_filter-option_month_${i}`).prop('disabled', false);
                }
            }

            $("#input_filter-month").select2({
                theme: 'bootstrap4',
                placeholder: 'Bulan Kehadiran'
            });
        }

        $(document).ready((e) => {
            displayTime();
            // Validate
            validateProgressInput();

            // Add More Button
            addMoreActivity();
            addMoreActivityCheckout();
            addMoreNewActivity();

            // Datatable Filter
            filterAttendanceDateTable();

            $("#input_filter-year").select2({
                theme: 'bootstrap4',
                placeholder: 'Tahun Kehadiran'
            });
            $("#input_filter-month").select2({
                theme: 'bootstrap4',
                placeholder: 'Bulan Kehadiran'
            });

            $('.input-date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
            });
            $('.input-time').daterangepicker({
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                startDate: moment().format('HH:mm'),
                // maxDate: "{{ date("H:i:s") }}",
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
                    data: function(d){
                        d.filter_year = $("#input_filter-year").val();
                        d.filter_month = $("#input_filter-month").val();
                    }
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
                            let rawDate = new Date(moment(row));
                            let date = `${convertMomentJsToIndonesia(rawDate, 'days')}, ${moment(rawDate).format('DD')} ${convertMomentJsToIndonesia(rawDate, 'months')} ${moment(rawDate).format('YYYY')}`;
                            return `
                                <span>${date}</span>
                                <hr class="my-1"/>
                                <small>Lokasi: ${!(jQuery.isEmptyObject(data.location)) ? data.location.value : '-'}</small>
                            `;
                        }
                    }, {
                        "targets": 1,
                        "render": (row, type, data) => {
                            let uuid = data.uuid;
                            // let template = whatsappFormat();
                            // Check-in Format
                            let formatedData = {
                                'name': data.user.name,
                                'date': moment(data.date).format('DD/MM/YYYY'),
                                'time': data.checkin_time,
                                'type': 'check-in',
                                'location': !(jQuery.isEmptyObject(data.location)) ? data.location.value : '-'
                            };
                            let formatedTask = [];
                            if(!(jQuery.isEmptyObject(data.attendance_task))){
                                (data.attendance_task).forEach((data, row) => {
                                    if(data.added_on == null || data.added_on == 'check-in'){
                                        var encodedStr = data.task.name;
                                        var parser = new DOMParser;
                                        var dom = parser.parseFromString('<!doctype html><body>' + encodedStr, 'text/html');
                                        var decodedString = dom.body.textContent;
                                        
                                        formatedTask.push({
                                            'name': decodedString,
                                            'progress': data.progress_start,
                                        });
                                    }
                                });
                            }
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
                                'type': 'check-out',
                                'location': !(jQuery.isEmptyObject(data.location)) ? data.location.value : '-'
                            };
                            let formatedTask = [];
                            if(!(jQuery.isEmptyObject(data.attendance_task))){
                                (data.attendance_task).forEach((data, row) => {
                                    var encodedStr = data.task.name;
                                    var parser = new DOMParser;
                                    var dom = parser.parseFromString('<!doctype html><body>' + encodedStr, 'text/html');
                                    var decodedString = dom.body.textContent;

                                    formatedTask.push({
                                        'name': decodedString,
                                        'progress': data.progress_end
                                    });
                                });
                            }
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
                                <div class="block mb-1">
                                    <span class="tw__inline-flex tw__items-center tw__justify-center tw__p-1 tw__text-xs tw__font-bold tw__leading-none tw__text-blue-100 tw__bg-blue-400 tw__rounded-full">
                                        <span class="tw__inline-flex tw__items-center tw__justify-center tw__p-1 tw__text-xs tw__leading-none tw__text-blue-400 tw__bg-blue-100 tw__rounded-full tw__mr-1 tw__h-4" style="min-width: 1rem;">${data.attendance_task_count}</span>
                                        <span class="tw__mr-1">Task</span>
                                    </span>
                                </div>
                                <div class="block">
                                    <span class="tw__inline-flex tw__items-center tw__justify-center tw__p-1 tw__text-xs tw__font-bold tw__leading-none tw__text-red-100 tw__bg-indigo-400 tw__rounded-full">
                                        <span class="tw__inline-flex tw__items-center tw__justify-center tw__p-1 tw__text-xs tw__leading-none tw__text-indigo-400 tw__bg-indigo-100 tw__rounded-full tw__mr-1 tw__h-4" style="min-width: 1rem;">${data.attendance_pause_count}x</span>
                                        <span class="tw__mr-1">Pause</span>
                                    </span>
                                </div>
                            `;
                        }
                    }, {
                        "targets": 4,
                        "searchable": false,
                        "orderable": false,
                        "render": (row, type, data) => {
                            let button = [];
                            let now = new Date();
                            let currData = new Date(data.date);

                            let formatedNow = moment(now).format("DD/MM/YYYY");
                            let formatedCurr = moment(currData).format("DD/MM/YYYY");
                            if(formatedNow === formatedCurr && data.checkout_time == null){
                                button.push(`<button type="button" class="btn btn-sm btn-info" onclick="newTask('${data.uuid}', '${data.date}')">Tambah Task</button>`);
                            }
                            if(formatedCurr < formatedNow && data.checkout_time == null){
                                button.push(`<button type="button" class="btn btn-sm btn-warning" onclick="checkOut('${data.uuid}')">Check-out</button>`);
                            }
                            button.push(`<button type="button" class="btn btn-sm btn-primary" onclick="attendanceDetail('${data.uuid}')">Detail</button>`);

                            return jQuery.isEmptyObject(button) ? `-` : `<div class="btn-group">${button.join('')}</div>`;
                        }
                    }
                ]
            });

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
        });

        $("#input_filter-year").change((e) => {
            filterAttendanceDateTable();

            setTimeout((e) => {
                $("#attendance-table").DataTable().ajax.reload();
            });
        });
        $("#input_filter-month").change((e) => {
            setTimeout((e) => {
                let currYear = "{{ date('Y') }}";
                let currMonth = "{{ date('m') }}";
                if($("#input_filter-month").val() != currMonth || $("#input_filter-year").val() != currYear){
                    $("#filter-now").attr('disabled', false);
                } else {
                    $("#filter-now").attr('disabled', true);
                }

                $("#attendance-table").DataTable().ajax.reload();
            });
        });
        $("#filter-now").click((e) => {
            $("#input_filter-year").val("{{ date("Y") }}").change();

            $(e.target).attr('disabled', true);
        });

        function refreshData(el){
            $(el).attr('disabled', true);
            $(el).html('<i class="fas fa-sync fa-spin mr-2"></i>Loading...');
            $("#attendance-table").DataTable().ajax.reload((e) => {
                setTimeout((e) => {
                    $(el).attr('disabled', false);
                    $(el).html('<i class="fas fa-sync mr-2"></i>Refresh Data');
                }, 250);
            });
        }

        function displayTime(){
            // let myTime = setTimeout(displayTimeNow(), 1000);

            var refresh = 1000; // Refresh rate in milli seconds
            mytime = setInterval(() => {
                let data = displayTimeNow();
                let rawDate = new Date(moment(data.date, 'DD/MM/YYYY'));
                // console.log(data);
                // console.log(rawDate);
                // console.log(moment(rawDate).format('dddd'));
                let date = `${convertMomentJsToIndonesia(rawDate, 'days')}, ${moment(rawDate).format('DD')} ${convertMomentJsToIndonesia(rawDate, 'months')} ${moment(rawDate).format('YYYY')}`;

                $("#time-now .time").text(data.time);
                $("#time-now .date").text(date);
            }, refresh);
        }

        function attendanceDetail(uuid){
            $.get(`{{ route('system.json.attendance.index') }}/${uuid}`, (result) => {
                let data = result.data;
                let now = new Date();
                console.log(now.toDateString());
                let rawDate = new Date(moment(data.date));
                console.log(rawDate.toDateString());

                let workDuration = 0;
                let workTime = [];
                workTime.push(`Check-in: ${data.checkin_time} WIB`);
                if(data.checkout_time != null){
                    workTime.push(`Check-out: ${data.checkout_time} WIB`);
                }

                $("#modalDetail .date").text(`${convertMomentJsToIndonesia(rawDate, 'days')}, ${moment(rawDate).format('DD')} ${convertMomentJsToIndonesia(rawDate, 'months')} ${moment(rawDate).format('YYYY')}`);
                $("#modalDetail .work-time").text(workTime.join(' / '));
                $("#modalDetail .pause-time").text(`${data.pauseAccumulation} menit`);
                $("#modalDetail .location").text(!(jQuery.isEmptyObject(data.location)) ? data.location.value : '-');

                $("#modalDetail #taskList").empty();
                let task = [];
                if(!(jQuery.isEmptyObject(data.attendance_task))){
                    (data.attendance_task).forEach((data, row) => {
                        task.push(`
                            <li class="list-group-item">
                                <span>${data.task.name}</span>
                                
                                <div class="tw__mt-2">
                                    <small>Progress from ${data.progress_start} to ${data.progress_end ?? '-'}</small>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: ${data.progress_end ?? data.progress_start}%;" aria-valuenow="${data.progress_end ?? data.progress_start}" aria-valuemin="0" aria-valuemax="100">${data.progress_end ?? data.progress_start}%</div>
                                    </div>
                                </div>
                            </li>
                        `);
                    });
                    
                    $(task.join('')).appendTo($("#modalDetail #taskList"));
                }
                $("#modalDetail .task-count").text(task.length);

                setTimeout((e) => {
                    $("#modalDetail").modal('show');
                });
            });
        }

        $("#modalPause").submit((e) => {
            e.preventDefault();
            let targetUrl = $(e.target).attr('action');

            $.post(targetUrl, $(e.target).serialize(), (result) => {
                Swal.fire({
                    title: "Aksi Berhasil",
                    text: result.message,
                    icon: 'success',
                    confirmButtonText: 'Tutup Pesan!',
                    reverseButtons: true,
                }).then((result) => {
                    location.reload();
                });
            });
        });
    </script>

    @if(!empty($activeAttendance) && empty($activeAttendance->checkout_time))
        <script>
            function displayWorkTime()
            {
                let startTime = $("#work-time .time").attr('data-time');
                // let parse = Date.parse("{{ date('m/d/Y H:i:s', strtotime($activeAttendance->date.' '.$activeAttendance->checkin_time)) }}");
                // let startWork = new Date(parse + ({{ $pausedAccumulation }} * 60000));

                let parse = Date.parse(startTime);
                let startWork = new Date(parse);
                @if(!empty($getPaused))
                    let parsePaused = Date.parse("{{ date('m/d/Y H:i:s', strtotime($activeAttendance->date.' '.$getPaused->start)) }}");
                    $("#work-time .time-pause").text(moment(parsePaused).format('HH:mm'));
                @endif

                let now = new Date();

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