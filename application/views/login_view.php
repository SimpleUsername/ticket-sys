<?php if (isset($data['error'])): ?>
    <div class="alert alert-danger">
        <?=htmlspecialchars($data['error']) ?>
    </div>
<?php endif; ?>
<div class="row">
    <div class="panel panel-default col-sm-6 col-sm-offset-2">
        <div class="panel-body">
            <h2>Вход</h2>
            <? if(isset($session['error'])) : ?>
                <div class="alert alert-danger">
                <?=$session["error"]?>
                </div><?
                unset($session["error"]);
            endif; ?>
            <form method="post" id="login_form">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" placeholder="логин" class="form-control input-lg" name="login"
                            <?=isset($session['user_login'])?"value='".$session["user_login"]."'":""?>>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" placeholder="пароль" class="form-control input-lg" name="password">
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary btn-lg">Войти</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('input[name=login]').focus();
        $('#login_form').bootstrapValidator({
            fields: {
                login: {
                    validators: {
                        notEmpty: {
                            message: 'Логин обязателен и не может быть пустым'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: 'Пароль обязателен и не может быть пустым'
                        }
                    }
                }
            }
        });
    });
</script>

