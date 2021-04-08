<?php


class Customers
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

        public function getAllCustomers()
        {
                $sql = "SELECT * FROM customers ORDER BY lastname ASC";
                $stmt = $this->db->query($sql);
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $data;
        }

        public function getCustomers($id)
        {
                $sql = "SELECT * FROM customers WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array($id));
                $data = $stmt->fetch(PDO::FETCH_OBJ);
                return $data;
        }

        public function insertCustomers($firstname, $lastname, $dni)
        {
                $sql = "INSERT INTO customers (fistname, lastname, dni) VALUES (?,?,?)";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($firstname, $lastname, $dni));
                return $status;
        }

        public function updateCustomers($id, $firstname, $lastname, $dni)
        {
                $sql = "UPDATE customers SET firstname=?, lastname=?, dni=? WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($firstname, $lastname, $dni, $id));
                return $status;
        }

        public function deleteCustomers($id)
        {
                $sql = "DELETE FROM customers WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($id));
                return $status;
        }
}
