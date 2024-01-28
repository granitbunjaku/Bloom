<?php
session_start();
$_SESSION['title'] = 'Account Settings';

include('includes/header.php');
include('classes/User.php');

$crud = new CRUD();
$userDB = new User($crud);

$user = $crud->read("users", ['id' => $_SESSION['user_id']]);
extract($user[0]);

$errors = [];

if ($_POST) {
    if (isset($_POST['update'])) {
        $errors = $userDB->editProfile($user);
    }


    if (isset($_POST['changepassword'])) {
        $errors = $userDB->changePassword($user);
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
        <input type="password" id="inputPassword" class="" name="opassword">
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