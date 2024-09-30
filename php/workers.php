<?php
require_once ('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'employee' || $_SESSION['empl_type'] != 1) {
    header('Location: home');
    exit;
}

// Получение списка сотрудников
$sql = "SELECT * FROM workers_in_company";
$stmt = $pdo->query($sql);
$workers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4 text-center">Сотрудники ОГКУ «Государственное юридическое бюро»</h2>
<a href="addworker" class="btn btn-primary mb-3">Добавить сотрудника</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Должность</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($workers as $worker): ?>
            <tr>
                <td><?= htmlspecialchars($worker['key_of_worker']) ?></td>
                <td><?= htmlspecialchars($worker['name_of_worker']) ?></td>
                <td><?= htmlspecialchars($worker['surname_of_worker']) ?></td>
                <td>
                    <?php
                    switch ($worker['type_of_worker']) {
                        case 1:
                            echo "Администратор";
                            break;
                        case 2:
                            echo "Делопроизводитель";
                            break;
                        case 3:
                        default:
                            echo "Юристконсульт";
                            break;
                    }
                    ?>
                </td>
                <td><?= htmlspecialchars($worker['telephone_of_worker']) ?></td>
                <td><?= htmlspecialchars($worker['email_of_worker']) ?></td>
                <td>
                    <a href="editworker?id=<?= $worker['key_of_worker'] ?>" class="btn btn-warning btn-sm">Редактировать</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>