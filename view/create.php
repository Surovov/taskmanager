<?php
// Определение базового пути для action формы
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
$actionPath = $baseDir . '/control/post.php';
?>

<div id="create-task-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Создать задачу</h2>

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

        <form action="<?php echo htmlspecialchars($actionPath); ?>" method="post" enctype="multipart/form-data">
            <div class="uk-margin">
                <input class="uk-input" name="title" type="text" placeholder="Заголовок" required>
            </div>
            <div class="uk-margin">
                <textarea class="uk-textarea" name="description" rows="5" placeholder="Описание"></textarea>
            </div>
            <div class="uk-margin">
                <input class="uk-input" name="deadline_date" type="date" placeholder="Дата дедлайна">
            </div>
            <div class="uk-margin">
                <div uk-grid class="uk-grid-small">
                    <div class="uk-width-auto">
                        <img id="uploaded-image" class="uk-width-small" style="display: none;">
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
                <button class="uk-button uk-button-primary uk-width-1-1" type="submit">Создать</button>
            </div>
        </form>
    </div>
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

    UIkit.upload('.js-upload', {
        url: '<?php echo htmlspecialchars($actionPath); ?>',
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
