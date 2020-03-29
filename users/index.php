<?php
require_once '../init.php';

$user = new User;

if (!$user->isLoggedIn())
    Redirect::to('login.php');

if (!$user->hasPermissions('admin'))
    Redirect::to('profile.php');

if (Input::exists('get')) {
    if (Input::get('down'))
        $user->update(['group_id' => 1], Input::get('down'));

    if (Input::get('up'))
        $user->update(['group_id' => 2], Input::get('up'));

    if (Input::get('delete'))
        $user->delete(Input::get('delete'));
}

$users = DataBase::getInstance()->getAll('users')->results();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Users</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom styles for this template -->
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
    <div class="col-md-12">
        <h1>Пользователи</h1>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Действия</th>
            </tr>
            </thead>

            <tbody>


            <?php if (is_array($users) && !empty($users)): ?>
                <?php foreach ($users as $objUser): ?>

                    <?php $currentUser = new User($objUser->id); ?>

                    <tr>
                        <td><?php echo $objUser->id ?></td>
                        <td><?php echo $objUser->name ?></td>
                        <td><?php echo $objUser->email ?></td>
                        <td>
                            <?php if ($currentUser->hasPermissions('admin')): ?>
                                <a href=".?down=<?php echo $objUser->id ?>" class="btn btn-danger">Разжаловать</a>
                            <?php else: ?>
                                <a href=".?up=<?php echo $objUser->id ?>" class="btn btn-success">Назначить
                                    администратором</a>
                            <?php endif ?>
                            <a href="edit.php?id=<?php echo $objUser->id ?>" class="btn btn-warning">Редактировать</a>
                            <a href=".?delete=<?php echo $objUser->id ?>" class="btn btn-danger"
                               onclick="return confirm('Вы уверены?');">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif ?>

            </tbody>
        </table>
    </div>
</div>
</body>
</html>
