<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if(empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

require_once 'classes/Order.php';
require_once 'classes/User.php';

$orderObj = new Order();
$userObj = new User();
$user = $userObj->getUserById($_SESSION['user_id']);

$message = '';
$error = '';

// Calculate total
$total = 0;
foreach($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderType = $_POST['order_type'];
    $paymentMethod = $_POST['payment_method'];
    
    $order_id = $orderObj->createOrder(
        $_SESSION['user_id'], 
        $_SESSION['cart'], 
        $total, 
        $orderType, 
        $paymentMethod
    );
    
    if($order_id) {
        // Clear cart
        $_SESSION['cart'] = [];
        
        // Redirect to success page
        header("Location: order-success.php?id=" . $order_id);
        exit();
    } else {
        $error = "Order failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - RMS Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Checkout</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Delivery Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?php echo $user['username']; ?></p>
                        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
                        <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
                    </div>
                </div>
                
                <form method="POST" action="">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Order Type</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="order_type" id="dine_in" value="dine_in" checked>
                                <label class="form-check-label" for="dine_in">Dine In</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="order_type" id="takeaway" value="takeaway">
                                <label class="form-check-label" for="takeaway">Takeaway</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="order_type" id="delivery" value="delivery">
                                <label class="form-check-label" for="delivery">Delivery</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" checked>
                                <label class="form-check-label" for="cash">Cash on Delivery</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="card" value="card">
                                <label class="form-check-label" for="card">Credit/Debit Card</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                <label class="form-check-label" for="paypal">PayPal</label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg w-100">Place Order</button>
                </form>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <?php foreach($_SESSION['cart'] as $item): ?>
                            <tr>
                                <td><?php echo $item['title']; ?> x <?php echo $item['quantity']; ?></td>
                                <td class="text-end">Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <th>Total</th>
                                <th class="text-end">Rs. <?php echo number_format($total, 2); ?></th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>