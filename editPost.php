<?php

session_start();

if(!isset($_SESSION['is_loggedin'])) {
    header('Location: login.php');
}

include('classes/Posts.php');

$crud = new CRUD();
$postDB = new Posts($crud);

echo $postDB->editPost();
