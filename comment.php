<?php

session_start();
include 'classes/CRUD.php';
include 'classes/Comments.php';

$crud = new CRUD();
$commentDB = new Comments;

$requestBody = file_get_contents("php://input");
$data = json_decode($requestBody, true);

$post_id = $data["postId"];
$content = $data["content"];

if(strlen(trim($data['content'])) > 0) {
    $crud->create("comments", ['content' => $content, 'post_id' => $post_id, 'user_id' => $_SESSION['user_id']]);
} else {
    http_response_code(404);
}

echo $commentDB->readLastComment($post_id)[0]['id'];