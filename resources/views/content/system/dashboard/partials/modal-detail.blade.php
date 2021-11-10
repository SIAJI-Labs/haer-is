<div class="modal fade" id="modalDetail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Kehadiran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-striped table-bordered">
                    <tr>
                        <th>Tanggal</th>
                        <td>
                            <span class="date"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Lama Waktu Bekerja</th>
                        <td>
                            <span class="work-time"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Lama Timer Berhenti</th>
                        <td>
                            <span class="pause-time"></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>
                            <span class="location"></span>
                        </td>
                    </tr>
                </table>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Task Activity</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-striped table-bordered mb-4">
                            <tr>
                                <th>Jumlah Task</th>
                                <td>
                                    <span class="task-count"></span>
                                </td>
                            </tr>
                        </table>

                        <ul class="list-group" id="taskList">
                        </ul>
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
</div>
<!-- /.modal -->