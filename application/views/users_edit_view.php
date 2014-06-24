<? switch($data['action']) {
    case "create" : ?><h1>Добавление пользователя</h1><? break;
    case "edit" : ?><h1>Изменение данных пользователя</h1><? break;
}?>
<form role="form" autocomplete="off"  class="form-horizontal col-sm-8" method="POST" id="user-<?=$data['action']?>-form">
    <? if(isset($data['error'])) { ?><div class="alert alert-danger"><?=$data['error']?></div><? } ?>
    <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
    <input style="display:none" type="text" name="fakeusernameremembered"/>
    <input style="display:none" type="password" name="fakepasswordremembered"/>

    <div class="form-group">
        <label for="user_name" class="col-sm-4 control-label">Полное имя</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="фамилия, имя и отчество" name="user_name"
                   id="user_name" value="<?=isset($data['user_name'])?$data['user_name']:"" ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="user_login" class="col-sm-4 control-label">Логин</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="логин" name="user_login"
                   id="user_login" value="<?=isset($data['user_login'])?$data['user_login']:"" ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-sm-4 control-label">Пароль</label>
        <div class="col-sm-8">
            <input type="password" class="form-control" placeholder="пароль" name="password" id="password">
            <?=$data['action'] == 'edit'?"<span class='help-block'>Оставьте пустым, чтобы не изменять</span>":""?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Тип пользователя</label>
        <div class="col-sm-8">
            <? foreach($data['user_types'] as $user_type){?>
                <label><input type="radio" name="user_type" <?
                        if (isset($data["user_type_id"]) && $data["user_type_id"] == $user_type["type_id"] ||
                            !isset($data["user_type_id"]) && $user_type["type_id"] == 1) {
                        ?> checked="checked" <? }?>" value="<?=$user_type["type_id"]?>">
                    <?=$user_type['user_type']?>
                </label>
            <? } ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-primary"><?=$data['action'] == 'create'?"Добавить":"Сохранить" ?></button>
            <a href="/users" class="btn btn-default">Отмена</a>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#user-<?=$data['action']?>-form').bootstrapValidator({
            fields: {
                <? if($data['action']=="create") {?>
                password: {
                    validators: {
                        notEmpty: {
                            message: 'Пароль обязателен и не может быть пустым'
                        },
                        stringLength: {
                            min: 8,
                            message: 'Пароль должен быть длинее 8 символов'
                        }
                    }
                },
                <? } ?>
                user_name: {
                    validators: {
                        stringLength: {
                            min: 10,
                            max: 64,
                            message: 'Имя должно быть длинее 10 символов и короче 64 символов'
                        },
                        notEmpty: {
                            message: 'Имя обязательно и не может быть пустым'
                        },
                        regexp: {
                            regexp: /^[a-zа-яёїі'\s]+$/i,
                            message: 'Имя может состоять только из букв и пробелов'
                        }
                    }
                },
                user_login: {
                    validators: {
                        stringLength: {
                            min: 5,
                            max: 30,
                            message: 'Логин должен быть длинее 5 символов и короче 30 символов'
                        },
                        regexp: {
                            regexp: /^[a-z-/.]+$/i,
                            message: 'Логин может состоять только из латинских символов, точки или дефиса'
                        },
                        notEmpty: {
                            message: 'Логин обязателен и не может быть пустым'
                        },
                        remote: {
                            message: 'Этот логин уже используется другим пользователем',
                            url: '/users/checkLoginAvailableAjax/<?=isset($data['user_login'])?"?old_login=".$data['user_login']:"" ?>'
                        }
                    }
                }
            }
        });
    });
</script>