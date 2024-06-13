<?php
require '../vendor/auth.php'; // Подключение auth.php для проверки аутентификации
require '../vendor/db_connect.php'; // Подключение к базе данных

// Получение ID задачи из URL
$taskId = $_GET['id'] ?? null;

if (!$taskId) {
    header('Location: ../index.php?error=' . urlencode('Неверный ID задачи.'));
    exit;
}

// Получение данных задачи из базы данных
try {
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = :id AND user_id = :user_id');
    $stmt->bindParam(':id', $taskId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        header('Location: ../index.php?error=' . urlencode('Задача не найдена.'));
        exit;
    }
} catch (PDOException $e) {
    die('Ошибка: ' . $e->getMessage());
}
?>

<?php include '../header.php'; ?>

<div class="uk-width-3-4">
    <h2>Редактировать задачу</h2>

    <!-- Аллерты для ошибок -->
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

    <form action="../control/update.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
        <div class="uk-margin">
            <input class="uk-input" name="title" type="text" placeholder="Заголовок" value="<?php echo htmlspecialchars($task['title']); ?>" required>
        </div>
        <div class="uk-margin">
            <textarea class="uk-textarea" name="description" rows="5" placeholder="Описание"><?php echo htmlspecialchars($task['description']); ?></textarea>
        </div>
        <div class="uk-margin">
            <input class="uk-input" name="deadline_date" type="date" placeholder="Дата дедлайна" value="<?php echo htmlspecialchars($task['deadline_date']); ?>">
        </div>
        <div class="uk-margin">
            <div uk-grid class="uk-grid-small">
                <div class="uk-width-auto">
                    <?php if (!empty($task['image'])): ?>
                        <img src="../<?php echo htmlspecialchars($task['image']); ?>" class="uk-width-small">
                        <div class="uk-margin">
                            <input  class="uk-input" type="text" value="<?php echo htmlspecialchars(basename($task['image'])); ?>" disabled>
                            <button type="button" class="uk-button uk-button-danger" onclick="deleteImage(<?php echo $task['id']; ?>)">Удалить изображение</button>
                        </div>
                    <?php else: ?>
                        <img id="uploaded-image" class="uk-width-small" style="display: none;">
                    <?php endif; ?>
                </div>
                <div class="uk-width-expand">
                    <input id="image-path" class="uk-input" type="text" disabled style="display: none;">
                    <div class="js-upload uk-placeholder uk-text-center">
                        <span uk-icon="icon: cloud-upload"></span>
                        <span class="uk-text-middle">Прикрепите файлы, перетащив их сюда или</span>
                        <div uk-form-custom>
                            <input type="file" name="image" accept="image/*" onchange="previewImage(event)">
                            <span class="uk-link">выберите один</span>
                        </div>
                    </div>
                    <progress id="js-progressbar" class="uk-progress" value="0" max="100" hidden></progress>
                </div>
            </div>
        </div>

        <div class="uk-margin">
            <button class="uk-button uk-button-primary uk-width-1-1" type="submit">Обновить</button>
        </div>
    </form>
</div>

<script>
    var bar = document.getElementById('js-progressbar');

    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('uploaded-image');
            output.src = reader.result;
            output.style.display = 'block';

            var input = document.getElementById('image-path');
            input.value = event.target.value.split('\\').pop();
            input.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function deleteImage(taskId) {
        if (confirm('Вы уверены, что хотите удалить изображение?')) {
            window.location.href = '../control/delete_image.php?id=' + taskId;
        }
    }

    UIkit.upload('.js-upload', {
        url: '',
        multiple: false,

        beforeSend: function () {
            console.log('beforeSend', arguments);
        },
        beforeAll: function () {
            console.log('beforeAll', arguments);
        },
        load: function () {
            console.log('load', arguments);
        },
        error: function () {
            console.log('error', arguments);
        },
        complete: function () {
            console.log('complete', arguments);
        },

        loadStart: function (e) {
            console.log('loadStart', arguments);

            bar.removeAttribute('hidden');
            bar.max = e.total;
            bar.value = e.loaded;
        },

        progress: function (e) {
            console.log('progress', arguments);

            bar.max = e.total;
            bar.value = e.loaded;
        },

        loadEnd: function (e) {
            console.log('loadEnd', arguments);

            bar.max = e.total;
            bar.value = e.loaded;
        },

        completeAll: function () {
            console.log('completeAll', arguments);

            setTimeout(function () {
                bar.setAttribute('hidden', 'hidden');
            }, 1000);

            alert('Загрузка завершена');
        }
    });
</script>

<?php include '../footer.php'; ?>
