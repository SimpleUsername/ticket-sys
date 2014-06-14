var result_ajax = "#result_ajax";
var body = "body";

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