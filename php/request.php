<?php
require_once ('db.php');

// Получение ID заявки из параметров URL
$reqId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$key_of_request = $reqId;

// Загрузка текущих данных заявки
$sql = "SELECT cr.*, (SELECT text_of_message FROM messages_in_requests WHERE key_of_request = ? ORDER BY date_time_of_message ASC LIMIT 1) AS problem FROM requests_of_clients AS cr WHERE key_of_request = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$reqId, $reqId]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

// Загрузка всех статусов
$sql = "SELECT * FROM statuses_of_requests";
$statuses = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Загрузка всех типов заявок
$sql = "SELECT * FROM types_of_requests";
$types = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Загрузка всех юристконсультов
$sql = "SELECT * FROM workers_in_company WHERE type_of_worker = 3";
$workers_in_company = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updaterequest'])) {
    $reqTypeId = intval($_POST['key_of_type']);
    $reqStatusId = intval($_POST['key_of_status']);
    $employerId = intval($_POST['key_of_worker']);

    // Обновление заявки
    $sql = "UPDATE requests_of_clients SET key_of_type = ?, key_of_status = ?, key_of_worker = ? WHERE key_of_request = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$reqTypeId, $reqStatusId, $employerId, $reqId])) {
        if ($request['key_of_status'] != $reqStatusId) {
            // Добавление записи в историю заявки
            $sql = "INSERT INTO history_of_requests (key_of_status, key_of_request) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$reqStatusId, $reqId]);
        }

        echo "<div class='alert alert-success text-center'>Параметры заявки успешно обновлены.</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Ошибка при обновлении параметров заявки: " . $stmt->errorInfo()[2] . "</div>";
    }
}

// Запрос истории обработки заявки
$stmt = $pdo->prepare('
    SELECT rh.date_time_of_history, rs.name_of_status
    FROM history_of_requests rh
    JOIN statuses_of_requests rs ON rh.key_of_status = rs.key_of_status
    WHERE rh.key_of_request = :key_of_request
    ORDER BY rh.date_time_of_history ASC
');
$stmt->execute(['key_of_request' => $reqId]);
$history = $stmt->fetchAll();

// ЧАТ
// Обработка отправки нового сообщения
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendchat'])) {
    $message = $_POST['message'] ?? '';
    $key_of_client = $_SESSION['user_type'] === 'client' ? $_SESSION['user_id'] : null;
    $key_of_worker = $_SESSION['user_type'] === 'employee' ? $_SESSION['user_id'] : null;
    
    if (!empty($message)) {
        $stmt = $pdo->prepare('INSERT INTO messages_in_requests (text_of_message, key_of_client, key_of_worker, key_of_request) VALUES (:text_of_message, :key_of_client, :key_of_worker, :key_of_request)');
        $stmt->execute(['text_of_message' => $message, 'key_of_client' => $key_of_client, 'key_of_worker' => $key_of_worker, 'key_of_request' => $key_of_request]);
    }
    
    // Обработка загрузки файла
    if (!empty($_FILES['file']['name'])) {
        $name_of_file = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $size_of_file = $_FILES['file']['size'];
        $server_name_of_file = uniqid() . '-' . $name_of_file;
        
        move_uploaded_file($file_tmp, 'uploads/' . $server_name_of_file);
        
        $stmt = $pdo->prepare('INSERT INTO files_of_requests (name_of_file, server_name_of_file, size_of_file, key_of_client, key_of_worker, key_of_request) VALUES (:name_of_file, :server_name_of_file, :size_of_file, :key_of_client, :key_of_worker, :key_of_request)');
        $stmt->execute(['name_of_file' => $name_of_file, 'server_name_of_file' => $server_name_of_file, 'size_of_file' => $size_of_file, 'key_of_client' => $key_of_client, 'key_of_worker' => $key_of_worker, 'key_of_request' => $key_of_request]);
    }
    
    header("Location: request?id=$reqId");
    exit();
}

