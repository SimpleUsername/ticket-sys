<select size="<?=count($data['sectors'])>20?"20":count($data['sectors'])?>" id="sector" class="form-control">
    <? foreach ($data['sectors'] as $id => $sector) {
        if ($sector['sector_free_count'] > 0) { ?>
            <option data-sector-id="<?=$sector['sector_id']?>" data-sector-price="<?=$sector['sector_price']?>">
                <?=$sector['sector_name']?> (Цена - <?=$sector['sector_price']; ?> грн. Свободно мест - <?=$sector['sector_free_count']?>)
            </option>
        <? }
    } ?>
</select>
<select size="1" id="row" class="form-control">
    <option>Сначала выберите cектор</option>
</select>
<select size="1" id="place" class="form-control" multiple>
    <option>Сначала выберите ряд</option>
</select>
<div class="text-right">
    <h4>Билеты: </h4>
    <div id="tickets">

    </div>
    <h4>Итого: <b id="total">0</b> грн.</h4>
</div>
<script>
    var tickets = [];
    var prices = [];
    var event_id = <?=$data['event_id']?>;

    $("#row").html('').hide();
    $("#place").html('').hide();
    $("#btn-modal-confirm-sell").addClass("disabled");

    $("#sector").change(function() {

        $("#sector").prop("disabled", true);
        $("#row").html('').fadeOut();
        $("#place").html('').fadeOut();
        $("#btn-modal-confirm-sell").addClass("disabled");

        $.post("/tickets/getRows", {
            event_id: <?=$data['event_id']?>,
            sector_id: $('#sector option:selected').data('sectorId')
        }).done(function (response) {
            var arr = $.parseJSON(response);
            var selectSize = arr.length>20 ? 20 : arr.length;
            $("#row").attr("size", selectSize).fadeIn();
            for (var i=0; i<arr.length; i++) {
                if (arr[i]['free_count'] != 0) {
                    $("#row").append("<option data-row-no="+ arr[i]['row_no'] +">Ряд "+arr[i]['row_no']+
                        " (Свободно мест - "+arr[i]['free_count']+")</option>");
                }
            }
            $("#sector").removeAttr("disabled").attr("size", 1);
        });
    });
    $("#row").change(function() {

        $("#row").prop("disabled", true);
        $("#place").html('').fadeOut();
        $("#btn-modal-confirm-sell").addClass("disabled");
        $("#place_change").css('display','block');
        $.post("/tickets/getPlaces", {
            event_id: <?=$data['event_id']?>,
            sector_id: $('#sector option:selected').data('sectorId'),
            row_no: $('#row option:selected').data('rowNo')
        }).done(function (response) {
                var arr = $.parseJSON(response);
                var selectSize = arr.length>20 ? 20 : arr.length;
                $("#place").fadeIn().attr("size", selectSize).html("");
                for (var i=0; i<arr.length; i++) {
                    var disabledAttr = '';
                    var selectedAttr = '';
                    var color;
                    if (tickets.indexOf(parseInt(arr[i]['place_id'])) > -1) {
                        selectedAttr = "selected";
                    }
                    if (arr[i]['ticket_type'] == 'purchased') {
                        disabledAttr = "disabled";
                        color = "Snow";
                    } else if (arr[i]['ticket_type'] == 'reserved') {
                        disabledAttr = "disabled";
                        color = "#d9edf7";
                    } else {
                        color = "#dff0d8";
                    }
                    $("#place_change").append("<p data-place-change-id="+ arr[i]['place_id'] + " class='place_change'>Место "+arr[i]['place_no']+"<span  data-place-data-id="+ arr[i]['place_id']+" class='label label-primary ticket change_ticket '>Разрешить продажу</span>&nbsp;</p>");

                    if (arr[i]['ticket_type'] == 'purchased') {
                        $("[data-place-id="+ arr[i]['place_id']+"]").append("<span class='status'> - продано</span>");
                    } else if (arr[i]['ticket_type'] == 'reserved') {
                        $("[data-place-id="+ arr[i]['place_id']+"]").append("<span class='status'> - забронировано</span>");
                    }
                }
                $("#row").removeAttr("disabled").attr("size", 1);
            });
    });

    $('#place_change').on('click', '.change_ticket', function(){
        var conf = confirm("Отправить в свободнужю продажу ?");
        if(conf){
            var place_change = $(this).data('place-data-id');
            $.post(
                '/tickets/changeStatus',
                {
                    event_id: <?=$data['event_id']?>,
                    place_id : place_change

                },
                function(json){
                    console.log(json);
                    $('option[data-place-id="'+ place_change +'"] .status').remove();
                    $('option[data-place-id="'+ place_change +'"] ').css({'background':'#dff0d8'});
                },
                'json'
            );
        }


    });
    $("#tickets").on("click", "span.ticket", function (event) {
        var placeId = $(event.target).data('placeId');
        var index = tickets.indexOf(placeId);
        $(event.target).remove();
        $("#total").html(parseFloat($("#total").html())-prices[index]);
        prices.splice(index, 1);
        tickets.splice(index, 1);
        $("option[data-place-id="+ placeId +"]").attr("selected", false);
        if (tickets.length > 0) {
            $("#btn-modal-confirm-sell").removeClass("disabled");
        } else {
            $("#btn-modal-confirm-sell").addClass("disabled");
        }
    });
    $("#place").change(function() {
        $.each($('#place option'), function(index, option) {
            if ($(option).is(':selected') == true) {
                if (tickets.indexOf($(option).data('placeId')) == -1) {
                    tickets.push($(option).data('placeId'));
                    prices.push($("#sector option:selected").data('sectorPrice'));
                    $("#total").html(parseFloat($("#total").html())+prices[prices.length-1]);
                    $('#tickets').append("<span title='Удалить' style='cursor: pointer' class='label label-primary ticket'" +
                        " data-place-id="+$(option).data('placeId')+">С-"+$('#sector option:selected').data('sectorId')+
                        " Р-"+$("#row option:selected").data('rowNo')+
                        " М-"+$(option).data('placeNo')+" &times;</span> ");
                }
            } else {
                if (tickets.indexOf($(option).data('placeId')) > -1) {
                    var placeId = $("span[data-place-id="+ $(option).data('placeId') +"]").data('placeId');
                    var i = tickets.indexOf(placeId);
                    $("span[data-place-id="+ $(option).data('placeId') +"]").remove();
                    $("#total").html(parseFloat($("#total").html())-prices[i]);
                    prices.splice(i, 1);
                    tickets.splice(i, 1);
                }
            }
        });

        if (tickets.length > 0) {
            $("#btn-modal-confirm-sell").removeClass("disabled");
        } else {
            $("#btn-modal-confirm-sell").addClass("disabled");
        }
    });
    $('#btn-modal-confirm-<?=$data['role']?>').on("click", function() {
        $('#btn-modal-confirm-<?=$data['role']?>').addClass('disabled');
        $.post("/tickets/<?=$data['role']?>Tickets/<?=$data['event_id']?>", {
            tickets: JSON.stringify(tickets)<? if ($data['role'] == 'reserve' ) { ?>,
            customer_id: <? echo $data['customer_id']; } ?>
        }).done(function (response) {
                $("#dialog-modal").children().first().modal("hide");
                $('#dialog-modal').on('hidden.bs.modal', function () {
                    $('#dialog-modal').unbind();
                    $("#dialog-modal").html(response);
                });
            });
    });
    <? if ($data['role'] == 'reserve' ) { ?>
    $("#dialog-modal").children().first().modal();
    <? } ?>
</script>