<table class="table">
<? $previous_event_id = -1 ?>
<? foreach($data['tickets'] as $key=>$ticket) { ?>
    <? if ($ticket['event_id'] != $previous_event_id) { ?>
    <? $previous_event_id = $ticket['event_id']; ?>
    <tr>
        <th colspan="6">
            <h4>
                <?=$ticket['event_name']?> <?=$ticket['event_date']?>
            </h4>
        </th>
    </tr>
    <tr>
        <th></th>
        <th>Сектор</th>
        <th>Ряд</th>
        <th>Место</th>
        <th>Цена</th>
        <th>Отменить бронь</th>
    </tr>
    <? } ?>
    <tr id="ticket-<?=$ticket['event_id']?>-<?=$ticket['place_id']?>" class="success">
        <td>
            <input <?=$ticket['sale_available']?"":"disabled"?> type="checkbox" class="checkbox-ticket" data-event-id="<?=$ticket['event_id']?>"
                   data-place-id="<?=$ticket['place_id']?>" data-price="<?=$ticket['price']?>" checked="checked">
        </td>
        <td>
            <?=$ticket['sector_name']?>
        </td>
        <td>
            <?=$ticket['row_no']?>
        </td>
        <td>
            <?=$ticket['place_no']?>
        </td>
        <td>
            <?=$ticket['price']?> грн
        </td>
        <td>
            <button class="btn btn-default reserve-delete" data-event-id="<?=$ticket['event_id']?>"
                    data-place-id="<?=$ticket['place_id']?>">
                <i class="glyphicon glyphicon-remove"></i>
            </button>
        </td>
    </tr>
<? } ?>
</table>
<div class="text-right">
    <h4>Итого: <b id="total">0</b> грн.</h4>
</div>
<script>
    var tickets = [];

    $.each($("input[type='checkbox']"), function (i, checkbox) {
        ticket = $(checkbox).data();
        tickets.push(ticket);
        $("#total").html(parseFloat($("#total").html())+parseFloat(ticket.price));
    });

    $('#btn-modal-delete-reserve').addClass('disabled');
    $('#btn-modal-sell-reserve').addClass('disabled');
    $("#dialog-modal").children().first().modal();

    $(".checkbox-ticket").on("change",  function (event) {
        var sender = event.target;
        var ticket = $(event.target).data();
        if (sender.checked) {
            $("#ticket-"+ticket.eventId+"-"+ticket.placeId).addClass("success");
            tickets.push(ticket);
            $("#total").html(parseFloat($("#total").html())+parseFloat(ticket.price));
            $('#btn-modal-delete-reserve').removeClass('disabled');
            $('#btn-modal-sell-reserve').removeClass('disabled');
        } else {
            $("#ticket-"+ticket.eventId+"-"+ticket.placeId).removeClass("success");
            tickets.splice(tickets.indexOf(ticket), 1);
            $("#total").html(parseFloat($("#total").html())-parseFloat(ticket.price));
            if (tickets.length == 0) {
                $('#btn-modal-delete-reserve').addClass('disabled');
                $('#btn-modal-sell-reserve').addClass('disabled');
            }
        }
    });
    $('#btn-modal-sell-reserve').click(function () {
        $(this).addClass('disabled');
        $.post("/tickets/reserveSell", {
            tickets: JSON.stringify(tickets)
        }).done(function (response) {
            $("#dialog-modal").children().first().modal("hide").on('hidden.bs.modal', function () {
                $('#dialog-modal').unbind().html(response);
            });
        });
    });
    var sender;
    $(".reserve-delete").click(function (event) {
        if ($(event.target).hasClass("reserve-delete")) {
            sender = event.target;
        } else {
            sender = event.target.parentNode;
        }
        if (confirm("are you seriously?")) {
            $.post("/tickets/deleteReserve", {
                place_id : sender.dataset.placeId,
                event_id : sender.dataset.eventId
            }).done(function (response) {
                if($(".checkbox-ticket:checked[data-event-id="+sender.dataset.eventId+
                    "][data-place-id="+sender.dataset.placeId+"]").length) {
                    $(".checkbox-ticket:checked[data-event-id="+sender.dataset.eventId+
                        "][data-place-id="+sender.dataset.placeId+"]").attr("checked", false).trigger("change");
                }
                $(".checkbox-ticket:checked[data-event-id="+sender.dataset.eventId+
                    "][data-place-id="+sender.dataset.placeId+"]").attr("disabled", true);
                $(sender).parent().parent().fadeOut();
            });
        }
    });


</script>