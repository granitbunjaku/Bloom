<?php

class Reports
{
    private $connection;
    private $crud;

    public function __construct(CRUD $crud) {
        $this->connection = Database::getInstance()->getConnection();
        $this->crud = $crud;
    }

    public function sendReport() {
        $errors = [];

        if(strlen($_POST['title']) <= 5) {
            $errors[] = "You should write a longer title!";
        }

        if(strlen($_POST['content']) <= 5) {
            $errors[] = "You should write more content!";
        }

        if(count($errors) == 0) {
            $this->crud->create("reports", ["title" => $_POST['title'], "content" => $_POST['content'], "user_id" => $_SESSION['user_id']]);
        }

        return $errors;
    }

    public function getAllReports() {
        $sql = "SELECT r.title, r.content, u.email, u.pfp FROM reports as r JOIN users as u ON r.user_id = u.id";

        $results = [];

        if ($query = $this->connection->query($sql)) {
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    $results[] = $row;
                }
            }
        }

        return $results;
    }
}