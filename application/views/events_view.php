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
            <!--<th>Старт бронирования</th>
            <th>Конец бронирования</th>
            <th>Старт продаж</th>-->
            <th></th>
        </tr>
        <? foreach($data as $id => $value){?>
            <? if($id%2 == 0){?>
                <tr class="success">
                    <td><img class="img-thumbnail img_custom" src="<?=$value['event_img_path'].$value['event_img_md5']?>" alt="<?=$value['event_img_name']?>" class="img-thumbnail"></td>
            <? } else { ?>
                <tr class="info">
                    <td><img src="<?=$value['event_img_path'].$value['event_img_md5']?>" alt="<?=$value['event_img_name']?>" class="img-thumbnail img_custom"></td>
            <? } ?>
                    <td><?=$value['event_name']?></td>
                    <td><?=$value['estatus_name']?></td>
                    <td><?=preg_replace('/\s/', '&nbsp', $value['event_date'])?></td>
                    <th>
                        <p><span class="label label-success">Продано:&nbsp;<?=$value['purchased_count']?></span></p>
                        <p><span class="label label-info">Забронировано:&nbsp;<?=$value['reserved_count']?></span></p>
                        <p><span class="label label-default">Свободно:&nbsp;<?=$value['free_count']?></span></p>
                    </th>
                    <!--<td><?=$value['event_booking']?></td>
                    <td><?=$value['event_booking_end']?></td>
                    <td><?=$value['event_sale']?></td>-->

                    <td class="events-list-btns">
                    <? if ($_SESSION['user_seller']) { ?>
                        <a class="btn btn-primary btn-sell <?=$value['event_purchase_available']?"":"disabled"?>"
                           data-event-id="<?=$value['event_id']?>">
                            Продать билет
                        </a>
                        <a class="btn btn-primary btn-reserve <?=$value['event_reserve_available']?"":"disabled"?>"
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
        <? }?>
    </table>
</div>
<? } ?>

