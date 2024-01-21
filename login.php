<?php
    session_start();

    if(isset($_SESSION['is_loggedin']) && $_SESSION['is_loggedin']) {
        header('Location: index.php');
    }

    $_SESSION['title'] = 'Log In';

    include 'classes/Roles.php';
    include 'classes/User.php';

    $crud = new CRUD;
    $userDB = new User($crud);

    $errors = [];

    if ($_POST) {
        if (isset($_POST['loginButton'])) {
            $errors = $userDB->login();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloom | <?= $_SESSION['title'] ?></title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
    <div class="container">
        <form action="<?= $_SERVER['PHP_SELF']?>" method="post" class="login" id="loginForm">
            <a href="index.php"><img src="images/Bloom.png" alt="bloom logo"></a>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <div class="form-footer">
                <button type="submit" name="loginButton" class="loginButton">LOGIN</button>

                <div class="last-part">
                    <small>Don't have an account yet? <a href="register.php">Register</a></small>
                </div>

            </div>

            <div class="error-holder">
                <?php foreach($errors as $error) : ?>
                    <p class="error-message" id="errorMessage"><?=$error?></p>
                <?php endforeach; ?>
            </div>

            <div class="error-holder-js"></div>
        </form>
    </div>

    <script src="assets/js/login.js"></script>

</body>

</html>