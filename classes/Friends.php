<?php

class Friends
{
    private $connection = null;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->connection = $db->getConnection();
    }

    public function readFriends($id)
    {
        $sql = "SELECT u.id as id1, u.fullname as fullname1, u.pfp as pfp1, u2.id as id2, u2.fullname as fullname2, u2.pfp as pfp2 
        FROM `friends` as f
        JOIN users as u ON u.id = f.user_id 
        JOIN users as u2 ON u2.id = f.user_id2
        WHERE f.accepted = true and (f.user_id = $id OR f.user_id2 = $id)";

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

    public function acceptRequest($requester, $receiver)
    {
        $sql = "UPDATE friends SET accepted = true WHERE user_id = $requester AND user_id2 = $receiver";

        return $this->connection->query($sql) ? true : $this->connection->error;
    }

    public function rejectRequest($requester, $receiver)
    {
        $sql = "DELETE FROM friends WHERE user_id = $requester AND user_id2 = $receiver OR user_id2 = $requester AND user_id = $receiver";

        return $this->connection->query($sql) ? true : $this->connection->error;
    }

    public function isFriend($id, $id2)
    {
        $sql = "SELECT * FROM friends WHERE user_id = $id AND user_id2 = $id2 or user_id = $id2 AND user_id2 = $id";

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

    public function readRequests($id)
    {
        $sql = "SELECT *
        FROM friends as f
        JOIN users as u ON u.id = user_id
        WHERE f.user_id2 = $id AND f.accepted = false";

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