<?php

session_start();

include 'classes/CRUD.php';
include 'classes/Comments.php';

$crud = new CRUD();
$commentDB = new Comments($crud);

echo $commentDB->makeComment();