<?php
    session_start();

    if(isset($_SESSION['is_loggedin']) && $_SESSION['is_loggedin']) {
        header('Location: index.php');
    }

    $_SESSION['title'] = 'Register';

    include 'classes/User.php';

    $crud = new CRUD;
    $userDB = new User($crud);

    $errors = [];

    if ($_POST) {
        if (isset($_POST['signup-button'])) {
            $errors = $userDB->register();
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
        <input type="text" name="fullname" id="fullname" class="fullname">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" class="email">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="password">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirmPassword" id="confirmPassword" class="confirmPassword">
        <label for="gender">Gender</label>
        <select name="gender" class="gender">
            <option value="">Select your gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
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

        <div class="error-holder-js"></div>
    </form>

    <script src="assets/js/register.js"></script>

</body>

</html>