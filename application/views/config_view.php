<h1 class="page-header">Цена на билеты</h1>
<div class="well"> <button class="btn btn-primary btn-lg btn-block" id="prices" data-action="1">Редактировать цены</button></div>


<? foreach($data as $key => $value) {?>
    <div class="row">
        <div class="form-group form-inline prices-parent">
            <label for="name" class="col-sm-2"><?=$value['sector_name']?> , грн</label>
            <div class="col-xs-4"> <input type="text" class="form-control col-sm-2 prices"  data-sector_id="<?=$value['sector_id']?>" placeholder="Цена" value="<?=$value['sector_price']?>"  disabled="disabled" required="required"></div>

        </div>
    </div>

<? } ?>

<? //echo "<pre>";print_r($data); echo "</pre>";?>

