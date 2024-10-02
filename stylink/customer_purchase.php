<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");

if (!isset($_SESSION["username"])) {
    // Redirect to the appropriate page for logged-in users
    header("Location: user_login.php");
    exit;
}
$username = $_SESSION["username"];

// Fetch cart items and product details for the specified user
$stmt = $conn->prepare("
    SELECT cc.*, pl.product_image, pl.product_stocks 
    FROM checkout_order cc
    JOIN product_list pl ON cc.product_id = pl.product_id
    WHERE cc.username = ?
");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$categories = [];
$totalCount = 0;  // Initialize total count
$overallTotal = 0; // Initialize overall total
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
    $productId = $row['product_id'];
    $productPrice = $row['product_price'];
    $productQty = $row['product_quantity'];

    $totalCount += $productQty;  // Add the quantity to the total count
    // $overallTotal += $productPrice * $productQty;

    $overallTotal = 0; // Initialize overall total

}

// Fetch the cart items for the logged-in user
$checkCartStmt = $conn->prepare("SELECT COUNT(*) FROM customer_cart WHERE username = ?");
$checkCartStmt->bind_param("s", $username);
$checkCartStmt->execute();
$checkCartStmt->bind_result($cartItemCount);
$checkCartStmt->fetch();
$checkCartStmt->close();



ob_end_flush();

?>

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
                        <li class="active">My Account</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!-- Begin Li's Main Blog Page Area -->
        <div class="li-main-blog-page li-main-blog-details-page pt-60 pb-60 pb-sm-45 pb-xs-45">
            <div class="container">
                <div class="row">
                    <!-- Begin Li's Blog Sidebar Area -->
                    <div class="col-lg-3 order-lg-1 order-2">
                        <div class="li-blog-sidebar-wrapper">
                            <div class="li-blog-sidebar">
                                <div class="li-sidebar-search-form">
                                    <!-- <form action="#">
                                        <input type="text" class="li-search-field" placeholder="search here">
                                        <button type="submit" class="li-search-btn"><i class="fa fa-search"></i></button>
                                    </form> -->
                                </div>
                            </div>
                            <div class="li-blog-sidebar pt-25">
                                <h4 class="li-blog-sidebar-title">My Account</h4>
                                <ul class="li-blog-archive">
                                    <li><a href="customer_profile.php">My Profile</a></li>
                                    <li><a href="customer_purchase.php">My Purchase</a></li>

                                </ul>
                            </div>

                        </div>
                    </div>
                    <!-- Li's Blog Sidebar Area End Here -->
                    <!-- Begin Li's Main Content Area -->
                    <div class="col-lg-9 order-lg-2 order-1">
                        <div class="row li-main-content">
                            <div class="col-lg-12">

                                <?php
                                $username = $_SESSION['username'];

                                // Fetch unique shop names and corresponding order details
                                $orderStmt = $conn->prepare("SELECT DISTINCT shop_name, transaction_id, total_overallprice, checkout_status  FROM checkout_order WHERE username = ? ORDER BY transaction_id DESC");
                                $orderStmt->bind_param("s", $username);
                                $orderStmt->execute();
                                $uniqueShopResult = $orderStmt->get_result();
                                ?>

                                <div class="li-comment-section">
                                    <ul>
                                        <?php while ($uniqueShop = $uniqueShopResult->fetch_assoc()) :
                                            $shopName = $uniqueShop['shop_name'];
                                            $transactionId = $uniqueShop['transaction_id'];
                                            $total_overallprice = $uniqueShop['total_overallprice'];
                                            $checkout_status = $uniqueShop['checkout_status'];

                                            // Fetch products for this shop and transaction
                                            $productsStmt = $conn->prepare("SELECT * FROM checkout_order WHERE shop_name = ? AND transaction_id = ? AND username = ?");
                                            $productsStmt->bind_param("sss", $shopName, $transactionId, $username);
                                            $productsStmt->execute();
                                            $productsResult = $productsStmt->get_result();

                                            // Initialize total price for the shop
                                            $shopTotalPrice = 0;

                                        ?>
                                            <h5 class="reply-btn1 pt-15 pt-xs-5" style="text-align: end; text-transform: uppercase; color: #457b75;">
                                                <?php echo $checkout_status; ?>
                                            </h5>
                                            <li>
                                                <div class="author-avatar pt-15">
                                                    <img src="images/product-details/user.png" alt="User">
                                                </div>
                                                <div class="comment-body pl-15">
                                                    <span class="reply-btn pt-15 pt-xs-5"><a href="single-shop.php?shop name=<?php echo $shopName; ?>">View Shop</a></span>
                                                    <!-- <span class="reply-btn pt-15 pt-xs-5"><a href="#">View Shop</a></span> -->


                                                    <a href="single-shop.php?shop_name=<?php echo $shopName; ?>">
                                                        <h5 style="color: #404040;" class="comment-author pt-15">
                                                            <?php echo $shopName; ?>
                                                        </h5>
                                                    </a>
                                                    <!-- <span class="reply-btn pt-15 pt-xs-5">
                                                        Transaction ID: <php echo $transactionId; ?>
                                                    </span> -->

                                                    <ul class="comment-list">
                                                        <?php while ($product = $productsResult->fetch_assoc()) :

                                                            // Calculate total for this product
                                                            $productTotal = $product['product_price'] * $product['product_quantity'];
                                                            // Add to the shop's total
                                                            $shopTotalPrice += $productTotal;

                                                        ?>
                                                            <li style="margin-bottom:0px;
                                                             border-color:white; border-bottom: 1px solid #dddddd;">
                                                                <div class="col-lg-12">

                                                                    <div class="col-lg-12">
                                                                        <div class="row">


                                                                            <div class="col-md-8">

                                                                                <!-- <img src="<php echo $product['product_image']; ?>" alt="User"> -->
                                                                                <h4 style="color: #404040;" class="comment-author pt-15"><a href="single-product.php?product_id=<?php echo $product['product_id']; ?>">
                                                                                        <?php echo $product['product_name']; ?></a>
                                                                                </h4>
                                                                                <span style="color: #404040;">Price: ₱<?php echo number_format($product['product_price'], 2); ?></span><br>
                                                                                <span style="color: #404040;">Quantity: <?php echo $product['product_quantity']; ?></span>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <!-- <span class="reply-btn1 pt-15 pt-xs-5">
                                                                                    Product Status: <php echo $product['checkout_status']; ?>
                                                                                </span> -->

                                                                                <br>
                                                                                <br>

                                                                                <h3 style="color: #404040;" class="comment-author pt-15">

                                                                                    ₱<?php echo number_format($product['total_price'], 2); ?>
                                                                            </div>
                                                                        </div>


                                                                    </div>

                                                            </li>

                                                        <?php endwhile;

                                                        $productsStmt->close(); ?>

                                                    </ul>
                                                    <div class="shop-total" style="text-align: end;">
                                                        <br>
                                                        <h4 style="color:#8c8c8c;">Order Total: <span style="color:#457b75;">₱<?php echo number_format($shopTotalPrice, 2); ?></span></h4>
                                                    </div>
                                                </div>
                                            </li>

                                            <!--  -->
                                        <?php endwhile;
                                        $orderStmt->close(); ?>

                                    </ul>

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Li's Main Content Area End Here -->
                </div>
            </div>
        </div>
        <!-- Li's Main Blog Page Area End Here -->
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

            <!-- Footer Static Middle Area End Here -->
            <!-- Begin Footer Static Bottom Area -->
            <!-- Begin Footer Area -->
            <?php include("include/footer.php"); ?>
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
</body>

<!-- blog-details-left-sidebar32:00-->

</html>