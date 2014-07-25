<h1>События</h1>
<? if(isset($data['msg'])) {
?>
    <p><?=$data['msg']?></p>
<? } else { ?>
<div class="table-responsive">
    <table class="table">
        <tr>
            <th>Превью</th>
            <th>Название</th>
            <th>Статус</th>
            <th>Дата события</th>
            <th>Места</th>
            <th></th>
        </tr>
        <? if (!empty($data)) {
            foreach($data as $id => $value){?>
            <? if($id%2 == 0){?>
                <tr class="success">
                    <td><img class="img-thumbnail img_custom" src="<?=$value['event_img_path'].$value['event_img_md5']?>" alt="<?=$value['event_img_name']?>" class="img-thumbnail"></td>
            <? } else { ?>
                <tr class="info">
                    <td><img src="<?=$value['event_img_path'].$value['event_img_md5']?>" alt="<?=$value['event_img_name']?>" class="img-thumbnail img_custom"></td>
            <? } ?>
                    <td><?=$value['event_name']?></td>
                    <td><?=$value['estatus_name']?></td>
                    <td>
                        <?=preg_replace('/\s/', '&nbsp', $value['event_date'])?><span class="help-block">
                        <script> document.write(moment('<?=$value['event_date']?>', 'DD.MM.YYYY HH:mm').fromNow()); </script></span>
                    </td>
                    <td>
                        <p>
                            <span>
                                Продано:&nbsp;<span data-event-id="<?=$value['event_id']?>" class="purchased-count"><?=$value['purchased_count']?></span>
                            </span>
                        </p>
                        <p>
                            <span>
                                Забронировано:&nbsp;<span data-event-id="<?=$value['event_id']?>" class="reserved-count"><?=$value['reserved_count']?></span>
                            </span>
                        </p>
                        <p>
                            <span>
                                Свободно:&nbsp;<span data-event-id="<?=$value['event_id']?>" class="reserved-free"><?=$value['free_count']?></span>
                            </span>
                        </p>
                    </td>

                    <td class="events-list-btns">
                    <? if ($_SESSION['user_seller']) { ?>
                        <a class="btn btn-default btn-sell <?=$value['event_purchase_available']?"":"disabled"?>"
                           data-event-id="<?=$value['event_id']?>">
                            Продать билет
                        </a>
                        <a class="btn btn-default btn-reserve <?=$value['event_reserve_available']?"":"disabled"?>"
                           data-event-id="<?=$value['event_id']?>">
                            Бронировать место
                        </a>
                    <? } ?>
                    <? if ($_SESSION['user_manager']) { ?>
                        <a class="btn btn-success btn-edit" href="/events/edit/<?=$value['event_id']?>">Редактировать</a>
                        <button class="btn btn-danger btn-delete" id="del_ev" data-event_id="<?=$value['event_id']?>">Удалить</button>
                    <? } ?>
                    </td>
                </tr>
        <?  }
        } else { ?>
           <tr><td colspan="5"><span class="help-block"> Пусто :(</span></td></tr>
        <? }?>
    </table>
</div>
<script>

    var checkingInterval = setInterval(function () {
        $.post("/events/getCountersAndEventStatuses").done(function (response) {
            try {
                var eventsStats = $.parseJSON(response);
            } catch (err) {
                clearInterval(checkingInterval);
                $('#errorMessageModal').modal('show');
            }
            console.log(eventsStats);
            if (eventsStats == null) {
                throw true;
            }
            $.each(eventsStats, function(eventId, eventStats) {
                $("[data-event-id="+eventId+"].purchased-count").html(eventStats.purchased_count);
                $("[data-event-id="+eventId+"].reserved-count").html(eventStats.reserved_count);
                $("[data-event-id="+eventId+"].free-count").html(eventStats.free_count);
                if (eventStats.event_purchase_available) {
                    $(".btn-sell[data-event-id="+eventId+"]").removeClass("disabled");
                } else {
                    $(".btn-sell[data-event-id="+eventId+"]").addClass("disabled");
                }
                if (eventStats.event_reserve_available) {
                    $(".btn-reserve[data-event-id="+eventId+"]").removeClass("disabled");
                } else {
                    $(".btn-reserve[data-event-id="+eventId+"]").addClass("disabled");
                }
            });
        }).error(function() {
            clearInterval(checkingInterval);
            $('#errorMessageModal').modal('show');

        });
    }, 10*1000);

</script>
<? } ?>

