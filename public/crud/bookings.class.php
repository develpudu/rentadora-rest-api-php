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

        private function genBookingId()
        {
                $chars = array(
                        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
                        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q',
                        'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
                );

                $long = 6;

                $bookingid = '';
                for ($i = 0; $i < $long; $i++) {
                        $bookingid .= $chars[rand(0, count($chars))];
                }
                return $bookingid;
        }

        public function getAll()
        {
                $sql = "SELECT bookings.*, cars.name AS car, customers.* FROM bookings INNER JOIN cars ON bookings.carid = cars.id INNER JOIN customers ON bookings.customerid = customers.id ORDER BY bookings.startdate ASC";
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

        public function insertBookings($customerid, $carid, $startdate, $startkm)
        {
                $bookingid = $this->genBookingId();
                $sql = "INSERT INTO bookings (bookingid, customerid, carid, startdate, startkm) VALUES (?,?,?,?,?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array($bookingid, $customerid, $carid, $startdate, $startkm));
                return $bookingid;
        }

        public function updateBookings($enddate, $endkm, $bookingid)
        {
                $sql = "UPDATE bookings SET enddate=?, endkm=?, isopen=0 WHERE bookingid=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($enddate, $endkm, $bookingid));
                return $status;
        }

        public function deleteBookings($bookingid)
        {
                $sql = "DELETE FROM bookings WHERE bookingid=?";
                $stmt = $this->db->prepare($sql);
                $status = $stmt->execute(array($bookingid));
                return $status;
        }
}
