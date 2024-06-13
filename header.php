<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.7.6/css/uikit.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css">
</head>
<body>
    <header class="uk-navbar-container" uk-sticky="media: 960; target-offset: 0; ">
    	<div class="uk-container ">
        	
            <nav class="uk-navbar" uk-navbar>
                <div class="uk-navbar-left">
                    <a href="/" class="uk-navbar-item uk-logo">
                        <img src="https://getuikit.com/docs/images/logo.svg" alt="Логотип" width="50">
                    </a>
                </div>
                <div class="uk-navbar-right">
                    <ul class="uk-navbar-nav">
                        <li><a href="#" uk-toggle="target: #create-task-modal">Добавить задачу</a></li>
                        <li><a href="control/logout.php">Выйти</a></li>
                    </ul>
                </div>
        	</nav>
        </div>
    </header>

    <div class="uk-container uk-margin-top">
        <div class="uk-grid-small" uk-grid>
        	<?php include('sidebar.php'); ?>