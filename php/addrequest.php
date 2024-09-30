<?php
require_once('db.php');

// Проверка авторизации
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] != 'client' && !isset($_SESSION['empl_type']) && $_SESSION['empl_type'] != 2)) {
    header('Location: login');
    exit;
}

$errors = [];

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text_of_message = $_POST['text_of_message'];

    // Валидация данных
    if (empty($text_of_message))
        $errors[] = 'Опишите проблемную ситуацию.';

    // Если ошибок нет, сохранение данных
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Добавление заявки
            if(isset($_SESSION['empl_type']) && $_SESSION['empl_type'] == 2) // Делопроизводитель
                $key_of_client = $_POST['key_of_client'];
            else
                $key_of_client = $_SESSION['user_id'];

            $stmt = $pdo->prepare("INSERT INTO requests_of_clients (key_of_client) VALUES (?)");
            $stmt->execute([$key_of_client]);
            $key_of_request = $pdo->lastInsertId();
            

            // Добавление сообщения
            $stmt = $pdo->prepare("INSERT INTO messages_in_requests (text_of_message, key_of_client, key_of_request) VALUES (?, ?, ?)");
            $stmt->execute([$text_of_message, $key_of_client, $key_of_request]);

            // Добавление записи в историю заявок
            $stmt = $pdo->prepare("INSERT INTO history_of_requests (key_of_status, key_of_request) VALUES (1, ?)");
            $stmt->execute([$key_of_request]);

            // Обработка загруженных файлов
            if (!empty($_FILES['files']['name'][0])) {
                $uploadDir = 'uploads/';
                foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
                    $fileName = basename($_FILES['files']['name'][$key]);
                    $fileSize = $_FILES['files']['size'][$key];
                    $fileServerName = uniqid() . '-' . $fileName;
                    $filePath = $uploadDir . $fileServerName;

                    if (move_uploaded_file($tmpName, $filePath)) {
                        $stmt = $pdo->prepare("INSERT INTO files_of_requests (name_of_file, server_name_of_file, size_of_file, key_of_client, key_of_request) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$fileName, $fileServerName, $fileSize, $_SESSION['user_id'], $key_of_request]);
                    }
                }
            }

            $pdo->commit();

            echo '<div class="alert alert-success text-center">Заявка на оказание бесплатной юридической помощи успешно создана.</div>';
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = '<div class="alert alert-danger text-center">Ошибка при создании заявки: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

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
    <div class="text-center">
        <button type="submit" class="btn btn-primary">Создать заявку</button>
    </div>
</form>