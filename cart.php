<?php
session_start();
require_once 'classes/Food.php';
require_once 'classes/Order.php';

// Initialize cart if not exists
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if(isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $foodObj = new Food();
    $food = $foodObj->getFoodById($_GET['id']);
    
    if($food) {
        $item = [
            'f_id' => $food['f_id'],
            'title' => $food['title'],
            'price' => $food['price'],
            'quantity' => 1,
            'img' => $food['img']
        ];
        
        // Check if item already in cart
        $found = false;
        foreach($_SESSION['cart'] as &$cartItem) {
            if($cartItem['f_id'] == $food['f_id']) {
                $cartItem['quantity']++;
                $found = true;
                break;
            }
        }
        
        if(!$found) {
            $_SESSION['cart'][] = $item;
        }
        
        header("Location: cart.php");
        exit();
    }
}

// Remove from cart
if(isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['index'])) {
    $index = $_GET['index'];
    if(isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
    }
    header("Location: cart.php");
    exit();
}

// Update quantity
if(isset($_POST['update_cart'])) {
    foreach($_POST['quantity'] as $index => $qty) {
        if(isset($_SESSION['cart'][$index])) {
            if($qty <= 0) {
                unset($_SESSION['cart'][$index]);
            } else {
                $_SESSION['cart'][$index]['quantity'] = $qty;
            }
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

// Calculate total
$total = 0;
foreach($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - RMS Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation (copy from index.php) -->
    
    <div class="container my-5">
        <h2 class="mb-4">Your Shopping Cart</h2>
        
        <?php if(empty($_SESSION['cart'])): ?>
            <div class="alert alert-info">
                Your cart is empty. <a href="menu.php">Continue shopping</a>
            </div>
        <?php else: ?>
            <form method="POST" action="">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th width="150">Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($_SESSION['cart'] as $index => $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="images/<?php echo $item['img'] ?: 'default.jpg'; ?>" 
                                         width="50" height="50" class="me-2" style="object-fit: cover;">
                                    <?php echo $item['title']; ?>
                                </div>
                            </td>
                            <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $index; ?>]" 
                                       value="<?php echo $item['quantity']; ?>" min="0" class="form-control">
                            </td>
                            <td>Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <a href="?action=remove&index=<?php echo $index; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Remove this item?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th colspan="2">Rs. <?php echo number_format($total, 2); ?></th>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="d-flex justify-content-between">
                    <a href="menu.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                    <div>
                        <button type="submit" name="update_cart" class="btn btn-warning">
                            <i class="fas fa-sync-alt"></i> Update Cart
                        </button>
                        <a href="checkout.php" class="btn btn-success">
                            Proceed to Checkout <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
    
    <!-- Footer -->
</body>
</html>