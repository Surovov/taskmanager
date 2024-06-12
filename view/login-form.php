<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
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
        <h2 class="uk-text-center">Авторизация</h2>

        <!-- Поле login -->
        <form action="../control/login.php" method="post">
            <div class="uk-margin">
                <input class="uk-input" name="email" type="email" placeholder="E-mail" required>
            </div>

            <!-- Поле password -->
            <div class="uk-margin">
                <input class="uk-input" name="password" type="password" placeholder="Пароль" required>
            </div>

            <!-- Кнопка войти -->
            <div class="uk-margin">
                <button class="uk-button uk-button-primary uk-width-1-1"  type="submit" >Войти</button>
            </div>

            <!-- Ссылка забыли пароль -->
            <div class="uk-margin uk-text-center">
                <a href="#" class="uk-link-reset">Забыли пароль?</a>
            </div>
            <?php if (isset($_GET['error'])): ?>
                <div id="password-match-error" class="uk-alert-danger" uk-alert style="display: none;">
                    <p><?php echo htmlspecialchars($_GET['error']); ?></p>
                </div>
            <?php endif; ?>
            <!-- Аллерты для ошибок -->
            <div id="login-error" class="uk-alert-danger" uk-alert style="display: none;">
                <p>Логин не существует.</p>
            </div>
            <div id="password-error" class="uk-alert-danger" uk-alert style="display: none;">
                <p>Неправильный пароль.</p>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit-icons.min.js"></script>
</body>
</html>
