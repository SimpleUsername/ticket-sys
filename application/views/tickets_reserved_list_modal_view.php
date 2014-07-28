<table class="table">
    <tr>
        <th colspan="6">
            <h4>
                <?=$data['event']['event_name']?> <?=$data['event']['event_date']?>
            </h4>
        </th>
    </tr>
    <tr>
        <th>Сектор</th>
        <th>Ряд</th>
        <th>Место</th>
        <th>Цена</th>
        <th>Отменить бронь</th>
    </tr>
    <? $total = 0; ?>
    <? foreach($data['tickets'] as $ticket) { ?>
    <tr id="ticket-<?=$ticket['event_id']?>-<?=$ticket['place_id']?>">
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
            <button class="btn btn-default reserve-delete"
                    onclick="deleteReserve(<?=$ticket['event_id']?>, <?=$ticket['place_id']?>, <?=$ticket['price']?>)">
                <i class="glyphicon glyphicon-remove"></i>
            </button>
        </td>
    </tr>
    <? $total+=$ticket['price']; ?>
<? } ?>
</table>
<div class="text-right">
    <h4>Итого: <b id="total"><?=$total?></b> грн.</h4>
</div>
<script>
    var tickets =
    [<? for ($ticket = $data['tickets'][$i = 0]; $i < count($data['tickets']); $ticket = $data['tickets'][++$i]) {?>
        {placeID:<?=$ticket['place_id']?>, eventID:<?=$ticket['event_id']?>}<?=($i != count($data['tickets'])-1)?','.PHP_EOL:''?>
    <? } ?>];

    $("#dialog-modal").children().first().modal();

    <? if (!$data['sale_available']) { ?>
    $('#btn-modal-sell-reserve').attr("disabled", "disabled");
    <? } ?>
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

    function deleteReserve(eventID, placeID, price) {
        if (confirm("Отправить билет в свободную продажу?")) {
            $.post("/tickets/deleteReserve", {
                place_id : placeID,
                event_id : eventID
            }).done(function (response) {
                tickets.splice(tickets.indexOf({placeID:placeID, eventID:eventID}), 1);
                $("#total").html(parseFloat($("#total").html())-parseFloat(price));
                $('#ticket-'+eventID+'-'+placeID).fadeOut();
            });
        }
    }

</script>