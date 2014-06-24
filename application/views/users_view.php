<h1>Пользователи</h1>
<!--<a href="/users/create" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>Добавить</a>-->
<table class="table table-striped">
<? foreach($data as $user){?>
    <tr>
        <td>
            <b><?=$user["user_login"]?></b>
        </td>
        <td>
            <p><?=$user["user_name"]?></p>
        </td>
        <td>
            <span class="label <? switch($user["user_type_id"]) {
                case 1 : ?>label-warning"><? break;
                case 2 : ?>label-success"><? break;
                case 3 : ?>label-primary"><? break;
                default : ?>label-default"><?
            }?><?=$user["user_type"]?></span>
        </td>
        <td>
            <a class="btn btn-success"  href="/users/edit/<?=$user['user_id']?>" title="Редактировать">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>
            <a class="btn btn-danger<?=$_SESSION['user_id'] == $user['user_id']?" disabled":""?>"
               onclick="confirm('Удалить ?')" href="/users/delete/<?=$user['user_id']?>" title="Удалить">
                <i class="glyphicon glyphicon-remove"></i>
            </a>
        </td>
    </tr>
<? } ?>
</table>