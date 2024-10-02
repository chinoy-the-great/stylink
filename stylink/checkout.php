<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");


if (!isset($_SESSION["username"])) {
    // Redirect to the appropriate page for logged-in users
    header("Location: user_login.php");
    exit;
}
$username = $_SESSION["username"];
// Fetch cart items and product details for the specified user
$stmt = $conn->prepare("
    SELECT cc.*, pl.product_image, pl.product_stocks 
    FROM customer_cart cc
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


// Check if the user has an address stored
$addressCheckStmt = $conn->prepare("SELECT * FROM customer_address WHERE username = ?");
$addressCheckStmt->bind_param("s", $username);
$addressCheckStmt->execute();
$addressResult = $addressCheckStmt->get_result();
$hasAddress = $addressResult->num_rows > 0;




// Fetch the cart items for the logged-in user
$checkCartStmt = $conn->prepare("SELECT COUNT(*) FROM customer_cart WHERE username = ?");
$checkCartStmt->bind_param("s", $username);
$checkCartStmt->execute();
$checkCartStmt->bind_result($cartItemCount);
$checkCartStmt->fetch();
$checkCartStmt->close();




ob_end_flush();

include("include/head.php");
?>
<link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/all.min.css">
<link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
<!-- endinject -->
<!-- inject:css -->
<!-- <link rel="stylesheet" href="css/style1.css"> -->



<link rel="shortcut icon" href="images/favicon.png" />

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
                        <li class="active">Checkout</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!--Checkout Area Strat-->
        <div class="checkout-area pt-60 pb-30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-12">

                        <?php if ($hasAddress) : // If the user has an address, show the address information 
                        ?>

                            <?php
                            $addressRow = $addressResult->fetch_assoc();
                            ?>
                            <div class="li-blog-blockquote">
                                <blockquote style="padding:20px;margin: 0px 0;">
                                    <h5>SHIPPING ADDRESS</h5>
                                    <p>
                                        <b><?php echo $addressRow['first_name'] . ' ' . $addressRow['last_name']; ?></b>
                                        <?php echo $addressRow['phone_no']; ?><br>
                                        <?php echo $addressRow['full_address']; ?><br>
                                        <?php echo $addressRow['barangay'] . ', ' . $addressRow['municipality']; ?><br>
                                        <?php echo $addressRow['province']; ?>
                                    </p>
                                </blockquote>
                            </div>
                        <?php else : // If the user doesn't have an address, show the form  isset($_SESSION["username"])
                        ?>

                            <form action="customer_address.php" method="post">
                                <div class="checkbox-form">
                                    <h3>SHIPPING ADDRESS</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="checkout-form-list">
                                                <label>First Name <span class="required">*</span></label>
                                                <input placeholder="" name="first_name" type="text" required>

                                                <input type="hidden" name="username" value="<?php echo $username; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="checkout-form-list">
                                                <label>Last Name <span class="required">*</span></label>
                                                <input placeholder="" name="last_name" type="text" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="checkout-form-list">
                                                <label>Phone Number <span class="required">*</span></label>
                                                <input placeholder="eg. 09123456789" name="phone_no" max="11" type="tel" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="country-select clearfix">
                                                <label>State/Province <span class="required">*</span></label>
                                                <input readonly type="text" name="province" class="form-control" id="exampleInputName1" placeholder="Province" value="Laguna">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="country-select clearfix">
                                                <label>City/Municipality <span class="required">*</span></label>
                                                <select class="js-example-basic-single1 w-100" name="customer_municipal" id="municipalities" name="municipality" class="form-control" required>
                                                </select>
                                                <option value="" disabled selected hidden>Choose...</option>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="country-select clearfix">
                                                <label>Barangay/Sitio <span class="required">*</span></label>
                                                <select class="js-example-basic-single1 w-100" name="customer_barangay" id="barangays" name="barangay" class="form-control" required>
                                                    <option value="" disabled selected hidden>Choose...</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="checkout-form-slist">
                                                <label>Address <span class="required">*</span></label>
                                                <input placeholder="Street address" name="full_address" type="text" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-button-payment">
                                        <input type="submit" placeholder="Add Address">
                                    </div>


                                    <!-- <div class="different-address">
                                    <div class="order-notes">
                                        <div class="checkout-form-list">
                                            <label>Order Notes</label>
                                            <textarea id="checkout-mess" cols="30" rows="10" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                        </div>
                                    </div>
                                </div> -->
                                </div>
                            </form>

                        <?php endif; ?>




                    </div>
                    <div class="col-lg-8 col-12">
                        <style>
                            .product-image1 {
                                /* position: relative; */
                            }

                            .product-image1 a img {
                                width: 50%;
                            }

                            .product-image1 {
                                width: 15%;
                            }

                            .product-image1 img {
                                /* width: auto; */
                                /* aspect-ratio: 1 / 1; */

                                /* Sets a 1:1 aspect ratio directly */
                                object-fit: cover;
                            }

                            .table td,
                            .table th {
                                padding: .75rem;
                                vertical-align: middle;
                                border-top: 1px solid #dee2e6;
                            }

                            .cart-plus-minus1 {
                                float: left;
                                margin-right: 15px;
                                position: relative;
                                width: 76px;
                                text-align: left;
                            }

                            /* Quantity Control Container */
                            .quantity-control {
                                display: flex;
                                align-items: center;
                                background-color: #f5f5f5;
                                border-radius: 8px;
                                padding: 8px;
                                width: 120px;
                            }

                            .quantity-control input {
                                height: 20px;
                            }

                            /* Decrease/Increase Buttons */
                            .decrease-btn,
                            .increase-btn {
                                background-color: transparent;
                                border: none;
                                font-size: 18px;
                                cursor: pointer;
                                transition: color 0.2s ease;
                            }

                            .decrease-btn:hover,
                            .increase-btn:hover {
                                color: #007bff;
                                /* Or your preferred accent color */
                            }

                            /* Quantity Input */
                            .cart-plus-minus-box {
                                width: 40px;
                                text-align: center;
                                border: none;
                                font-size: 16px;
                                margin: 0 8px;
                                background-color: transparent;
                                /* Make it blend with the container */
                            }

                            /* Optional: Focus Style */
                            .cart-plus-minus-box:focus {
                                outline: none;
                                box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.5);
                                /* Or your preferred focus color */
                            }
                        </style>
                        <form action="checkout_order.php" method="post">
                            <div class="your-order">
                                <h3>Your order</h3>
                                <div class="your-order-table table-responsive">

                                    <?php if ($cartItemCount > 0) : ?>
                                        <table class="table1" id="order-listing">
                                            <thead>
                                                <tr>
                                                    <th class="cart-product-name"></th>
                                                    <th class="cart-product-name" style="text-align: start;">Product</th>
                                                    <th class="cart-product-total">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                                <?php foreach ($categories as $category) : ?>
                                                    <tr class="cart_item">
                                                        <td class="product-image1">
                                                            <?php

                                                            $selectedImage1 = $category['product_id'];
                                                            $selectedColor = $category['product_color'];
                                                         

                                                            // Fetch existing categories for this user
                                                            $stmt1 = $conn->prepare("SELECT * FROM product_colors WHERE product_id = ? AND product_color = ? ");
                                                            $stmt1->bind_param("ss", $selectedImage1, $selectedColor);
                                                            $stmt1->execute();
                                                            $result1 = $stmt1->get_result();

                                                            $categories = [];
                                                            while ($row1 = $result1->fetch_assoc()) {
                                                                $productImage = $row1['product_image'];
                                                                // $productStocks = $row1['product_stocks'];
                                                            }

                                                            $stmt1->close();


                                                            $selectedStocks = $category['product_stocks'];
                                                            // Fetch existing categories for this user
                                                            $stmt2 = $conn->prepare("SELECT * FROM product_colors WHERE product_id = ? AND product_color = ? ");
                                                            $stmt2->bind_param("ss", $selectedImage1, $selectedColor);
                                                            $stmt2->execute();
                                                            $result1 = $stmt2->get_result();

                                                            $categories = [];
                                                            while ($row1 = $result1->fetch_assoc()) {
                                                                $productImage = $row1['product_image'];
                                                                // $productStocks = $row1['product_stocks'];
                                                            }
                                                            $stmt2->close();
                                                            ?>
                                                            <a href="single-product.php?product_id=<?php echo $productImage; ?>">
                                                                <img src="<?php echo $productImage; ?>" alt="<?php echo $productImage; ?>">

                                                            </a>
                                                            <?php
                                                            // $conn->close();
                                                            ?>
                                                        </td>
                                                        <td class="cart-product-name " style="text-align: start;">
                                                            <!-- <input type="text" name="product_name" value="<php echo $category['product_name']; ?>">
                                                        <input type="text" name="product_price" value="<php echo $category['product_price']; ?>">
                                                        <input type="text" name="product_quantity" value="<php echo $category['product_quantity']; ?>">
                                                        <input type="text" name="product_id" value="<php echo $productId; ?>"> -->


                                                            <input type="hidden" name="product_id[]" value="<?php echo $category['product_id']; ?>">
                                                            <input type="hidden" name="product_id2[]" value="<?php echo $category['product_id']; ?>">
                                                            <input type="hidden" name="product_name[]" value="<?php echo $category['product_name']; ?>">
                                                            <input type="hidden" name="product_price[]" value="<?php echo $category['product_price']; ?>">
                                                            <input type="hidden" name="product_quantity[]" value="<?php echo $category['product_quantity']; ?>">
                                                            <input type="hidden" name="shop_name[]" value="<?php echo $category['shop_name']; ?>">


                                                            <strong class="product-quantity">
                                                                <?php echo $category['product_name']; ?></strong> <br>
                                                            qty:<?php echo $category['product_quantity']; ?>
                                                        </td>
                                                        <td class="cart-product-total">

                                                            <span class="amount" id="total_price_<?php echo $productId; ?>">₱
                                                                <?php $total = $category['product_price'] * $category['product_quantity'];
                                                                $overallTotal += $total;
                                                                echo number_format($total, 2);
                                                                ?>
                                                            </span>
                                                            <input type="hidden" name="total_price" value=" <?php $total = $category['product_price'] * $category['product_quantity'];
                                                                                                            $overallTotal += $total;
                                                                                                            echo number_format($total, 2);
                                                                                                            ?>">

                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>





                                            </tbody>
                                            <tfoot>
                                                <!-- <tr class="cart-subtotal">
                                        <th></th>
                                        <th>Cart Subtotal</th>
                                            <td><span class="amount">£215.00</span></td>
                                        </tr> -->
                                                <tr class="order-total">
                                                    <th></th>
                                                    <th>Order Total</th>
                                                    <td><strong><span class="amount"><span id="overall_total"></span></span></strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>


                                </div>

                                <div class="payment-method">
                                    <h5>Mode of Payment</h5>
                                    <select class="js-example-basic-single1 w-100" name="modePayment" id="modePayment" class="form-control" required>
                                        <option value="" disabled selected hidden>Choose...</option>
                                        <option value="Cash on Delivery">Cash on Delivery</option>
                                        <option value="G-Cash Wallet">G-Cash Wallet</option>
                                    </select>
                                    <br>
                                    <br>
                                    <div class="payment-accordion">
                                        <div id="accordion">
                                            <div class="card">
                                                <div class="card-header" id="#payment-1">
                                                    <h5 class="panel-title">
                                                        <a class="" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                            Cash On Delivery(COD)
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapseOne" class="collapse" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <p>Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <!-- <div class="card-header" id="#payment-2">
                                                    <h5 class="panel-title">
                                                        <a class="collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                            G-Cash
                                                        </a>
                                                    </h5>
                                                </div> -->
                                                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                                    <div class="card-body">
                                                        <p>Note: Enter your reference number for approval of the Item. THank You!</p>
                                                      
                                                        <input name="reference_no" type="text" placeholder="enter your reference no here....">
                                                        <img src="images/gcash.png" width="50%">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="order-button-payment">
                                            <input type="hidden" name="product_username" value="<?php echo $username; ?>">
                                            <input type="hidden" name="mode_payment" id="paymentDisplay" readonly>
                                            <input readonly name="total_overallprice" type="hidden" id="overall_total1">
                                            <input readonly name="total_count" type="hidden" value="<?php echo $totalCount; ?>">



                                            <?php

                                            $checkSellerStmt = $conn->prepare("SELECT username FROM customer_address WHERE username = ?"); // or use username if that's what you use for login
                                            $checkSellerStmt->bind_param("s", $_SESSION["username"]); // Assuming you have the username in the session
                                            $checkSellerStmt->execute();
                                            $checkSellerResult = $checkSellerStmt->get_result();

                                            if ($checkSellerResult->num_rows > 0) {
                                                // Redirect if already registered as a seller
                                                // header("Location: seller_register1.php"); // Replace with your seller dashboard page
                                                // exit;
                                            ?>
                                                <input value="Place order" type="submit">


                                            <?php } else {
                                            ?>
                                                <input style="cursor:not-allowed;" disabled value="Place order" type="submit">
                                                <!-- <li><span></span><a href="seller_register.php">Be A Seller</a></li> -->
                                            <?php
                                            } ?>
                                            <!-- <button type="submit">Place order</button> -->
                                        </div>
                                    </div>
                                </div>
                            <?php else : ?>
                                <span>No Product Added Yet</span>
                            <?php endif; ?>
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const modePaymentSelect = document.getElementById('modePayment');
                                const paymentDisplayInput = document.getElementById('paymentDisplay');

                                modePaymentSelect.addEventListener('change', function() {
                                    paymentDisplayInput.value = this.value;
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!--Checkout Area End-->
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
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/misc.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <script src="js/dropify.js"></script>
    <script src="js/data-table.js"></script>
    <script src="js/form-validation.js"></script>
    <!-- endinject -->



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


    <!-- Get municipalities and Barangays -->

    <script>
        $(document).ready(function() {
            // Load municipalities on page load
            loadMunicipalities();

            // When the municipality selection changes:
            $('#municipalities').on('change', function() {
                var municipality = $(this).val();
                if (municipality) {
                    loadBarangays(municipality);
                } else {
                    $('#barangays').empty().append('<option value="">Select Barangay</option>');
                }
            });
        });

        function loadMunicipalities() {
            $.ajax({
                url: 'get_municipalities.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#municipalities').append($('<option>', {
                        value: '',
                        text: 'Select Municipality'
                    }));
                    $.each(data, function(index, name) {
                        $('#municipalities').append($('<option>', {
                            value: name,
                            text: name
                        }));
                    });
                }
            });
        }

        function loadBarangays(municipality) {
            $.ajax({
                url: 'get_barangays.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    municipality: municipality
                },
                success: function(data) {
                    $('#barangays').empty().append('<option value="">Select Barangay</option>');
                    $.each(data, function(index, name) {
                        $('#barangays').append($('<option>', {
                            value: name,
                            text: name
                        }));
                    });
                }
            });
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decreaseBtns = document.querySelectorAll('.decrease-btn');
            const increaseBtns = document.querySelectorAll('.increase-btn');
            const overallTotalSpan = document.getElementById('overall_total');
            const overallTotalSpan1 = document.getElementById('overall_total1');

            // Calculate initial overall total
            updateOverallTotal();

            decreaseBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    updateQuantity(this, -1);
                });
            });

            increaseBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    updateQuantity(this, 1);
                });
            });

            const initialBtn = document.querySelector('.increase-btn') // Or any button element
            updateQuantity(initialBtn, 0); // Call the function with change = 0 for initial calculation


            function updateQuantity(btn, change) {
                const input = btn.parentElement.querySelector('.cart-plus-minus-box');
                // Calculate and format total price (modified)
                const productPriceSpan = btn.parentElement.parentElement.previousElementSibling.querySelector('span'); // Find the correct product_price span
                const totalPriceSpan = btn.parentElement.parentElement.nextElementSibling.querySelector('span'); // Find the correct total_price span


                // Get stock information and current quantity
                const maxStock = parseInt(input.dataset.productStocks);
                let quantity = parseInt(input.value);

                // Calculate new quantity, ensuring it stays within limits
                quantity += change;
                quantity = Math.max(1, quantity); // Minimum of 1
                quantity = Math.min(maxStock, quantity); // Maximum of available stock

                input.value = quantity;

                // Calculate and format total price
                // const productPrice = parseFloat(productPriceSpan.dataset.price);
                const productPrice = parseFloat(productPriceSpan.dataset.price);
                const totalPrice = productPrice * quantity;
                const formattedTotalPrice = new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 2
                }).format(totalPrice);

                totalPriceSpan.textContent = formattedTotalPrice;

                // Send AJAX request to update the database (if needed)
                const productId = input.dataset.productId;
                const productColor = input.dataset.productColor;
                const productSize = input.dataset.productSize;

                fetch('update_cart_quantity.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        productId: productId,
                        productColor: productColor,
                        productSize: productSize, // Send product size in the request
                        quantity: quantity
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                updateOverallTotal();
            }



            document.getElementById('overall_total').textContent = "<?php echo number_format($overallTotal, 2); ?>";
            document.getElementById('overall_total1').value = "<?php echo number_format($overallTotal, 2); ?>";

            function updateOverallTotal() {
                let overallTotal = 0;
                document.querySelectorAll('[id^="total_price_"]').forEach(span => {
                    overallTotal += parseFloat(span.textContent.replace(/[^\d.]/g, '')); // Extract numeric value from formatted price
                });

                // Format the overall total
                overallTotalSpan.textContent = new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 2
                }).format(overallTotal);

                overallTotalSpan1.value = new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 2
                }).format(overallTotal);

            }


        });
    </script>

</body>

<!-- checkout31:27-->

</html>