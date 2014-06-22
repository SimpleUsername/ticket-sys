<?php if (isset($data['error'])) { ?>
    <div class="alert alert-danger">
        <?=htmlspecialchars($data['error']) ?>
    </div>
<?php } ?>
<form method="post">
    <div class="form-group">
        <input type="text" placeholder="login" class="form-control" name="login" >
    </div>
    <div class="form-group">
        <input type="password" placeholder="password" class="form-control" name="password">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>
</form>