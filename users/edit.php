<?php
require_once '../init.php';

$user = new User;

if (!$user->isLoggedIn())
    Redirect::to('login.php');

if (!$user->hasPermissions('admin'))
    Redirect::to('profile.php');

if (Input::exists('get') && Input::get('id'))
    $currentUser = new User(Input::get('id'));

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();

        $validate->check($_POST, [
            'name' => [
                'required' => true,
                'min' => 2
            ],
            'status' => [
                'required' => true,
                'min' => 5
            ]
        ]);

        if ($validate->passed()) {
            $user->update(['name' => Input::get('name'), 'status' => Input::get('status')], Input::get('id'));
            Session::flash('profile_edit', 'Профиль обновлен');
            Redirect::to('edit.php?id=' . Input::get('id'));
        }
    }
}

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
        <div class="col-md-8">
            <h1>Профиль пользователя - <?php if (isset($currentUser)) echo $currentUser->data()->name ?></h1>

            <?php
            if (!Input::exists() && $flash = Session::flash('profile_edit'))
                echo '<div class="alert alert-success">' . $flash . '</div>';
            ?>

            <?php
            if (isset($validate) && !empty($validate->errors())) {
                echo '<div class="alert alert-danger"><ul>';
                foreach ($validate->errors() as $error) {
                    echo "<li>$error</li>";
                }
                echo '</ul></div>';
            }
            ?>

            <!--
          <div class="alert alert-success">Профиль обновлен</div>

          <div class="alert alert-danger">
            <ul>
              <li>Ошибка валидации</li>
            </ul>
          </div>
          -->
            <form action="" method="post" class="form">

                <input type="hidden" name="token" value="<?php echo Token::generate() ?>">

                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="<?php if (isset($currentUser)) echo $currentUser->data()->name ?>">
                </div>
                <div class="form-group">
                    <label for="status">Статус</label>
                    <input type="text" name="status" id="status" class="form-control"
                           value="<?php if (isset($currentUser)) echo $currentUser->data()->status ?>">
                </div>

                <div class="form-group">
                    <button class="btn btn-warning">Обновить</button>
                </div>
            </form>


        </div>
    </div>
</div>
</body>
</html>