<form class="modal fade" id="modalPause" action="{{ route('system.attendance.update', (!empty($activeAttendance) ? $activeAttendance->uuid : 0)) }}" method="POST">
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