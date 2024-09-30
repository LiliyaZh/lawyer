<h2 class="mb-4 text-center">Редактировать данные сотрудника</h2>
<?php if (isset($worker)): ?>
    <form action="edit_worker.php?id=<?= $worker['key_of_worker'] ?>" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($worker['key_of_worker']) ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Имя</label>
            <input type="text" class="form-control" id="name" name="name"
                value="<?= htmlspecialchars($worker['name_of_worker']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Фамилия</label>
            <input type="text" class="form-control" id="surname" name="surname"
                value="<?= htmlspecialchars($worker['surname_of_worker']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="info" class="form-label">Информация</label>
            <textarea class="form-control" id="info" name="info" rows="3"
                required><?= htmlspecialchars($worker['information_of_worker']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Телефон</label>
            <input type="text" class="form-control" id="telephone" name="telephone"
                value="<?= htmlspecialchars($worker['telephone_of_worker']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                value="<?= htmlspecialchars($worker['email_of_worker']) ?>" required>
        </div>
        <div class="mb-4">
            <label for="type" class="form-label">Должность</label>
            <select class="form-select" id="type" name="type" required>
                <option value="1" <?= $worker['type_of_worker'] == 1 ? 'selected' : '' ?>>Администратор</option>
                <option value="2" <?= $worker['type_of_worker'] == 2 ? 'selected' : '' ?>>Делопроизводитель</option>
                <option value="3" <?= $worker['type_of_worker'] == 3 ? 'selected' : '' ?>>Юристконсульт</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
    </form>
<?php else: ?>
    <p>Сотрудник не найден.</p>
<?php endif; ?>