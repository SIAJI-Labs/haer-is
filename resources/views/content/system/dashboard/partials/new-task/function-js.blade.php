<script>
    function newTask(uuid, checkinDate){
        $.get(`{{ route('system.json.attendance.index') }}/${uuid}`, (result) => {
            console.log(result);
            let data = result.data;

            let currTaskTemplate = [];
            $.each(data.attendance_task, (row, data) => {
                console.log(data);

                currTaskTemplate.push(`
                    <tbody>
                        <tr>
                            <td class="align-middle tw__text-center" rowspan="2">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" checked="" onclick="return false;">
                                    <label class="custom-control-label"></label>
                                </div>
                            </td>
                            <td>
                                <input type="number" min="0" max="100" step="1" class="form-control newActivity-progress" placeholder="Progress Aktivitas" value="${data.task.progress}" readonly>
                            </td>
                            <td>
                                <input type="text" class="form-control" placeholder="Judul Aktivitas" value="${data.task.name}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea class="form-control" placeholder="Catatan Aktivitas (Opsional)" readonly>${data.task.notes}</textarea>
                                <small class="text-muted">**Catatan dapat dirubah ketika anda melakukan Check-out</small>
                            </td>
                        </tr>
                    </tbody>
                `);
            });
            if(!(jQuery.isEmptyObject(currTaskTemplate))){
                $("#modalNewTask #newActivity-task").prepend($(currTaskTemplate.join()));
            }

            setTimeout((e) => {
                $("#modalNewTask").modal('show');
            }, 0);
        });

    }
    
    const addMoreNewActivity = () => {
        let newActivityStart = 1;
        let newActivityContent = $("#newActivity-task");
        let newActivityAddMoreBtn = $("#newActivityAddMore-btn");

        $(newActivityAddMoreBtn).click((e) => {
            let template = `
                <tbody>
                    <tr>
                        <td class="align-middle tw__text-center" rowspan="2">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="input_${newActivityStart}_new-included" name="task[${newActivityStart}][include]" checked="" onclick="return false;">
                                <label for="input_${newActivityStart}_new-included" class="custom-control-label"></label>
                            </div>
                        </td>
                        <td>
                            <input type="number" min="0" max="100" step="1" class="form-control newActivity-progress" name="task[${newActivityStart}][progress]" id="input_${newActivityStart}_new-progress" placeholder="Progress Aktivitas">
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" name="task[${newActivityStart}][name]" class="form-control" id="input_${newActivityStart}_new-name" placeholder="Judul Aktivitas">

                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger btn-sm activity_new-remove"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <textarea class="form-control" id="input_0_new-note" name="task[${newActivityStart}][note]" placeholder="Catatan Aktivitas (Opsional)"></textarea>
                        </td>
                    </tr>
                </tbody>
            `;

            $(template).appendTo($(newActivityContent));
            newActivityStart++;
            setTimeout(() => {
                validateNewProgressInput();
            }, 0);
        });
        $(newActivityContent).on('click', '.activity_new-remove', (e) => {
            const item = $(e.target).closest('tbody');
            console.log(e.target);
            console.log(item);
            $(item).remove();
        });
    }

    $("#modalNewTask").submit((e) => {
        e.preventDefault();
        let targetUrl = $(e.target).attr('action');

        $.post(targetUrl, ($(e.target).serialize()), (result) => {
            location.reload();
            // console.log(result);
        });
    });
    $('#modalNewTask').on('hidden.bs.modal', function (e) {
        $("#newActivity-task tbody").remove();
        $(`
            <tbody>
                <tr>
                    <td class="align-middle tw__text-center" rowspan="2">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="input_0_new-included" name="task[0][include]" checked="" onclick="return false;">
                            <label for="input_0_new-included" class="custom-control-label"></label>
                        </div>
                    </td>
                    <td>
                        <input type="number" min="0" max="100" step="1" class="form-control activity-progress" name="task[0][progress]" id="input_0_new-progress" placeholder="Progress Aktivitas">
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
        `).appendTo($("#newActivity-task"));
    });
</script>