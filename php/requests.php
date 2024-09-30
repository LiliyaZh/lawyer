<?php
require_once ('db.php');

function fetchRequests($pdo, $filters)
{
    $query = "SELECT 
                cr.date_time_of_request, 
                rt.name_of_type, 
                rs.name_of_status, 
                c.name_of_client, 
                c.surname_of_client, 
                c.telephone_of_client, 
                e.name_of_worker, 
                e.surname_of_worker, 
                e.telephone_of_worker,
                cr.key_of_request
            FROM requests_of_clients cr
            LEFT JOIN types_of_requests rt ON cr.key_of_type = rt.key_of_type
            JOIN statuses_of_requests rs ON cr.key_of_status = rs.key_of_status
            JOIN clients_of_company c ON cr.key_of_client = c.key_of_client
            LEFT JOIN workers_in_company e ON cr.key_of_worker = e.key_of_worker
            WHERE 1=1";

    $params = [];
    if (isset($_SESSION['empl_type']) && $_SESSION['empl_type'] == 3) { // Юристконсульт
        $query .= " AND cr.key_of_worker = :key_of_worker";
        $params[':key_of_worker'] = $_SESSION['user_id'];
    }

$filters = [
    'key_of_status' => $_GET['key_of_status'] ?? '',
    'key_of_type' => $_GET['key_of_type'] ?? '',
    'key_of_client' => $_GET['key_of_client'] ?? '',
    'key_of_worker' => $_GET['key_of_worker'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? ''
];

$requests = fetchRequests($pdo, $filters);

?>


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
<script>
    function exportTableToCSV(filename) {
        var csv = [];
        var rows = document.querySelectorAll("table#requests tr");

        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");

            for (var j = 0; j < cols.length - 1; j++)
                row.push(cols[j].innerText);

            csv.push(row.join(","));
        }

        // Сохранение в файл
        var csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link); // Удаление элемента после скачивания
    }
</script>