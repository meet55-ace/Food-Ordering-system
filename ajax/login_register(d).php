<?php
include('../inc-del/link.php');

if(isset($_POST['register']))
{
    $data = filteration($_POST);

    //match password and confirm password field

    if($data['password'] != $data['c_password'])
    {   
        echo '1' ;
        exit;
    }

    //check user exists or not

    $u_exist = select("SELECT * FROM `d_details` WHERE `d_email`=? OR `d_phone`=? LIMIT 1",
    [$data['email'],$data['mobile']],'si');

    if(mysqli_num_rows($u_exist)!=0)
    {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['d_email']) == $data['email'] ? '2' : '3';
        exit;
    }
    $hashed_password=password_hash($data['password'],PASSWORD_DEFAULT);
    $query = "INSERT INTO d_details(d_name,d_email,d_phone,d_username,d_password,d_address,d_vehicle_type,d_vehicle_rc,d_license,d_bankname,d_branchname,d_account,d_ifsc)
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        
    $values = [$data['name'],$data['email'],$data['mobile'],$data['username'],$hashed_password,$data['address'],$data['vehicle-type'],$data['vehicle-rc-book'],$data['License'],$data['bank-name'],$data['branch-name'],$data['account-no'],$data['ifsc-code']];
    
    if(insert($query,$values,'ssissssssssss')){
        echo 5;
    }
    else{
        echo 4;
    }
}

if(isset($_POST['login']))
    {
        $data = filteration($_POST);

        $u_exist = select("SELECT * FROM `d_details` WHERE `d_email`=? OR `d_phone`=? LIMIT 1",
        [$data['email_mob'],$data['email_mob']],"si");

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
                if(!password_verify($data['password'],$u_fetch['d_password'])){
                    echo 4;
                    exit;
                }
                else
                {
                    $_SESSION['login'] = true;
                    $_SESSION['d_id'] = $u_fetch['d_id']; 
                    $_SESSION['name'] = $u_fetch['d_name']; 
                    $_SESSION['mobile'] = $u_fetch['d_phone'];
                    echo 1;
                }
            }
        }
    }

?>