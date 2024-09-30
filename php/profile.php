<h2 class="text-center mb-3">Данные профиля пользователя АИС</h2>
<div class="row mb-3">
    <div class="col-3"></div>
    <div class="col-2">Тип аккаунта</div>
    <div class="col-4"><?= $type ?></div>
    <div class="col-3"></div>
</div>
<div class="row mb-3">
    <div class="col-3"></div>
    <div class="col-2">Имя</div>
    <div class="col-4"><?= $name ?></div>
    <div class="col-3"></div>
</div>
<div class="row mb-3">
    <div class="col-3"></div>
    <div class="col-2">Фамилия</div>
    <div class="col-4"><?= $surname ?></div>
    <div class="col-3"></div>
</div>
<div class="row mb-3">
    <div class="col-3"></div>
    <div class="col-2">Телефон</div>
    <div class="col-4"><?= $telephone ?></div>
    <div class="col-3"></div>
</div>
<div class="row mb-3">
    <div class="col-3"></div>
    <div class="col-2">Email</div>
    <div class="col-4"><?= $email ?></div>
    <div class="col-3"></div>
</div>

<?php if ($del){ ?>
    <form action="" method="post" class="mt-5">
        <button type="submit" name="del" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить ваш аккаунт и все сообщения и заявки?')">Удалить аккаунт</button>
    </form>
<?php } ?>