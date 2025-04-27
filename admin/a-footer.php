<script>
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
        xhr.open("POST", "../ajax/login_register(a).php", true);

        xhr.onload = function() {
            let responseText = this.responseText.split(' ').pop();
            //console.log(responseText)
            switch (responseText) {
                case '0':
                    alert("Invalid Email or Mobile Number!");
                    break;
                case '4':
                    alert("Incorrect Password!");
                    break;
                default:
                    alert('Login successfully');
                    window.location.href='admin.php';
            }
        };


        xhr.send(data);
    });
    </script>