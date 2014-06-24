<h1>Изменение пароля</h1>
<form role="form" id="password_edit" autocomplete="off"  class="form-horizontal col-sm-8" method="POST">
    <? if(isset($data['error'])) { ?><div class="alert alert-danger"><?=$data['error']?></div><? } ?>

    <div class="form-group">
        <label for="new_password" class="col-sm-4 control-label">Новый пароль</label>
        <div class="col-sm-8">
            <input type="password" class="form-control" placeholder="Пароль" name="new_password" id="new_password">
        </div>
    </div>
    <div class="form-group">
        <label for="new_password_confirm" class="col-sm-4 control-label">Поддтверждение</label>
        <div class="col-sm-8">
            <input type="password" class="form-control" placeholder="Пароль" name="new_password_confirm" id="new_password_confirm">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="/user" class="btn btn-default">Отмена</a>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#password_edit').bootstrapValidator({
            fields: {
                new_password: {
                    validators: {
                        notEmpty: {
                            message: 'Пароль обязателен и не может быть пустым'
                        }
                    }
                },
                new_password_confirm: {
                    validators: {
                        notEmpty: {
                            message: 'Пароль обязателен и не может быть пустым'
                        },
                        identical: {
                            field: 'new_password',
                            message: 'Пароли не совпадают'
                        }
                    }
                }
            }
        });
    });
</script>