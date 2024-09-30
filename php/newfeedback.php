<?php
require_once('db.php');

// Проверка авторизации
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'client') {
    header('Location: login');
    exit;
}

// Обработка формы
$alert = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = htmlspecialchars($_POST['message']);

    // SQL запрос на вставку данных
    $sql = "INSERT INTO feedbacks_of_clients (key_of_client, message_of_feedback) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id'], $message]);

    $alert = "Ваше сообщение доставлено. Ожидайте, с Вами свяжутся.";
}
?>

<h2 class="text-center">Форма обратной связи</h2>
<?php
if($alert){
    echo "<div class='alert alert-success text-center'>$alert</div>";
}
?>

<form action="" method="POST">
    <div class="mb-3">
        <label for="message" class="form-label">Оставьте Ваше сообщение</label>
        <textarea class="form-control" id="message" name="message" rows="10" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>