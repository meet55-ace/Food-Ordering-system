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
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item me-lg-4">
                            <a class="nav-link" href="admin.php">Home</a>
                        </li>
                        <li class="nav-item me-lg-4">
                            <a class="nav-link" href="requests.php">Requests</a>
                        </li>
                        <li class="nav-item me-lg-4">
                            <a class="nav-link" href="admin_orders.php">Orders</a>
                        </li>
                        <li class="nav-item me-lg-4">
                            <a class="nav-link" href="view_resturants.php">View Restuarants</a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <?php
                    
                  if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']==true)
                  {
                    echo<<<data
                    <div class="btn-group">
                      <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                       <i class="fa-solid fa-user px-1">
                       </i> 
                       <span style="font-weight: bold;">$_SESSION[name]</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                      <li><a class="dropdown-item" href="logouta.php">Log-out</a></li>
                    </ul>
                  </div>
                  data;
                  }
                  else {
                    echo <<<data
                <button type="submit" class="btn btn-outline-dark me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                data;
                }
                  ?>

                    </div>
                </div>
            </nav>
            <!-- <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
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
                                    <a href="/forgot-password" class="text-secondary text-decoration-none">Forgot
                                        password</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
</header>