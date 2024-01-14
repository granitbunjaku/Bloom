<?php

session_start();
include('classes/Posts.php');

$crud = new CRUD();
$postDB = new Posts($crud);

echo $postDB->editPost();
