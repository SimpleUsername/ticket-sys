<div id="new-reserve">
    <form class="form-horizontal" role="form" id="new-reserve-info-form">
        <div class="form-group">
            <label for="customer-name" class="col-sm-4 control-label">Имя покупателя</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="customer_name" id="customer-name" placeholder="Полное имя покупателя">
            </div>
        </div>
        <div class="form-group">
            <label for="reserve-description" class="col-sm-4 control-label">Информации о бронировании</label>
            <div class="col-sm-8">
                <textarea class="form-control" id="reserve-description" placeholder="Не обязательно"></textarea>
            </div>
        </div>
    </form>
</div>

<script>
    $('#new-reserve-info-form').bootstrapValidator({
        fields: {
            customer_name: {
                validators: {
                    notEmpty: {
                        message: 'Обязательно введите имя покупателя'
                    },
                    stringLength: {
                        min: 5,
                        message: 'Введите ПОЛНОЕ имя покупателя (минимум 5 символов)'
                    }
                }
            }
        }
    });
    var validator = $('#new-reserve-info-form').data('bootstrapValidator');
    $("#btn-modal-confirm-new-reserve-info").on("click", function () {
        validator.validate();
        if(validator.isValid()) {
            $.post("/tickets/reserve/<?=$data['event_id']?>", {
                customer_name: $("#customer-name").val(),
                reserve_description: $("#reserve-description").val()
            }).done(function (response) {
                $("#dialog-modal").children().first().modal("hide").on('hidden.bs.modal', function () {
                    $('#dialog-modal').unbind().html(response);
                });
            });
            //TODO add .error
        }
    });
</script>