// Запрос для получения сообщений и файлов
$stmt = $pdo->prepare('
    SELECT m.text_of_message, m.date_time_of_message, c.name_of_client, c.surname_of_client, e.name_of_worker, e.surname_of_worker
    FROM messages_in_requests m
    LEFT JOIN clients_of_company c ON m.key_of_client = c.key_of_client
    LEFT JOIN workers_in_company e ON m.key_of_worker = e.key_of_worker
    WHERE m.key_of_request = :key_of_request
    ORDER BY m.date_time_of_message ASC
');
$stmt->execute(['key_of_request' => $reqId]);
$messages = $stmt->fetchAll();

$stmt = $pdo->prepare('
    SELECT f.name_of_file, f.server_name_of_file, f.date_time_upload_of_file, c.name_of_client, c.surname_of_client, e.name_of_worker, e.surname_of_worker
    FROM files_of_requests f
    LEFT JOIN clients_of_company c ON f.key_of_client = c.key_of_client
    LEFT JOIN workers_in_company e ON f.key_of_worker = e.key_of_worker
    WHERE f.key_of_request = :key_of_request
    ORDER BY f.date_time_upload_of_file ASC
');
$stmt->execute(['key_of_request' => $reqId]);
$files = $stmt->fetchAll();
?>

<h2 class="text-center mb-4">Управление заявкой на оказание бесплатной юридической помощи</h2>
<form action="" method="POST">
    <div class="mb-3">
        <label for="problem" class="form-label">Вопрос/Проблемная ситуация гражданина</label>
        <textarea class="form-control" readonly
            id="problem"><?= $request['problem'] ? htmlspecialchars($request['problem']) : '' ?></textarea>
    </div>
    <div class="mb-3">
        <label for="key_of_type" class="form-label">Вид юридической услуги</label>
        <select class="form-select" id="key_of_type" name="key_of_type" <?= $_SESSION['user_type'] == 'client' ? 'disabled' : 'required' ?> >
            <option value="">Выбрать вид услуги</option>
            <?php foreach ($types as $type): ?>
                <option value="<?php echo htmlspecialchars($type['key_of_type']); ?>" <?php echo $type['key_of_type'] == $request['key_of_type'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($type['name_of_type']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="key_of_status" class="form-label">Статус заявки</label>
        <select class="form-select" id="key_of_status" name="key_of_status" <?= $_SESSION['user_type'] == 'client' ? 'disabled' : 'required' ?> >
            <?php foreach ($statuses as $status): ?>
                <option value="<?php echo htmlspecialchars($status['key_of_status']); ?>" <?php echo $status['key_of_status'] == $request['key_of_status'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($status['name_of_status']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="key_of_worker" class="form-label">Ответственный юристконсульт</label>
        <select class="form-select" id="key_of_worker" name="key_of_worker" <?= $_SESSION['user_type'] == 'client' ? 'disabled' : 'required' ?> >
            <option value="">Выбрать ответственного юристконсульта</option>
            <?php foreach ($workers_in_company as $employer): ?>
                <option value="<?php echo htmlspecialchars($employer['key_of_worker']); ?>" <?php echo $employer['key_of_worker'] == $request['key_of_worker'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($employer['name_of_worker']) . ' ' . htmlspecialchars($employer['surname_of_worker']) . ' [' . htmlspecialchars($employer['telephone_of_worker']) . ']'; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="comment_of_request" class="form-label">Комментарий юристконсульта по результатам работы с
            гражданином</label>
        <textarea class="form-control"
            id="comment_of_request" <?= $_SESSION['user_type'] == 'client' ? 'readonly' : '' ?> ><?= $request['comment_of_request'] ? htmlspecialchars($request['comment_of_request']) : '' ?></textarea>
    </div>
    <?php if($_SESSION['user_type'] == 'employee') { ?> 
        <div class="mt-5 mb-3">
            <button type="submit" name="updaterequest" class="btn btn-primary">Обновить параметры заявки</button>
        </div>
    <?php } ?>
</form>

<h4 class="text-start mb-4 mt-5">История обработки заявки:</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Дата и время</th>
            <th>Статус</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($history as $entry): ?>
            <tr>
                <td><?php echo htmlspecialchars($entry['date_time_of_history']); ?></td>
                <td><?php echo htmlspecialchars($entry['name_of_status']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h4 class="text-start mt-5 mb-4">Чат взаимодействия между юридическим бюро и гражданином:</h4>
<style>
    .client-message {
        background-color: #d1ecf1;
        padding: 10px;
        border-radius: 10px;
        margin: 10px 0;
    }

    .employee-message {
        background-color: #f8d7da;
        padding: 10px;
        border-radius: 10px;
        margin: 10px 0;
    }

    .file-message {
        padding: 10px;
        border-radius: 10px;
        margin: 10px 0;
        background-color: #e2e3e5;
    }
</style>
<div class="chat-box">
    <?php foreach ($messages as $message): ?>
        <div class="<?php echo $message['name_of_client'] ? 'client-message' : 'employee-message'; ?>">
            <strong><?php echo htmlspecialchars($message['name_of_client'] ?? $message['name_of_worker']) . ' ' . htmlspecialchars($message['surname_of_client'] ?? $message['surname_of_worker']); ?>:</strong>
            <p><?php echo htmlspecialchars($message['text_of_message']); ?></p>
            <small><?php echo htmlspecialchars($message['date_time_of_message']); ?></small>
        </div>
    <?php endforeach; ?>
    <?php foreach ($files as $file): ?>
        <div class="file-message">
            <strong><?php echo htmlspecialchars($file['name_of_client'] ?? $file['name_of_worker']) . ' ' . htmlspecialchars($file['surname_of_client'] ?? $file['surname_of_worker']); ?>:</strong>
            <p>Файл: <a href="uploads/<?php echo htmlspecialchars($file['server_name_of_file']); ?>"
                    download><?php echo htmlspecialchars($file['name_of_file']); ?></a></p>
            <small><?php echo htmlspecialchars($file['date_time_upload_of_file']); ?></small>
        </div>
    <?php endforeach; ?>
</div>
<form action="request?id=<?php echo $key_of_request; ?>" method="post" enctype="multipart/form-data"
    class="mt-3">
    <div class="mb-3">
        <label for="message" class="form-label">Новое сообщение</label>
        <textarea name="message" id="message" rows="3" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label for="file" class="form-label">Приложить файл</label>
        <input type="file" name="file" id="file" class="form-control">
    </div>
    <button type="submit" name="sendchat" class="btn btn-primary">Отправить</button>
</form>