<?php

include 'classes/Posts.php';

$crud = new CRUD();
$postDB = new Posts($crud);

if ($_POST) {
    if (isset($_POST["post-button"])) {
        $postDB->createPost();
    }
}