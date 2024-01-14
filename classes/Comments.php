<?php

class Comments
{
    private $connection = null;
    private $crud;

    public function __construct(CRUD $crud = null)
    {
        $this->connection = Database::getInstance()->getConnection();
        $this->crud = $crud;
    }

    public function editComment() {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        $content = $data['content'];
        $commentId = $data['comment_id'];
        $uid = $data['user_id'];

        if($uid === $_SESSION['user_id']) {
            if(!strlen(trim($content)) <= 0) {
                if($this->crud->update("comments", ['content' => $content], ['column' => 'id', 'value' => $commentId])) {
                    header('X-Status-Code: 200');
                } else {
                    header('X-Status-Code: 400');
                }
            } else {
                header('X-Status-Code: 400');
            }
        }
    }

    public function makeComment() {
        $requestBody = file_get_contents("php://input");
        $data = json_decode($requestBody, true);

        $post_id = $data["postId"];
        $content = $data["content"];

        if(strlen(trim($data['content'])) > 0) {
            $this->crud->create("comments", ['content' => $content, 'post_id' => $post_id, 'user_id' => $_SESSION['user_id']]);
        } else {
            http_response_code(404);
        }

        return $this->readLastComment($post_id)[0]['id'];
    }

    public function readComments()
    {
        $sql = "SELECT c.id as comment_id, c.post_id, c.content, u.id, u.fullname, u.pfp FROM comments as c
        JOIN users as u ON u.id = c.user_id";

        $results = [];

        if ($query = $this->connection->query($sql)) {
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    $results[] = $row;
                }
            }

            return $results;
        } else {
            return $this->connection->error;
        }
    }

    public function readLastComment($post_id)
    {
        $sql = "SELECT c.id FROM comments as c WHERE c.post_id = $post_id ORDER BY c.id DESC LIMIT 1";

        $results = [];

        if ($query = $this->connection->query($sql)) {
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    $results[] = $row;
                }
            }

            return $results;
        } else {
            return $this->connection->error;
        }
    }

}