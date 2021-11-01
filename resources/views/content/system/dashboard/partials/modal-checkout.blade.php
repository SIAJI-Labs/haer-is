<form class="modal fade" id="modalCheckOut" action="{{ route('system.attendance.index') }}" method="POST">
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
                <div id="checkout-alert"></div>

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
                                @if ($activeAttendance)
                                    @foreach ($activeAttendance->attendanceTask as $key =>  $item)
                                        <tr>
                                            <td class="align-middle tw__text-center">
                                                <input type="hidden" name="task[{{ $key }}][validate]" value="{{ $item->id }}" readonly>

                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="input_{{ $key }}-checkout_included" name="task[{{ $key }}][include]" checked="" onclick="return false;">
                                                    <label for="input_0-checkout_included" class="custom-control-label"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" min="0" max="100" step="1" class="form-control activity-progress" name="task[{{ $key }}][progress]" id="input_{{ $key }}-checkout_progress" value="{{ $item->progress_start }}" placeholder="Progress Aktivitas">
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
                                            <input type="number" min="0" max="100" step="1" class="form-control activity-progress" name="task[0][progress]" id="input_0-checkout_progress" placeholder="Progress Aktivitas">
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