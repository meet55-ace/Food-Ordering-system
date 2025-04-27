<?php
include 'link.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminLogin</title>
    <!-- <link rel="stylesheet" href="../css/font.css"> -->
    <link rel="stylesheet" href="nav.css">
</head>

<body>
    <header>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <img src="../Imgs/logo/final.png" alt="" class="navbar-brand img-fluid"
                    style="max-width: 50px; max-height: 40px;">
                <h4 style="font-family: 'Nerko One'; font-size:40px; color:orangered;">Foodziee</h4>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item me-2 ">
                            <a class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#loginModal" href="#"
                                role="button">Login</a>
                        </li>
                        <!-- <li class="nav-item">
                    <a class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#registerModal" href="#"
                        role="button">Sign in</a>
                </li> -->
                    </ul>
                </div>
            </nav>
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
                            <form id=login-form>
                                <div class="mb-3">
                                    <label class="form-label">Phone/Email</label>
                                    <input type="text" class="form-control" name="email_mob">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="pass">
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <button type="submit" class="btn btn-dark shadow-none">Login</button>
                                    <!-- <a href="/forgot-password" class="text-secondary text-decoration-none">Forgot
                                        password</a> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </header>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="../Imgs/admin/food-delivery-admin.jpg" alt="Food Delivery Admin" class="img-fluid"
                    style="height: 400px; object-fit: cover;">
            </div>
            <div class="col-md-6 mt-2 text-center">
                <h1 class="fw-bold">Online Food Ordering Website</h1>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <!-- <h2 class="text-center mb-4">Admin Panel Features</h2> -->
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card p-3 shadow">
                    <i class="fas fa-chart-line fa-3x text-success"></i>
                    <h4 class="mt-3">Monthly Revenue & Orders Analysis</h4>
                    <p>View detailed revenue reports and track orders per month for better insights.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow">
                    <i class="fas fa-user-check fa-3x text-primary"></i>
                    <h4 class="mt-3">Approve Restaurant & Delivery Boy Requests</h4>
                    <p>Review and accept new restaurant and delivery personnel applications.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 shadow">
                    <i class="fas fa-motorcycle fa-3x text-warning"></i>
                    <h4 class="mt-3">Analysis the most orders taaken by delivery boy</h4>
                    <p>Identify which delivery boy completes the most orders.</p>
                </div>
            </div>
        </div>


    </div>


    <?php include 'a-footer.php'?>
</body>

</html>