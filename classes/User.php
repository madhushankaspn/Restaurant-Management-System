<?php
require_once 'config/Database.php';

class User {
    private $conn;
    private $table = "users";

    public $u_id;
    public $username;
    public $email;
    public $password;
    public $phone;
    public $address;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Register
    public function register() {
        $query = "INSERT INTO " . $this->table . " 
                  SET username=:username, email=:email, password=:password, 
                      phone=:phone, address=:address";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize and hash
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = md5($this->password); // Use password_hash() in production
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->address = htmlspecialchars(strip_tags($this->address));
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":address", $this->address);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Login
    public function login() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (username=:credential OR email=:credential) 
                  AND password=:password LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $this->password = md5($this->password);
        
        $stmt->bindParam(":credential", $this->username); // username or email
        $stmt->bindParam(":password", $this->password);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->u_id = $row['u_id'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            return true;
        }
        return false;
    }
    
    // Get user by ID
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE u_id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>