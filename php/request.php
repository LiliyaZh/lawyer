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

<h4 class="text-start mt-5 mb-4">Чат взаимодействия между юридическим бюро и гражданином:</h4>
<style>
    .client-message {
        background-color: #d1ecf1;
        padding: 10px;
        border-radius: 10px;
        margin: 10px 0;
    }

    .employee-message {
        background-color: #f8d7da;
        padding: 10px;
        border-radius: 10px;
        margin: 10px 0;
    }

    .file-message {
        padding: 10px;
        border-radius: 10px;
        margin: 10px 0;
        background-color: #e2e3e5;
    }
</style>
<div class="chat-box">
    <?php foreach ($messages as $message): ?>
        <div class="<?php echo $message['name_of_client'] ? 'client-message' : 'employee-message'; ?>">
            <strong><?php echo htmlspecialchars($message['name_of_client'] ?? $message['name_of_worker']) . ' ' . htmlspecialchars($message['surname_of_client'] ?? $message['surname_of_worker']); ?>:</strong>
            <p><?php echo htmlspecialchars($message['text_of_message']); ?></p>
            <small><?php echo htmlspecialchars($message['date_time_of_message']); ?></small>
        </div>
    <?php endforeach; ?>
    <?php foreach ($files as $file): ?>
        <div class="file-message">
            <strong><?php echo htmlspecialchars($file['name_of_client'] ?? $file['name_of_worker']) . ' ' . htmlspecialchars($file['surname_of_client'] ?? $file['surname_of_worker']); ?>:</strong>
            <p>Файл: <a href="uploads/<?php echo htmlspecialchars($file['server_name_of_file']); ?>"
                    download><?php echo htmlspecialchars($file['name_of_file']); ?></a></p>
            <small><?php echo htmlspecialchars($file['date_time_upload_of_file']); ?></small>
        </div>
    <?php endforeach; ?>
</div>
<form action="request?id=<?php echo $key_of_request; ?>" method="post" enctype="multipart/form-data"
    class="mt-3">
    <div class="mb-3">
        <label for="message" class="form-label">Новое сообщение</label>
        <textarea name="message" id="message" rows="3" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label for="file" class="form-label">Приложить файл</label>
        <input type="file" name="file" id="file" class="form-control">
    </div>
    <button type="submit" name="sendchat" class="btn btn-primary">Отправить</button>
</form>