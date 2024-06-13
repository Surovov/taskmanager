<?php
// Определяем базовый URL
$baseUrl = dirname($_SERVER['SCRIPT_NAME'], 2); // Поднимаемся на два уровня вверх от текущего файла
$baseUrl = rtrim($baseUrl, '/') . '/';
?>

<div class="uk-width-1-4 uk-background-muted uk-padding-small uk-height-1-1">
    <ul class="uk-nav uk-nav-default uk-nav-parent-icon" uk-nav>
        <li class="uk-active"><a href="<?php echo $baseUrl; ?>"><span uk-icon="icon: home"></span> Главная</a></li>
        <li><a href="<?php echo $baseUrl; ?>view/complete-list.php"><span uk-icon="icon: check"></span> Выполненные задачи</a></li>
        <li><a href="<?php echo $baseUrl; ?>view/archive-list.php"><span uk-icon="icon: folder"></span> Архивированные задачи</a></li>
    </ul>
</div>

<style>
    .uk-height-1-1 {
        height: 100vh;
    }
</style>
