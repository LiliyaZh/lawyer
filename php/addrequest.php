<h2 class="text-center">Добавить заявку</h2>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form method="post" enctype="multipart/form-data">
    <?php if (isset($_SESSION['empl_type']) && $_SESSION['empl_type'] == 2): ?>
        <div class="mb-3">
            <label for="key_of_client" class="form-label">Клиент</label>
            <select id="key_of_client" name="key_of_client" class="form-select" required>
                <option value="">Выберите клиента</option>
                <?php
                $clients_of_company = $pdo->query("SELECT key_of_client, CONCAT(name_of_client, ' ', surname_of_client) AS client_fullname FROM clients_of_company")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($clients_of_company as $client) {
                    echo "<option value=\"{$client['key_of_client']}\"" . ($filters['key_of_client'] == $client['key_of_client'] ? ' selected' : '') . ">{$client['client_fullname']}</option>";
                }
                ?>
            </select>
        </div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="text_of_message" class="form-label">Описание проблемы</label>
        <textarea class="form-control" id="text_of_message" name="text_of_message" rows="5" required></textarea>
    </div>
    <div class="mb-3">
        <label for="files" class="form-label">Приложить файлы</label>
        <input type="file" class="form-control" id="files" name="files[]" multiple>
    </div>
    <button type="submit" class="btn btn-primary">Создать заявку</button>
</form>