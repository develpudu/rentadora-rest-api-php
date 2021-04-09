<?php

class Users
{

        public function __construct()
        {
                $this->db = $this->getDB();
        }

        // Connect Database
        private function getDB()
        {
                $dbConnection = new PDO("mysql:host=" . $_ENV['DBHOST'] . ";dbname=" . $_ENV['DBNAME'], $_ENV['DBUSER'], $_ENV['DBPASS']);
                $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $dbConnection;
        }

        public function getAll()
        {
                $sql = "SELECT * FROM users ORDER BY name ASC";
                $stmt = $this->db->query($sql);
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $data;
        }

        public function checkLogin($email, $password)
        {
                $sql = "SELECT * FROM users WHERE email=? AND password=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array($email, $password));
                $data = $stmt->fetch(PDO::FETCH_OBJ);
                return $data;
        }

        public function getUsers($id)
        {
                $sql = "SELECT * FROM users WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array($id));
                $data = $stmt->fetch(PDO::FETCH_OBJ);
                return $data;
        }

        public function insertUsers($name, $email, $password)
        {
                $sql = "INSERT INTO users (name, email, password) VALUES (?,?,?)";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($name, $email, md5($password)));
                return $status;
        }

        public function updateUsers($id, $name, $email, $password)
        {
                $sql = "UPDATE users SET name=?, email=?, password=? WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($name, $email, md5($password), $id));
                return $status;
        }

        public function deleteUsers($id)
        {
                $sql = "DELETE FROM users WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($id));
                return $status;
        }
}
