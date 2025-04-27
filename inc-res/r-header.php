<!-- restaurant header -->

<header>
      <div class="container-fluid mb-3">
        <div class="row">
          <div class="col">
            <nav class="navbar navbar-expand-lg ">
              <a href="restaurant.php" style="text-decoration:none;"><img src="Imgs/logo/final.png" alt=""  class="navbar-brand img-fluid" style="max-width: 50px; max-height: 45px;"></a>
              <a href="restaurant.php" style="text-decoration:none;"><h4 style="font-family: 'Nerko One'; font-size:45px; color:orangered;">Foodziee</h4></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto gap-lg-4">
                  <li class="nav-item me-1"><a href="restaurant.php" class="nav-link"><i class="fa-solid fa-house px-2"></i>Home</a> </li>
                  <li class="nav-item me-1"><a href="restaurant_confirm.php" class="nav-link"><i class="fa-solid fa-cart-shopping px-2"></i>Orders</a> </li>
                  <!-- <li class="nav-item me-lg-2"><a href="" class="nav-link"><i class="fa-solid fa-person-chalkboard px-2"></i>About us</a></li> -->
                  <!-- <li class="nav-item me-lg-2"><a href="" class="nav-link" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fa-solid fa-user px-2"></i>Sign In</a></li> -->
                  <li class="nav-item me-lg-2"><a href="wallet.php" class="nav-link"><i class="fa-solid fa-wallet px-2"></i>Wallet</a></li>
                  <li class="nav-item me-lg-2"><a href="menu.php" class="nav-link"><i class="fa-regular fa-pen-to-square px-2"></i>Menu</a></li>
                  <li class="nav-item me-lg-2"><a href="analytics_r.php" class="nav-link"><i class="fa-regular fa-bar-chart px-2"></i>Analytics</a></li>
                  <!-- <li class="nav-item me-lg-2"><a href="" class="nav-link"><i class="fa-solid fa-circle-user px-2"></i>Profile</a></li> -->
                  <?php
                  if(isset($_SESSION['login']) && $_SESSION['login']==true)
                  {
                    echo<<<data
                    <div class="btn-group">
                    <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                     <i class="fa-solid fa-user px-1">
                     </i> 
                     <span style="font-weight: bold;">$_SESSION[name]</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-lg-end">
                    <li><a class="dropdown-item" href="logoutr.php">Log-out</a></li>
                  </ul>
                </div>
                data;
                  }
                  ?>
                </ul>
              </div>
            </nav>
          </div>
        </div>
      </div>
</header>