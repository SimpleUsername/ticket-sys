<h1>События</h1>


<div class="table-responsive">
    <table class="table">
        <th>Превью</th>
        <th>Название</th>
        <th>Статус</th>

        <th>Дата события</th>
        <th>Старт бронирования</th>
        <th>Старт продаж</th>
        <th>Редактировать</th>
        <th>Удалить</th>
        <? foreach($data as $id => $value){?>
            <? if($id%2 == 0){?>
                <tr class="success">
                    <td><img class="img-thumbnail img_custom" src="<?=$value['event_img_path'].$value['event_img_md5']?>" alt="<?=$value['event_img_name']?>" class="img-thumbnail"></td>
                    <td><?=$value['event_name']?></td>
                    <td><?=$value['estatus_name']?></td>
                    <td><?=$value['event_date']?></td>
                    <td><?=$value['event_booking']?></td>
                    <td><?=$value['event_sale']?></td>
                    <td><a class="btn btn-success" href="/events/edit/<?=$value['event_id']?>">Редактировать</a></td>
                    <td><button class="btn btn-danger" id="del_ev" onclick="confirm('Удалить ?')" data-event_id="<?=$value['event_id']?>">Удалить</button></td>
                </tr>
            <? }else {?>
                <tr class="info"><td><img src="<?=$value['event_img_path'].$value['event_img_md5']?>" alt="<?=$value['event_img_name']?>" class="img-thumbnail img_custom"></td>
                    <td><?=$value['event_name']?></td>
                    <td><?=$value['estatus_name']?></td>
                    <td><?=$value['event_date']?></td>
                    <td><?=$value['event_booking']?></td>
                    <td><?=$value['event_sale']?></td>
                    <td><a class="btn btn-success" href="/events/edit/<?=$value['event_id']?>">Редактировать</a></td>
                    <td><button class="btn btn-danger" id="del_ev"  onclick="confirm('Удалить ?')" data-event_id="<?=$value['event_id']?>">Удалить</button></td>
                </tr>
            <? }?>
        <? }?>
    </table>
</div>

<? //echo "<pre>"; print_r($data); echo "</pre>";?>
