<?php

include 'classes/CRUD.php';
$crud = new CRUD;

session_start();

$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

$content = $data['content'];
$commentId = $data['comment_id'];
$uid = $data['user_id'];

if($uid === $_SESSION['user_id']) {
    if($crud->update("comments", ['content' => $content], ['column' => 'id', 'value' => $commentId])) {
        echo $content;
    } else {
        http_response_code(404);
    }
}