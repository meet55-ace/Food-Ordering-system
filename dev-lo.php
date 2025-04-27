<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery</title>
    <?php include 'inc-del/link.php';?>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
    <style>
    .nav-item+.nav-item {
        margin-left: 10px;
    }

    .imagem-container {
        position: relative;
    }

    .image-text {
        position: absolute;
        top: 50%;
        right: 3%;
        transform: translate(-50%, -50%);
        color: darkblue;
        text-align: center;
        font-size: 2.5vw;
        font-weight: bold;
        line-height: 1.5;
        padding: 20px;
    }

    .section {
        display: grid;
        place-items: center;
        height: 55vh;
        /* background-color: #bdc3c7; */
    }

    .header-section {
        postion: relative;
        /* padding-bottom:5px; */
        text-align: center;
        font-weight: 900;
        /* padding-top:25px; */
        color: black;
    }

    .header-section:after {
        content: '';
        position: absolute;
        height: 3px;
        width: 200px;
        bottom: 0;
        left: cal(50% - 100px)
    }

    .header-section span {
        display: block;
        font-size: 15px;
        font-weight: 300;
    }

    .testimonials {
        max-width: 1000px;
        padding: 0 15px 50px;
        margin: 0 auto 80px auto;
    }

    .single-item {
        background-color: #fff;
        color: #111;
        padding: 46px;
        margin: 5px 51px;
    }

    .profile {
        margin-bottom: 30px;
        text-align: center;
    }

    .img-area {
        margin: 0 15px 15px 15px;
    }

    .img-area img {
        height: 200px;
        width: 200px;
        border-radius: 50%;
        border: 7px solid white;
    }

    .content {
        font-size: 18px;
    }

    .content p {
        text-align: justify;
    }

    .bio h4 {
        font-family: berkshire swash;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: black;
    }

    .customer-support {
        text-align: center;
        background: rgb(115, 49, 172);
        /* Dark purple background */
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        margin: 30px auto;
    }

    .customer-support h2 {
        color: #EEEEEE;
        /* Light text color */
        font-size: 24px;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .customer-support p {
        font-size: 18px;
        margin: 8px 0;
        color: #D4BEE4;
        /* Softer purple */
    }

    .customer-support a {
        color: #FFFFFF;
        font-weight: bold;
        text-decoration: none;
        transition: 0.3s;
    }

    .customer-support a:hover {
        text-decoration: underline;
        /* color:rgb(222, 188, 247); */
    }

    /* Icons */
    .customer-support i {
        margin-right: 8px;
        color: #D4BEE4;
        font-size: 20px;
    }
    </style>
</head>

<body>
    <!-- header start -->
    <?php include 'inc-del/d-log-header.php';?>
    <!-- header end -->
    <div class="imagem-container">
        <img src="Imgs/del1.jpg" style="width:100%; height:345px" alt="">
        <div class="image-text">Join with Foodziee!!!<br>
            Increase Your online orders<br>
            Access Foodziee tools
        </div>
    </div>
    <?php include 'inc-del/d-log-footer.php';?>
    <div class="customer-support">
        <h2>Need Help? Contact Our Support Team</h2>
        <p><i class="fas fa-phone"></i> Call Us: <a href="tel:+91 9825942142">+91 9825942142</a></p>
        <p><i class="fas fa-envelope"></i> Email Us: <a
                href="mailto:meetpadaliya55@gmail.com">meetpadaliya55@gmail.com</a>
        </p>
    </div>
</body>

</html>