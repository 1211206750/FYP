<nav class="navbar navbar-expand-lg navbar-dark bg-info">
    
    <!-- Logo and Category Bar -->
    <div class="logo-title">
        <img src="images/Micson.png" alt="Micson Florist and Balloon">
        <h1>Micson Florist and Balloon</h1>
    </div>

    <div class="category-bar">
        <a href="index.php">Home</a>
        <a href="shopping.php">Shop</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a class="nav-item nav-link" href="view_cart.php">Cart (<?php echo $cart->get_cart_count();?>)</a>
        </div>
    </div>
</nav>
