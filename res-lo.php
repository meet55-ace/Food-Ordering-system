<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant</title>
    <?php include 'inc-res/link.php';?>
    <link rel="stylesheet" href="css/res-lo.css">
    <link rel="stylesheet" href="css/font.css">
    <style>
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

<body class="bg-light">
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2Q75FHbPTYL4tbdvFcLHhQrJh7xnt+VYyjQaQ7FhR" crossorigin="anonymous"></script> -->
    <?php include 'inc-res/r-log-header.php';?>

    <div class="imagem-container">
        <img src="https://www.kotak.com/content/dam/Kotak/article-images/hero-banners/partnership-deed-d.jpg"
            style="width:100%; height:270px" alt="">
        <div class="image-text">Join with Foodziee!!!<br>
            Increase Your online orders<br>
            Access Foodziee tools
        </div>
    </div>



    <?php include 'inc-res/r-log-footer.php';?>
    <div class="customer-support">
        <h2>Need Help? Contact Our Support Team</h2>
        <p><i class="fas fa-phone"></i> Call Us: <a href="tel:+91 9825942142">+91 9825942142</a></p>
        <p><i class="fas fa-envelope"></i> Email Us: <a
                href="mailto:meetpadaliya55@gmail.com">meetpadaliya55@gmail.com</a>
        </p>
    </div>
</body>

</html>