<?php

include 'Database.php';

class CRUD {
    private $connection = null;

    public function __construct() {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function create($table, $data) {
        $sql = "INSERT INTO `" . $table . "` SET ";

        if(count($data)) {
            $count = 1;

            foreach ($data as $column => $value) {
                if (count($data) > $count) {
                    $sql .= "`" . $column . "`='" . $value . "',";
                } else {
                    $sql .= "`" . $column . "`='" . $value . "'";
                }

                $count++;
            }
        }

        return $this->connection->query($sql) ? true : $this->connection->error;
    }

    public function read($table, $conditions = [], $limit = null) {
        $sql = "SELECT * FROM `" . $table . "`";
        $results = [];

        $sql .= $this->conditionReader($conditions);

        if(!is_null($limit)) {
            $sql .= " LIMIT " . $limit;
        }

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

    public function update($table, $data, $conditions = []) {
        $sql = "UPDATE `" . $table . "` SET ";

        if(count($data)) {
            $count = 1;

            foreach ($data as $column => $value) {
                if (count($data) > $count) {
                    $sql .= "`" . $column . "`='" . $value . "',";
                } else {
                    $sql .= "`" . $column . "`='" . $value . "'";
                }

                $count++;
            }
        }

        if(count($conditions)) {
            $sql .= " WHERE `" . $conditions['column'] . "`='" . $conditions['value'] . "'";
        }

        return $this->connection->query($sql) ? true : $this->connection->error;
    }

    public function delete($table, $conditions, $limit = null) {
        $sql = "DELETE FROM `" . $table . "`";

        $sql .= $this->conditionReader($conditions);

        if (!is_null($limit)) {
            $sql .= " LIMIT " . $limit;
        }

        return $this->connection->query($sql) ? true : $this->connection->error;
    }

    public function search($table, $column, $value)
    {
        $sql = "SELECT * FROM `" . $table . "` WHERE `" . $column . "` LIKE '%" . $value . "%'";

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

    private function conditionReader($conditions) {
        $sqlToReturn = "";

        if (count($conditions)) {

            $is_first_time = true;

            foreach ($conditions as $column => $value) {
                if ($is_first_time) {
                    $sqlToReturn .= " WHERE `" . $column . "`='" . $value . "'";
                    $is_first_time = false;
                } else {
                    $sqlToReturn .= " AND `" . $column . "`='" . $value . "'";
                }
            }
        }

        return $sqlToReturn;
    }

}