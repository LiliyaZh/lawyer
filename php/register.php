<?php
require_once('db.php');

$errors = [];

// Проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Валидация данных
    if (empty($name))
        $errors[] = 'Введите имя.';
    if (empty($surname))
        $errors[] = 'Введите фамилию.';
    if (empty($tel))
        $errors[] = 'Введите телефон.';
    if (empty($email))
        $errors[] = 'Введите email.';
    if (empty($password))
        $errors[] = 'Введите пароль.';
    if ($password !== $confirm_password)
        $errors[] = 'Пароли не совпадают.';

    // Проверка на уникальность email
    $stmt = $pdo->prepare("SELECT * FROM clients_of_company WHERE email_of_client = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0)
        $errors[] = 'Этот email уже зарегистрирован.';

    // Если ошибок нет, вставка данных в базу
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO clients_of_company (name_of_client, surname_of_client, telephone_of_client, email_of_client, password_of_client) VALUES (?, ?, ?, ?, ?)");
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->execute([$name, $surname, $tel, $email, $hashed_password]);

        // Перенаправление на страницу входа
        $_SESSION['message'] = 'Регистрация успешна! Теперь вы можете войти.';
        header('Location: login');
        exit;
    }
}
?>

<?php if (isset($_GET['p']) && $_GET['p'] == "d"): ?>
    <div class="alert alert-info text-center mt-3 mb-3">Ваш аккаунт был успешно удален</div>
<?php endif; ?>

<h2>Зарегистрируйтесь, чтобы оставлять заявки на бесплатную юридическую помощь</h2>
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
        <label for="name" class="form-label">Имя</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="surname" class="form-label">Фамилия</label>
        <input type="text" class="form-control" id="surname" name="surname" required>
    </div>
    <div class="mb-3">
        <label for="tel" class="form-label">Телефон</label>
        <input type="text" class="form-control" id="tel" name="tel" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="confirm_password" class="form-label">Подтвердите пароль</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
</form>