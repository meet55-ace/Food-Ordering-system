<?php
if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];

    $sql_cart_count = "SELECT SUM(quantity) AS total_items FROM user_cart WHERE c_id = ?";
    $result = selectt($sql_cart_count, [$uid], 'i'); 
    $row = mysqli_fetch_assoc($result);
    $cartCount = $row['total_items'] ?? 0; 
} else {
    $cartCount = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $cartCount += $item['quantity'];
        }
    }
}
?>
<header>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg bg-light">
            <a href="foodziee.php"><img src="Imgs/logo/final.png" alt="" class="navbar-brand img-fluid"
                    style="max-width: 50px; max-height: 40px;"></a>
            <a href="foodziee.php" style="text-decoration:none;">
                <h4 style="font-family: 'Nerko One'; font-size:40px; color:orangered;">Foodziee</h4>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto gap-lg-2">
                    <li class="nav-item me-lg-2">
                        <a href="foodziee.php" class="nav-link"><i class="fa-solid fa-house px-2"></i>Home</a>
                    </li>
                    <li class="nav-item me-lg-2">
                        <a href="search.php" class="nav-link"><i class="fa-solid fa-magnifying-glass px-2"></i>Search</a>
                    </li>
                    <li class="nav-item me-lg-2">
                        <a href="order.php" class="nav-link"><i class="fa-solid fa-box px-2"></i>My orders</a>
                    </li>
                    <?php if (!isset($_SESSION['login']) || $_SESSION['login'] != true): ?>
                        <li class="nav-item dropdown me-lg-2">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                                    class="fa-solid fa-handshake px-2"></i>Partnership</a>
                            <ul class="dropdown-menu">
                                <li><a href="res-lo.php" class="dropdown-item">Restaurant</a></li>
                                <li><a href="dev-lo.php" class="dropdown-item">Delivery Boy</a></li>
                                <li><a href="admin/index.php" class="dropdown-item">Admin Login</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item me-lg-4">
                        <a href="cart.php" class="nav-link">
                            <i class="fa-solid fa-cart-shopping px-2"></i>
                            Cart (<span id="cart-count"><?php echo $cartCount; ?></span>)
                        </a>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php
                    if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                        echo <<<data
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user px-1"></i>
                                <span style="font-weight: bold;">{$_SESSION['uName']}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="c-profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Log-out</a></li>
                            </ul>
                        </div>
                        data;
                    } else {
                        echo <<<data
                        <button type="button" class="btn btn-outline-dark me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#registerModal">Sign in</button>
                        data;
                    }
                    ?>
                </div>
            </div>
        </nav>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="loginModalLabel">
                        <i class="fa-solid fa-circle-user fs-3 me-2"></i>Login
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="login-form" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Phone/Email</label>
                            <input type="text" class="form-control" name="email_mob" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="pass" required>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <button type="submit" class="btn btn-dark shadow-none">Login</button>
                            <!-- <a href="/forgot-password" class="text-secondary text-decoration-none">Forgot Password?</a> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="registerModalLabel">
                        <i class="fa-solid fa-circle-user fs-3 me-2"></i>Register
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="register-form"  method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Mobile No.</label>
                                <input type="text" class="form-control" name="phone_number" required>
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="c_password" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
