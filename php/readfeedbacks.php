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