<div id="reserve-search">
    <form class="form-horizontal" role="form" id="reserve-search-form">
        <div class="form-group">
            <label for="event" class="col-sm-4 control-label">Событие</label>
            <div class="col-sm-7">
                <select class="form-control" name="event_id" id="event-id">
                    <? foreach ($data['events'] as $event) { ?>
                        <option value="<?=$event['event_id']?>"><?=$event['event_name']?> (<?=$event['event_date']?>)</option>
                    <? } ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="customer-name" class="col-sm-4 control-label">Имя покупателя</label>
            <div class="col-sm-7">
                <input type="text" class="form-control reserve-info-search-inputs" name="customer_name" id="customer-name" placeholder="Полное имя покупателя">
            </div>
        </div>
        <div class="form-group">
            <label for="reserve-description" class="col-sm-4 control-label">Когда бронировались билеты</label>
            <div class="col-sm-8">
                <div class="container">
                    <div class="row">
                        <div class='col-sm-5'>
                            <div class="form-group">
                                <div class='input-group date' id='reserve-datepicker' data-date-format="DD.MM.YYYY">
                                    <input type='text' class="form-control reserve-info-search-inputs" id="reserve-date"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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

<script>
    var customersSearchTimeout;
    $("#loading-animation").hide();
    $("#customers-list").hide();
    $("#new-customer").hide();

    $('#reserve-datepicker').datetimepicker({
        pickTime: false,
        language: 'ru',
        maxDate: "<?=$data['current_date']?>"
    }).on("dp.hide", function () {
        requestReserveList();
    });
    $("#event-id").change(function () {
        requestReserveList();
    });

    function requestReserveList() {
        if (!($("#customer-name").val().length == 0 && $("#reserve-date").val() == 0)) {
            $("#customers-list").hide();
            $("#loading-animation").show();
            $.post("/tickets/getReserveInfo", {
                customer_name : $("#customer-name").val(),
                event_id: $("#event-id").val(),
                reserve_date: $("#reserve-date").val()
            }, function(data) {
                $("#loading-animation").hide();
                $("#customers-list").html("");
                if (data.length == 0) {
                    $("#customers-list").append('<a href="#" class="list-group-item disabled list-group-item-warning">Покупатели не найдены!</a>');
                }
                for (var i = 0; i < data.length; i++) {
                    var additional = '';
                    if (data[i].reserve_description.length>0) {
                        additional = '<p class="list-group-item-text">Дополнительно: '+
                            data[i].reserve_description+'</p>';
                    }
                    $("#customers-list").append(
                        '<a href="#" onclick="return customerClick(' + data[i].reserve_id+ ')" class="list-group-item customer">' +
                            '<h4 class="list-group-item-heading">' +
                            '<span class="customer-name" data-reserve-id='+data[i].reserve_id+'>'+
                            data[i].customer_name +
                            '</span>' +
                            '<span class="pull-right">' +
                            '<button class="btn btn-sm btn-success" onclick="reserveEditName(' +
                            data[i].reserve_id + ', \'' + data[i].customer_name + '\'' +
                            ')"><span class="glyphicon glyphicon-edit"></span> Редактировать имя</button>'+
                            //'<button class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>'+
                            '</span>' +
                            '</h4>' +
                            '<p class="list-group-item-text">Дата бронирования: '+
                            data[i].reserve_created + '</p>' +
                            '<p class="list-group-item-text">Забронированно билетов: '+
                            data[i].tickets_reserved+'</p>'+additional +
                            '</a>');
                }
                $("#customers-list").slideDown();
            }, "json");
        }
    }

    $(".reserve-info-search-inputs").keyup(function() {
        clearTimeout(customersSearchTimeout);
        customersSearchTimeout = setTimeout(function () {requestReserveList();}, 750);
    });

    var lock = false;
    function reserveEditName(reserveId, defaultName) {
        lock = true;
        var newName = prompt('Имя покупателя', defaultName);
        while(true){
            if(newName && newName.length>5 || !newName){
                if (newName) {
                    $(".customer-name[data-reserve-id="+reserveId+"]").html(newName);
                    $.post("/tickets/editCustomerName", {
                        reserve_id : reserveId,
                        customer_name: newName
                    });
                }
                break;
            }else{
                newName = prompt('Имя покупателя. БОЛЕЕ 5 СИМВОЛОВ!', defaultName);
            }
        }
    }
    function customerClick(resereveId) {
        if (lock) {
            return lock = false;
        }
        <? if (isset($data['event_id'])) { ?>
        $.post("/tickets/reserve/<?=$data['event_id']?>", {reserve_id : resereveId}, function(data) {
        <? } else { ?>
        $.post("/tickets/reserveSearch/"+resereveId+"/", function(data) {
        <? } ?>
            $("#dialog-modal").children().first().modal("hide").on('hidden.bs.modal', function () {
                $('#dialog-modal').unbind().html(data);
            });
        });
        return false;
    }
    $("#new-reserve").on("click", function() {
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
</script>