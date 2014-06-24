<div id="confirm" class="modal fade" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalLabel">Подтверждение</h4>
            </div>
            <div class="modal-body" id="confirm-modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" id="delete">Удалить</button>
                <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
            </div>
        </div>
    </div>
</div>
<h1>Пользователи</h1>

<!--<a href="/users/create" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>Добавить</a>-->
<table class="table table-striped">
<? foreach($data as $user){?>
    <tr>
        <td>
            <b class="user-<?=$user['user_id']?>-login"><?=$user["user_login"]?></b>
        </td>
        <td>
            <p><?=$user["user_name"]?></p>
        </td>
        <td>
            <h5><span class="label <? switch($user["user_type_id"]) {
                case 1 : ?>label-warning"><? break;
                case 2 : ?>label-success"><? break;
                case 3 : ?>label-primary"><? break;
                default : ?>label-default"><?
            }?><?=$user["user_type"]?></span></h5>
        </td>
        <td>
            <a class="btn btn-success"  href="/users/edit/<?=$user['user_id']?>" title="Редактировать">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            <a class="btn btn-warning<?=$user['user_hash'] == null?" disabled":""?>"  href="/users/logout/<?=$user['user_id']?>" title="Закрыть сессию">
                <i class="glyphicon glyphicon-off"></i>
            </a>
            <a class="btn btn-delete btn-danger<?=$_SESSION['user_id'] == $user['user_id']?" disabled":""?>"
               data-user-id=<?=$user['user_id']?> title="Удалить">
                <i data-user-id=<?=$user['user_id']?> class="glyphicon glyphicon-remove"></i>
            </a>
        </td>
    </tr>
<? } ?>
</table>

<script>
    $('a.btn-delete').on('click', function(e){
        var id = $(e.target).data('userId');
        $('#confirm-modal-body').html('Удалить пользователя '+$('.user-'+ id +'-login').html()+'?');
        $('#confirm').modal({ backdrop: 'static', keyboard: false })
            .one('click', '#delete', function (e) {
                document.location = '/users/delete/'+id;
            });
    });
</script>