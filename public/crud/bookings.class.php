<?php


class Bookings
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

        public function getAllBookings()
        {
                $sql = "SELECT bookings.*, cars.name AS car, customers.* FROM bookings INNER JOIN cars ON bookings.carid = cars.id INNER JOIN customers ON bookings.customerid = customers.id ORDER BY bookingid ASC";
                $stmt = $this->db->query($sql);
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $data;
        }

        public function getBookings($bookingid)
        {
                $sql = "SELECT bookings.*, cars.name AS car, customers.* FROM bookings INNER JOIN cars ON bookings.carid = cars.id INNER JOIN customers ON bookings.customerid = customers.id WHERE bookings.bookingid=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array($bookingid));
                $data = $stmt->fetch(PDO::FETCH_OBJ);
                return $data;
        }

        public function insertBookings($name, $costformula, $km)
        {
                $sql = "INSERT INTO bookings (name, costformula, km) VALUES (?,?,?)";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($name, $costformula, $km));
                return $status;
        }

        public function updateBookings($id, $name, $costformula, $km)
        {
                $sql = "UPDATE bookings SET name=?, costformula=?, km=? WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($name, $costformula, $km, $id));
                return $status;
        }

        public function deleteBookings($id)
        {
                $sql = "DELETE FROM bookings WHERE id=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($id));
                return $status;
        }
}
