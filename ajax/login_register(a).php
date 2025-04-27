<?php
include('../admin/link.php');

if(isset($_POST['login']))
{
    $data = filteration($_POST);

    $u_exist = select("SELECT * FROM `a_details` WHERE `a_email`=? OR `a_phone`=? LIMIT 1",
    [$data['email_mob'],$data['email_mob']],"ss");

    if(mysqli_num_rows($u_exist)==0)
    {
        echo 0;
        exit;
    }
    else
    {
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if($data['pass']!=$u_fetch['a_password']){
            echo 4;
            exit;
        }
        else{
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['a_id'] = $u_fetch['a_id']; 
            $_SESSION['name'] = $u_fetch['a_name']; 
            $_SESSION['mobile'] = $u_fetch['a_phone'];
            echo 1;
        }
    }
}
   
        

?>