 <?php

function alerto($type,$msg){
$bs_class = ($type == "success") ? "alert-success" : "alert-danger";
// here doc method in php
echo <<<alert
    <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
        <strong class="me-3">$msg</strong> 
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
alert;
}


?>