<h2>Вход в систему</h2>
<form method="post">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Тип пользователя</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="user_type" id="client" value="client" required>
            <label class="form-check-label" for="client">Гражданин</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="user_type" id="employee" value="employee" required>
            <label class="form-check-label" for="employee">Сотрудник юридического бюро</label>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Войти</button>
</form>