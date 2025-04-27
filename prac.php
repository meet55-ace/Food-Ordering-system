<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include 'inc-cus/link.php';?>
</head>

<body>
    <header>
        <div class="container-fluid mb-3">
            <div class="row">
                <div class="col">
                    <nav class="navbar navbar-expand-lg">
                      
                        <img src="Imgs/logo/final.png" alt="" class="navbar-brand img-fluid"
                            style="max-width: 50px; max-height: 45px;">
                        <h4 style="font-family: 'Nerko One'; font-size:45px; color:orangered;">Foodziee</h4>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse me-auto" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto gap-lg-4">
                                <li class="nav-item me-1"><a href="" class="nav-link"><i
                                            class="fa-solid fa-house px-2"></i>Home</a> </li>
                                <li class="nav-item me-1"><a href="" class="nav-link"><i
                                            class="fa-solid fa-magnifying-glass px-2"></i>Search</a> </li>
                                <li class="nav-item dropdown me-lg-3">
                                    <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                                            class="fa-solid fa-handshake px-2"></i>Partnership</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="res-lo.php" class="dropdown-item">Restaurant</a></li>
                                        <li><a href="delivery.php" class="dropdown-item">Courier service</a></li>
                                        <li><a href="" class="dropdown-item">Admin login</a></li>
                                    </ul>
                                </li>
                                <!-- <li c lass="nav-item me-lg-2"><a href="" class="nav-link" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fa-solid fa-user px-2"></i>Sign In</a></li> -->
                                <li class="nav-item me-lg-2"><a href="" class="nav-link"><i
                                            class="fa-solid fa-cart-shopping px-2"></i>Cart</a></li>
                                <li class="nav-item">
                                    <a class="btn btn-outline-dark" data-bs-toggle="modal"
                                        data-bs-target="#registerModal" href="#" role="button">Sign in</a>
                                </li>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#loginModal">
                                    login
                                </button>
                                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#registerModal">
                                register
                            </button> -->

                            </ul>
                            <div class="modal fade" tabindex="-1" aria-labelledby="loginModalLabel" id="loginModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="" method="POST">
                                            <div class="modal-header">
                                                <h5 class="modal-title d-flex align-items-center">
                                                    <i class="fa-solid fa-circle-user fs-3 me-2"></i>
                                                    <span id="login-toggle">Login</span>
                                                    <!-- <a href="#" class="text-warning text-decoration-none" id="register-toggle" style="display: inline;">/ Register</a> -->
                                                </h5>
                                                <button type="reset" class="btn-close shadow-none"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="login-fields">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email-id/Username</label>
                                                        <input type="text" name="username" required
                                                            class="form-control shadow-none">
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="form-label">Password</label>
                                                        <input type="password" name="password" required
                                                            class="form-control shadow-none">
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <button type="submit"
                                                            class="btn btn-dark shadow-none">Login</button>
                                                        <a href="/forgot-password"
                                                            class="text-secondary text-decoration-none">Forgot
                                                            password</a>
                                                    </div>
                                                </div>
                                                <div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="registerModal" tabindex="-1"
                                aria-labelledby="registerModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="registerModalLabel">
                                                <i class="fa-solid fa-circle-user fs-3 me-2"></i>Register
                                            </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="step-1">
                                                <form>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-1">
                                                            <label for="name" class="form-label">Name</label>
                                                            <input type="text" class="form-control" id="name" />
                                                        </div>
                                                        <div class="col-md-6 mb-1">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="email" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-1">
                                                            <label for="mobile" class="form-label">Mobile No.</label>
                                                            <input type="tel" class="form-control" id="mobile" />
                                                        </div>
                                                        <div class="col-md-6 mb-1">
                                                            <label for="username" class="form-label">Username</label>
                                                            <input type="text" class="form-control" id="username" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-1">
                                                            <label for="password" class="form-label">Password</label>
                                                            <input type="password" class="form-control" id="password" />
                                                        </div>
                                                        <div class="mb-1">
                                                            <label for="address" class="form-label">Address</label>
                                                            <textarea class="form-control" id="address"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-1">
                                                            <label for="bank-name" class="form-label">Bank Name</label>
                                                            <input type="text" class="form-control" id="bank-name" />
                                                        </div>
                                                        <div class="col-md-6 mb-1">
                                                            <label for="account-no" class="form-label">Account
                                                                No.</label>
                                                            <input type="text" class="form-control" id="account-no" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-1">
                                                            <label for="ifsc-code" class="form-label">IFSC Code</label>
                                                            <input type="text" class="form-control" id="ifsc-code" />
                                                        </div>
                                                        <div class="col-md-6 mb-1">
                                                            <label for="branch-name" class="form-label">Branch
                                                                Name</label>
                                                            <input type="text" class="form-control" id="branch-name" />
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="step-2" style="display: none;">
                                                <form>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-1">
                                                            <label for="restaurant-name" class="form-label">Restaurant
                                                                Name</label>
                                                            <input type="text" class="form-control"
                                                                id="restaurant-name" />
                                                        </div>
                                                        <div class="col-md-6 mb-1">
                                                            <label for="restaurant-email" class="form-label">Restaurant
                                                                Email</label>
                                                            <input type="email" class="form-control"
                                                                id="restaurant-email" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-1">
                                                            <label for="restaurant-mobile" class="form-label">Restaurant
                                                                Mobile No.</label>
                                                            <input type="tel" class="form-control"
                                                                id="restaurant-mobile" />
                                                        </div>
                                                        <div class="mb-1">
                                                            <label for="restaurant-address"
                                                                class="form-label">Restaurant Address</label>
                                                            <textarea class="form-control"
                                                                id="restaurant-address"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="mb-1">
                                                            <label for="fssai-no" class="form-label">FSSAI No.</label>
                                                            <input type="text" class="form-control" id="fssai-no" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-1">
                                                            <label for="restaurant-bank-name" class="form-label">Bank
                                                                Name</label>
                                                            <input type="text" class="form-control"
                                                                id="restaurant-bank-name" />
                                                        </div>

                                                        <div class="col-md-6 mb-1">
                                                            <label for="restaurant-account-no"
                                                                class="form-label">Account No.</label>
                                                            <input type="text" class="form-control"
                                                                id="restaurant-account-no" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-1">
                                                            <label for="restaurant-ifsc-code" class="form-label">IFSC
                                                                Code</label>
                                                            <input type="text" class="form-control"
                                                                id="restaurant-ifsc-code" />
                                                        </div>

                                                        <div class="col-md-6 mb-1">
                                                            <label for="restaurant-branch-name"
                                                                class="form-label">Branch Name</label>
                                                            <input type="text" class="form-control"
                                                                id="restaurant-branch-name" />
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" id="next-btn">Next</button>
                                            <button type="button" class="btn btn-primary" id="submit-btn"
                                                style="display: none;">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>





                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">
        Launch demo modal
    </button>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>