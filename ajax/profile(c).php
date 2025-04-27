<?php
//session_start();
include('../inc-cus/link.php');

if (isset($_POST['info_form']))
{
    $frm_data=filteration($_POST);

    $u_exist = select("SELECT * FROM `c_register` WHERE `c_phone`=? AND `c_id`!=? LIMIT 1",
        [$frm_data['phone_number'],$_SESSION['uid']],'ss');

        if(mysqli_num_rows($u_exist)!=0)
        {
            echo 1;
            exit;
        }

        $query="UPDATE `c_register` SET `c_name`=?,`c_username`=?,`c_phone`=?,`c_address`=? WHERE c_id=?";
        $values=[$frm_data['name'],$frm_data['username'],$frm_data['phone_number'],$frm_data['address'],$_SESSION['uid']];

        if(update($query,$values,'sssss'))
        {
            $_SESSION['uName']=$frm_data['name'];
            echo 0; 
        }
        else
        {
            echo 2;
        }

}
if(isset($_POST['pass_form']))
    {
        $frm_data = filteration($_POST);
        

        if($frm_data['new_pass']!=$frm_data['confirm_pass']){
            echo 1;
            exit;   
        }
        $hashed_password=password_hash($frm_data['new_pass'],PASSWORD_DEFAULT);
        $query = "UPDATE `c_register` SET `c_password`= ? WHERE `c_id`= ? LIMIT 1";
        
        $values =  [$hashed_password,$_SESSION['uid']];

        if(update($query,$values,'ss')){
            echo 0;
        }
        else
        {
            echo 2;
        }

    }
?>