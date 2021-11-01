<form class="modal fade" id="modalLocation" action="{{ route('system.profile-preference.update', \Auth::user()->uuid) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="hidden" name="type" value="location" readonly>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pengaturan Data Lokasi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="locationContent">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input location-is_default" type="checkbox" id="input_0-is_default" name="location[0][is_default]">
                                        <label for="input_0-is_default" class="custom-control-label">Default</label>
                                    </div>
                                </span>
                            </div>
                            <input type="text" class="form-control" name="location[0][name]" id="input_0-location" placeholder="Lokasi">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-primary" id="locationAddMore-btn"><i class="fas fa-plus mr-2"></i>Tambah Lainnya</button>
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