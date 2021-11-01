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
                                        <input type="number" min="0" max="100" step="1" class="form-control activity-progress" name="task[0][progress]" id="input_0-progress" placeholder="Progress Aktivitas">
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
                <button type="submit" class="btn tw__bg-blue-400">Submit</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</form>
<!-- /.modal -->