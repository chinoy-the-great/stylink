<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");


ob_end_flush();

?>
<style>
    body {
        background-color: #c9cba3; /* Main color in theme */
        font-family: 'Arial', sans-serif;
        color: #000000;
    }


    .product-image {
        position: relative;
    }

    .product-image img {
        width: 100%;
        aspect-ratio: 1 / 1;
        /* Sets a 1:1 aspect ratio directly */
        object-fit: cover;
    }
    .footer-block > h3 {
    font-size: 20px;
    line-height: 24px;
    font-weight: 500;
    color: #3c3c3c;
    margin: 0 0 15px 0;
    cursor: pointer;
    padding-top: 20px;
    text-transform: capitalize;
    }

    .slider-with-banner {
        margin-top: 20px; /* Adjust this value as needed */
    }

    .slider-with-banner .row {
        display: flex;
        align-items: stretch; /* This keeps the slider and the banners the same height */
    }

    .slider-area {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .li-banner {
        height: 50%; /* Each banner will take 50% of the right section */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .li-banner img {
        height: 100%;
        width: 100%;
        object-fit: cover; /* Ensures the images fill the space without distorting */
    }

    .mt-15 {
        margin-top: 15px;
    }


</style>

<body>
    <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <!-- Begin Body Wrapper -->
    <div class="body-wrapper">
        <?php include("include/header.php"); ?>
        <!-- Begin Slider With Banner Area -->
        <div class="slider-with-banner">
            <div class="container">
                <div class="row">
                    <!-- Begin Slider Area -->
                    <div class="col-lg-8 col-md-8">
                        <div class="slider-area">
                            <div class="slider-active owl-carousel">
                                <!-- Begin Single Slide Area -->
                                <div class="single-slide align-center-left  animation-style-01 bg-1">
                                    <div class="slider-progress"></div>
                                    <div class="slider-content">
                                        <h5><strong><span>BIG DROP</span> THIS WEEK</strong></h5>
                                        <h2>100% Cotton Hoodies</h2>
                                        <h3>Starting at <span>
                                                ₱99</span></h3>
                                        <div class="default-btn slide-btn">
                                            <a class="links" href="#">Shopping Now</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Slide Area End Here -->
                                <!-- Begin Single Slide Area -->
                                <div class="single-slide align-center-left animation-style-02 bg-2">
                                    <div class="slider-progress"></div>
                                    <div class="slider-content">
                                        <h5><span>5th Anniversary Sale</span> This Week</h5>
                                        <h2>Oversized Fashion, Streetwear, Vintage Shirts, and more</h2>
                                        <h3>Starting at <span>₱98</span></h3>
                                        <div class="default-btn slide-btn">
                                            <a class="links" href="#">Start Shopping</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Slide Area End Here -->
                                <!-- Begin Single Slide Area -->
                                <div class="single-slide align-center-left animation-style-01 bg-3">
                                    <div class="slider-progress"></div>
                                    <div class="slider-content">
                                        <h5>Sale Offer <span>10% Off</span> This Week</h5>
                                        <h2>Korean Plain Pullover Sweater </h2>
                                        <h3>Starting at <span>₱99</span></h3>
                                        <div class="default-btn slide-btn">
                                            <a class="links" href="#">Start Shopping</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single Slide Area End Here -->
                            </div>
                        </div>
                    </div>
                    <!-- Slider Area End Here -->
                    <!-- Begin Li Banner Area -->
                    <br>
                    <div class="col-lg-4 col-md-4 text-center pt-xs-30">
                        <div class="li-banner"> <br> <br>
                            <a href="#">
                                <img src="images/banner/stock-pic-shirt.jpg" alt="">
                            </a>
                        </div>
                        <div class="li-banner mt-15 mt-sm-30 mt-xs-30">
                            <a href="#">
                                <img src="images/banner/stock-pic-shoes.jpg" alt="">
                            </a>
                        </div>
                    </div>
                    <!-- Li Banner Area End Here -->
                </div>
            </div>
        </div>
        <!-- Slider With Banner Area End Here -->
        <!-- Begin Product Area -->
        <div class="product-area pt-60 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="li-product-tab">
                            <ul class="nav li-product-menu">
                                <li><a class="active" data-toggle="tab" href="#li-new-product"><span>New Arrival</span></a></li>
                                <li><a data-toggle="tab" href="#li-bestseller-product2"><span>Bestseller</span></a></li>
                                <li><a data-toggle="tab" href="#li-featured-product"><span>Featured Products</span></a></li>
                            </ul>
                        </div>
                        <!-- Begin Li's Tab Menu Content Area -->
                    </div>
                </div>
                <div class="tab-content">
                    <div id="li-new-product" class="tab-pane active show" role="tabpanel">
                        <div class="row">
                            <div class="product-active owl-carousel">

                                <?php
                                // Fetch product data
                                $productQuery = "SELECT * FROM product_list";
                                $productResult = $conn->query($productQuery);

                                if ($productResult->num_rows > 0) {
                                    while ($product = $productResult->fetch_assoc()) {
                                ?>
                                        <div class="col-lg-12">
                                            <!-- single-product-wrap start -->
                                            <div class="single-product-wrap">
                                                <div class="product-image">
                                                    <a href="single-product.php?product_id=<?php echo $product['product_id']; ?>">
                                                        <img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>">
                                                    </a>
                                                    <!-- <span class="sticker">New</span> -->
                                                </div>
                                                <div class="product_desc">
                                                    <div class="product_desc_info">
                                                        <div class="product-review">
                                                            <h5 class="manufacturer">
                                                                <a href="shop-left-sidebar.html"><?php echo $product['shop_name']; ?></a>
                                                            </h5>
                                                            <div class="rating-box">
                                                                <ul class="rating">
                                                                    <!-- <li><i class="fa fa-star-o"></i></li>
                                                                <li><i class="fa fa-star-o"></i></li>
                                                                <li><i class="fa fa-star-o"></i></li>
                                                                <li class="no-star"><i class="fa fa-star-o"></i></li>
                                                                <li class="no-star"><i class="fa fa-star-o"></i></li> -->
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <h4><a class="product_name" href="single-product.php"><?php echo $product['product_name']; ?></a></h4>
                                                        <div class="price-box">
                                                            <span class="new-price">₱<?php echo $product['product_price']; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="add-actions">
                                                        <ul class="add-actions-link">
                                                            <li class="add-cart active"><a href="#">View Product</a></li>
                                                            <!-- <li><a class="links-details" href="wishlist.html"><i class="fa fa-heart-o"></i></a></li> -->
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- single-product-wrap end -->
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo "No products found.";
                                }

                                // ... (Rest of your PHP code) ...
                                ?>
                            </div>
                        </div>
                    </div>

                    <div id="li-bestseller-product2" class="tab-pane" role="tabpanel">
                        <div class="row">
                            <div class="product-active owl-carousel">

                                <?php

                                // Fetch product data
                                $productQuery = "SELECT * FROM product_list";
                                $productResult = $conn->query($productQuery);

                                if ($productResult->num_rows > 0) {
                                    while ($product = $productResult->fetch_assoc()) {
                                ?>
                                        <div class="col-lg-12">
                                            <!-- single-product-wrap start -->
                                            <div class="single-product-wrap">
                                                <div class="product-image">
                                                    <a href="single-product.php?product_id=<?php echo $product['product_id']; ?>">
                                                        <img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>">
                                                    </a>
                                                    <!-- <span class="sticker">New</span> -->
                                                </div>
                                                <div class="product_desc">
                                                    <div class="product_desc_info">
                                                        <div class="product-review">
                                                            <!-- <h5 class="manufacturer">
                                                                <a href="shop-left-sidebar.html">Graphic Corner</a>
                                                            </h5> -->
                                                            <div class="rating-box">
                                                                <ul class="rating">
                                                                    <!-- <li><i class="fa fa-star-o"></i></li>
                                                                <li><i class="fa fa-star-o"></i></li>
                                                                <li><i class="fa fa-star-o"></i></li>
                                                                <li class="no-star"><i class="fa fa-star-o"></i></li>
                                                                <li class="no-star"><i class="fa fa-star-o"></i></li> -->
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <h4><a class="product_name" href="single-product.php"><?php echo $product['product_name']; ?></a></h4>
                                                        <div class="price-box">
                                                            ₱<span class="new-price"><?php echo $product['product_price']; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="add-actions">
                                                        <ul class="add-actions-link">
                                                            <li class="add-cart active"><a href="#">Add to cart</a></li>
                                                            <li><a class="links-details" href="wishlist.html"><i class="fa fa-heart-o"></i></a></li>
                                                            <!-- /    <li><a href="#" title="quick view" class="quick-view-btn" data-toggle="modal" data-target="#exampleModalCenter1"><i class="fa fa-eye"></i></a></li> -->
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- single-product-wrap end -->
                                        </div>




                                <?php
                                    }
                                } else {
                                    echo "No products found.";
                                }

                                // ... (Rest of your PHP code) ...
                                ?>
                            </div>
                        </div>
                    </div>

                    <div id="li-featured-product" class="tab-pane" role="tabpanel">
                        <div class="row">
                            <div class="product-active owl-carousel">

                                <?php
                                // Fetch product data
                                $productQuery = "SELECT * FROM product_list WHERE featured_product = 'Yes'";
                                $productResult = $conn->query($productQuery);

                                if ($productResult->num_rows > 0) {
                                    while ($product = $productResult->fetch_assoc()) {
                                ?>
                                        <div class="col-lg-12">
                                            <!-- single-product-wrap start -->
                                            <div class="single-product-wrap">
                                                <div class="product-image">
                                                    <a href="single-product.php?product_id=<?php echo $product['product_id']; ?>">
                                                        <img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>">
                                                    </a>
                                                    <!-- <span class="sticker">New</span> -->
                                                </div>
                                                <div class="product_desc">
                                                    <div class="product_desc_info">
                                                        <div class="product-review">
                                                            <h5 class="manufacturer">
                                                                <a href="shop-left-sidebar.html"><?php echo $product['shop_name']; ?></a>
                                                            </h5>
                                                            <div class="rating-box">
                                                                <ul class="rating">
                                                                    <!-- <li><i class="fa fa-star-o"></i></li>
                                                                <li><i class="fa fa-star-o"></i></li>
                                                                <li><i class="fa fa-star-o"></i></li>
                                                                <li class="no-star"><i class="fa fa-star-o"></i></li>
                                                                <li class="no-star"><i class="fa fa-star-o"></i></li> -->
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <h4><a class="product_name" href="single-product.php"><?php echo $product['product_name']; ?></a></h4>
                                                        <div class="price-box">
                                                            <span class="new-price">₱<?php echo $product['product_price']; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="add-actions">
                                                        <ul class="add-actions-link">
                                                            <li class="add-cart active"><a href="#">View Product</a></li>
                                                            <!-- <li><a class="links-details" href="wishlist.html"><i class="fa fa-heart-o"></i></a></li> -->
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- single-product-wrap end -->
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo "No products found.";
                                }

                                // ... (Rest of your PHP code) ...
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Area End Here -->
        <!-- Begin Li's Static Banner Area -->
        <!-- <div class="li-static-banner">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4 text-center">
                        <div class="single-banner">
                            <a href="#">
                                <img src="images/banner/1.png" alt="Li's Static Banner">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 text-center pt-xs-30">
                        <div class="single-banner">
                            <a href="#">
                                <img src="images/banner/2.png" alt="Li's Static Banner">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 text-center pt-xs-30">
                        <div class="single-banner">
                            <a href="#">
                                <img src="images/banner/3.png" alt="Li's Static Banner">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

    <!-- Hide muna Trending View -->
        <!-- <section class="product-area li-trending-product pt-60 pb-45">
            <div class="container">
                <div class="row">
                 
                    <div class="col-lg-12">
                        <div class="li-product-tab li-trending-product-tab">
                            <h2>
                                <span>Trending Products</span>
                            </h2>
                            <ul class="nav li-product-menu li-trending-product-menu">
                                <li><a class="active" data-toggle="tab" href="#home1"><span>Tops</span></a></li>
                                <li><a data-toggle="tab" href="#home2"><span>Bottoms</span></a></li>
                                <li><a data-toggle="tab" href="#home3"><span>Accessories</span></a></li>
                            </ul>
                        </div>
                       
                        <div class="tab-content li-tab-content li-trending-product-content">
                            <div id="home1" class="tab-pane show fade in active">
                                <div class="row">
                                    <div class="product-active owl-carousel">

                                        <?php

                                        // Fetch product data
                                        $productQuery = "SELECT * FROM product_list";
                                        $productResult = $conn->query($productQuery);

                                        if ($productResult->num_rows > 0) {
                                            while ($product = $productResult->fetch_assoc()) {
                                        ?>
                                                <div class="col-lg-12">
                                               
                                                    <div class="single-product-wrap">
                                                        <div class="product-image">
                                                            <a href="single-product.php?product_id=<?php echo $product['product_id']; ?>">
                                                                <img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>">
                                                            </a>
                                                          
                                                        </div>
                                                        <div class="product_desc">
                                                            <div class="product_desc_info">
                                                                <div class="product-review">
                                                                    
                                                                    <div class="rating-box">
                                                                        <ul class="rating">
                                                                           
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <h4><a class="product_name" href="single-product.php"><?php echo $product['product_name']; ?></a></h4>
                                                                <div class="price-box">
                                                                    ₱<span class="new-price"><?php echo $product['product_price']; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="add-actions">
                                                                <ul class="add-actions-link">
                                                                    <li class="add-cart active"><a href="#">Add to cart</a></li>
                                                                    <li><a class="links-details" href="wishlist.html"><i class="fa fa-heart-o"></i></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        } else {
                                            echo "No products found.";
                                        }

                                        // ... (Rest of your PHP code) ...
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                     
                    </div>
                  
                </div>
            </div>
        </section> -->
      


        <div class="content-wraper pt-60 pb-60">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Begin Li's Banner Area -->
                        <!-- <div class="single-banner shop-page-banner">
                            <a href="#">
                                <img src="images/bg-banner/2.png" alt="Li's Static Banner">
                            </a>
                        </div> -->
                        <!-- Li's Banner Area End Here -->
                        <!-- shop-top-bar start -->
                        <style>
                            .shop-top-bar {
                                display: flex;

                                justify-content: center
                            }
                        </style>
                        <div class="shop-top-bar mt-30">
                            <div class="shop-bar-inner">
                                <div class="product-view-mode">

                                    <h2>
                                        <span>Daily Discover</span>
                                    </h2>

                                </div>

                                <!-- <span>Showing 1 to 9 of 15</span> -->

                            </div>
                            <!-- product-select-box start -->
                            <!-- <div class="product-select-box">
                                    <div class="product-short">
                                        <p>Sort By:</p>
                                        <select class="nice-select">
                                            <option value="trending">Relevance</option>
                                            <option value="sales">Name (A - Z)</option>
                                            <option value="sales">Name (Z - A)</option>
                                            <option value="rating">Price (Low &gt; High)</option>
                                            <option value="date">Rating (Lowest)</option>
                                            <option value="price-asc">Model (A - Z)</option>
                                            <option value="price-asc">Model (Z - A)</option>
                                        </select>
                                    </div>
                                </div> -->
                            <!-- product-select-box end -->
                        </div>
                        <!-- shop-top-bar end -->
                        <!-- shop-products-wrapper start -->
                        <div class="shop-products-wrapper">
                            <div class="tab-content">
                                <div id="grid-view" class="tab-pane fade active show" role="tabpanel">
                                    <div class="product-area shop-product-area">


                                        <div class="row">
                                            <?php
                                            // Base product query
                                            $productQuery = "SELECT * FROM product_list pl";
                                            // Parameters for prepared statement
                                            $queryParams = [];
                                            $paramTypes = "";
                                            // Array to store WHERE conditions
                                            $whereConditions = [];

                                            // Handle Search Query Filtering
                                            if (isset($_GET['Search']) && !empty($_GET['Search'])) {
                                                $searchQuery = $conn->real_escape_string($_GET['Search']);
                                                $whereConditions[] = "(pl.product_name LIKE ? OR pl.product_description LIKE ?)";
                                                // Wildcards around the search term to find partial matches
                                                $queryParams[] = "%$searchQuery%";
                                                $queryParams[] = "%$searchQuery%";
                                                $paramTypes .= "ss"; // Two strings for binding
                                            }


                                            // Handle Shop Name Filtering
                                            if (isset($_GET['shop_name']) && !empty($_GET['shop_name'])) {
                                                $selectedShopName = $conn->real_escape_string($_GET['shop_name']);
                                                $whereConditions[] = "pl.shop_name = ?";
                                                $queryParams[] = $selectedShopName;
                                                $paramTypes .= "s";
                                            }

                                            // Handle product Typeclothes Type Filtering (Rental/Non-Rental)
                                            if (isset($_GET['type-filter']) && !empty($_GET['type-filter'])) {
                                                $selectedproduct_typeclothes = $conn->real_escape_string($_GET['type-filter']);
                                                $whereConditions[] = "pl.product_typeclothes = ?";
                                                $queryParams[] = $selectedproduct_typeclothes;
                                                $paramTypes .= "s";
                                            }


                                            // Handle product style Type Filtering
                                            if (isset($_GET['style-filter']) && !empty($_GET['style-filter'])) {
                                                $selectedproduct_style = $conn->real_escape_string($_GET['style-filter']);
                                                $whereConditions[] = "pl.product_style = ?";
                                                $queryParams[] = $selectedproduct_style;
                                                $paramTypes .= "s";
                                            }

                                            // Handle product category Type Filtering
                                            if (isset($_GET['category-filter']) && !empty($_GET['category-filter'])) {
                                                $selectedproduct_category = $conn->real_escape_string($_GET['category-filter']);
                                                $whereConditions[] = "pl.product_category = ?";
                                                $queryParams[] = $selectedproduct_category;
                                                $paramTypes .= "s";
                                            }

                                            // Handle Size Filtering
                                            if (isset($_GET['size']) && !empty($_GET['size'])) {
                                                $selectedSize = $conn->real_escape_string($_GET['size']);
                                                $productQuery .= " JOIN product_sizes ps ON pl.product_id = ps.product_id";
                                                $whereConditions[] = "ps.product_size = ?";
                                                $queryParams[] = $selectedSize;
                                                $paramTypes .= "s";
                                            }

                                            // Handle Color Filtering
                                            if (isset($_GET['color']) && !empty($_GET['color'])) {

                                                $selectedColors = $_GET['color'];

                                                if (!is_array($selectedColors)) {
                                                    $selectedColors = explode(',', $selectedColors);
                                                }

                                                // $productQuery .= " JOIN product_colors pc ON pl.product_id = pc.product_id";
                                                $productQuery .= " JOIN product_colors pc ON pl.product_id = pc.product_id";

                                                // Use a placeholder for IN clause conditions
                                                $whereConditions[] = "pc.product_color IN (" . implode(',', array_fill(0, count($selectedColors), '?')) . ")";
                                                $queryParams = array_merge($queryParams, $selectedColors); // Add selected colors to query params
                                                $paramTypes .= str_repeat('s', count($selectedColors));  // Add type specifiers for each color
                                            }

                                            // $whereConditions[] = "pl.product_type = 'Women Fashion'";
                                            $whereConditions[] = "(pl.product_type = 'Men Fashion' OR pl.product_type = 'Women Fashion')";

                                            // Pagination Settings
                                            $itemsPerPage = 12;
                                            $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                                            $offset = ($currentPage - 1) * $itemsPerPage;


                                            // Combine WHERE conditions
                                            if (!empty($whereConditions)) {
                                                $productQuery .= " WHERE " . implode(" AND ", $whereConditions);
                                            }
                                            // Randomize results
                                            $productQuery .= " ORDER BY RAND()";

                                            // Modify Query for Pagination
                                            $productQuery .= " LIMIT $offset, $itemsPerPage";


                                            // // Prepare and execute the query
                                            // $stmtProduct = $conn->prepare($productQuery);

                                            // Prepare and execute the query for products
                                            if ($stmtProduct = $conn->prepare($productQuery)) {
                                                // Dynamically bind parameters based on filters
                                                if (!empty($queryParams)) {
                                                    $stmtProduct->bind_param($paramTypes, ...$queryParams); // Unpack parameters
                                                }
                                                $stmtProduct->execute();
                                                $productResult = $stmtProduct->get_result();
                                            } else {
                                                die("Error preparing statement: " . $conn->error);
                                            }

                                            // Dynamically bind parameters based on filters
                                            if (!empty($queryParams)) {
                                                $stmtProduct->bind_param($paramTypes, ...$queryParams); // Unpack parameters
                                            }

                                            $stmtProduct->execute();
                                            $productResult = $stmtProduct->get_result();

                                            // Calculate Total Pages
                                            $totalItemsQuery = "SELECT COUNT(*) as total FROM product_list pl";
                                            $stmtTotal = $conn->prepare($totalItemsQuery);
                                            $stmtTotal->execute();
                                            $totalResult = $stmtTotal->get_result();
                                            $totalItems = $totalResult->fetch_assoc()['total'];
                                            $totalPages = ceil($totalItems / $itemsPerPage);

                                            // Fetch and display products (your existing code)
                                            if ($productResult->num_rows > 0) {
                                                while ($product = $productResult->fetch_assoc()) {
                                                    $productId = $product['product_id'];
                                                    $productImage = $product['product_image'];

                                            ?>

                                                    <div class="col-lg-3 col-md-3 col-sm-6 mt-40">
                                                        <div class="single-product-wrap">
                                                            <div class="product-image">
                                                                <a href="single-product.php?product_id=<?php echo $productId; ?>">
                                                                    <img src="<?php echo $productImage; ?>" alt="<?php echo $product['product_name']; ?>">
                                                                </a>
                                                            </div>
                                                            <div class="product_desc">
                                                                <div class="product_desc_info">
                                                                    <div class="product-review">
                                                                        <h5 class="manufacturer">
                                                                            <a href="single-shop.php?shop_name=<?php echo $product['shop_name']; ?>"><?php echo $product['shop_name']; ?></a>
                                                                        </h5>
                                                                        <h5 class="manufacturer">
                                                                            <a href="all_product.php?type-filter=<?php echo $product['product_typeclothes']; ?>">(<?php echo $product['product_typeclothes']; ?>)</a>
                                                                        </h5>
                                                                    </div>
                                                                    <h4 style=" -webkit-box-orient: vertical; -webkit-line-clamp: 2; display: -webkit-box; overflow: hidden; "><a class="product_name" href="single-product.php?product_id=<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></a></h4>
                                                                    <div class="price-box">
                                                                        <span class="new-price">₱<?php echo $product['product_price']; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="add-actions">
                                                                    <ul class="add-actions-link">
                                                                        <li class="add-cart active"><a href="single-product.php?product_id=<?php echo $product['product_id']; ?>">View Product</a></li>
                                                                        <!-- <li><a class="links-details" href="wishlist.html"><i class="fa fa-heart-o"></i></a></li> -->
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            } else {
                                                echo "No products found.";
                                            }
                                            ?>
                                        </div>


                                    </div>
                                </div>


                            </div>
                        </div>
                        <!-- shop-products-wrapper end -->
                    </div>
                </div>
            </div>
        </div>



        <!-- Begin Footer Area -->
        <?php include("include/footer.php"); ?>

        <!-- Footer Area End Here -->
        <!-- Begin Quick View | Modal Area -->








        <!-- Quick View | Modal Area End Here -->
    </div>
    <!-- Body Wrapper End Here -->
    <!-- jQuery-V1.12.4 -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/vendor/popper.min.js"></script>
    <!-- Bootstrap V4.1.3 Fremwork js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Ajax Mail js -->
    <script src="js/ajax-mail.js"></script>
    <!-- Meanmenu js -->
    <script src="js/jquery.meanmenu.min.js"></script>
    <!-- Wow.min js -->
    <script src="js/wow.min.js"></script>
    <!-- Slick Carousel js -->
    <script src="js/slick.min.js"></script>
    <!-- Owl Carousel-2 js -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- Magnific popup js -->
    <script src="js/jquery.magnific-popup.min.js"></script>
    <!-- Isotope js -->
    <script src="js/isotope.pkgd.min.js"></script>
    <!-- Imagesloaded js -->
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <!-- Mixitup js -->
    <script src="js/jquery.mixitup.min.js"></script>
    <!-- Countdown -->
    <script src="js/jquery.countdown.min.js"></script>
    <!-- Counterup -->
    <script src="js/jquery.counterup.min.js"></script>
    <!-- Waypoints -->
    <script src="js/waypoints.min.js"></script>
    <!-- Barrating -->
    <script src="js/jquery.barrating.min.js"></script>
    <!-- Jquery-ui -->
    <script src="js/jquery-ui.min.js"></script>
    <!-- Venobox -->
    <script src="js/venobox.min.js"></script>
    <!-- Nice Select js -->
    <script src="js/jquery.nice-select.min.js"></script>
    <!-- ScrollUp js -->
    <script src="js/scrollUp.min.js"></script>
    <!-- Main/Activator js -->
    <script src="js/main.js"></script>
</body>

<!-- index30:23-->

</html>