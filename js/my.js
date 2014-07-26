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
/******************** svg  map function ************************/
function h()
{
    var tooltip = document.getElementById('tooltip');
    tooltip.setAttribute("visibility",'hidden');
    var tooltip_rect = document.getElementById('tooltip_rect');
    tooltip_rect.setAttribute("visibility",'hidden');
}
function sector_svg(id){
    var sector = $.trim(document.getElementById(id).textContent);
    if(sector == 'VIP A'){
        sector = 26;
    }else if(sector == 'VIP D'){
        sector = 27;
    }else{
        sector =  parseInt(sector);
    }
    return sector;
}
function action_sector(id_elem , color){
    $("#"+id_elem).mouseenter(function() {
        $("#"+id_elem).attr('color','#'+color);
        $("#"+id_elem+", #"+id_elem+" rect, #"+id_elem+" path").css('fill','red');
        var posx = parseInt($("#"+id_elem).attr('x'));
        var posy = parseInt($("#"+id_elem).attr('y'));
        var sec_res = sector_svg(id_elem);
        var width = parseInt($(this)[0].getBoundingClientRect().width);
        var height = parseInt($(this)[0].getBoundingClientRect().height);
        var tooltip = document.getElementById('tooltip');
        var tooltip_name = document.getElementById('tooltip_name');
        var tooltip_price = document.getElementById('tooltip_price');
        var tooltip_free_place = document.getElementById('tooltip_free_place');
        var tooltip_rect_width = tooltip_rect.getAttribute("width");
        var tooltip_rect_height = tooltip_rect.getAttribute("height");
        posy = posy - tooltip_rect_height - 2;
        posx = posx + parseInt((width - tooltip_rect_width)/2);
        $('#sec-'+sec_res).css('display', 'block');

        tooltip.setAttribute("x",posx);
        tooltip.setAttribute("y",posy);
        tooltip.setAttribute("visibility",'visible');
        tooltip_rect.setAttribute("x",posx);
        tooltip_rect.setAttribute("y",posy);
        tooltip_rect.setAttribute("visibility",'visible');

    });
    $("#"+id_elem).mouseleave(function() {
        var color = $(this).attr("color");
        var sec_res = sector_svg(id_elem);
        $('#sec-'+sec_res).css('display', 'none');
        $("#"+id_elem+", #"+id_elem+" rect, #"+id_elem+" path").css('fill',color);
        h();
    });
    $("#"+id_elem).click(function() {
        var event = $('#event_id').val();
        var sec_res = sector_svg(id_elem);
        $("#row").html('').fadeOut();
        $("#place").html('').fadeOut();
        $("#btn-modal-confirm-sell").addClass("disabled");
        $("#btn-modal-confirm-reserve").addClass("disabled");
        $.post("/tickets/getRows", {
            event_id: event,
            sector_id: sector_svg(id_elem)
        }).done(function (response) {
                var arr = $.parseJSON(response);
                var selectSize = arr.length>15 ? 15 : arr.length;
                $("#row").attr("size", selectSize).fadeIn();
                for (var i=0; i<arr.length; i++) {
                    if (arr[i]['free_count'] != 0) {
                        $("#row").append("<option data-row-no="+ arr[i]['row_no'] +">Ряд "+arr[i]['row_no']+
                            " (Свободно мест - "+arr[i]['free_count']+")</option>");
                    }
                }
                $('#sector_id').val(sec_res);
            });
    });
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
                $(".map").css('height','416px');
                action_sector("svg_19", 'daff00'); // 1
                action_sector("svg_16", '00ff00'); // 2
                action_sector("svg_117", '0055ff'); // 3
                action_sector("svg_121", 'ff00f8'); // 4
                action_sector("svg_123", 'f063c7'); // 5
                action_sector("svg_126", '35dde3'); // 6
                action_sector("svg_130", 'daff00'); // 7
                action_sector("svg_132", '00ff00'); // 8
                action_sector("svg_134", 'ff00f8'); // 9
                action_sector("svg_95", '35dde3'); // 10
                action_sector("svg_67", '00ff00'); // 11
                action_sector("svg_51", 'ff00f8'); // 12
                action_sector("svg_50", 'daff00'); //13
                action_sector('svg_143', '00ff00'); // 14
                action_sector("svg_62", 'f063c7'); // 15
                action_sector("svg_74", 'daff00'); // 16
                action_sector("svg_58", 'ff00f8'); // 17
                action_sector('svg_144', '35dde3'); // 18
                action_sector("svg_110", '00ff00'); // 19
                action_sector("svg_112", 'daff00'); // 20
                action_sector("svg_113", 'ff00f8'); // 21
                action_sector("svg_114", '35dde3'); // 22
                action_sector("svg_37", '0055ff'); // 23
                action_sector("svg_23", 'daff00'); // 24
                action_sector("svg_5", 'ff00f8'); // 25
                action_sector("svg_8", '35dde3'); // Vip A
                action_sector("svg_135", '0055ff'); // Vip D
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