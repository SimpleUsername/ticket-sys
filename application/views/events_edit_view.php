<form role="form" action="/events/<?=$data['action']?>" method="post" enctype="multipart/form-data" id="event-form">

    <div class="col-md-6">
        <h1 class="page-header"><?=$data['action']=='edit'?'Редактирование события':'Новое событие'?></h1>
        <? if(!empty($data['error'])) {?>
            <div class="form-group has-success has-feedback">
                <label  class="control-label col-sm-3"><?=$data['error']?></label>
            </div>
        <?}?>
        <? if (isset($data['event_id'])) { ?><input type="hidden" name="event_id" value="<?=$data['event_id']?>"/><? } ?>
        <div class="form-group">
            <label for="name">Название события*</label>
            <input type="text" class="form-control" id="name" name="event_name" value="<?=@$data['event_name'] ?>" placeholder="Введите название события" required="required">
        </div>

        <div class="form-group">
            <label for="date1">Дата события*</label>
            <div class='input-group date' id="event_date_datetimepicker">
                <input type='text' class="form-control" name="event_date" value="<?=@$data['event_date']?>" data-date-format="YYYY-MM-DD hh:mm" required="required"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
        </div>
        <div class="form-group">
            <label for="date2">Дата старта бронирования</label>
            <div class='input-group date' id="event_booking_datetimepicker">
                <input type='text' class="form-control" name="event_booking"  value="<?=@$data['event_booking']?>"data-date-format="YYYY-MM-DD hh:mm"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
        </div>
        <div class="form-group">
            <label for="date4">Дата отмены бронирования*</label>
            <div class='input-group date' id="event_booking_end_datetimepicker">
                <input type='text' class="form-control" name="event_booking_end"  value="<?=@$data['event_booking_end']?>"data-date-format="YYYY-MM-DD hh:mm"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
        </div>
        <div class="form-group">
            <label for="date3">Дата старта продаж</label>
            <div class='input-group date' id="event_sale_datetimepicker">
                <input type='text' class="form-control" name="event_sale" value="<?=@$data['event_sale']?>" data-date-format="YYYY-MM-DD hh:mm"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>
        </div>
        <? if ($data['action'] == 'edit') { ?>
        <div class="form-group">
            <label for="event_status">Статус события</label>
        </div>
        <div class="input-group">
            <div class="input-group-btn">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="status_but"><?=$data['statuses'][isset($data['event_status'])?$data['event_status']:0]['estatus_name']?> <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <? foreach($data['statuses'] as $id => $value ){
                        if ($value['estatus_id'] != 1) {?>
                        <li><a href="#" data-status_id="<?=$value['estatus_id']?>" class="status"><?=$value['estatus_name']?></a></li>
                    <? }
                    }?>
                </ul>
            </div><!-- /btn-group -->
            <input type="hidden" class="form-control" id="status" name="event_status" value="<?=$data['event_status'] ?>" required="required">
        </div><!-- /input-group -->
        <? }  else { ?>
            <input type="hidden" class="form-control" id="status" name="event_status" value="0" required="required">
        <? } ?>
        <div class="form-group">
            <label for="desc">Описание события</label>
            <textarea id="desc" name="event_desc" class="form-control" rows="3" placeholder="Введите название события"><?=$data['event_desc']?></textarea>
        </div>
        <div class="form-group">
            <label for="exampleInputFile">Баннер мероприятия</label>
            <input type="file" id="file" name="event_img">
            <? if ($action == 'edit') { ?>
            <div class="well"><p>Имя файла:  <?=$data['event_img_name'];?></p><img src="<?=$data['event_img_path'].$data['event_img_md5']?>" class="img-thumbnail img_custom" alt="<?=$data['event_img_name'];?>"/></div>
            <input type="hidden" name="event_img_name" value="<?=$data['event_img_name'];?>"/>
            <input type="hidden" name="event_img_path" value="<?=$data['event_img_path'];?>"/>
            <input type="hidden" name="event_img_md5" value="<?=$data['event_img_md5'];?>"/>
            <? } ?>
        </div>
        <button type="submit" class="btn btn-primary" id="send"><?=$data['action']=='edit'?'Сохранить Событие':'Добавить Событие'?></button>
        <? if ($data['action'] == 'edit') { ?><a class="btn btn-danger" onclick="confirm('Удалить ?')" href="/events/del/<?=$data['event_id']?>">Удалить</a><? } ?>
    </div>
    <div class="col-md-6">
        <h3 class="page-header">Цена на билеты для данного события</h3>
        <div class="well"> <button type="button" class="btn btn-primary btn-lg btn-block" id="prices" data-action="1">Редактировать цены</button></div>
        <? $i = 0;
        foreach($data['prices'] as $key => $value) {?>
            <div class="row">
                <div class="form-group form-inline prices-parent">
                    <label for="name" class="col-xs-4"><?=$value['sector_name']?> , грн</label>
                    <div class="col-xs-4">
                        <input type="hidden" name="sector[<?=$i;?>][sector_name]" value="<?=$value['sector_name']?>"/>
                        <input type="hidden" name="sector[<?=$i;?>][sector_id]" value="<?=$value['sector_id']?>"/>
                        <input type="number" required="required" class="form-control col-sm-2 prices"  min="0" max="20000"  name="sector[<?=$i;?>][sector_price]"  placeholder="Цена"  value="<?=$value['sector_price']?>"  disabled="disabled" required="required">
                    </div>
                </div>
            </div>
            <?  $i++;
        } ?>

    </div>
