<?php
require_once 'config/Database.php';

class Order {
    private $conn;
    private $table = "orders";

    public $o_id;
    public $u_id;
    public $order_type;
    public $total_amount;
    public $status;
    public $payment_method;
    public $payment_status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create new order
    public function createOrder($u_id, $cartItems, $total, $orderType = 'dine_in', $paymentMethod = 'cash') {
        try {
            $this->conn->beginTransaction();
            
            // Insert into orders table
            $query = "INSERT INTO " . $this->table . " 
                      (u_id, order_type, total_amount, payment_method) 
                      VALUES (:u_id, :order_type, :total, :payment_method)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":u_id", $u_id);
            $stmt->bindParam(":order_type", $orderType);
            $stmt->bindParam(":total", $total);
            $stmt->bindParam(":payment_method", $paymentMethod);
            $stmt->execute();
            
            $order_id = $this->conn->lastInsertId();
            
            // Insert into order_details
            foreach($cartItems as $item) {
                $query2 = "INSERT INTO order_details (o_id, f_id, quantity, price) 
                           VALUES (:o_id, :f_id, :qty, :price)";
                $stmt2 = $this->conn->prepare($query2);
                $stmt2->bindParam(":o_id", $order_id);
                $stmt2->bindParam(":f_id", $item['f_id']);
                $stmt2->bindParam(":qty", $item['quantity']);
                $stmt2->bindParam(":price", $item['price']);
                $stmt2->execute();
            }
            
            $this->conn->commit();
            return $order_id;
            
        } catch(Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Get orders by user
    public function getUserOrders($u_id) {
        $query = "SELECT o.*, 
                  (SELECT COUNT(*) FROM order_details WHERE o_id = o.o_id) as item_count
                  FROM " . $this->table . " o
                  WHERE o.u_id = :u_id
                  ORDER BY o.order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":u_id", $u_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>