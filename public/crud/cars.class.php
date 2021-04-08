<?php


class Cars
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

        public function getAllCars()
        {
                $sql = "SELECT * FROM cars ORDER BY name ASC";
                $stmt = $this->db->query($sql); 
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $data;
	}

        public function getCars($id)
        {
                $sql = "SELECT * FROM cars WHERE id=?";
                $stmt = $this->db->prepare($sql); 
                $stmt->execute(array($id));
                $data = $stmt->fetch(PDO::FETCH_OBJ);
                return $data;
	}

        public function insertCars($name, $costformula, $km)
        {
                $sql = "INSERT INTO cars (name, costformula, km) VALUES (?,?,?)";
                $stmt = $this->db->prepare($sql); 
                $status = $stmt->execute(array($name, $costformula, $km));
                return $status;
	}

        public function updateCars($id, $name, $costformula, $km)
        {
                $sql = "UPDATE cars SET name=?, costformula=?, km=? WHERE id=?";
                $stmt = $this->db->prepare($sql); 
                $status = $stmt->execute(array($name, $costformula, $km, $id));
                return $status;
	}

        public function deleteCars($id)
        {
                $sql = "DELETE FROM cars WHERE id=?";
                $stmt = $this->db->prepare($sql); 
                $status = $stmt->execute(array($id));
                return $status;
	}
}