<div id="customer-search">
    <div class="form-inline row">
        <? // if event_id set - so it's reserving. else it's reserve search ?>
        <div class="form-group col-sm-<?=isset($data['event_id'])?"8":"12" ?>">
            <div class="input-group">
                <span class="input-group-addon">Поиск</span>
                <input class="form-control" style="width: 100%" placeholder="Имя покупателя" id="customer-name">
            </div>
        </div>
        <? if (isset($data['event_id'])) { ?>
        <div class="form-group col-sm-4">
            <button class="btn btn-primary" id="new-customer-create">Добавить нового покупателя</button>
        </div>
        <? } ?>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12 text-center">
            <img src="/images/ajax-loader.gif" id="loading-animation">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="list-group" id="customers-list">
            </div>
        </div>
    </div>
</div>
<? if (isset($data['event_id'])) { ?>
<div class="row" id="new-customer">
    <div class="col-sm-12">
        <h3>Добавление нового покупателя</h3>
        <form class="form-horizontal" role="form" onsubmit="return false" id="customer-create-form">
            <div class="form-group">
                <label for="new-customer-name" class="col-sm-2 control-label">Имя</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="new-customer-name" placeholder="Полное имя покупателя">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Примечания</label>
                <div class="col-sm-10">
                    <textarea id="new-customer-description" class="form-control" placeholder="Не обязательно"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary" id="new-customer-create-confirm">Создать</button>
                    <button class="btn btn-default" id="new-customer-create-cancel">Отмена</button>
                </div>
            </div>
        </form>
    </div>
</div>
<? } ?>
<script>
    var customersSearchTimeout;
    $("#loading-animation").hide();
    $("#customers-list").hide();
    $("#new-customer").hide();
    $("#customer-name").keyup(function() {
        clearTimeout(customersSearchTimeout);
        customersSearchTimeout = setTimeout(function () {
            $("#customers-list").hide();
            if ($("#customer-name").val().length > 0) {
                $("#loading-animation").show();
                $.post("/tickets/getCustomers", {customer_name : $("#customer-name").val()}, function(data) {
                    $("#loading-animation").hide();
                    $("#customers-list").html("");
                    if (data.length == 0) {
                        $("#customers-list").append('<a href="#" class="list-group-item disabled list-group-item-warning">Покупатели не найдены!</a>');
                    }
                    for (var i = 0; i < data.length; i++) {
                        $("#customers-list").append(
                            '<a href="#" onclick="return customerClick(' + data[i].customer_id+ ')" class="list-group-item customer">' +
                            '<h4 class="list-group-item-heading">' +
                            data[i].customer_name + '</h4>'+
                            '<p class="list-group-item-text">'+
                            data[i].customer_description + '</p></a>');
                    }
                    $("#customers-list").slideDown();
                }, "json");
            }
        },750);
    });
    function customerClick(customerId) {
        <? if (isset($data['event_id'])) { ?>
        $.post("/tickets/reserve/<?=$data['event_id']?>", {customer_id : customerId}, function(data) {
        <? } else { ?>
        $.post("/tickets/reserveSearch/"+customerId+"/", function(data) {
        <? } ?>
            $("#dialog-modal").children().first().modal("hide").on('hidden.bs.modal', function () {
                $('#dialog-modal').unbind().html(data);
            });
        });
        return false;
    }
    <? if (isset($data['event_id'])) { ?>
    $("#new-customer-create").on("click", function() {
        $("#new-customer-name").val($("#customer-name").val());
        $("#new-customer-create-confirm").removeClass("disabled").removeAttr("disabled");
        $("#customer-search").slideUp();
        $("#new-customer").slideDown();
    });
    $("#new-customer-create-cancel").on("click", function() {
        $("#customer-search").slideDown();
        $("#new-customer").slideUp();
    });
    $("#new-customer-create-confirm").on("click", function() {
        if ($("#new-customer-name").val().length > 0) {
            $("#new-customer-create-confirm").addClass("disabled");
            $.post("/tickets/addCustomer", {
                customer_name: $("#new-customer-name").val(),
                customer_description: $("#new-customer-description").val()
            }).done(function (response) {
                $("#new-customer-create-confirm").removeClass("disabled");
                var customerName = $("#new-customer-name").val();
                $("#new-customer-name").val("");
                $("#new-customer-description").val("");
                $("#customer-search").slideDown();
                $("#new-customer").slideUp();
                $("#customer-name").val(customerName).trigger('keyup');
            });
        }
    });
    $('#customer-create-form').bootstrapValidator({
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'Обязательно введите имя покупателя'
                    }
                }
            }
        }
    });
    <? } ?>
</script>