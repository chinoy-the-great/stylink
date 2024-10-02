<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];

// Fetch favorite outfit IDs
$stmt = $conn->prepare("SELECT * FROM favorite_outfits WHERE username = ? ORDER BY id DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$top_id = [];
$bottom_id = [];
while ($row = $result->fetch_assoc()) {
    $top_id[] = $row['top_id'];
    $bottom_id[] = $row['bottom_id'];
}
$stmt->close();


ob_end_flush();

?>
<!-- blog-2-column31:55-->

<head>
   
   
    <!-- Modernizr js -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>

    <link rel="shortcut icon" href="images/favicon.png" />
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">



</head>

<style>
    .lg-image img,
    .sm-image img {

        width: 100%;
        aspect-ratio: 1 / 1;
        /* Sets a 1:1 aspect ratio directly */
        object-fit: cover;
    }

    .lg-image {
        padding: 10px;
    }

    .lg-image-cloth,
    .show_image img,
    .li-blog-content img {
        width: 70%;
        aspect-ratio: 1 / 1;
        /* Sets a 1:1 aspect ratio directly */
        object-fit: cover;
        /* padding: 10px; */
    }

    .show_image_top,
    .show_image_bottom {
        display: flex;
        justify-content: center;
    }
</style>

<body>
    <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <!-- Begin Body Wrapper -->
    <div class="body-wrapper">
        <!-- Begin Header Area -->
        <?php include("include/header.php"); ?>
        <!-- Header Area End Here -->
        <!-- Begin Li's Breadcrumb Area -->
        <div class="breadcrumb-area">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Personal Wardrobe</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-wraper">
            <div class="container">
                <h4 class="card-title">Wardrobe Management</h4>
                <p class="card-description">sample short descript about sa wardrobe management nyo(short lang)</p>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link " id="home-tab1" href="customer_wardrobe.php" aria-controls="home-1" aria-selected="true">Product Wardrobe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="profile-tab1" href="customer_customizewardrobe.php" aria-controls="profile-1" aria-selected="false">Customize Styling</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active " id="profile-tab1" href="customer_favoritewardrobe.php" aria-controls="profile-1" aria-selected="false">Favorite Outfits</a>
                    </li>


                </ul>
                <br>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="true">Favorite Product Outfits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#top-1" role="tab" aria-controls="top-1" aria-selected="false">Favorite Custmize Tops</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="bottom-tab" data-toggle="tab" href="#bottom-1" role="tab" aria-controls="bottom-1" aria-selected="false">Favorite Custmize Bottom</a>
                    </li>

                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                        <div class="col-lg-12">
                            <div class="row li-main-content">
                                <?php if (isset($error)) : ?>
                                    <div class="error-message">
                                        <p><?php echo $error; ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($success)) : ?>
                                    <div class="success-message alert">
                                        <p><?php echo $success; ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php

                                $stmt = $conn->prepare("SELECT * FROM favorite_outfits WHERE username = ? AND archive_status = 'Active' ORDER BY id DESC");
                                $stmt->bind_param("s", $username);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Display favorite outfits
                                if ($result->num_rows > 0) {
                                    while ($outfit = $result->fetch_assoc()) {
                                        $topId = $outfit['top_id'];
                                        $bottomId = $outfit['bottom_id'];
                                        $ID = $outfit['id'];
                                        // Fetch top image data
                                        $stmtTop = $conn->prepare("SELECT clothes_image FROM wardrobe_top WHERE id = ?");
                                        $stmtTop->bind_param("i", $topId);
                                        $stmtTop->execute();
                                        $resultTop = $stmtTop->get_result();

                                        if ($resultTop->num_rows > 0) { // Check if a row was found
                                            $topData = $resultTop->fetch_assoc();
                                            $topImage = $topData['clothes_image'];
                                        } else {
                                            $topImage = null; // Set to null if no image found
                                        }
                                        $stmtTop->close();

                                        // Fetch bottom image data
                                        $stmtBottom = $conn->prepare("SELECT clothes_image FROM wardrobe_bottom WHERE id = ?");
                                        $stmtBottom->bind_param("i", $bottomId);
                                        $stmtBottom->execute();
                                        $resultBottom = $stmtBottom->get_result();

                                        if ($resultBottom->num_rows > 0) { // Check if a row was found
                                            $bottomData = $resultBottom->fetch_assoc();
                                            $bottomImage = $bottomData['clothes_image'];
                                        } else {
                                            $bottomImage = null; // Set to null if no image found
                                        }
                                        $stmtBottom->close();
                                ?>
                                        <div class="col-lg-4 col-md-6" style="margin-bottom: 30px;">
                                            <div class="li-blog-single-item pb-25">
                                                <div class="li-blog-content">
                                                    <a href="remove_to_favorite.php?id=<?php echo $ID; ?>">Trash</a>
                                                </div>
                                                <div class="li-blog-content">
                                                    <?php if ($topImage) : ?>
                                                        <img class="img-full" src="<?php echo $topImage; ?>" alt="Favorite Top">
                                                    <?php else : ?>
                                                        <span>Deleted Top</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="li-blog-content">
                                                    <?php if ($bottomImage) : ?>
                                                        <img class="img-full" src="<?php echo $bottomImage; ?>" alt="Favorite Bottom">
                                                    <?php else : ?>
                                                        <span>Deleted Bottom</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo "No products found.";
                                }
                                $stmt->close();
                                ?>


                                <!-- Begin Li's Pagination Area -->
                                <div class="col-lg-12">
                                    <div class="li-paginatoin-area text-center pt-25">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="li-pagination-box">
                                                    <li><a class="Previous" href="#">Previous</a></li>
                                                    <li class="active"><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a class="Next" href="#">Next</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Li's Pagination End Here Area -->
                            </div>
                        </div>
                    </div>
                   
                    <div class="tab-pane fade" id="top-1" role="tabpanel" aria-labelledby="top-tab">
                        <div class="col-lg-12">
                            <div class="row li-main-content">
                                <?php if (isset($error)) : ?>
                                    <div class="error-message">
                                        <p><?php echo $error; ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($success)) : ?>
                                    <div class="success-message alert">
                                        <p><?php echo $success; ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php

                                $stmt = $conn->prepare("SELECT * FROM favorite_tops WHERE username = ? AND archive_status = 'Active' ORDER BY id DESC");
                                $stmt->bind_param("s", $username);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Display favorite outfits
                                if ($result->num_rows > 0) {
                                    while ($outfit = $result->fetch_assoc()) {
                                        $topId = $outfit['top_id'];
                                        $ID = $outfit['id'];
                                      
                                        // Fetch bottom image data
                                        $stmtBottom = $conn->prepare("SELECT clothes_image FROM wardrobe_top WHERE id = ?");
                                        $stmtBottom->bind_param("i", $topId);
                                        $stmtBottom->execute();
                                        $resultBottom = $stmtBottom->get_result();

                                        if ($resultBottom->num_rows > 0) { // Check if a row was found
                                            $bottomData = $resultBottom->fetch_assoc();
                                            $topImage = $bottomData['clothes_image'];
                                        } else {
                                            $topImage = null; // Set to null if no image found
                                        }
                                        $stmtBottom->close();
                                ?>
                                        <div class="col-lg-4 col-md-6" style="margin-bottom: 30px;">
                                            <div class="li-blog-single-item pb-25">
                                                <div class="li-blog-content">
                                                    <a href="remove_to_favorite_top.php?id=<?php echo $ID; ?>">Trash</a>
                                                </div>
                                                <div class="li-blog-content">
                                                    <?php if ($topImage) : ?>
                                                        <img class="img-full" src="<?php echo $topImage; ?>" alt="Favorite Top">
                                                    <?php else : ?>
                                                        <span>Deleted Top</span>
                                                    <?php endif; ?>
                                                </div>
                                                <!-- <div class="li-blog-content">
                                                    <?php if ($bottomImage) : ?>
                                                        <img class="img-full" src="<?php echo $bottomImage; ?>" alt="Favorite Bottom">
                                                    <?php else : ?>
                                                        <span>Deleted Bottom</span>
                                                    <?php endif; ?>
                                                </div> -->
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo "No products found.";
                                }
                                $stmt->close();
                                ?>


                                <!-- Begin Li's Pagination Area -->
                                <div class="col-lg-12">
                                    <div class="li-paginatoin-area text-center pt-25">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="li-pagination-box">
                                                    <li><a class="Previous" href="#">Previous</a></li>
                                                    <li class="active"><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a class="Next" href="#">Next</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Li's Pagination End Here Area -->
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="bottom-1" role="tabpanel" aria-labelledby="bottom-tab">
                        <div class="col-lg-12">
                            <div class="row li-main-content">
                                <?php if (isset($error)) : ?>
                                    <div class="error-message">
                                        <p><?php echo $error; ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($success)) : ?>
                                    <div class="success-message alert">
                                        <p><?php echo $success; ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php

                                $stmt = $conn->prepare("SELECT * FROM favorite_bottoms WHERE username = ? AND archive_status = 'Active' ORDER BY id DESC");
                                $stmt->bind_param("s", $username);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Display favorite outfits
                                if ($result->num_rows > 0) {
                                    while ($outfit = $result->fetch_assoc()) {
                                        // $topId = $outfit['top_id'];
                                        $bottomId = $outfit['bottom_id'];
                                        $ID = $outfit['id'];
                                        // Fetch top image data
                                        // $stmtTop = $conn->prepare("SELECT clothes_image FROM wardrobe_top WHERE id = ?");
                                        // $stmtTop->bind_param("i", $topId);
                                        // $stmtTop->execute();
                                        // $resultTop = $stmtTop->get_result();

                                        // if ($resultTop->num_rows > 0) { // Check if a row was found
                                        //     $topData = $resultTop->fetch_assoc();
                                        //     $topImage = $topData['clothes_image'];
                                        // } else {
                                        //     $topImage = null; // Set to null if no image found
                                        // }
                                        // $stmtTop->close();

                                        // Fetch bottom image data
                                        $stmtBottom = $conn->prepare("SELECT clothes_image FROM wardrobe_bottom WHERE id = ?");
                                        $stmtBottom->bind_param("i", $bottomId);
                                        $stmtBottom->execute();
                                        $resultBottom = $stmtBottom->get_result();

                                        if ($resultBottom->num_rows > 0) { // Check if a row was found
                                            $bottomData = $resultBottom->fetch_assoc();
                                            $bottomImage = $bottomData['clothes_image'];
                                        } else {
                                            $bottomImage = null; // Set to null if no image found
                                        }
                                        $stmtBottom->close();
                                ?>
                                        <div class="col-lg-4 col-md-6" style="margin-bottom: 30px;">
                                            <div class="li-blog-single-item pb-25">
                                                <div class="li-blog-content">
                                                    <a href="remove_to_favorite_bottom.php?id=<?php echo $ID; ?>">Trash</a>
                                                </div>
                                                <!-- <div class="li-blog-content">
                                                    <?php if ($topImage) : ?>
                                                        <img class="img-full" src="<?php echo $topImage; ?>" alt="Favorite Top">
                                                    <?php else : ?>
                                                        <span>Deleted Top</span>
                                                    <?php endif; ?>
                                                </div> -->
                                                <div class="li-blog-content">
                                                    <?php if ($bottomImage) : ?>
                                                        <img class="img-full" src="<?php echo $bottomImage; ?>" alt="Favorite Bottom">
                                                    <?php else : ?>
                                                        <span>Deleted Bottom</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo "No products found.";
                                }
                                $stmt->close();
                                ?>


                                <!-- Begin Li's Pagination Area -->
                                <div class="col-lg-12">
                                    <div class="li-paginatoin-area text-center pt-25">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="li-pagination-box">
                                                    <li><a class="Previous" href="#">Previous</a></li>
                                                    <li class="active"><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a class="Next" href="#">Next</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Li's Pagination End Here Area -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>









        <!-- Begin Footer Area -->
        <div class="footer">
            <!-- Begin Footer Static Top Area -->
            <div class="footer-static-top">
                <div class="container">
                    <!-- Begin Footer Shipping Area -->
                    <div class="footer-shipping pt-60 pb-55 pb-xs-25">
                        <div class="row">
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-sm-55 pb-xs-55">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="images/shipping-icon/1.png" alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Free Delivery</h2>
                                        <p>And free returns. See checkout for delivery dates.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-sm-55 pb-xs-55">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="images/shipping-icon/2.png" alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Safe Payment</h2>
                                        <p>Pay with the world's most popular and secure payment methods.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-xs-30">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="images/shipping-icon/3.png" alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Shop with Confidence</h2>
                                        <p>Our Buyer Protection covers your purchasefrom click to delivery.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                            <!-- Begin Li's Shipping Inner Box Area -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-xs-30">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="images/shipping-icon/4.png" alt="Shipping Icon">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>24/7 Help Center</h2>
                                        <p>Have a question? Call a Specialist or chat online.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Li's Shipping Inner Box Area End Here -->
                        </div>
                    </div>
                    <!-- Footer Shipping Area End Here -->
                </div>
            </div>
            <!-- Footer Static Top Area End Here -->
            <!-- Begin Footer Static Middle Area -->
            <div class="footer-static-middle">
                <div class="container">
                    <div class="footer-logo-wrap pt-50 pb-35">
                        <div class="row">
                            <!-- Begin Footer Logo Area -->
                            <div class="col-lg-4 col-md-6">
                                <div class="footer-logo">
                                    <img src="images/menu/logo/1.jpg" alt="Footer Logo">
                                    <p class="info">
                                        We are a team of designers and developers that create high quality HTML Template & Woocommerce, Shopify Theme.
                                    </p>
                                </div>
                                <ul class="des">
                                    <li>
                                        <span>Address: </span>
                                        6688Princess Road, London, Greater London BAS 23JK, UK
                                    </li>
                                    <li>
                                        <span>Phone: </span>
                                        <a href="#">(+123) 123 321 345</a>
                                    </li>
                                    <li>
                                        <span>Email: </span>
                                        <a href="mailto://info@yourdomain.com">info@yourdomain.com</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Footer Logo Area End Here -->
                            <!-- Begin Footer Block Area -->
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="footer-block">
                                    <h3 class="footer-block-title">Product</h3>
                                    <ul>
                                        <li><a href="#">Prices drop</a></li>
                                        <li><a href="#">New products</a></li>
                                        <li><a href="#">Best sales</a></li>
                                        <li><a href="#">Contact us</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Footer Block Area End Here -->
                            <!-- Begin Footer Block Area -->
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="footer-block">
                                    <h3 class="footer-block-title">Our company</h3>
                                    <ul>
                                        <li><a href="#">Delivery</a></li>
                                        <li><a href="#">Legal Notice</a></li>
                                        <li><a href="#">About us</a></li>
                                        <li><a href="#">Contact us</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Footer Block Area End Here -->
                            <!-- Begin Footer Block Area -->
                            <div class="col-lg-4">
                                <div class="footer-block">
                                    <h3 class="footer-block-title">Follow Us</h3>
                                    <ul class="social-link">
                                        <li class="twitter">
                                            <a href="https://twitter.com/" data-toggle="tooltip" target="_blank" title="Twitter">
                                                <i class="fa fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li class="rss">
                                            <a href="https://rss.com/" data-toggle="tooltip" target="_blank" title="RSS">
                                                <i class="fa fa-rss"></i>
                                            </a>
                                        </li>
                                        <li class="google-plus">
                                            <a href="https://www.plus.google.com/discover" data-toggle="tooltip" target="_blank" title="Google Plus">
                                                <i class="fa fa-google-plus"></i>
                                            </a>
                                        </li>
                                        <li class="facebook">
                                            <a href="https://www.facebook.com/" data-toggle="tooltip" target="_blank" title="Facebook">
                                                <i class="fa fa-facebook"></i>
                                            </a>
                                        </li>
                                        <li class="youtube">
                                            <a href="https://www.youtube.com/" data-toggle="tooltip" target="_blank" title="Youtube">
                                                <i class="fa fa-youtube"></i>
                                            </a>
                                        </li>
                                        <li class="instagram">
                                            <a href="https://www.instagram.com/" data-toggle="tooltip" target="_blank" title="Instagram">
                                                <i class="fa fa-instagram"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Begin Footer Newsletter Area -->
                                <div class="footer-newsletter">
                                    <h4>Sign up to newsletter</h4>
                                    <form action="#" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="footer-subscribe-form validate" target="_blank" novalidate>
                                        <div id="mc_embed_signup_scroll">
                                            <div id="mc-form" class="mc-form subscribe-form form-group">
                                                <input id="mc-email" type="email" autocomplete="off" placeholder="Enter your email" />
                                                <button class="btn" id="mc-submit">Subscribe</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- Footer Newsletter Area End Here -->
                            </div>
                            <!-- Footer Block Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Static Middle Area End Here -->
            <!-- Begin Footer Static Bottom Area -->
            <div class="footer-static-bottom pt-55 pb-55">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Begin Footer Links Area -->
                            <div class="footer-links">
                                <ul>
                                    <li><a href="#">Online Shopping</a></li>
                                    <li><a href="#">Promotions</a></li>
                                    <li><a href="#">My Orders</a></li>
                                    <li><a href="#">Help</a></li>
                                    <li><a href="#">Customer Service</a></li>
                                    <li><a href="#">Support</a></li>
                                    <li><a href="#">Most Populars</a></li>
                                    <li><a href="#">New Arrivals</a></li>
                                    <li><a href="#">Special Products</a></li>
                                    <li><a href="#">Manufacturers</a></li>
                                    <li><a href="#">Our Stores</a></li>
                                    <li><a href="#">Shipping</a></li>
                                    <li><a href="#">Payments</a></li>
                                    <li><a href="#">Warantee</a></li>
                                    <li><a href="#">Refunds</a></li>
                                    <li><a href="#">Checkout</a></li>
                                    <li><a href="#">Discount</a></li>
                                    <li><a href="#">Refunds</a></li>
                                    <li><a href="#">Policy Shipping</a></li>
                                </ul>
                            </div>
                            <!-- Footer Links Area End Here -->
                            <!-- Begin Footer Payment Area -->
                            <div class="copyright text-center">
                                <a href="#">
                                    <img src="images/payment/1.png" alt="">
                                </a>
                            </div>
                            <!-- Footer Payment Area End Here -->
                            <!-- Begin Copyright Area -->
                            <div class="copyright text-center pt-25">
                                <span><a target="_blank" href="https://www.templateshub.net">Templates Hub</a></span>
                            </div>
                            <!-- Copyright Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Static Bottom Area End Here -->
        </div>
        <!-- Footer Area End Here -->
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

    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="vendors/js/vendor.bundle.addons.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/misc.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <script src="js/dragula.js"></script>
    <script src="js/dropify.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/jquery-file-upload.js"></script>
    <script src="js/data-table.js"></script>

</body>

<!-- blog-2-column31:55-->

</html>