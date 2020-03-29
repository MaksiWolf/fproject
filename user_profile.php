<?php
require_once 'init.php';

if (Input::exists('get')) {
    $userCurrent = DataBase::getInstance()->get('users', ['id', '=', Input::get('id')])->first();
}

$user = new User;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">User Management</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Главная</a>
            </li>
            <?php
            if ($user->hasPermissions('admin'))
                echo '<li class="nav-item"><a class="nav-link" href="/users/index.php">Управление пользователями</a></li>';
            ?>
        </ul>

        <ul class="navbar-nav">
            <?php
            if ($user->isLoggedIn()) {
                echo '<li class="nav-item"><a href="/profile.php" class="nav-link">Профиль</a></li>';
                echo '<li class="nav-item"><a href="/logout.php" class="nav-link">Выйти</a></li>';
            } else {
                echo '<li class="nav-item"><a href="/login.php" class="nav-link">Войти</a></li>';
                echo '<li class="nav-item"><a href="/register.php" class="nav-link">Регистрация</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Данные пользователя</h1>
            <table class="table">
                <thead>
                <th>ID</th>
                <th>Имя</th>
                <th>Дата регистрации</th>
                <th>Статус</th>
                </thead>

                <tbody>
                <?php if (is_object($userCurrent)): ?>
                    <tr>
                        <td><?php echo $userCurrent->id ?></td>
                        <td><?php echo $userCurrent->name ?></td>
                        <td><?php echo date('d/m/Y', strtotime($userCurrent->date)) ?></td>
                        <td><?php echo $userCurrent->status ?></td>
                    </tr>
                <?php endif ?>
                </tbody>
            </table>


        </div>
    </div>
</div>
</body>
</html>