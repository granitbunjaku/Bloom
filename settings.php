<?php
include('includes/header.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$crud = new CRUD();

$user = $crud->read("users", ['id' => $_SESSION['user_id']]);

extract($user[0]);

$errors = [];

$success = [];

if ($_POST) {
    if (isset($_POST['update'])) {
        if (strlen($_POST['fullname'] > 0)) {
            if (strlen($_POST['fullname']) > 5) {
                if($_POST['fullname'] !== $fullname){
                    if ($crud->update("users", ['fullname' => $_POST['fullname']], ['column' => 'id', 'value' => $_SESSION['user_id']])) {
                        $_SESSION['fullname'] = $_POST['fullname'];
                        $success[] = "You updated your username successfully";
                    }
                } else {
                    $errors[] = "Your fullname shouldn't be the same as the recent!";
                }
            } else {
                $errors[] = "Fullname should be longer than 5 characters!";
            }
        }

        if (strlen($_POST['email'] > 0)) {
            if($_POST['email'] !== $email){
                if ($crud->update("users", ['email' => $_POST['email']], ['column' => 'id', 'value' => $_SESSION['user_id']])) {
                    $success[] = "You updated your username successfully";
                }
            } else {
                $errors[] = "Your email shouldn't be the same as the recent!";
            }
        }

        if ($_POST['bio'] !== $bio) {
            if ($crud->update("users", ['bio' => $_POST['bio']], ['column' => 'id', 'value' => $_SESSION['user_id']])) {
                $success[] = "You updated your bio successfully";
            }
        } else {
            $errors[] = "Your bio shouldn't be the same as the recent!";
        }

        if(count($errors) < 3) {
            header('Location: settings.php');
            $errors = [];
        }
    }

    $user = $crud->read("users", ['id' => $_SESSION['user_id']]);

    extract($user[0]);

    if (isset($_POST['changepassword'])) {

        if (strlen($_POST['opassword']) === 0) {
            $errors[] = "Old password field shouldn't be empty!";
        }

        if (strlen($_POST['npassword']) === 0 || strlen($_POST['npassword']) < 8) {
            $errors[] = "New password field should be 8+ characters long!";
        }

        if ($_POST['rpassword'] !== $_POST['npassword']) {
            $errors[] = "Repeat password and new password field should be the same!";
        }

        $res = password_verify($_POST['opassword'], $password);

        if (count($errors) === 0) {
            if ($res) {
                $npassword = password_hash($_POST['npassword'], PASSWORD_BCRYPT);
                if ($crud->update("users", ['password' => $npassword], ['column' => 'id', 'value' => $_SESSION['user_id']])) {
                    $success[] = "You changed your password successfully";
                }
            } else {
                $errors[] = "Your old password is incorrect!";
            }
        }
    }
}
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="settings--form">
    <div class="settings--infos">
        <?php if ($errors) : ?>
            <?php foreach ($errors as $error) : ?>
                <div class="settings--error--holder">
                    <p class="settings--error--message"><?= $error ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($success) : ?>
            <?php foreach ($success as $s) : ?>
                <div class="settings--success--holder">
                    <p class="settings--success--message"><?= $s ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php if (isset($_SESSION['is_loggedin'])) : ?>

    <div class="settings--input" style="margin-top: 30px;">
        <label for="inputFullname">Full Name</label>
        <input type="text" id="inputFullname" name="fullname" value="<?=$fullname?>">
    </div>
    <div class="settings--input" style="margin-top: 20px;">
        <label for="inputEmail" class="form-label">Email Address</label>
        <input type="email" id="inputEmail" class="form-control" name="email" value="<?=$email?>">
    </div>
    <div class="settings--input" style="margin-top: 20px;">
        <label for="inputBio" class="form-label">Bio</label>
        <input type="text" id="inputBio" name="bio" value="<?=$bio?>">
    </div>
    <div class="settings--input" style="margin-top: 20px;">
        <button type="submit" name="update">Update</button>
    </div>

    <div class="settings--input" style="margin-top: 30px;">
        <label for="inputPassword" class="form-label">Old Password</label>
        <input type="text" id="inputPassword" class="" name="opassword">
    </div>
    <div class="settings--input" style="margin-top: 20px;">
        <label for="inputPassword1" class="form-label">New Password</label>
        <input type="password" id="inputPassword1" class="" name="npassword">
    </div>
    <div class="settings--input" style="margin-top: 20px;">
        <label for="inputPassword2" class="form-label">Repeat Password</label>
        <input type="password" id="inputPassword2" class="" name="rpassword">
    </div>
    <div class="settings--input" style="margin-top: 20px;">
        <button type="submit" class="" name="changepassword">Change Password</button>
    </div>
    </form>

<?php else : header('Location: login.php') ?>

<?php endif; ?>