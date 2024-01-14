<?php

include "Utils.php";
include 'classes/CRUD.php';

class Posts
{
    private $connection;
    private $crud;

    public function __construct(CRUD $crud) {
        $this->connection = Database::getInstance()->getConnection();
        $this->crud = $crud;
    }

    public function editPost() {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);

        $post_id = $data['post_id'];
        $new_content = $data['new_content'];
        $uid = $data['uid'];

        if($uid === $_SESSION['user_id']) {
            if(strlen($new_content) > 0) {
                $this->crud->update("posts", ['content' => $new_content], ['column' => 'id', 'value' => $post_id]);
                header('X-Status-Code: 200');
            } else {
                header('X-Status-Code: 400');
            }
        }

        return $new_content;
    }

    public function likePost() {
        $postRelations = $this->crud->read("likes", ['user_id' => $_SESSION['user_id'], 'post_id' => $_GET['id']]);

        if(!$postRelations) {
            $this->increaseLike($_SESSION['user_id'], $_GET['id']);
        } else {
            $this->decreaseLike($_SESSION['user_id'], $_GET['id']);
        }
    }

    public function createPost()
    {
        $errors = [];

        if (strlen($_POST['content']) === 0 && strlen($_FILES['post-file']['name']) === 0) {
            $errors[] = "You should write something!";
        }

        if (count($errors) === 0) {
            if ($_FILES) {
                if (str_starts_with($_FILES['post-file']['type'], 'image')) {
                    $imagefile = time() . $_FILES['post-file']['name'];
                    $videofile = null;
                } elseif (str_starts_with($_FILES['post-file']['type'], 'video')) {
                    $videofile = time() . $_FILES['post-file']['name'];
                    $imagefile = null;
                } else {
                    $imagefile = null;
                    $videofile = null;
                }
            }

            if ($this->crud->create("posts", [
                'content' => $_POST['content'],
                'user_id' => $_SESSION['user_id'],
                'image' => $imagefile,
                'video' => $videofile
            ])) {
                if (!is_null($imagefile)) {
                    move_uploaded_file($_FILES['post-file']['tmp_name'], 'post-photos/' . $imagefile);
                } elseif (!is_null($videofile)) {
                    move_uploaded_file($_FILES['post-file']['tmp_name'], 'post-videos/' . $videofile);
                }

                if(isset($_GET['id'])) {
                    header('Location: profile.php?id='.$_GET['id']);
                } else {
                    header('Location: index.php');
                }
            }
        }
    }

    public function readPosts($column = null, $value = null, $limit = null)
    {
        $sql = "SELECT p.*,u.id as uid,u.fullname,u.pfp, l.user_id
            FROM posts as p JOIN users as u ON p.user_id = u.id LEFT JOIN likes as l ON l.post_id = p.id";

        if (!is_null($column) && !is_null($value)) {
            $sql .= " WHERE $column = $value";
        }

        $sql .= " ORDER BY id DESC";

        $posts = [];

        if ($query = $this->connection->query($sql)) {
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    if (exists($posts, function ($item) use ($row) {
                        return $item['id'] == $row['id'];
                    })) {
                        for ($i = 0; $i < count($posts); $i++) {
                            if ($posts[$i]['id'] === $row['id']) {
                                $posts[$i]['likes'][] = $row['user_id'];
                            }
                        }
                    }
                    else {
                        if (is_null($row['user_id'])) {
                            $row['likes'] = [];
                        } else {
                            $row['likes'][] = $row['user_id'];
                        }
                        $posts[] = $row;
                    }
                }
            }
        }
        return $posts;
    }

    public function increaseLike($user_id, $post_id)
    {
        $sql = "INSERT INTO likes(user_id, post_id) VALUES ($user_id, $post_id)";

        return $this->connection->query($sql) ? true : $this->connection->error;
    }

    public function decreaseLike($user_id, $post_id)
    {
        $sql = "DELETE FROM likes WHERE user_id = $user_id AND post_id = $post_id";

        return $this->connection->query($sql) ? true : $this->connection->error;
    }
}