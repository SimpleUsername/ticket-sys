<div class="form-inline row">
    <div class="form-group col-sm-8">
        <div class="input-group">
            <span class="input-group-addon">Поиск</span>
            <input class="form-control" style="width: 100%" placeholder="Имя покупателя" id="customer-name">
        </div>
    </div>
    <div class="form-group col-sm-4">
        <button class="btn btn-primary disabled">Добавить нового покупателя<!-- not implemented yet --></button>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-12 text-center">
        <img src="/images/ajax-loader.gif" id="loading-animation">
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="list-group" id="customers-list">
        </div>
    </div>
</div>
<script>
    $("#loading-animation").hide();
    $("#customers-list").hide();
    $("#customer-name").keyup(function() {
        $("#customers-list").slideUp();
        if ($("#customer-name").val().length > 0) {
            $("#loading-animation").fadeIn();
            $.post("/tickets/getCustomers", {customer_name : $("#customer-name").val()}, function(data) {
                $("#loading-animation").hide();
                $("#customers-list").slideDown().html("");
                if (data.length == 0) {
                    $("#customers-list").append('<a href="#" class="list-group-item disabled list-group-item-warning">Покупатели не найдены!</a>');
                }
                for (var i = 0; i < data.length; i++) {
                    $("#customers-list").append(
                        '<a href="#" onclick="return customerClick(' + data[i].customer_id+ ')" class="list-group-item customer">' +
                        '<h4 class="list-group-item-heading">' +
                        data[i].customer_name + '</h4>'+
                        '<p class="list-group-item-text">'+
                        data[i].customer_description + '</p></a>');
                }
            }, "json");
        }
    });
    function customerClick(customerId) {
        console.log(customerId);
        $.post("/tickets/reserve/<?=$data['event_id']?>", {customer_id : customerId}, function(data) {
            $("#dialog-modal").children().first().modal("hide");
            $('#dialog-modal').on('hidden.bs.modal', function () {
                $('#dialog-modal').unbind();
                $("#dialog-modal").html(data);
            });
        });
        return false;
    }
</script>