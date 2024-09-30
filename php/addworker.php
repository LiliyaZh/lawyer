<h2 class="mb-4 text-center">Добавить сотрудника</h2>
<form action="add_worker.php" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Имя</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="surname" class="form-label">Фамилия</label>
        <input type="text" class="form-control" id="surname" name="surname" required>
    </div>
    <div class="mb-3">
        <label for="info" class="form-label">Информация</label>
        <textarea class="form-control" id="info" name="info" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label for="telephone" class="form-label">Телефон</label>
        <input type="text" class="form-control" id="telephone" name="telephone" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-4">
        <label for="type" class="form-label">Должность</label>
        <select class="form-select" id="type" name="type" required>
            <option value="1">Администратор</option>
            <option value="2">Делопроизводитель</option>
            <option value="3" selected>Юристконсульт</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Добавить</button>
</form>