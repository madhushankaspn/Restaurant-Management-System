<?php
session_start();
require_once 'classes/Food.php';
require_once 'classes/Category.php'; // If you create Category class

$foodObj = new Food();
$foodItems = $foodObj->getAllFood();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .food-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .food-img {
            height: 200px;
            object-fit: cover;
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-utensils"></i> RMS Restaurant
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="menu.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php">Book Table</a>
                    </li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <i class="fas fa-shopping-cart"></i> Cart
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="my-orders.php">My Orders</a></li>
                                <li><a class="dropdown-item" href="my-bookings.php">My Bookings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-dark text-white py-5">
        <div class="container text-center">
            <h1 class="display-4">Welcome to RMS Restaurant</h1>
            <p class="lead">Delicious food delivered to your doorstep</p>
            <a href="menu.php" class="btn btn-primary btn-lg">View Menu</a>
            <a href="booking.php" class="btn btn-outline-light btn-lg">Book a Table</a>
        </div>
    </div>

    <!-- Featured Food Items -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Our Featured Dishes</h2>
        <div class="row">
            <?php foreach(array_slice($foodItems, 0, 6) as $food): ?>
            <div class="col-md-4">
                <div class="card food-card">
                    <img src="images/<?php echo $food['img'] ?: 'default.jpg'; ?>" class="card-img-top food-img" alt="<?php echo $food['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $food['title']; ?></h5>
                        <p class="card-text text-muted"><?php echo substr($food['description'], 0, 60); ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary">Rs. <?php echo number_format($food['price'], 2); ?></span>
                            <a href="food-detail.php?id=<?php echo $food['f_id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2024 RMS Restaurant. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>