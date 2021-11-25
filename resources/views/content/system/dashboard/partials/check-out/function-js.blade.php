<script>
    var activityCheckoutStart = 0;

    function checkOut(uuid){
        activityCheckoutStart = 0;

        $.get(`{{ route('system.json.attendance.index') }}/${uuid}`, (result) => {
            let data = result.data

            // Append Attendance Alert
            let checkoutAlert = $("#modalCheckOut #checkout-alert");
            $(checkoutAlert).empty();
            $(`
                <div class="tw__mb-4 tw__bg-blue-100 tw__text-blue-700 tw__px-4 tw__py-3 tw__rounded tw__relative" role="alert">
                    <strong class="tw__font-bold">Data Kehadiran!</strong>
                    <span class="tw__block">Anda melakukan check-in kehadiran pada <u>${convertMomentJsToIndonesia(data.date, 'days')}, ${moment(data.date).format('MMMM Do, YYYY')}, ${data.checkin_time} WIB</u></span>
                </div>
            `).appendTo($(checkoutAlert));

            if(!(jQuery.isEmptyObject(data.location))){
                $('#input-checkout_location').val(`${data.location.value}${data.location.is_default ? ' - Default' : ''}`);
            }
            // Update Data
            $("#modalCheckOut").attr('action', `{{ route('system.attendance.index') }}/${data.uuid}`);
            $('#input-checkout_date').data('daterangepicker').setStartDate(moment(data.date).format('DD/MM/YYYY'));
            $('#input-checkout_date').data('daterangepicker').setEndDate(moment(data.date).format('DD/MM/YYYY'));
            $("#input-checkout_date").val(moment(data.date).format('DD/MM/YYYY'));
            // Update Task Data
            let taskTemplate = [];
            let contentContainer = $("#modalCheckOut #activity-task_checkout");
            $(contentContainer).find('tbody').remove();

            let taskCount = 0;
            $.each(data.attendance_task, (row, data) => {
                console.log(data);

                taskTemplate.push(`
                    <tbody>
                        <tr>
                            <td class="align-middle tw__text-center" rowspan="2">
                                <input type="hidden" name="task[${row}][validate]" value="${data.uuid}" readonly>

                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="input_${row}-checkout_included" name="task[${row}][include]" checked="" onclick="return false;">
                                    <label for="input_0-checkout_included" class="custom-control-label"></label>
                                </div>
                            </td>
                            <td>
                                <input type="number" min="0" max="100" step="1" class="form-control activity-progress" name="task[${row}][progress]" id="input_${row}-checkout_progress" value="${data.progress_end}" placeholder="Progress Aktivitas">
                            </td>
                            <td>
                                <input type="text" name="task[${row}][name]" class="form-control" id="input_${row}-checkout_name" value="${data.task.name}" placeholder="Judul Aktivitas" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea class="form-control" id="input_${row}-checkout_note" name="task[${row}][note]" placeholder="Catatan Aktivitas (Opsional)">${data.task.notes ?? ''}</textarea>
                            </td>
                        </tr>
                    </tbody>
                `);
                taskCount++;
            });
            if(!(jQuery.isEmptyObject(taskTemplate))){
                activityCheckoutStart = taskCount;
                $(taskTemplate.join()).appendTo($(contentContainer));
            }

            setTimeout((e) => {
                $("#modalCheckOut").modal('show');
            });
        });
    }
    
    const addMoreActivityCheckout = () => {
        let activityCheckoutContent = $("#activity-task_checkout");
        let activityCheckoutAddMoreBtn = $("#activityAddMoreCheckout-btn");

        $(activityCheckoutAddMoreBtn).click((e) => {
            let template = `
                <tbody>
                    <tr>
                        <td class="align-middle tw__text-center" rowspan="2">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="input_${activityCheckoutStart}-included" name="task[${activityCheckoutStart}][include]" checked="" onclick="return false;">
                                <label for="input_${activityCheckoutStart}-included" class="custom-control-label"></label>
                            </div>
                        </td>
                        <td>
                            <input type="number" min="0" max="100" step="1" class="form-control activity-progress" name="task[${activityCheckoutStart}][progress]" id="input_${activityCheckoutStart}-progress" placeholder="Progress Aktivitas">
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" name="task[${activityCheckoutStart}][name]" class="form-control" id="input_${activityCheckoutStart}-name" placeholder="Judul Aktivitas">

                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger btn-sm activity_checkout-remove"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <textarea class="form-control" id="input_${activityCheckoutStart}-checkout_note" name="task[${activityCheckoutStart}][note]" placeholder="Catatan Aktivitas (Opsional)"></textarea>
                        </td>
                    </tr>
                </tbody>
            `;

            $(template).appendTo($(activityCheckoutContent));
            activityCheckoutStart++;
            setTimeout(() => {
                validateProgressInput();
            }, 0);
        });
        $(activityCheckoutContent).on('click', '.activity_checkout-remove', (e) => {
            const item = $(e.target).closest('tbody');
            $(item).remove();
        });
    }

    $("#modalCheckOut").submit((e) => {
        e.preventDefault();
        let targetUrl = $(e.target).attr('action');

        $.post(targetUrl, $(e.target).serialize(), (result) => {
            location.reload();
        });
    });
</script>