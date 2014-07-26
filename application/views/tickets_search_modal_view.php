<div id="ticket-search" xmlns="http://www.w3.org/1999/html">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#manual" role="tab" data-toggle="tab">Вручную</a></li>
        <li><a href="#byId" role="tab" data-toggle="tab">По идентификатору</a></li>
    </ul>
    <br>
    <div class="tab-content">
        <div class="tab-pane active" id="manual">
            <div class="form-horizontal" role="form">
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
                            <? foreach($data['sectors'] as $key=>$sector) { ?>
                                <option value="<?=$sector['sector_id']?>"><?=$sector['sector_name']?></option>
                            <? } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="row" class="col-sm-2 control-label">Ряд</label>
                    <div class="col-sm-10">
                        <input type="number" placeholder="" class="form-control" id="search-row"
                           onkeyup="if (this.value != 0) {
                                $('#btn-search').removeAttr('disabled');
                           } else {
                                $('#btn-search').attr('disabled', 'disabled');
                           }
                           if (event.keyCode == 13) {
                               $('#btn-search').trigger('click');
                           }">
                    </div>
                </div>
                <div class="form-group">
                    <label for="place" class="col-sm-2 control-label">Место</label>
                    <div class="col-sm-10">
                        <input type="number" placeholder="" class="form-control" id="search-place"
                            onkeyup="if (this.value != 0) {
                                $('#btn-search').removeClass('disabled');
                            } else {
                                $('#btn-search').addClass('disabled');
                            }
                            if (event.keyCode == 13) {
                                $('#btn-search').trigger('click');
                            }">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button class="btn btn-primary disabled" id="btn-search" disabled="disabled">Проверить</button>
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
        <div class="tab-pane" id="byId">
            <div class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="event" class="col-sm-2 control-label">Событие</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="search-event-id">
                            <? foreach($data['events'] as $key=>$event) { ?>
                                <option value="<?=$event['event_id']?>"><?=$event['event_name']?></option>
                            <? } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="event" class="col-sm-2 control-label">Идентификатор</label>
                    <div class="col-sm-10">
                        <input class="form-control" placeholder="ID" type="number" id="search-place-id" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button class="btn btn-primary disabled" id="btn-search-id">Проверить</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <img src="/images/ajax-loader.gif" id="loading-animation-id">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table" id="ticket-id">
                    </table>
                </div>
            </div>
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
    $("#loading-animation-id").hide();
    $('#btn-search').on("click", function(event) {
        $("#loading-animation").show();
        $("#btn-search").addClass("disabled");
        $("#ticket").hide();
        $.post("/tickets/getTicketsManual", {
            event_id: $("#search-event").val(),
            sector_id: $("#search-sector").val(),
            row_no: $("#search-row").val(),
            place_no: $("#search-place").val()
        }, function (data) {
            $("#btn-search").removeClass("disabled");
            $("#loading-animation").hide();
            var table = $("#ticket").html("<tr><td>Ошибка!</td><td>Место не найдено!</td></tr>");
            $("#ticket").show();
            var ticket = JSON.parse(data);
            var table = $("#ticket").html("");
            console.log(ticket.ticket_type);
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
        });
    });



    $('#btn-search-id').on("click", function(event) {
        $("#loading-animation-id").show();
        $("#btn-search-id").addClass("disabled");
        $("#ticket").hide();
        $.post("/tickets/getTicketsById", {
            event_id: $("#search-event-id").val(),
            place_no: $("#search-place-id").val()
        }, function (data) {
            $("#btn-search-id").removeClass("disabled");
            $("#loading-animation-id").hide();
            var table = $("#ticket-id").html("<tr><td>Ошибка!</td><td>Место не найдено!</td></tr>");
            $("#ticket-id").show();
            var ticket = JSON.parse(data);
            var table = $("#ticket-id").html("");
            console.log(ticket.ticket_type);
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
        });
    });
</script>