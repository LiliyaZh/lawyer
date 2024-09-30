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