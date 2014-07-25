var result_ajax = "#result_ajax";
var body = "body";
var caret = " <span class='caret'></span>";
var success = " <span class='glyphicon glyphicon-ok form-control-feedback'></span>";

tinymce.init({
    selector: "textarea",

    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    autosave_ask_before_unload: false,
    max_height: 200,
    min_height: 160,
    height : 180,
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
    file_browser_callback: function(field, url, type, win) {
        tinyMCE.activeEditor.windowManager.open({
            file: '/libs/kcfinder/browse.php?opener=tinymce4&field=' + field + '&type=' + type,
            title: 'KCFinder',
            width: 700,
            height: 500,
            resizable: "yes",
            inline: true,
            close_previous: "no",
            popup_css: false
        }, {
            window: win,
            input: field
        });
        return false;
    }
});

function show_popup(to_scroll){
    if (typeof to_scroll === 'undefined'){
        to_scroll = false;
    }
    if (to_scroll) {
        $(body).addClass('no_scroll');
        $(result_ajax).addClass('to_scroll');
    }
    $(result_ajax).parent().show();
}
function hide_popup(time_out){
    if (typeof time_out == 'undefined'){
        time_out = 1;
    }
    setTimeout(function(){
        if ($(result_ajax).hasClass('no_auto_hide')==false){
            $(body).removeClass('no_scroll');
            $(result_ajax)
                .removeClass('ok')
                .removeClass('msg_ok')
                .removeClass('msg_err')
                .removeClass('to_scroll')
                .removeClass('error')
                .html('')
                .parent().hide()
        }

    },time_out);
}

$(document).ready(function(){
    $('.prices').attr('disabled','disabled');

    $(body).on('click', '.status', function(){
        var stat = $(this).html();
        var stat_id = $(this).data('status_id');
        $('#status_but').html(stat + caret);
        $('#status').val(stat_id);
    });


    $(body).on('click', '#send', function(){
        $('.prices').removeAttr('disabled');
        $(this).submit();
    });

    $(body).on('click', "#prices", function(){
        var  action = $(this).data('action');
        if(action == 1){
            $(this).data('action', 2);
            $(this).text('Сохранить изменения');
            $('.prices').removeAttr('disabled');

        }
        else{
            $(this).data('action', 1);
            $(this).text('Редактировать цены');
            $('.prices').attr('disabled','disabled');
            $('.prices-parent').removeClass('has-success').removeClass('has-feedback');
            $('.form-control-feedback').remove();
        }
    });

    $(body).on('keyup', '.prices', function(){
        var sector_id = $(this).data('sector_id');
        var sector_price = $(this).val();
        var parent = $(this).parent();
        $.post(
            '/config/ajax',
            {
                sector_id : sector_id,
                sector_price: sector_price
            },
            function(json){
                parent.addClass('has-success').addClass('has-feedback');
                parent.append(success);
            },
            'json'
        );
    });

    $(body).on('click','#del_ev', function(){
        if (confirm('Удалить ?')) {
            var del_id = $(this).data('event_id');
            $.post(
                '/events/del_ajax' ,
                {
                    del_id : del_id
                },
                function(json){
                    location.reload();
                    //json['msg'];
                    ///  $(body).modal({show:true});
                },
                'json'
            );
        }
    });

    $(".btn-sell").on("click", function(e) {
        var sender = $(e.target);
        var eventId = sender.data('eventId');
        sender.addClass("disabled");
        $.ajax({ url: "/tickets/sell/"+eventId })
            .done(function(html) {
                $("#dialog-modal").html(html).children().first().modal();
                sender.removeClass("disabled");
            })
            .fail(function( jqXHR, textStatus ) {
                $(".modal-error-message").html("Request failed: " + textStatus );
                $('#errorMessageModal').modal('show');
                sender.removeClass("disabled");
            });

    });
    $(".btn-reserve").on("click", function(e) {
        var sender = $(e.target);
        var eventId = sender.data('eventId');
        sender.addClass("disabled");
        $.ajax({ url: "/tickets/reserve/"+eventId })
            .done(function(html) {
                $("#dialog-modal").html(html).children().first().modal();
                sender.removeClass("disabled");
            })
            .fail(function( jqXHR, textStatus ) {
                $(".modal-error-message").html("Request failed: " + textStatus );
                $('#errorMessageModal').modal('show');
            });
    });
    $(".btn-ticket-search").on("click", function(e) {
        $.ajax({ url: "/tickets/search" })
            .done(function(html) {
                $("#dialog-modal").html(html).children().first().modal();
            })
            .fail(function( jqXHR, textStatus ) {
                $(".modal-error-message").html("Request failed: " + textStatus );
                $('#errorMessageModal').modal('show');
            });
    });
    $(".btn-reserve-search").on("click", function(e) {
        $.ajax({ url: "/tickets/reserveSearch" })
            .done(function(html) {
                $("#dialog-modal").html(html).children().first().modal();
            })
            .fail(function( jqXHR, textStatus ) {
                $(".modal-error-message").html("Request failed: " + textStatus );
                $('#errorMessageModal').modal('show');
            });
    });
});