<?php

include 'classes/CRUD.php';
include 'classes/Comments.php';

$crud = new CRUD;
$commentDB = new Comments($crud);

session_start();

echo $commentDB->editComment();