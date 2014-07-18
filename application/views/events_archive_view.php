<h1>Архив событий</h1>
<? if(isset($data['msg'])) {
?>
    <p><?=$data['msg']?></p>
<? } else { ?>
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
            <tr>
                <td><img src="<?=$value['event_img_path'].$value['event_img_md5']?>" alt="<?=$value['event_img_name']?>" class="img-thumbnail img_custom"></td>
                <td><?=$value['event_name']?></td>
                <td><?=isset($value['estatus_name'])?$value['estatus_name']:'удалено'?></td>
                <td>
                    <?=preg_replace('/\s/', '&nbsp', $value['event_date'])?><span class="help-block">
                    <script> document.write(moment('<?=$value['event_date']?>', 'DD.MM.YYYY HH:mm').fromNow()); </script></span>
                </td>
                <td>
                    <p><span>Продано:&nbsp;<?=$value['purchased_count']?></span></p>
                    <p><span>Не продано:&nbsp;<?=$value['free_count']?></span></p>
                </td>
                <td>
                    <? if (!isset($value['estatus_name'])) { ?>
                        <a href="/events/recovery/<?=$value['event_id']?>" class="btn btn-success">Восстановить</a>
                    <? } ?>
                </td>
            </tr>
        <?  }
        } else { ?>
            <tr><td colspan="5"><span class="help-block"> Пусто :(</span></td></tr>
        <? }?>
    </table>
<? } ?>

