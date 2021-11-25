<form class="modal fade" id="modalNewTask" action="{{ route('system.attendance.update', (!empty($activeAttendance) ? $activeAttendance->uuid : 0)) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="hidden" name="type" value="new_task" readonly>

    <div class="form-group">
        <input type="hidden" name="added_on" value="working" readonly>
    </div>

    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Aktivitas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover" id="newActivity-task">
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
                    <tbody>
                        <tr>
                            <td class="align-middle tw__text-center" rowspan="2">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="input_0_new-included" name="task[0][include]" checked="" onclick="return false;">
                                    <label for="input_0_new-included" class="custom-control-label"></label>
                                </div>
                            </td>
                            <td>
                                <input type="number" min="0" max="100" step="1" class="form-control newActivity-progress" name="task[0][progress]" id="input_0_new-progress" placeholder="Progress Aktivitas">
                            </td>
                            <td>
                                <input type="text" name="task[0][name]" class="form-control" id="input_0_new-name" placeholder="Judul Aktivitas">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea class="form-control" id="input_0_new-note" name="task[0][note]" placeholder="Catatan Aktivitas (Opsional)"></textarea>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <button type="button" class="btn btn-sm btn-primary" id="newActivityAddMore-btn"><i class="fas fa-plus mr-2"></i>Tambah Lainnya</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
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