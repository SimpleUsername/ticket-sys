<h1>Билеты</h1>
    <? foreach($data['tickets'] as $key => $ticket){?>
        <div class="well">
            <div class="row tic_mar">
                <div class=" col-md-6 col-xs-6"><strong>Название События</strong></div>
                <div class=" col-md-6 col-xs-6"><strong>Дата События</strong></div>
            </div>
            <div class="row tic_mar">
                <div class="tick_ev_name col-md-6 col-xs-6"><?=$ticket['event_name']?></div>
                <div class="tick_ev_date col-md-6 col-xs-6"><?=$ticket['event_date']?></div>
            </div>
            <div class="row tic_mar">
                <div class="col-md-2 col-xs-2 sec"><strong>Сектор:</strong></div>
                <div class="col-md-2 col-xs-2 "><?=$ticket['sector_id']?></div>
                <div class="col-md-2 col-xs-2 sec"><strong>Ряд:</strong></div>
                <div class="col-md-2 col-xs-2 "><?=$ticket['row_no']?></div>
                <div class="col-md-2 col-xs-2 sec"><strong>Место:</strong></div>
                <div class="col-md-2 col-xs-2 "><?=$ticket['place_no']?></div>
            </div>
            <div class="row tic_mar">
                <div class="col-md-3 col-xs-3 "><strong>Уникальный номер:</strong></div>
                <div class="col-md-3 col-xs-3 "><?=$ticket['place_id']?></div>
                <div class="col-md-3 col-xs-3 "><strong>Цена билета:</strong></div>
                <div class="col-md-3 col-xs-3 "><?=$ticket['price']?> UAH</div>
            </div>
        </div>


    <? }?>
<div class="row total_mar">
    <table class="table table-striped">
      <tr>
          <th>Данные о событии</th>
          <th>Итого</th>
      </tr>
        <tr>
            <td><?=$data['title']?></td>
            <td><?=$data['total']?> UAH</td>
        </tr>
    </table>
</div>

<script>
    $("#dialog-modal").children().first().modal();
</script>