<?php
class DatabaseService {
    private $db_host = "db"; // Nombre del servicio en docker-compose
    private $db_name = "tienda";
    private $db_user = "usuario";
    private $db_password = "usuario";
    private $connection = null;

    public function getConnection(){
        if ($this->connection == null) {
            $this->connection = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_password);
        }
        return $this->connection;
    }
}
?>
