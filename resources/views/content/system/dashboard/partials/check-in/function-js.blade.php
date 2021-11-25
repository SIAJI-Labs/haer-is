<script>
    const addMoreActivity = () => {
        let activityStart = 1;
        let activityContent = $("#activity-task");
        let activityAddMoreBtn = $("#activityAddMore-btn");

        $(activityAddMoreBtn).click((e) => {
            let template = `
                <tbody>
                    <tr>
                        <td class="align-middle tw__text-center" rowspan="2">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="input_${activityStart}-included" name="task[${activityStart}][include]" checked="" onclick="return false;">
                                <label for="input_${activityStart}-included" class="custom-control-label"></label>
                            </div>
                        </td>
                        <td>
                            <input type="number" min="0" max="100" step="1" class="form-control activity-progress" name="task[${activityStart}][progress]" id="input_${activityStart}-progress" placeholder="Progress Aktivitas">
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
                    <tr>
                        <td colspan="2">
                            <textarea class="form-control" name="task[${activityStart}][note]" id="input_${activityStart}-note" placeholder="Catatan Aktivitas (Opsional)"></textarea>
                        </td>
                    </tr>
                </tbody>
            `;

            $(template).appendTo($(activityContent));
            activityStart++;
            setTimeout(() => {
                validateProgressInput();
            }, 0);
        });
        $(activityContent).on('click', '.activity-remove', (e) => {
            const item = $(e.target).closest('tbody');
            $(item).remove();
        });
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
            location.reload();
            // console.log(result);
        });
    });
    $('#modalCheckIn').on('hidden.bs.modal', function (e) {
        // $("#activityContent").empty();
        $("#activity-task tbody").remove();
        $(`
            <tbody>
                <tr>
                    <td class="align-middle tw__text-center" rowspan="2">
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
                <tr>
                    <td colspan="2">
                        <textarea class="form-control" id="input_0-note" name="task[0][note]" placeholder="Catatan Aktivitas (Opsional)"></textarea>
                    </td>
                </tr>
            </tbody>
        `).appendTo($("#activity-task"));
    });
</script>