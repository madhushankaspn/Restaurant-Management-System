<?php
require_once 'config/Database.php';

class Food {
    private $conn;
    private $table = "food";

    public $f_id;
    public $cat_id;
    public $title;
    public $slogan;
    public $price;
    public $img;
    public $description;
    public $status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all food items
    public function getAllFood() {
        $query = "SELECT f.*, c.cat_name 
                  FROM " . $this->table . " f
                  LEFT JOIN category c ON f.cat_id = c.cat_id
                  WHERE f.status = 'available'
                  ORDER BY f.f_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single food item
    public function getFoodById($id) {
        $query = "SELECT f.*, c.cat_name 
                  FROM " . $this->table . " f
                  LEFT JOIN category c ON f.cat_id = c.cat_id
                  WHERE f.f_id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>