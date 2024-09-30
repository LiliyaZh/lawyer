<h2 class="text-center mb-3">Сообщения из формы обратной связи</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Клиент</th>
            <th>Email</th>
            <th>Телефон</th>
            <th>Сообщение</th>
            <th>Дата/Время</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($feedbacks) > 0): ?>
            <?php foreach ($feedbacks as $feedback): ?>
                <tr>
                    <td><?php echo htmlspecialchars($feedback['name_of_client']." ".$feedback['surname_of_client']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['email_of_client']); ?></td>
                    <td><?php echo htmlspecialchars($feedback['telephone_of_client']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($feedback['message_of_feedback'])); ?></td>
                    <td><?php echo $feedback['date_time_of_feedback']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">На данный момент нет оставленных сообщений.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Пагинация -->
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if ($i == $page)
                echo 'active'; ?>">
                <a class="page-link" href="readfeedbacks?page=<?php echo $i; ?>&limit=<?php echo $messages_per_page; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

<!-- Форма для изменения количества сообщений на странице -->
<form action="" method="GET" class="mt-3">
    <div class="row">
        <div class="col-md-4">
            <label for="limit" class="form-label">Сообщений на странице:</label>
            <input type="number" name="limit" id="limit" value="<?php echo $messages_per_page; ?>" class="form-control"
                min="1" max="100">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Применить</button>
        </div>
    </div>
</form>