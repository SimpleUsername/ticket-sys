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

<table class="table table-striped">
<? foreach($data as $user):?>
    <tr>
        <td>
            <b class="user-<?=$user['user_id']?>-login"><?=$user["user_login"]?></b>
        </td>
        <td>
            <p><?=$user["user_name"]?></p>
        </td>
        <td>
            <h5>
                <?=$user['user_type'] & 0x04?'<span class="label label-warning">Администратор</span>':''?>
                <?=$user['user_type'] & 0x02?'<span class="label label-success">Менеджер</span>':''?>
                <?=$user['user_type'] & 0x01?'<span class="label label-primary">Продавец</span>':''?>
            </h5>
        </td>
        <td>
            <a class="btn btn-success"  href="/users/edit/<?=$user['user_id']?>" title="Редактировать">
                <i class="glyphicon glyphicon-pencil"></i> Редактировать
            </a>
            <a class="btn btn-warning<?=$user['user_hash'] == null?" disabled":""?>"
               href="/users/logout/<?=$user['user_id']?>" title="Закрыть сессию">
                <i class="glyphicon glyphicon-off"></i> Закрыть сессию
            </a>
            <a class="btn btn-delete btn-danger<?=$_SESSION['user_id'] == $user['user_id']?" disabled":""?>"
               data-user-id=<?=$user['user_id']?> title="Удалить">
                <i data-user-id=<?=$user['user_id']?> class="glyphicon glyphicon-remove"></i> Удалить
            </a>
        </td>
    </tr>
<? endforeach; ?>
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