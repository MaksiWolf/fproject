<?php
require_once 'init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();

        $validate->check($_POST, [
            'email' => [
                'required' => true,
                'email' => true
            ],
            'password' => [
                'required' => true
            ]
        ]);

        if ($validate->passed()) {
            $user = new User;
            $remember = (Input::get('remember') === 'on') ? true : false;

            $login = $user->login(Input::get('email'), Input::get('password'), $remember);

            if ($login) {
                Session::flash('login_success', 'login success');
                Redirect::to('index.php');
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="text-center">
<form class="form-signin" action="" method="post">
    <img class="mb-4" src="images/apple-touch-icon.png" alt="" width="72" height="72">
    <h1 class="h3 mb-3 font-weight-normal">Авторизация</h1>

    <?php
    $flash = Session::flash('register_success');
    if ($flash) {
        echo '<div class="alert alert-success">';
        echo $flash;
        echo '</div>';
    }
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

    <?php
    if (isset($login) && !$login)
        echo '<div class="alert alert-info">login failed</div>';
    ?>

    <input type="hidden" name="token" value="<?php echo Token::generate() ?>">

    <div class="form-group">
        <input type="email" name="email" class="form-control" id="email" placeholder="Email"
               value="<?php echo Input::get('email') ?>">
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" id="password" placeholder="Пароль">
    </div>

    <div class="checkbox mb-3">
        <label>
            <input name="remember" type="checkbox"> Запомнить меня
        </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2017-2020</p>
</form>
</body>
</html>
