<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php require('inc-cus/link.php'); ?>
    <title>PROFILE</title>
    <link rel="stylesheet" href="./css/foodziee.css">

</head>

<body class="bg-light">
    <?php require('inc-cus/c-header.php'); 

    if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
    {
      include('foodziee.php');
    }
    
    $u_exist = select("SELECT * FROM  `c_register` WHERE `c_id`=? LIMIT 1",[$_SESSION['uid']],'s');

    if(mysqli_num_rows($u_exist)==0)
    {
      include('foodziee.php');
    }
    $u_fetch = mysqli_fetch_assoc($u_exist);

  ?>


    <div class="container">
        <div class="row">

            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">PROFILE</h2>

            </div>

            <div class="col-12 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="info_form">
                        <h5 class="mb-3 fw-bold">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" value="<?php echo $u_fetch['c_name']?>" class="form-control"
                                    name="name" />
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" value="<?php echo $u_fetch['c_email']?>" class="form-control"
                                    name="email" />
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control"
                                    name="address"><?php echo $u_fetch['c_address']?></textarea>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="<?php echo $u_fetch['c_username']?>"
                                    name="username" />
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Mobile No.</label>
                                <input type="text" class="form-control" value="<?php echo $u_fetch['c_phone']?>"
                                    name="phone_number" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <button type="submit" class="btn btn-dark shadow-none">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8 mb-5 px-4">
        <div class="bg-white p-3 p-md-4 rounded shadow-sm">
          <form id="pass-form">
            <h5 class="mb-3 fw-bold">Change Password</h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">New Password</label>
                <input name="new_pass" type="password"  class="form-control shadow-none" required>      
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label">Confirm Password</label>
                <input name="confirm_pass" type="password"  class="form-control shadow-none" required>      
              </div>
            </div>
            <button type="submit" class="btn btn-dark shadow-none">Save Changes</button>
          </form>
        </div>
      </div>
        </div>
    </div>


    <?php require('inc-cus/c-footer.php'); ?>

    <script>
    let info_form = document.getElementById('info_form');

    info_form.addEventListener('submit', function(e) {
        e.preventDefault();

        let data = new FormData();

        data.append('info_form', '');
        data.append('name', info_form.elements['name'].value);
        data.append('email', info_form.elements['email'].value);
        data.append('address', info_form.elements['address'].value);
        data.append('username', info_form.elements['username'].value);
        data.append('phone_number', info_form.elements['phone_number'].value);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/profile(c).php", true);

        xhr.onload = function() {
            let responseText = this.responseText.trim().split(' ').pop();  
            console.log(responseText);
            switch (responseText) {
                case '1':
                    alert("Phone number already exists");
                    break;
                case '2':
                    alert("an unexpected error");
                    break;
                default:
                    alert("Changes saved");
                    location.reload();
                    break;
            }
        }

        xhr.send(data);

    });

    let pass_form = document.getElementById('pass-form');

    pass_form.addEventListener('submit',function(e){
      e.preventDefault();

      let new_pass = pass_form.elements['new_pass'].value;
      let confirm_pass = pass_form.elements['confirm_pass'].value; 

      if(new_pass!=confirm_pass){
        alert("Password do not match!");
        return  false;
      }


      let data = new FormData();

      data.append('pass_form','');
      data.append('new_pass',new_pass);
      data.append('confirm_pass',confirm_pass);

      let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/profile(c).php",true);

        xhr.onload = function(){
          if(this.responseText == 1){
            alert("Password do not match meet!");
          }
          else if(this.responseText == 2){
            alert("Updation failed!");
          }
          else{
            alert("Changes saved!");
            
          }
        }

        xhr.send(data);

    });  

    </script>

</body>

</html>