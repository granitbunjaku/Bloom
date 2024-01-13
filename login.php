<?php
    session_start();

    $_SESSION['title'] = 'Log In';

    include('classes/CRUD.php');
    include('classes/Roles.php');
    $crud = new CRUD;
    $errors = [];

    if ($_POST) {
        if (isset($_POST['loginButton'])) {

            if (strlen($_POST['email']) < 11) {
                $errors[] = "Too short! Please enter a valid email!";
            }

            if (strlen($_POST['password']) < 8) {
                $errors[] = "Too short! Please enter a valid password!";
            }

            $user = $crud->read("users", ['email' => $_POST['email']], 1);

            if($user) extract($user[0]);

            if(!count($errors)) {
                if (is_array($user) && count($user) > 0) {
                    if (password_verify($_POST['password'], $password)) {
                        $_SESSION['fullname'] = $fullname;
                        $_SESSION['is_loggedin'] = true;
                        $_SESSION['user_id'] = $id;
                        $_SESSION['pfp'] = $pfp;
                        $_SESSION['is_admin'] = ($roleId == Roles::ADMIN);
                        header('Location: index.php');
                    } else {
                        $errors[] = "Wrong password!";
                    }
                } else {
                    $errors[] = "Invalid user!";
                }
            }
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
                <button type="submit" name="loginButton">LOGIN</button>

                <div class="last-part">
                    <small>Don't have an account yet? <a href="register.php">Register</a></small>
                </div>

            </div>

            <div class="error-holder">
                <?php foreach($errors as $error) : ?>
                    <p class="error-message" id="errorMessage"><?=$error?></p>
                <?php endforeach; ?>
            </div>
        </form>
    </div>

</body>

</html>