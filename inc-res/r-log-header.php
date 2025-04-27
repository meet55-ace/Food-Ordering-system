<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <div class="container-fluid">
        <img src="Imgs/logo/final.png" alt="" class="navbar-brand img-fluid" style="max-width: 50px; max-height: 40px;">
        <h4 style="font-family: 'Nerko One'; font-size:40px; color:orangered;">Foodziee</h4>
        <button class="navbar-toggler me-2 ms-2" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item me-2 ">
                    <a class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#loginModal" href="#"
                        role="button">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#registerModal" href="#"
                        role="button">Sign in</a>
                </li>
            </ul>
        </div>
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
                <form id="r-login">
                    <div class="mb-3">
                        <label class="form-label">Mobile-no./Email</label>
                        <input type="text" class="form-control" name="email_mob">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password">
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
                <div class="step-1">
                    <form id="r-user" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" />
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Mobile No.</label>
                                <input type="text" class="form-control" name="mobile" />
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" />
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="c_password" />
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Bank Name</label>
                                <input type="text" class="form-control" name="bank-name" />
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Account No.</label>
                                <input type="text" class="form-control" name="account-no" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label class="form-label">IFSC Code</label>
                                <input type="text" class="form-control" name="ifsc-code" />
                            </div>
                            <div class="col-md-6 mb-1">
                                <label class="form-label">Branch Name</label>
                                <input type="text" class="form-control" name="branch-name" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input type="button" class="btn btn-dark mt-2" value="Next" id="next-btn">
                            </div>
                        </div>
                        <!-- </form> -->
                </div>
                <div class="step-2" style="display: none;">
                    <!-- <form id="res-form"> -->
                    <div class="row">
                        <div class="col-md-6 mb-1">
                            <label class="form-label">Restaurant Name</label>
                            <input type="text" class="form-control" name="res-name" />
                        </div>
                        <div class="col-md-6 mb-1">
                            <label class="form-label">Restaurant Email</label>
                            <input type="text" class="form-control" name="res-email" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-1">
                            <label class="form-label">Restaurant Mobile No.</label>
                            <input type="text" class="form-control" id="res-mobile" />
                        </div>
                        <div class="col-md-6 mb-1">
                            <label class="form-label">Restaurant Banner</label>
                            <input name="profile" type="file" accept=".png, .jpg, .jpeg, .webp"
                                class="form-control shadow-none" required>
                        </div>
                        <div class="mb-1">
                            <label class="form-label">Restaurant Address</label>
                            <textarea class="form-control" name="res-address"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-1">
                            <label class="form-label">FSSAI No.</label>
                            <input type="text" class="form-control" name="res-fssai" />
                        </div>
                        <div class="col-md-6 mb-1">
                            <label class="form-label">Approx Delivery(45-50mins) </label>
                            <input type="text" class="form-control" name="approx" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-dark" id="submit-btn"
                                style="display: none;">Submit</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<script>
document.getElementById('next-btn').addEventListener('click', () => {
    document.querySelector('.step-1').style.display = 'none';
    document.querySelector('.step-2').style.display = 'block';
    document.getElementById('next-btn').style.display = 'none';
    document.getElementById('submit-btn').style.display = 'block';
});
</script>