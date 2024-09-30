<h2 class="text-center mb-4">Управление заявкой на оказание бесплатной юридической помощи</h2>
<form action="" method="POST">
    <div class="mb-3">
        <label for="problem" class="form-label">Вопрос/Проблемная ситуация гражданина</label>
        <textarea class="form-control" readonly
            id="problem"><?= $request['problem'] ? htmlspecialchars($request['problem']) : '' ?></textarea>
    </div>
    <div class="mb-3">
        <label for="key_of_type" class="form-label">Вид юридической услуги</label>
        <select class="form-select" id="key_of_type" name="key_of_type" <?= $_SESSION['user_type'] == 'client' ? 'disabled' : 'required' ?> >
            <option value="">Выбрать вид услуги</option>
            <?php foreach ($types as $type): ?>
                <option value="<?php echo htmlspecialchars($type['key_of_type']); ?>" <?php echo $type['key_of_type'] == $request['key_of_type'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($type['name_of_type']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="key_of_status" class="form-label">Статус заявки</label>
        <select class="form-select" id="key_of_status" name="key_of_status" <?= $_SESSION['user_type'] == 'client' ? 'disabled' : 'required' ?> >
            <?php foreach ($statuses as $status): ?>
                <option value="<?php echo htmlspecialchars($status['key_of_status']); ?>" <?php echo $status['key_of_status'] == $request['key_of_status'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($status['name_of_status']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="key_of_worker" class="form-label">Ответственный юристконсульт</label>
        <select class="form-select" id="key_of_worker" name="key_of_worker" <?= $_SESSION['user_type'] == 'client' ? 'disabled' : 'required' ?> >
            <option value="">Выбрать ответственного юристконсульта</option>
            <?php foreach ($workers_in_company as $employer): ?>
                <option value="<?php echo htmlspecialchars($employer['key_of_worker']); ?>" <?php echo $employer['key_of_worker'] == $request['key_of_worker'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($employer['name_of_worker']) . ' ' . htmlspecialchars($employer['surname_of_worker']) . ' [' . htmlspecialchars($employer['telephone_of_worker']) . ']'; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="comment_of_request" class="form-label">Комментарий юристконсульта по результатам работы с
            гражданином</label>
        <textarea class="form-control"
            id="comment_of_request" <?= $_SESSION['user_type'] == 'client' ? 'readonly' : '' ?> ><?= $request['comment_of_request'] ? htmlspecialchars($request['comment_of_request']) : '' ?></textarea>
    </div>
    <?php if($_SESSION['user_type'] == 'employee') { ?> 
        <div class="mt-5 mb-3">
            <button type="submit" name="updaterequest" class="btn btn-primary">Обновить параметры заявки</button>
        </div>
    <?php } ?>
</form>

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