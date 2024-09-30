<?php
require_once('db.php');

$errors = [];

// Проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Валидация данных
    if (empty($email))
        $errors[] = 'Введите email.';
    if (empty($password))
        $errors[] = 'Введите пароль.';
    if (empty($user_type))
        $errors[] = 'Выберите тип пользователя.';

    // Если ошибок нет, проверка данных
    if (empty($errors)) {
        if ($user_type == 'client') {
            $stmt = $pdo->prepare("SELECT * FROM clients_of_company WHERE email_of_client = ?");
        } else {
            $stmt = $pdo->prepare("SELECT * FROM workers_in_company WHERE email_of_worker = ?");
        }

        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user[$user_type == 'client' ? 'password_of_client' : 'password_of_worker'])) {
            // Успешный вход
            $_SESSION['user_id'] = $user[$user_type == 'client' ? 'key_of_client' : 'key_of_worker'];
            $_SESSION['user_name'] = $user[$user_type == 'client' ? 'name_of_client' : 'name_of_worker'];
            $_SESSION['user_surname'] = $user[$user_type == 'client' ? 'surname_of_client' : 'surname_of_worker'];
            $_SESSION['user_type'] = $user_type == 'client' ? 'client' : 'employee';
            $_SESSION['empl_type'] = $user_type == 'employee' ? $user['type_of_worker'] : null;
            header('Location: requests');
            exit;
        } else {
            $errors[] = 'Неверный email или пароль.';
        }
    }
}
?>

<h2 class="text-center">Авторизация в системе</h2>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
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
    <div class="text-center">
        <button type="submit" class="btn btn-success">Войти в личный кабинет</button>
    </div>
</form>