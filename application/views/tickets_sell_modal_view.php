<select size="<?=count($data['sectors'])?>" id="sector" class="form-control">
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

    $("#row").html('').hide();
    $("#place").html('').hide();
    $("#btn-modal-confirm").addClass("disabled");

    $("#sector").change(function() {

        $("#sector").prop("disabled", true);
        $("#row").html('').fadeOut();
        $("#place").html('').fadeOut();
        $("#btn-modal-confirm").addClass("disabled");

        $.post("/tickets/getRows", {
            event_id: <?=$data['event_id']?>,
            sector_id: $('#sector option:selected').data('sectorId')
        }).done(function (response) {
            var arr = $.parseJSON(response);
            $("#row").attr("size", arr.length).fadeIn();;
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
        $("#btn-modal-confirm").addClass("disabled");

        $.post("/tickets/getPlaces", {
            event_id: <?=$data['event_id']?>,
            sector_id: $('#sector option:selected').data('sectorId'),
            row_no: $('#row option:selected').data('rowNo')
        }).done(function (response) {
            var arr = $.parseJSON(response);
            $("#place").fadeIn().attr("size", arr.length).html("");
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
                $("#place").append("<option "+disabledAttr+" "+selectedAttr+
                    " style='background-color:"+color+"' data-place-id="+ arr[i]['place_id'] +
                    " data-place-no="+ arr[i]['place_no'] +
                    ">Место "+arr[i]['place_no']+"</option>");
                if (arr[i]['ticket_type'] == 'purchased') {
                    $("[data-place-id="+ arr[i]['place_id']+"]").append(" - продано");
                } else if (arr[i]['ticket_type'] == 'reserved') {
                    $("[data-place-id="+ arr[i]['place_id']+"]").append(" - забронировано");
                }
            }
            $("#row").removeAttr("disabled").attr("size", 1);
        });
    });
    $("#tickets").on("click", "span.ticket", function (event) {
        console.log(event);
        var placeId = $(event.target).data('placeId');
        var index = tickets.indexOf(placeId);
        $(event.target).remove();
        $("#total").html(parseInt($("#total").html())-prices[index]);
        prices.splice(index, 1);
        tickets.splice(index, 1);
        $("option[data-place-id="+ placeId +"]").attr("selected", false);
        if (tickets.length > 0) {
            $("#btn-modal-confirm").removeClass("disabled");
        } else {
            $("#btn-modal-confirm").addClass("disabled");
        }
    });
    $("#place").change(function() {
        $.each($('#place option'), function(index, option) {
            if ($(option).is(':selected') == true) {
                if (tickets.indexOf($(option).data('placeId')) == -1) {
                    tickets.push($(option).data('placeId'));
                    prices.push($("#sector option:selected").data('sectorPrice'));
                    $("#total").html(parseInt($("#total").html())+prices[prices.length-1]);
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
                    $("#total").html(parseInt($("#total").html())-prices[i]);
                    prices.splice(i, 1);
                    tickets.splice(i, 1);
                }
            }
        });

        if (tickets.length > 0) {
            $("#btn-modal-confirm").removeClass("disabled");
        } else {
            $("#btn-modal-confirm").addClass("disabled");
        }
    });
</script>