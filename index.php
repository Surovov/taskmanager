<?php
require 'vendor/auth.php'; // Подключение auth.php для проверки аутентификации
require 'vendor/db_connect.php'; // Подключение к базе данных

// Установка текущей страницы для пагинации
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$tasksPerPage = 10;
$offset = ($page - 1) * $tasksPerPage;

// Подсчет общего количества задач пользователя со статусом 1
try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM tasks WHERE user_id = :user_id AND status = 1');
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $totalTasks = $stmt->fetchColumn();
} catch (PDOException $e) {
    die('Ошибка: ' . $e->getMessage());
}

// Получение задач пользователя со статусом 1
try {
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE user_id = :user_id AND status = 1 LIMIT :offset, :tasksPerPage');
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':tasksPerPage', $tasksPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Ошибка: ' . $e->getMessage());
}
?>

<?php include('header.php'); ?>
<div class="uk-width-3-4">
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
    <div uk-grid>
        <!-- Task Grid and Completed Tasks -->
        <div class="uk-width-3-4" uk-height-viewport="offset-bottom: 30">
            <div class="uk-grid-small uk-child-width-1-2@s uk-child-width-1-3@m" uk-grid id="task-grid">
                <?php foreach ($tasks as $task): ?>
                    <div id="task-<?php echo htmlspecialchars($task['id']); ?>" data-id="<?php echo htmlspecialchars($task['id']); ?>">
                        <div class="uk-card uk-card-default uk-card-body uk-padding-small">
                            <?php if (!empty($task['image'])): ?>
                                <img src="<?php echo htmlspecialchars($task['image']); ?>" alt="Изображение">
                            <?php endif; ?>
                            <h3 class="uk-card-title"><?php echo htmlspecialchars($task['title']); ?></h3>
                            <p>Создано: <?php echo htmlspecialchars($task['created_at']); ?></p>
                            <div class="uk-margin-top">
                                <form action="control/complete.php" method="post" style="display:inline;">
                                    <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                                    <button class="uk-icon-button" uk-icon="check"></button>
                                </form>
                                <form action="control/archive.php" method="post" style="display:inline;">
                                    <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                                    <button class="uk-icon-button" uk-icon="folder"></button>
                                </form>
                                <form style="display:inline;">
                                    <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                                    <button type="button" class="uk-icon-button" uk-icon="trash"></button>
                                </form>
                                <a href="view/edit.php?id=<?php echo htmlspecialchars($task['id']); ?>" class="uk-icon-button" uk-icon="pencil"></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Пагинация -->
            <?php if ($totalTasks > $tasksPerPage): ?>
            <ul class="uk-pagination uk-flex-center uk-margin-large-top">
                <li><a href="?page=<?php echo max(1, $page - 1); ?>"><span uk-pagination-previous></span></a></li>
                <li><span>Страница <?php echo $page; ?></span></li>
                <li><a href="?page=<?php echo $page + 1; ?>"><span uk-pagination-next></span></a></li>
            </ul>
            <?php endif; ?>
        </div>

        <!-- Completed Tasks Area -->
        <div class="uk-width-1-4" id="completed-tasks">
            <div class="uk-placeholder uk-text-center uk-flex uk-flex-middle" uk-height-viewport="offset-bottom: 30" style="background-color: #e0ffe0;">
                <div>
                    <span uk-icon="icon: check"></span><br>
                    <span class="uk-text-middle">Перетащите задачи сюда для выполнения</span>
                </div>
            </div>
        </div>

        <!-- Archived Tasks Area -->
        <div class="uk-width-1-1 uk-margin-top" id="archived-tasks">
            <div class="uk-placeholder uk-text-center" style="background-color: #f8f8f8;">
                <span uk-icon="icon: folder"></span>
                <span class="uk-text-middle">Перетащите задачи сюда для архивирования</span>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var taskGrid = document.getElementById('task-grid');
        var completedTasks = document.getElementById('completed-tasks');
        var archivedTasks = document.getElementById('archived-tasks');

        new Sortable(taskGrid, {
            group: {
                name: 'tasks',
                pull: 'clone',
                put: false
            },
            animation: 150
        });

        new Sortable(completedTasks, {
            group: 'tasks',
            onAdd: function (evt) {
                var taskId = evt.item.getAttribute('data-id');
                fetch('control/complete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id: taskId })
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          evt.item.remove(); // Удаляем элемент из DOM
                          location.reload(); // Перезагрузка страницы
                      } else {
                          alert('Ошибка выполнения задачи: ' + data.error);
                      }
                  }).catch(error => {
                      alert('Ошибка выполнения запроса');
                      console.error('Error:', error);
                  });
            }
        });

        new Sortable(archivedTasks, {
            group: 'tasks',
            onAdd: function (evt) {
                var taskId = evt.item.getAttribute('data-id');
                fetch('control/archive.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id: taskId })
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          evt.item.remove(); // Удаляем элемент из DOM
                          location.reload(); // Перезагрузка страницы
                      } else {
                          alert('Ошибка архивирования задачи: ' + data.error);
                      }
                  }).catch(error => {
                      alert('Ошибка выполнения запроса');
                      console.error('Error:', error);
                  });
            }
        });
    });
</script>

<?php include('view/create.php'); ?>

<?php include('footer.php'); ?>
