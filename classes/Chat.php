<?php
    require_once 'vendor/autoload.php';
    use Ramsey\Uuid\Uuid;

    class Chat {
        private $connection = null;
        private $crud;

        public function __construct(CRUD $crud = null) {
            $this->connection = Database::getInstance()->getConnection();
            $this->crud = $crud;
        }

        public function getChat($id1, $id2) {
            $sql = 'SELECT * FROM chat WHERE user1 = '.$id1.' AND user2 = '.$id2.' OR user1 = '.$id2.' AND user2 = '.$id1;

            $results = null;

            if ($query = $this->connection->query($sql)) {
                if ($query->num_rows > 0) {
                    while (($row = $query->fetch_assoc()) && $results == null) {
                        $results = $row;
                    }
                    return $results['id'];
                } else {
                    $id = Uuid::uuid4()->toString();
                    $this->crud->create("chat", ['id' => $id, 'user1' => $id1, 'user2' => $id2]);
                    return $id;
                }
            } else {
                return $this->connection->error;
            }
        }
    }

?>