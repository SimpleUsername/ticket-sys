<? switch($data['action']) {
    case "create" : ?><h1>Добавление пользователя</h1><? break;
    case "edit" : ?><h1>Изменение данных пользователя</h1><? break;
}?>
<form role="form" autocomplete="off"  class="form-horizontal col-sm-8" method="POST">
    <? if(isset($data['error'])) { ?><div class="alert alert-danger"><?=$data['error']?></div><? } ?>
    <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
    <input style="display:none" type="text" name="fakeusernameremembered"/>
    <input style="display:none" type="password" name="fakepasswordremembered"/>

    <div class="form-group">
            <label for="user_login" class="col-sm-4 control-label">Логин</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Имя пользователя" name="user_login"
                    id="user_login" value="<?=isset($data['user_login'])?$data['user_login']:"" ?>">
            </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-sm-4 control-label">Пароль</label>
        <div class="col-sm-8">
            <input type="password" class="form-control" placeholder="Пароль" name="password" id="password">
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
        </div>
    </div>
</form>