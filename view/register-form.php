<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/css/uikit.min.css" />
</head>
<body>

<div class="uk-flex uk-flex-middle uk-height-viewport">
    <div class="uk-width-large uk-padding-small uk-margin-auto">

        <!-- Логотип -->
        <div class="uk-text-center uk-margin-bottom">
            <img src="https://getuikit.com/docs/images/logo.svg" alt="Логотип" width="100">
        </div>

        <!-- Заголовок -->
        <h2 class="uk-text-center">Регистрация</h2>

        <!-- Проверка на ошибки и их отображение -->
        <?php if (isset($_GET['error'])): ?>
            <div id="server-error" class="uk-alert-danger" uk-alert>
                <p><?php echo htmlspecialchars($_GET['error']); ?></p>
            </div>
        <?php endif; ?>

        <!-- Аллерты для ошибок -->
        <div id="password-match-error" class="uk-alert-danger" uk-alert style="display: none;">
            <p>Пароли не совпадают.</p>
        </div>
        <div id="registration-success" class="uk-alert-success" uk-alert style="display: none;">
            <p>Регистрация успешно завершена.</p>
        </div>
            
        <!-- Поля для ввода данных регистрации -->
        <form id="register-form" action="../control/register.php" method="post">
            <div class="uk-margin">
                <input class="uk-input" name="name" type="text" placeholder="Имя пользователя" required>
            </div>
            <div class="uk-margin">
                <input class="uk-input" name="email" type="email" placeholder="Почта" required>
            </div>
            <div class="uk-margin">
                <input class="uk-input" name="password" type="password" placeholder="Пароль" required>
            </div>
            <div class="uk-margin">
                <input class="uk-input" name="confirm_password" type="password" placeholder="Подтвердите пароль" required>
            </div>

            <!-- Кнопка регистрации -->
            <div class="uk-margin">
                <button class="uk-button uk-button-primary uk-width-1-1" type="submit">Зарегистрироваться</button>
            </div>
        </form>

        <!-- Ссылка на вход -->
        <div class="uk-margin uk-text-center">
            <a href="../view/login-form.php" class="uk-link-reset">Уже зарегистрированы? Войти</a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit-icons.min.js"></script>
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.querySelector('input[name="password"]').value;
        const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
        if (password !== confirmPassword) {
            e.preventDefault();
            document.getElementById('password-match-error').style.display = 'block';
        } else {
            document.getElementById('password-match-error').style.display = 'none';
        }
    });
</script>
</body>
</html>
