<h1 class="text-center">Заявки на оказание бесплатной юридической помощи</h1>
<form method="POST" class="mb-4">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="key_of_status" class="form-label">Статус</label>
            <select id="key_of_status" name="key_of_status" class="form-select">
                <option value="">Выберите статус</option>
                <?php
                $statuses = $pdo->query("SELECT key_of_status, name_of_status FROM statuses_of_requests")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($statuses as $status) {
                    echo "<option value=\"{$status['key_of_status']}\"" . ($filters['key_of_status'] == $status['key_of_status'] ? ' selected' : '') . ">{$status['name_of_status']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="key_of_type" class="form-label">Тип заявки</label>
            <select id="key_of_type" name="key_of_type" class="form-select">
                <option value="">Выберите тип</option>
                <?php
                $types = $pdo->query("SELECT key_of_type, name_of_type FROM types_of_requests")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($types as $type) {
                    echo "<option value=\"{$type['key_of_type']}\"" . ($filters['key_of_type'] == $type['key_of_type'] ? ' selected' : '') . ">{$type['name_of_type']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="key_of_client" class="form-label">Клиент</label>
            <select id="key_of_client" name="key_of_client" class="form-select">
                <option value="">Выберите клиента</option>
                <?php
                $clients_of_company = $pdo->query("SELECT key_of_client, CONCAT(name_of_client, ' ', surname_of_client) AS client_fullname FROM clients_of_company")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($clients_of_company as $client) {
                    echo "<option value=\"{$client['key_of_client']}\"" . ($filters['key_of_client'] == $client['key_of_client'] ? ' selected' : '') . ">{$client['client_fullname']}</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-4">
            <label for="key_of_worker" class="form-label">Сотрудник</label>
            <select id="key_of_worker" name="key_of_worker" class="form-select">
                <option value="">Выберите сотрудника</option>
                <?php
                $workers_in_company = $pdo->query("SELECT key_of_worker, CONCAT(name_of_worker, ' ', surname_of_worker) AS employer_fullname FROM workers_in_company")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($workers_in_company as $employee) {
                    echo "<option value=\"{$employee['key_of_worker']}\"" . ($filters['key_of_worker'] == $employee['key_of_worker'] ? ' selected' : '') . ">{$employee['employer_fullname']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="date_from" class="form-label">Дата от</label>
            <input type="date" id="date_from" name="date_from" class="form-control"
                value="<?= htmlspecialchars($filters['date_from']) ?>">
        </div>
        <div class="col-md-4">
            <label for="date_to" class="form-label">Дата до</label>
            <input type="date" id="date_to" name="date_to" class="form-control"
                value="<?= htmlspecialchars($filters['date_to']) ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-start">
            <button type="submit" class="btn btn-primary">Отфильтровать</button>
        </div>
        <div class="col-md-6 text-end">
            <a href="#" class="btn btn-secondary" onclick="exportTableToCSV('Заявки.csv')">Экспортировать таблицу в CSV</a>
        </div>
    </div>
</form>
<table class="table table-bordered" id="requests">
    <thead>
        <tr>
            <th>Дата и время</th>
            <th>Тип заявки</th>
            <th>Статус заявки</th>
            <th>Гражданин</th>
            <th>Ответственный сотрудник</th>
            <th>Действие</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?= isset($request['date_time_of_request']) ? htmlspecialchars($request['date_time_of_request']) : '' ?></td>
                <td><?= isset($request['name_of_type']) ? htmlspecialchars($request['name_of_type']) : '' ?></td>
                <td><?= isset($request['name_of_status']) ? htmlspecialchars($request['name_of_status']) : '' ?></td>
                <td><?= (isset($request['name_of_client']) ? htmlspecialchars($request['name_of_client']) : '') . ' ' . (isset($request['surname_of_client']) ? htmlspecialchars($request['surname_of_client']) : '') . ' [' . (isset($request['telephone_of_client']) ? htmlspecialchars($request['telephone_of_client']) : '') . ']' ?></td>
                <td><?= (isset($request['name_of_worker']) ? htmlspecialchars($request['name_of_worker']) : '') . ' ' . (isset($request['surname_of_worker']) ? htmlspecialchars($request['surname_of_worker']) : '') . (isset($request['telephone_of_worker']) ? ' [' . htmlspecialchars($request['telephone_of_worker']) . ']' : '')  ?></td>
                <td>
                    <a href="request?id=<?= $request['key_of_request'] ?>" class="btn btn-sm btn-primary">Просмотреть</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
