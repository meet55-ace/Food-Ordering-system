<?php
include('../inc-cus/link.php');
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

    $u_exist = select("SELECT * FROM `c_register` WHERE `c_email`=? OR `c_phone`=? LIMIT 1",
    [$data['email'],$data['phone_number']],'ss');

    if(mysqli_num_rows($u_exist)!=0)
    {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['c_email']) == $data['email'] ? '2' : '3';
        exit;
    }
    $v_code = bin2hex(random_bytes(16));
    $hashed_password=password_hash($data['password'],PASSWORD_DEFAULT);
    $query = "INSERT INTO c_register(c_name,c_username,c_email,c_password,c_phone,c_address,verification_code,is_verified)
                                VALUES(?,?,?,?,?,?,?,?)";
        
    $values = [$data['name'],$data['username'],$data['email'],$hashed_password,
    $data['phone_number'],$data['address'],'$v_code','0'];
    
    if(insert($query,$values,'sssssssi')){
        echo 5;
    }
    else{
        echo 4;
    }

}
if(isset($_POST['login']))
    {
        $data = filteration($_POST);

        $u_exist = select("SELECT * FROM `c_register` WHERE `c_email`=? OR `c_phone`=? LIMIT 1",
        [$data['email_mob'],$data['email_mob']],"ss");

        if(mysqli_num_rows($u_exist)==0)
        {
            echo 0;
            exit;
        }
        else{
            $u_fetch = mysqli_fetch_assoc($u_exist);
            if($u_fetch['c_status']==0){
                echo 2;
                exit;
            }
            else
            {
                if(!password_verify($data['pass'],$u_fetch['c_password']))
                {
                    echo 4;
                    exit;
                }
                else{
                    // session_start();
                    $_SESSION['login'] = true;
                    $_SESSION['uid'] = $u_fetch['c_id']; 
                    $_SESSION['uName'] = $u_fetch['c_name']; 
                    $_SESSION['uphone'] = $u_fetch['c_phone'];
                    echo 1;
                }
            }
        }
    }

?>