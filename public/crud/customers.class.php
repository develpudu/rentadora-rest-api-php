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

        public function getAll()
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
                $sql_booking = "SELECT bookingid,isopen FROM bookings WHERE customerid=?";
                $stmt_booking = $this->db->prepare($sql_booking);
                $stmt_booking->execute(array($id));
                $data_booking = $stmt_booking->fetchAll(PDO::FETCH_OBJ);
                $data->bookings = $data_booking;
                return $data;
        }

        public function insertCustomers($firstname, $lastname, $license)
        {
                $sql = "INSERT INTO customers (firstname, lastname, license) VALUES (?,?,?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array($firstname, $lastname, $license));
                $customerid = $this->db->lastInsertId();
                return $customerid;
        }

        public function updateCustomers($id, $firstname, $lastname, $license)
        {
                $sql = "UPDATE customers SET firstname=?, lastname=?, license=? WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($firstname, $lastname, $license, $id));
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
