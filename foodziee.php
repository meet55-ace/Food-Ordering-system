<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Foodziee</title>
    <?php include 'inc-cus/link.php';?>
    <link rel="stylesheet" href="./css/foodziee.css">
    <link rel="stylesheet" href="css/font.css">
</head>
<!-- <script src="script.js"></script> -->

<body class="bg-light">

    <!-- navbar -->
    <?php
      include 'inc-cus/c-header.php';
      ?>
    <!-- navbar end -->

    <!-- carousel start -->
    <div class="container">
        <h2 class="mb-4 text-center" style="font-weight:bold; color: #333;">Tasty items for you!!!</h2>
        <div class="owl-carousel owl-theme" id="carouselExampleControls">
            <div class="item">
                <a href="category.php?category_name=burger">
                    <img src="Imgs/testing-slider/burger.jpg" class="img-circle" alt="Burger">
                </a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=burger" style="text-decoration:none; color:black;">
                        <h4>Burger</h4>
                    </a>
                </div>
            </div>
            <div class="item">

                <a href="category.php?category_name=sandwich"><img src="Imgs/testing-slider/sandwich.jpg"
                        class="img-circle" alt="Sandwich"></a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=sandwich" style="text-decoration:none; color:black;">
                        <h4>Sandwich</h4>
                    </a>
                </div>
            </div>
            <div class="item">
                <a href="category.php?category_name=pizza"><img src="Imgs/testing-slider/pizza.jpg" class="img-circle"
                        alt="Pizza"></a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=pizza" style="text-decoration:none; color:black;">
                        <h4>Pizza</h4>
                    </a>
                </div>
            </div>
            <div class="item">
                <a href="category.php?category_name=momos"><img src="Imgs/testing-slider/momos.jpg" class="img-circle"
                        alt="Pizza"></a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=momos" style="text-decoration:none; color:black;">
                        <h4>Momos</h4>
                    </a>
                </div>
            </div>
            <div class="item">
                <a href="category.php?category_name=frankie"><img src="Imgs/testing-slider/frankie.jpg"
                        class="img-circle" alt="Frankie"></a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=frankie" style="text-decoration:none; color:black;">
                        <h4>Frankie</h4>
                    </a>
                </div>
            </div>
            <div class="item">
                <a href="category.php?category_name=vadapav"><img src="Imgs/testing-slider/vadapav.jpg"
                        class="img-circle" alt="Vada Pav"></a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=vadapav" style="text-decoration:none; color:black;">
                        <h4>Vada Pav</h4>
                    </a>
                </div>
            </div>
            <div class="item">
                <a href="category.php?category_name=fries"><img src="Imgs/testing-slider/frenchfries.jpg"
                        class="img-circle" alt="French Fries"></a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=fries" style="text-decoration:none; color:black;">
                        <h4>Fries</h4>
                    </a>
                </div>
            </div>
            <!-- <div class="item">
                <a href="category.php?category_name=chinese"><img src="Imgs/testing-slider/chinese.jpg" class="img-circle" alt="Chinese"></a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=chinese" style="text-decoration:none; color:black;"><h4>Chinese</h4></a>
                </div>
            </div> -->
            <div class="item">
                <a href="category.php?category_name=dosa"><img src="Imgs/testing-slider/dosa.jpg" class="img-circle"
                        alt="Dosa"></a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=dosa" style="text-decoration:none; color:black;">
                        <h4>Dosa</h4>
                    </a>
                </div>
            </div>
            <div class="item">
                <a href="category.php?category_name=noodles"><img src="Imgs/slider/noodles.jpg" class="img-circle"
                        alt="Noodles"></a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=noodles" style="text-decoration:none; color:black;">
                        <h4>Noodles</h4>
                    </a>
                </div>
            </div>
            <div class="item">
                <a href="category.php?category_name=pasta"><img src="Imgs/testing-slider/pasta1.jpg" class="img-circle"
                        alt="Pasta"></a>
                <div class="text-center mt-2">
                    <a href="category.php?category_name=pasta" style="text-decoration:none; color:black;">
                        <h4>Pasta</h4>
                    </a>
                </div>
            </div>
        </div>


        <!-- </div> -->
        <hr>

        <!-- <hr> -->
        <script>
        $('.owl-carousel').owlCarousel({
            // loop:true,
            margin: 10,
            nav: true,
            navText: [
                '<span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>',
                '<span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>'
            ],
            draggable: true,
            responsive: {
                0: {
                    items: 2
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        })
        </script>
        <!-- carousel end -->

        <!-- cards start -->
        <h3 class="mb-3">Restaurants</h3>
        <div class="container">
            <div class="row">
                <?php
                                $sql = "SELECT r_o.res_name, r_o.approx, r_o.res_address, r_o.profile, c.category_name
                                FROM r_o_details r_o
                                JOIN category c ON r_o.r_o_id = c.r_o_id
                                WHERE r_o.status = 1
                                ORDER BY r_o.res_name";
                        

                                    $result = selectt($sql, [], ""); 


                                    $restaurants = [];

                                        if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                                $res_name = $row['res_name'];
                                                $approx = $row['approx'];
                                                $res_address = $row['res_address'];
                                                $profile = $row['profile'];
                                                $category_name = $row['category_name'];

                            
                                        if (!isset($restaurants[$res_name])) {
                                            $restaurants[$res_name] = [
                                            'approx' => $approx,
                                            'res_address' => $res_address,
                                            'profile' => $profile,
                                            'categories' => []
                                        ];
                                        }

                            
                                    $restaurants[$res_name]['categories'][] = $category_name;
                                }
                            } else {
                                    echo "<p>No restaurants found.</p>";
                                    }

                ?>

                <div class="container">
                    <div class="row">
                        <?php
                            foreach ($restaurants as $res_name => $data) {
                            $categories = implode(', ', $data['categories']);
                        ?>
                        <div class="col-lg-4 col-md-6 col-sm-6 mb-5">
                            <div class="card border-0 shadow" style="max-width:300px;">
                                <img src="ajax/imgs/card(restaurant)/<?php echo $data['profile']; ?>"
                                    class="card-img-top" style="max-height:230px;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $res_name; ?></h5>
                                    <h6 class="card-sub-title">&raquo;<?php echo $data['approx']; ?></h6>
                                    <p class="card-text"><i
                                            class="fa-solid fa-location-dot"></i>&nbsp;<?php echo $data['res_address']; ?>
                                    </p>
                                    <h6>&gt;<?php echo strtoupper($categories); ?></h6>
                                    <a href="displayresitems.php?res_name=<?php echo urlencode($res_name); ?>"
                                        class="btn btn-outline-dark">Visit</a>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- cards end -->

    <!-- footer start -->

    <?php include 'inc-cus/c-footer.php';?>
    <!-- footer end -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>