<h1>События</h1>
<?
date_default_timezone_set('Europe/Kiev');
$current_date = time();
?>
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
            <th>Старт бронирования</th>
            <th>Старт продаж</th>
            <? if ($_SESSION['user_type_id'] == 2) { ?>
            <th>Редактировать</th>
            <th>Удалить</th>
            <? } elseif ($_SESSION['user_type_id'] == 3) { ?>
            <th>Продать</th>
            <th>Забронировать</th>
        </tr>
        <? } ?>
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
                    <td><?=$value['event_date']?><? $event_date = strtotime($value['event_date']); ?></td>
                    <td><?=$value['event_booking']?><? $event_booking = strtotime($value['event_booking']); ?></td>
                    <td><?=$value['event_sale']?><? $event_sale = strtotime($value['event_sale']); ?></td>
                    <? if ($_SESSION['user_type_id'] == 2) { ?>
                    <td><a class="btn btn-success" href="/events/edit/<?=$value['event_id']?>">Редактировать</a></td>
                    <td><button class="btn btn-danger" id="del_ev" onclick="confirm('Удалить ?')" data-event_id="<?=$value['event_id']?>">Удалить</button></td>
                    <? } elseif ($_SESSION['user_type_id'] == 3) { ?>
                    <td>
                        <a class="btn btn-primary btn-sell <?=($event_date < $current_date || $event_sale > $current_date)?"disabled":""?>"
                           data-event-id="<?=$value['event_id']?>">
                            Продать
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-primary btn-reserve <?=($event_date < $current_date || $event_booking > $current_date)?"disabled":""?>"
                           data-event-id="<?=$value['event_id']?>">
                            Бронировать
                        </a>
                    </td>
                    <? } ?>
                </tr>
        <? }?>
    </table>
</div>
<? } ?>
<? //echo "<pre>"; print_r($data); echo "</pre>";?>
