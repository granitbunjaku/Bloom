<?php
    session_start();

    $_SESSION['title'] = 'Register';

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include 'classes/User.php';

    $crud = new CRUD;
    $userDB = new User($crud);

    $errors = [];

    $roles = $crud->read('roles');

    if ($_POST) {
        if (isset($_POST['signup-button'])) {
            $errors = $userDB->regiser();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="register">
        <a href="index.php"><img src="images/Bloom.png" alt="bloom logo"></a>

        <label for="fullname">Name</label>
        <input type="text" name="fullname" id="fullname">
        <label for="email">Email</label>
        <input type="text" name="email" id="email">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password">
        <label for="gender">Gender</label>
        <select name="gender">
            <option value="">Select your gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <label for="role">Role</label>
        <select id="role" name="role">
            <option value="">Select your role</option>
            <?php foreach($roles as $column => $value) : ?>
                <option name="<?= $value['name'] ?>"><?= $value['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="signup-button">REGISTER</button>

        <div class="last-part">
            <small>Already have an account? <a href="login.php">Log In</a></small>
        </div>

        <div class="error-holder">
            <?php foreach($errors as $error) : ?>
                <p class="error-message" id="errorMessage"><?=$error?></p>
            <?php endforeach; ?>
        </div>
    </form>

</body>

</html>