<?php
require_once ('db.php');

$del = false;
if ($_SESSION['user_type'] == "client"){
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['del'])) {
        $key_of_client = $_SESSION['user_id'];

        $sql = "DELETE FROM messages_in_requests WHERE key_of_client = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$key_of_client]);

        $sql = "DELETE FROM files_of_requests WHERE key_of_client = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$key_of_client]);

        $sql = "DELETE FROM feedbacks_of_clients WHERE key_of_client = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$key_of_client]);

        $sql = "DELETE FROM requests_of_clients WHERE key_of_client = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$key_of_client]);

        $sql = "DELETE FROM clients_of_company WHERE key_of_client = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$key_of_client]);

        session_unset();
        session_destroy();
        header("Location: register?p=d");
        exit();
    }

    $sql = "SELECT * FROM clients_of_company WHERE key_of_client = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    $type = "Клиент";
    $name = $client['name_of_client'];
    $surname = $client['surname_of_client'];
    $telephone = $client['telephone_of_client'];
    $email = $client['email_of_client'];
    $del = true;
}
else if ($_SESSION['user_type'] == "employee"){
    $sql = "SELECT * FROM workers_in_company WHERE key_of_worker = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $worker = $stmt->fetch(PDO::FETCH_ASSOC);

    $type = $_SESSION['empl_type'] == "1" ? "Администратор" : ($_SESSION['empl_type'] == "2" ? "Делопроизводитель" : ($_SESSION['empl_type'] == "3" ? "Юристконсульт" : "Ошибка"));
    $name = $worker['name_of_worker'];
    $surname = $worker['surname_of_worker'];
    $telephone = $worker['telephone_of_worker'];
    $email = $worker['email_of_worker'];
}
?>

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