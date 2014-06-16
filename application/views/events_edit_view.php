<? if(!empty($data['error'])) {?>
    <div class="form-group has-success has-feedback">
        <label  class="control-label col-sm-3"><?=$data['error']?></label>
    </div>
<?}?>
    <form role="form" action="/events/edit" method="post" enctype="multipart/form-data" >
        <input type="hidden" name="event_id" value="<?=$data['event_id']?>"/>
        <div class="form-group">
            <label for="name">Название события*</label>
            <input type="text" class="form-control" id="name" name="event_name" value="<?=$data['event_name']?>" placeholder="Введите название события" required="required">
        </div>
        <div class="form-group">
            <label for="event_status">Статус события</label>
        </div>
        <div class="input-group">

            <div class="input-group-btn">

                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="status_but"><?=$data['statuses'][$data['event_status']-1]['estatus_name']?> <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <? foreach($data['statuses'] as $id => $value ){?>
                        <li><a href="#" data-status_id="<?=$value['estatus_id']?>" class="status"><?=$value['estatus_name']?></a></li>
                    <? }?>
                </ul>
            </div><!-- /btn-group -->
            <input type="hidden" class="form-control" id="status" name="event_status" value="1" required="required">
        </div><!-- /input-group -->
        <div class="form-group">
            <label for="desc">Описание события</label>
            <textarea id="desc" name="event_desc" class="form-control" rows="3"   placeholder="Введите название события"><?=$data['event_desc']?></textarea>
        </div>
        <div class="form-group">
            <label for="exampleInputFile">Баннер мероприятия</label>
            <input type="file" id="file" name="event_img">
            <div class="well"><p>Имя файла:  <?=$data['event_img_name'];?></p><img src="<?=$data['event_img_path'].$data['event_img_md5']?>" class="img-thumbnail img_custom" alt="<?=$data['event_img_name'];?>"/></div>
        </div>
        <div class="form-group">
            <label for="date1">Дата события</label>
            <div class='input-group date' id='datetimepicker1'>
                <input type='text' class="form-control" name="event_date" value="<?=$data['event_date']?>" data-date-format="YYYY-MM-DD hh:mm"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
        </div>
        <div class="form-group">
            <label for="date2">Дата старта бронирования</label>
            <div class='input-group date' id='datetimepicker2'>
                <input type='text' class="form-control" name="event_booking"  value="<?=$data['event_booking']?>"data-date-format="YYYY-MM-DD hh:mm"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
        </div>
        <div class="form-group">
            <label for="date3">Дата старта продаж</label>
            <div class='input-group date' id='datetimepicker3'>
                <input type='text' class="form-control" name="event_sale" value="<?=$data['event_sale']?>" data-date-format="YYYY-MM-DD hh:mm"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить Событие</button>
        <a class="btn btn-danger"   onclick="confirm('Удалить ?')" href="/events/del/<?=$data['event_id']?>">Удалить</a>
    </form>


    <script type="text/javascript">
        $(function () {
            $('#datetimepicker1').datetimepicker({
                language: 'ru'
            });
            $('#datetimepicker2').datetimepicker({
                language: 'ru'
            });
            $('#datetimepicker3').datetimepicker({
                language: 'ru'
            });
        });
    </script>
<? print_r($_POST);?>
    <br/>
<? print_r($data);?>
    <br/>
<? print_r($_FILES);?>