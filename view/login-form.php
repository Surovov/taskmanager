<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
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
        <h2 class="uk-text-center">Вход</h2>

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

        <!-- Поля для ввода данных входа -->
        <form action="../control/login.php" method="post">
            <div class="uk-margin">
                <input class="uk-input" name="email" type="email" placeholder="Почта" required>
            </div>
            <div class="uk-margin">
                <input class="uk-input" name="password" type="password" placeholder="Пароль" required>
            </div>

            <!-- Кнопка входа -->
            <div class="uk-margin">
                <button class="uk-button uk-button-primary uk-width-1-1" type="submit">Войти</button>
            </div>
        </form>

        <!-- Ссылка на восстановление пароля -->
        <div class="uk-margin uk-text-center">
            <a href="../view/forgot-form.php" class="uk-link-reset">Забыли пароль?</a>
        </div>

        <!-- Ссылка на регистрацию -->
        <div class="uk-margin uk-text-center">
            <a href="../view/register-form.php" class="uk-link-reset">Еще не зарегистрированы? Регистрация</a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit-icons.min.js"></script>
</body>
</html>
