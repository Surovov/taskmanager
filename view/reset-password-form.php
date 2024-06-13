<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Смена пароля</title>
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
        <h2 class="uk-text-center">Смена пароля</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="uk-alert-danger" uk-alert>
                <p><?php echo htmlspecialchars($_GET['error']); ?></p>
            </div>
        <?php endif; ?>

        <!-- Поля для ввода нового пароля -->
        <form action="../control/reset-password-handler.php" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <div class="uk-margin">
                <input class="uk-input" name="password" type="password" placeholder="Новый пароль" required>
            </div>
            <div class="uk-margin">
                <input class="uk-input" name="confirm_password" type="password" placeholder="Подтвердите пароль" required>
            </div>

            <!-- Кнопка смены пароля -->
            <div class="uk-margin">
                <button class="uk-button uk-button-primary uk-width-1-1" type="submit">Сменить пароль</button>
            </div>
        </form>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit-icons.min.js"></script>
</body>
</html>
