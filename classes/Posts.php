<?php

include "Utils.php";

class Posts
{
    private $connection;

    public function __construct() {
        $this->connection = Database::getInstance()->getConnection();
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