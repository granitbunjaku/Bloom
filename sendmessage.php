<?php
    include 'classes/CRUD.php';

    $crud = new CRUD;

    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);

    $chatId = $data['chatId'];
    $message = $data['message'];
    $senderId = $data['senderId'];

    $crud->create("messages", ['chat_id' => $chatId, 'message' => $message, 'sender_id' => $senderId]); 