</form>
<script type="text/javascript">

    $(document).ready(function () {
        $('#event-form').bootstrapValidator({
            fields: {
                event_date: {
                    validators: {
                        callback: {
                            message: 'Нельзя указать прошедшее время и дату, пересекающуюся с другими событиями',
                            callback: function(value, validator) {
                                var m = new moment(value, 'DD.MM.YYYY HH:mm', true);
                                return m.isValid()
                                    && !m.isBefore()
                                    && !m.isBefore(moment('<?=$data['now']?>', 'DD.MM.YYYY HH:mm'))
                                    <? foreach ($data['disabled_dates'] as $disabled_date): ?>
                                    && Math.abs(m - <?=$disabled_date?>) >= 86400000
                                    <? endforeach; ?>
                                    ;
                            }
                        }
                    }
                },
                event_booking: {
                    validators: {
                        callback: {
                            message: 'Дата должна быть перед датой события',
                            callback: function(value, validator) {
                                var m = new moment(value, 'DD.MM.YYYY HH:mm', true);
                                console.log(m.isValid());
                                console.log( m.isBefore(moment($("input[name=event_date]").val(), 'DD.MM.YYYY HH:mm')));
                                return m.isValid()
                                    && m.isBefore(moment($("input[name=event_date]").val(), 'DD.MM.YYYY HH:mm'));
                            }
                        }
                    }
                },
                event_booking_end: {
                    validators: {
                        callback: {
                            message: 'Неправильный диапазон',
                            callback: function(value, validator) {
                                var m = new moment(value, 'DD.MM.YYYY HH:mm', true);
                                return m.isValid()
                                    && !m.isBefore(moment('<?=$data['now']?>', 'DD.MM.YYYY HH:mm'))
                                    && !m.isBefore()
                                    && m.isBefore(moment($("input[name=event_date]").val(), 'DD.MM.YYYY HH:mm'));
                            }
                        }
                    }
                },
                event_sale: {
                    validators: {
                        callback: {
                            message: 'Дата должна быть перед датой события',
                            callback: function(value, validator) {
                                var m = new moment(value, 'DD.MM.YYYY HH:mm', true);
                                return m.isValid()
                                    && m.isBefore(moment($("input[name=event_date]").val(), 'DD.MM.YYYY HH:mm'));
                            }
                        }
                    }
                }
            }
        });

        //event_date
        $('#event_date_datetimepicker').datetimepicker({
            language: 'ru',
            disabledDates: [
                <?=implode(",", $data['disabled_dates']) ?>
            ]
        });
        $('#event_date_datetimepicker').on("dp.change", function (e) {
            $('#event-form')
                .data('bootstrapValidator')
                .updateStatus('event_date', 'NOT_VALIDATED')
                .validateField('event_date');
            $("input[name=event_date]").trigger("change");
        });
        $("input[name=event_date]").on("change", function () {
            var event_date = moment($("input[name=event_date]").val(), 'DD.MM.YYYY HH:mm');
            $('#event_booking_datetimepicker').data("DateTimePicker").setMaxDate(event_date);
            $('#event_sale_datetimepicker').data("DateTimePicker").setMaxDate(event_date);
            $('#event_booking_end_datetimepicker').data("DateTimePicker").setMaxDate(event_date);
            $("input[name=event_booking_end]").val(event_date.subtract('minutes', 30).format('DD.MM.YYYY HH:mm')).trigger("dp.change");
        });

        //event_booking
        $('#event_booking_datetimepicker').datetimepicker({
            language: 'ru'
        });
        $('#event_booking_datetimepicker').on("dp.change",function (e) {
            $('#event-form')
                .data('bootstrapValidator')
                .updateStatus('event_booking', 'NOT_VALIDATED')
                .validateField('event_booking');
            $("input[name=event_booking]").trigger("change");
        });
        $("input[name=event_booking]").on("change", function () {
            var event_booking = moment($("input[name=event_booking]").val(), 'DD.MM.YYYY HH:mm');
            $('#event_booking_end_datetimepicker').data("DateTimePicker").setMinDate(event_booking);
        });

        //event_booking_end
        $('#event_booking_end_datetimepicker').datetimepicker({
            language: 'ru'
        });
        $('#event_booking_end_datetimepicker').on("dp.change",function (e) {
            $('#event-form')
                .data('bootstrapValidator')
                .updateStatus('event_booking_end', 'NOT_VALIDATED')
                .validateField('event_booking_end');
        });

        //event_sale
        $('#event_sale_datetimepicker').datetimepicker({
            language: 'ru'
        });
        $('#event_sale_datetimepicker').on("dp.change",function (e) {
            $('#event-form')
                .data('bootstrapValidator')
                .updateStatus('event_sale', 'NOT_VALIDATED')
                .validateField('event_sale');
        });

        $('#event_date_datetimepicker').data("DateTimePicker").setMinDate(moment('<?=$data['now']?>', 'DD.MM.YYYY HH:mm'));
        $('#event_booking_datetimepicker').data("DateTimePicker").setMinDate(moment('<?=$data['now']?>', 'DD.MM.YYYY HH:mm'));
        $('#event_sale_datetimepicker').data("DateTimePicker").setMinDate(moment('<?=$data['now']?>', 'DD.MM.YYYY HH:mm'));
        $('#event_booking_end_datetimepicker').data("DateTimePicker").setMinDate(moment('<?=$data['now']?>', 'DD.MM.YYYY HH:mm'));
        $('#event_booking_datetimepicker').data("DateTimePicker").setMaxDate(moment('<?=$data['now']?>', 'DD.MM.YYYY HH:mm'));
        $('#event_sale_datetimepicker').data("DateTimePicker").setMaxDate(moment('<?=$data['now']?>', 'DD.MM.YYYY HH:mm'));
        $('#event_booking_end_datetimepicker').data("DateTimePicker").setMaxDate(moment('<?=$data['now']?>', 'DD.MM.YYYY HH:mm'));
    });
</script>
