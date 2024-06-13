<?php
require '../vendor/auth.php'; // Подключение auth.php для проверки аутентификации
require '../vendor/db_connect.php'; // Подключение к базе данных

// Установка текущей страницы для пагинации
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$tasksPerPage = 10;
$offset = ($page - 1) * $tasksPerPage;

// Подсчет общего количества выполненных задач пользователя
try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM tasks WHERE user_id = :user_id AND status = 2');
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $totalTasks = $stmt->fetchColumn();
} catch (PDOException $e) {
    die('Ошибка: ' . $e->getMessage());
}

// Получение выполненных задач пользователя
try {
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE user_id = :user_id AND status = 2 LIMIT :offset, :tasksPerPage');
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':tasksPerPage', $tasksPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Ошибка: ' . $e->getMessage());
}
?>

<?php include '../header.php'; ?>

<!-- Проверка на ошибки и сообщения -->
<?php if (isset($_GET['error'])): ?>
    <div class="uk-alert-danger" uk-alert>
        <p><?php echo htmlspecialchars($_GET['error']); ?></p>
    </div>
<?php endif; ?>
<?php if (isset($_GET['message'])): ?>
    <div class="uk-alert-success" uk-alert>
        <p><?php echo htmlspecialchars($_GET['message']); ?></p>
    </div>
<?php endif; ?>
<div class="uk-width-3-4">
    <h2>Выполненные задачи</h2>
    <table class="uk-table uk-table-divider">
        <thead>
            <tr>
                <th>Изображение</th>
                <th>Заголовок</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr id="task-<?php echo htmlspecialchars($task['id']); ?>" data-id="<?php echo htmlspecialchars($task['id']); ?>">
                    <td><?php if (!empty($task['image'])): ?>
                        <img src="<?php echo htmlspecialchars($task['image']); ?>" alt="Изображение" style="width: 50px;">
                    <?php endif; ?></td>
                    <td><?php echo htmlspecialchars($task['title']); ?></td>
                    <td><?php echo htmlspecialchars($task['created_at']); ?></td>
                    <td>
                        <form style="display:inline;">
                            <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                            <button type="button" class="uk-icon-button" uk-icon="trash"></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Пагинация -->
    <?php if ($totalTasks > $tasksPerPage): ?>
    <ul class="uk-pagination uk-flex-center uk-margin-large-top">
        <li><a href="?page=<?php echo max(1, $page - 1); ?>"><span uk-pagination-previous></span></a></li>
        <li><span>Страница <?php echo $page; ?></span></li>
        <li><a href="?page=<?php echo $page + 1; ?>"><span uk-pagination-next></span></a></li>
    </ul>
    <?php endif; ?>
</div>
<?php include '../footer.php'; ?>