<?php
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Функция для загрузки запрашиваемой страницы
function loadPage($page)
{
    $file = "php/$page.php";
    if (file_exists($file)) {
        require_once($file);
    } else {
        require_once("php/404.php");
    }
}
?>

// Извлечение URL и параметров
$url = isset($_GET['url']) ? $_GET['url'] : 'home';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>АИС оказания бесплатной юридической помощи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="home" title="Главная страница">АИС юридической помощи</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <!--li class="nav-item">
                            <a class="nav-link" href="home">Главная</a>
                        </li-->
                        <?php if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] == "client"): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="about">О бюро</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="government">Госорганы</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="law">Законодательство</a>
                            </li>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['user_type'] == "client" || (isset($_SESSION['empl_type']) && $_SESSION['empl_type'] == 2)): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="addrequest">Создать заявку</a>
                                </li>
                            <?php endif; ?>
                            <?php if ($_SESSION['user_type'] == "employee" && (isset($_SESSION['empl_type']) && $_SESSION['empl_type'] == 1)): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="workers">Сотрудники бюро</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="requests">Фильтр заявок</a>
                            </li>
                            <?php if ($_SESSION['user_type'] == "client"): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="newfeedback">Обратная связь</a>
                                </li>
                            <?php endif; ?>
                            <?php if ($_SESSION['user_type'] == "employee"): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="readfeedbacks">Обратная связь</a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="profile"><?php echo $_SESSION['user_surname'] . ' ' . $_SESSION['user_name']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-outline-danger" href="logout">Выйти</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="btn btn-outline-primary" href="register">Регистрация</a>
                            </li>
                            <li class="nav-item ms-5">
                                <a class="btn btn-outline-success" href="login">Авторизация</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        <?php loadPage($url); ?>
        <footer class="bg-light text-center text-lg-start mt-4 mb-5">
            <div class="container p-4">
                <p class="text-center">© <?php echo date("Y"); ?> АИС оказания бесплатной юридической помощи. Все права
                    защищены.</p>
            </div>
        </footer>
        <?php
        print_r($_SESSION);
        echo "<br>";
        print_r($_POST);
        echo "<br>";
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>
</body>

</html>