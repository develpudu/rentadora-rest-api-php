<?php


class Invoices
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

        public function getAllInvoices()
        {
                $sql = "SELECT bookings.*, cars.name AS car, customers.*, invoices.* FROM invoices INNER JOIN bookings ON invoices.bookingid = bookings.bookingid INNER JOIN cars ON bookings.carid = cars.id INNER JOIN customers ON bookings.customerid = customers.id ORDER BY invoices.id ASC";
                $stmt = $this->db->query($sql);
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $data;
        }

        public function getInvoices($bookingid)
        {
                $sql = "SELECT bookings.*, cars.name AS car, customers.*, invoices.* FROM invoices INNER JOIN bookings ON invoices.bookingid = bookings.bookingid INNER JOIN cars ON bookings.carid = cars.id INNER JOIN customers ON bookings.customerid = customers.id WHERE invoices.bookingid=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array($bookingid));
                $data = $stmt->fetch(PDO::FETCH_OBJ);
                return $data;
        }

        public function insertInvoices($bookingid, $cost, $totalkm, $carid, $customerid)
        {
                $sql = "INSERT INTO invoices (bookingid, cost, totalkm, carid, customerid, datecreated) VALUES (?,?,?,?,?,NOW())";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($bookingid, $cost, $totalkm, $carid, $customerid));
                return $status;
        }

        public function updateInvoices($id, $name, $costformula, $km)
        {
                $sql = "UPDATE invoices SET name=?, costformula=?, km=? WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($name, $costformula, $km, $id));
                return $status;
        }

        public function deleteInvoices($id)
        {
                $sql = "DELETE FROM invoices WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($id));
                return $status;
        }
}
