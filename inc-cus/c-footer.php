<div class="container-fluid bg-white mt-5">
    <div class="row">
        <div class="col-lg-5 p-4">
            <h4 style="font-family: 'Nerko One'; font-size:45px; color:orangered;">Foodziee</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam, reiciendis eius sint optio asperiores
                alias ex perferendis inventore, sed ullam libero autem iure dolore iste placeat blanditiis vitae
                voluptatum. Quam vitae cupiditate reprehenderit at perferendis culpa hic adipisci eos aspernatur.
            </p>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Links</h5>
            <a href="foodziee.php" class="d-inline-block mb-2 text-dark text-decoration-none active">Home</a><br>
            <a href="search.php" class="d-inline-block mb-2 text-dark text-decoration-none active">Search</a><br>
            <!-- <a href="" class="d-inline-block mb-2 text-dark text-decoration-none active">Partnership</a><br> -->
            <a href="cart.php" class="d-inline-block mb-2 text-dark text-decoration-none active">Cart</a>
        </div>
        <div class="col-lg-3 p-4">
            <h5 class="mb-3">Follow us</h5>
            <a href="https://twitter.com" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="fa-brands fa-twitter me-1"></i>
                Twitter
            </a><br>
            <a href="https://facebook.com" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="fa-brands fa-facebook me-1"></i>
                Facebook
            </a><br>
            <a href="https://instagram.com" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="fa-brands fa-instagram me-1"></i>
                Instagram
            </a>
        </div>
    </div>
</div>
<h6 class="text-center bg-dark text-white m-0 p-3">Designed and Developed by KSV STUDENTS</h6>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
<script>
let register_form = document.getElementById('register-form');

register_form.addEventListener('submit', function(e) {
    e.preventDefault();

    let data = new FormData();
    data.append('name', register_form.elements['name'].value);
    data.append('username', register_form.elements['username'].value);
    data.append('email', register_form.elements['email'].value);
    data.append('password', register_form.elements['password'].value);
    data.append('c_password', register_form.elements['c_password'].value);
    data.append('phone_number', register_form.elements['phone_number'].value);
    data.append('address', register_form.elements['address'].value);
    data.append('register', '');

    // Hide the modal
    var myModal = document.getElementById("registerModal");
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/login_register.php", true);

    xhr.onload = function() {
    let responseText = this.responseText.split(' ').pop();
    // console.log(typeof responseText)
    switch (responseText) {
        case '1':
            alert("Password Mismatch!");
            break;
        case '2':
            alert("Email is already registered!");
            break;
        case '3':
            alert("Phone number is already registered!");
            break;
        case '4':
            alert("Inseration not done");
            break;
        case '5':
            alert("Register sucessfully");
            register_form.reset();
            break;
        default:
            alert("unknown occured.");
    }
};
    xhr.send(data);
});

let login_form = document.getElementById('login-form');

login_form.addEventListener('submit', function(e) {
    e.preventDefault();

    let data = new FormData();

    data.append('email_mob', login_form.elements['email_mob'].value);
    data.append('pass', login_form.elements['pass'].value);
    data.append('login', '');

    var myModal = document.getElementById("loginModal");
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();


    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/login_register.php", true);

    xhr.onload = function() {
    let responseText = this.responseText.split(' ').pop();
    //console.log(responseText)
    switch (responseText) {
        case '0':
            alert("Invalid Email or Mobile Number!");
            break;
        case '2':
            alert("Account Suspended! Please contact Admin.");
            break;
        case '4':
            alert("Incorrect Password!");
            break;
        default:
            alert('Login successfully');
            location.reload();     

    }
};


    xhr.send(data);
});
</script>