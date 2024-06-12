<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля</title>
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
        <h2 class="uk-text-center">Восстановление пароля</h2>

        <!-- Поля для ввода нового пароля -->
        <form action="control/reset-password.php" method="post">
            <div class="uk-margin">
                <input class="uk-input" name="password" type="password" placeholder="Новый пароль" required>
            </div>
            <div class="uk-margin">
                <input class="uk-input" type="password" placeholder="Подтвердите новый пароль" required>
            </div>

            <!-- Кнопка обновить пароль -->
            <div class="uk-margin">
                <button class="uk-button uk-button-primary uk-width-1-1" type="submit" >Обновить пароль</button>
            </div>

            <!-- Аллерты для ошибок -->
            <div id="password-match-error" class="uk-alert-danger" uk-alert style="display: none;">
                <p>Пароли не совпадают.</p>
            </div>
            <div id="password-reset-success" class="uk-alert-success" uk-alert style="display: none;">
                <p>Пароль успешно обновлен.</p>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/js/uikit-icons.min.js"></script>
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const password = document.querySelector('input[type="password"]:nth-child(1)').value;
        const confirmPassword = document.querySelector('input[type="password"]:nth-child(2)').value;
        if (password !== confirmPassword) {
            document.getElementById('password-match-error').style.display = 'block';
        } else {
            document.getElementById('password-match-error').style.display = 'none';
            document.getElementById('password-reset-success').style.display = 'block';
        }
    });
</script>
</body>
</html>
