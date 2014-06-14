<form role="form" action="" method="post">
    <div class="form-group">
        <label for="name">Название события</label>
        <input type="text" class="form-control" id="name" name="event_name" placeholder="Введите название события" required="required">
    </div>
    <div class="input-group">
        <div class="input-group-btn">

            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>

            </ul>
        </div><!-- /btn-group -->
        <input type="text" class="form-control" id="status" name="event_status" required="required">
    </div><!-- /input-group -->
    <div class="form-group">
        <label for="desc">Описание события</label>
        <textarea id="desc" name="event_desc" class="form-control" rows="3" placeholder="Введите название события"></textarea>
    </div>
    <div class="form-group">
        <label for="date1">Дата события</label>
        <div class='input-group date' id='datetimepicker1'>
            <input type='text' class="form-control" name="event_date"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
        </div>
    </div>
    <div class="form-group">
        <label for="date2">Дата старта бронирования</label>
        <div class='input-group date' id='datetimepicker2'>
            <input type='text' class="form-control" name="event_booking"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
        </div>
    </div>
    <div class="form-group">
        <label for="date3">Дата старта продаж</label>
        <div class='input-group date' id='datetimepicker3'>
            <input type='text' class="form-control" name="event_sale"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
        </div>
    </div>
    <button type="submit" class="btn btn-default">Добавить Событие</button>
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
<? print_r($status);?>