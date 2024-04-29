

<?php
    class libraries {
        private $id;
		private $name;
		private $address;
		private $type;
		private $lat;
		private $lng;
		private $conn;
		private $tableName = "libraries";
        
		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name; }
		function getName() { return $this->name; }
		function setAddress($address) { $this->address = $address; }
		function getAddress() { return $this->address; }
		function setType($type) { $this->type = $type; }
		function getType() { return $this->type; }
		function setLat($lat) { $this->lat = $lat; }
		function getLat() { return $this->lat; }
		function setLng($lng) { $this->lng = $lng; }
		function getLng() { return $this->lng; }



        public function __construct() {
            global $conn;  // Make sure to declare global here as well
            require_once '../connection.php';
            $this->conn = $conn;
        }

        public function getLibrariesBlankLatLng() {
            if ($this->conn === null) {
                throw new Exception("Database connection is not established.");
            }
            
            // Temporarily change the SQL query to fetch all records or specific condition known to return data
            $sql = "SELECT * FROM $this->tableName"; // For testing, fetch all rows
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $libraries = [];
            while ($row = $result->fetch_assoc()) {
                $libraries[] = $row;
            }
            return $libraries;
        }
        
        public function updateLibrariesWithLatLng() {
            $sql = "UPDATE $this->tableName SET lat = ?, lng = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
        
            // Check if prepare was successful
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }
        
            // Bind parameters. The "dds" string means Double, Double, String (based on the data types of lat, lng, id)
            $stmt->bind_param('ddi', $this->lat, $this->lng, $this->id);
        
            // Execute the query
            if($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        
    }
?>