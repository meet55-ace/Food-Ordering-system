<?php
include('../inc-res/link.php');

if (isset($_POST['register'])) {
    $data = filteration($_POST);

    // Match password and confirm password
    if ($data['password'] != $data['c_password']) {   
        echo '1';
        exit;
    }

    // Check if user exists
    $u_exist = select("SELECT * FROM `r_o_details` WHERE `res_email`=? OR `res_phone`=? LIMIT 1",
    [$data['res-email'], $data['res-mobile']], 'si');

    if (mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['res_email'] == $data['res-email']) ? '2' : '3';
        exit;
    }

    // Handle file upload
    $img_name = $_FILES['profile']['name']; // Get the original file name
    $img_tmp_name = $_FILES['profile']['tmp_name'];
    $img_error = $_FILES['profile']['error'];

    if ($img_error === 0) {
        $img_ext = pathinfo($img_name, PATHINFO_EXTENSION);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($img_ext), $allowed_exts)) {
            // Define upload directory
            $upload_dir = "imgs/card(restaurant)/";

            // Create the directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $upload_path = $upload_dir . $img_name;

            // Check if a file with the same name already exists
            if (file_exists($upload_path)) {
                echo '9'; // File with the same name already exists
                exit;
            }

            if (move_uploaded_file($img_tmp_name, $upload_path)) {
                $img = $img_name; // Save the original file name for the database entry
            } else {
                echo '6'; // Error in moving the uploaded file
                exit;
            }
        } else {
            echo '7'; // Invalid file type
            exit;
        }
    } else {
        echo '8'; // Error in file upload
        exit;
    }

    // Save data to database
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    $query = "INSERT INTO r_o_details(r_o_name,r_o_email,r_o_phone,r_o_username,r_o_password,r_o_address,r_o_bankname,r_o_branchname,r_o_account,r_o_ifsc,res_name,res_email,res_phone,profile,res_address,res_fssai,approx)
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    
    $values = [$data['name'], $data['email'], $data['mobile'], $data['username'], $hashed_password, $data['address'], $data['bank-name'], $data['branch-name'], $data['account-no'], $data['ifsc-code'], $data['res-name'], $data['res-email'], $data['res-mobile'], $img, $data['res-address'], $data['res-fssai'],$data['approx']];
    
    if (insert($query, $values, 'ssssssssiisssssss')) {
        echo 5;
    } else {
        echo 4;
    }
}



if(isset($_POST['login']))
    {
        $data = filteration($_POST);

        $u_exist = select("SELECT * FROM `r_o_details` WHERE `r_o_email`=? OR `r_o_phone`=? LIMIT 1",
        [$data['email_mob'],$data['email_mob']],"ss");

        if(mysqli_num_rows($u_exist)==0)
        {
            echo 0;
            exit;
        }
        $u_fetch = mysqli_fetch_assoc($u_exist);
        
        if($u_fetch['Requests'] === 'Pending' || $u_fetch['Requests'] === 'Rejected') 
        {
            echo 5; // Account status not allowed for login
        }
       
        else{
            // $u_fetch = mysqli_fetch_assoc($u_exist);
            if($u_fetch['status']==0){
                echo 2;
                exit;
            }
            else{
                if(!password_verify($data['password'],$u_fetch['r_o_password']))
                {
                    echo 4;
                    exit;
                }
                else{
                    
                    $_SESSION['login'] = true;
                    $_SESSION['r_o_id'] = $u_fetch['r_o_id']; 
                    $_SESSION['name'] = $u_fetch['res_name']; 
                    $_SESSION['mobile'] = $u_fetch['r_o_phone'];
                    echo 1;
                }
            }
        }
    }


?>