<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title" id="modalLabel"><?=$data['title']?></h4>
            </div>
            <div class="modal-body">
                <?php include 'application/views/'.$content_view; ?>
            </div>
            <? if (isset($data['role'])) { ?>
            <div class="modal-footer">
                <? if ($data['role'] == 'sell') { ?>
                    <button type="button" id="btn-modal-confirm-sell" class="btn btn-primary">Продать</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <? } elseif ($data['role'] == 'new-reserve-info') { ?>
                    <button type="button" id="btn-modal-confirm-new-reserve-info" class="btn btn-primary">Далее</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <? } elseif ($data['role'] == 'reserve') { ?>
                    <button type="button" id="btn-modal-confirm-reserve" class="btn btn-primary">Забронировать</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <? } elseif ($data['role'] == 'search_reserve') { ?>
                    <!--<button type="button" id="btn-modal-delete-reserve" class="btn btn-danger">Удалить</button>-->
                    <button type="button" id="btn-modal-sell-reserve" class="btn btn-primary">Выкупить</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <? } elseif ($data['role'] == 'error') { ?>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Ок</button>
                <? } else { ?>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <? switch($data['role']){
                        case "confirm" : echo "Подтвердить"; break;
                        case "success" : echo "ОК"; break;
                    }?>
                    </button>
                <? } ?>
            </div>
            <? } ?>
        </div>
    </div>
</div>