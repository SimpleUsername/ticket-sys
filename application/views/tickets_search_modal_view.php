<div id="ticket-search" xmlns="http://www.w3.org/1999/html">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#manual" role="tab" data-toggle="tab">Вручную</a></li>
        <li><a href="#byId" role="tab" data-toggle="tab">По идентификатору</a></li>
    </ul>
    <br>
    <div class="tab-content">
        <div class="tab-pane active" id="manual">
            <div class="form-horizontal" role="form" id="search-manual">
                <div class="form-group">
                    <label for="event" class="col-sm-2 control-label">Событие</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="search-event">
                            <? foreach($data['events'] as $key=>$event) { ?>
                                <option value="<?=$event['event_id']?>"><?=$event['event_name']?></option>
                            <? } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sector" class="col-sm-2 control-label">Сектор</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="search-sector">
                            <? foreach($data['sectors'] as $key=>$sector): ?>
                                <option value="<?=$sector['sector_id']?>"><?=$sector['sector_name']?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="row" class="col-sm-2 control-label">Ряд</label>
                    <div class="col-sm-10">
                        <input type="number" placeholder="" class="form-control" id="search-row" name="row"
                           onkeyup="validateManualForm();
                           if (event.keyCode == 13) {
                               $('#btn-search[disabled!=\'disabled\']').trigger('click');
                           }">
                    </div>
                </div>
                <div class="form-group">
                    <label for="place" class="col-sm-2 control-label">Место</label>
                    <div class="col-sm-10">
                        <input type="number" placeholder="" class="form-control" id="search-place" name="place"
                            onkeyup="validateManualForm();
                            if (event.keyCode == 13) {
                                $('#btn-search[disabled!=\'disabled\']').trigger('click');
                            }">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button class="btn btn-primary" id="btn-search" disabled="disabled">Проверить</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="byId">
            <div class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="event" class="col-sm-2 control-label">Событие</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="search-event-id">
                            <? foreach($data['events'] as $key=>$event): ?>
                                <option value="<?=$event['event_id']?>"><?=$event['event_name']?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="event" class="col-sm-2 control-label">Идентификатор</label>
                    <div class="col-sm-10">
                        <input class="form-control" placeholder="ID" type="number" id="search-place-id"
                               onkeyup="validateByIDForm();
                            if (event.keyCode == 13) {
                                $('#btn-search-id[disabled!=\'disabled\']').trigger('click');
                            }">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button class="btn btn-primary" id="btn-search-id" disabled="disabled">Проверить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <img src="/images/ajax-loader.gif" id="loading-animation">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table" id="ticket">
            </table>
        </div>
    </div>
</div>
<script>
    $('#manual a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#byId a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $("#loading-animation").hide();
    
    $("#search-row, #search-place").on("change", function () { validateManualForm() });
    $("#search-place-id").on("change", function () {validateByIDForm()});

    function validateManualForm() {
        if (isNumber($("input[name=place]").val()) && isNumber($("input[name=row]").val())) {
            $('#btn-search').removeAttr("disabled");
        } else {
            $('#btn-search').attr("disabled", "disabled");
        }
    }

    function validateByIDForm() {
        if (isNumber($("#search-place-id").val())) {
            $('#btn-search-id').removeAttr("disabled");
        } else {
            $('#btn-search-id').attr("disabled", "disabled");
        }
    }

    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    $('#btn-search').on("click", function(event) {
        $("#loading-animation").show();
        $("#btn-search").attr("disabled", "disabled");
        $("#ticket").hide();
        $("#search-event-id").val($("#search-event").val());
        $.post("/tickets/getTicketsManual", {
            event_id: $("#search-event").val(),
            sector_id: $("#search-sector").val(),
            row_no: $("#search-row").val(),
            place_no: $("#search-place").val()
        }, function (data) { printTicket(data) });
    });

    $('#btn-search-id').on("click", function(event) {
        $("#loading-animation").show();
        $("#btn-search-id").attr("disabled", "disabled");
        $("#ticket").hide();
        $("#search-event").val($("#search-event-id").val());
        $.post("/tickets/getTicketsById", {
            event_id: $("#search-event-id").val(),
            place_no: $("#search-place-id").val()
        }, function (data) { printTicket(data) });
    });

function printTicket(ticketJson) {
    $("#loading-animation").hide();
    $("#ticket").show();
    var table = $("#ticket");
    if (ticketJson == 'null') {
        validateByIDForm();
        validateManualForm();
        table.html("<tr><td class=\"danger\">Ошибка!</td><td>Место не найдено!</td></tr>");
        return false;
    }
    var ticket = JSON.parse(ticketJson);

    $("#search-sector").val(ticket.sector_id);
    $("#search-row").val(ticket.row_no);
    $("#search-place").val(ticket.place_no);
    $("#search-place-id").val(ticket.place_id);
    validateByIDForm();
    validateManualForm();

    var table = $("#ticket").html("");
    switch (ticket.ticket_type) {
        case 'reserved' : state = 'Забронировано'; break;
        case 'purchased' : state = 'Куплено'; break;
        default : state = 'Свободно'; break;
    }
    table.append('<tr><td>Состояние</td><td>' + state + '</td></tr>' );
    if (ticket.price != null) {
        table.append('<tr><td>Стоимость</td><td>' + ticket.price + ' грн</td></tr>' );
    }
    if (ticket.customer_name != null) {
        table.append('<tr><td>Покупатель</td><td>' + ticket.customer_name + '</td></tr>' );
    }
    if (state == 'Забронировано') {
        table.append('<tr><td colspan="2"><button class="btn btn-warning" onclick="deleteReserve('+
            ticket.event_id+', '+ ticket.place_id+')">Отправить в свободную продажу</button></td></tr>' );
    }
}
function deleteReserve(eventID, placeID) {
    if (confirm("are you seriously?")) {
        $.post("/tickets/deleteReserve", {
            place_id : placeID,
            event_id : eventID
        }).done(function (response) {
            $("#btn-search").trigger("click");
        });
    }
}
</script>