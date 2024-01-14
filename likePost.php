<?php

session_start();

include('classes/Posts.php');

$crud = new CRUD();
$postDB = new Posts($crud);

$postDB->likePost();