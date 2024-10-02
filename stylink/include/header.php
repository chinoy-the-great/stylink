<?php
?>
<style>
    .add-to-cart1 {
        background: #3a3f51;
        color: white;
        border: none;
        font-size: 14px;
        /* color: #242424; */
        position: relative;
        /* background: #fed700; */
        cursor: pointer;
        font-weight: 500;
        text-transform: capitalize;
        padding: 13px 70px;
        border-radius: 2px;
        transition: all 0.3s ease-in-out;
    }

    .add-to-cart1:hover {
        background-color: #5a8679;
    }

    .add-to-cart-outline {
        color: #457b75;
        background-color: transparent;
        background-image: none;
        border-color: #457b75;
        font-size: 14px;
        position: relative;
        border: 1px solid;
        cursor: pointer;
        font-weight: 500;
        text-transform: capitalize;
        padding: 13px 70px;
        border-radius: 2px;
        transition: all 0.3s ease-in-out;
    }

    .add-to-cart-outline:hover {
        background-color: #B9BB8B;
        color: white;
    }

    .li-button-dark {
        background: #3a3f51;
        color: #ffffff;
    }

    .li-button-dark:hover {
        background: #B9BB8B;
        color: #ffffff;
    }
</style>
<header>
    <!-- Begin Header Top Area -->
    <div class="header-top">
        <div class="container">
            <div class="row">
                <!-- Begin Header Top Left Area -->
                <div class="col-lg-3 col-md-4">
                    <div class="header-top-left">
                        <ul class="phone-wrap">
                            <?php

                            $sellerinfo2 = [];
                            $stmt2 = $conn->prepare("SELECT * FROM users WHERE username = ?");
                            $stmt2->bind_param("s", $_SESSION["username"]);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();

                            if ($result2->num_rows > 0) {
                                $sellerinfo2 = $result2->fetch_assoc();
                            }
                            $stmt2->close(); // Close the first statement



                            $checkSellerStmt = $conn->prepare("SELECT username FROM seller_register WHERE username = ?"); // or use username if that's what you use for login
                            $checkSellerStmt->bind_param("s", $_SESSION["username"]); // Assuming you have the username in the session
                            $checkSellerStmt->execute();
                            $checkSellerResult = $checkSellerStmt->get_result();

                            if ($checkSellerResult->num_rows > 0) {
                            ?>
                                <li><span></span><a href="seller_register.php">Seller Dashboard</a></li>
                            <?php
                            } else {
                            ?>

                                <li><span></span><a href="seller_register.php">Be A Seller</a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <!-- Header Top Left Area End Here -->
                <!-- Begin Header Top Right Area -->
                <div class="col-lg-9 col-md-8">
                    <div class="header-top-right">
                        <ul class="ht-menu">
                            <!-- Begin Setting Area -->
                            <li>
                                <?php
                                if (isset($_SESSION["username"])) {
                                ?>

                                    <div class="ht-setting-trigger"><span><?php echo isset($sellerinfo2['username']) ? $sellerinfo2['username'] : ''; ?></span></div>
                                <?php
                                } else {
                                ?>

                                    <div class="ht-setting-trigger"><span>Setting</span></div>

                                <?php
                                }
                                ?>
                                <div class="setting ht-setting">
                                    <ul class="ht-setting-list">
                                        <?php
                                        if (isset($_SESSION["username"])) {
                                        ?>
                                            <li><a href="customer_profile.php">My Account</a></li>
                                        <?php
                                        }
                                        ?>
                                        <li><a href="checkout.php">Checkout</a></li>
                                        <?php
                                        if (!isset($_SESSION["username"])) {
                                        ?>
                                            <li><a href="user_login.php">Sign In</a></li>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        if (isset($_SESSION["username"])) {
                                        ?>
                                            <li><a href="logout.php">Logout</a></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </li>
                            <li><span></span><a href="customer_wardrobe.php">Wardrobe</a></li>

                        </ul>
                    </div>
                </div>
                <!-- Header Top Right Area End Here -->
            </div>
        </div>
    </div>
    <!-- Header Top Area End Here -->
    <!-- Begin Header Middle Area -->
    <div class="header-middle pl-sm-0 pr-sm-0 pl-xs-0 pr-xs-0">
        <div class="container">
            <div class="row">
                <!-- Begin Header Logo Area -->
                <div class="col-lg-3">
                    <div class="logo pb-sm-30 pb-xs-30">
                        <a href="index.php">
                            <!-- <img src="images/menu/logo/1.jpg" alt=""> -->
                            <img src="images/menu/logo/5.png" width="200px" alt="">
                        </a>
                    </div>
                </div>
                <!-- Header Logo Area End Here -->
                <!-- Begin Header Middle Right Area -->
                <div class="col-lg-9 pl-0 ml-sm-15 ml-xs-15">
                    <!-- Begin Header Middle Searchbox Area -->

                    <!-- <span>show only in products .php </span> -->

                    <?php
                    // Get the current script's filename
                    $currentScript = basename($_SERVER['PHP_SELF']);

                    // Array of files where we want to HIDE the form
                    $excludedScripts = ['customer_Women_product.php', 'customer_Men_product.php', 'all_product.php'];

                    // Check if the current script is NOT in the excluded list
                    if (!in_array($currentScript, $excludedScripts)) {
                    ?>

                        <form action="all_product.php" method="GET" class="hm-searchbox">
                            <input type="text" name="Search" placeholder="Enter your search key..." value="<?php echo isset($_GET['Search']) ? $_GET['Search'] : ''; ?>">
                            <button class="li-btn" type="submit"><i class="fa fa-search"></i></button>
                        </form>

                    <?php }
                    // If the script IS in the excluded list, show the other form or nothing
                    else { ?>

                        <form action="" method="GET" class="hm-searchbox">
                            <input type="text" name="Search" placeholder="Enter your search key..." value="<?php echo isset($_GET['Search']) ? $_GET['Search'] : ''; ?>">
                            <button class="li-btn" type="submit"><i class="fa fa-search"></i></button>
                        </form>

                    <?php }
                    ?>









                    <!-- Header Middle Searchbox Area End Here -->
                    <!-- Begin Header Middle Right Area -->
                    <div class="header-middle-right">
                        <ul class="hm-menu">
                            <!-- Begin Header Middle Wishlist Area -->
                            <li class="hm-wishlist">
                                <a href="#">
                                    <!-- <span class="cart-item-count wishlist-item-count">0</span> -->
                                    <i class="fa fa-heart-o"></i>
                                </a>
                            </li>
                            <li class="hm-wishlist">
                                <a href="customer_cart.php">
                                    <!-- <span class="cart-item-count wishlist-item-count">0</span> -->
                                    <span class="item-icon"></span>

                                </a>
                            </li>

                            <!-- Header Middle Wishlist Area End Here -->
                            <!-- Begin Header Mini Cart Area -->


                            <!-- Header Mini Cart Area End Here -->
                        </ul>
                    </div>
                    <!-- Header Middle Right Area End Here -->
                </div>
                <!-- Header Middle Right Area End Here -->
            </div>
        </div>
    </div>
    <!-- Header Middle Area End Here -->
    <!-- Begin Header Bottom Area -->
    <div class="header-bottom mb-0 header-sticky stick d-none d-lg-block d-xl-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Begin Header Bottom Menu Area -->
                    <div class="hb-menu">
                        <nav>

                            <ul>
                                <!-- <li><a href="shop-left-sidebar.html">Smartwatch</a></li>
                                <li><a href="about-us.html">About Us</a></li> -->
                                <!-- <li><a href="shop-left-sidebar.html">Accessories</a></li> -->

                                <li class="catmenu-dropdown megamenu-holder"><a href="shop_view.php">Shop</a>
                                    <ul class="megamenu hb-megamenu">
                                        <li><a href="#">Shop Category</a>
                                            <ul>
                                                <li><a href="Rental-shop.php">Rental Shop</a></li>
                                                <li><a href="Non-Rental-shop.php">Non-Rental Shop</a></li>

                                            </ul>
                                        </li>
                                        <!-- <li><a href="single-product-gallery-left.html">Single Product Style</a>
                                                        <ul>
                                                            <li><a href="single-product-carousel.html">Single Product Carousel</a></li>
                                                            <li><a href="single-product-gallery-left.html">Single Product Gallery Left</a></li>
                                                            <li><a href="single-product-gallery-right.html">Single Product Gallery Right</a></li>
                                                            <li><a href="single-product-tab-style-top.html">Single Product Tab Style Top</a></li>
                                                            <li><a href="single-product-tab-style-left.html">Single Product Tab Style Left</a></li>
                                                            <li><a href="single-product-tab-style-right.html">Single Product Tab Style Right</a></li>
                                                        </ul>
                                                    </li>
                                                    <li><a href="single-product.html">Single Products</a>
                                                        <ul>
                                                            <li><a href="single-product.html">Single Product</a></li>
                                                            <li><a href="single-product-sale.html">Single Product Sale</a></li>
                                                            <li><a href="single-product-group.html">Single Product Group</a></li>
                                                            <li><a href="single-product-normal.html">Single Product Normal</a></li>
                                                            <li><a href="single-product-affiliate.html">Single Product Affiliate</a></li>
                                                        </ul>
                                                    </li> -->
                                    </ul>
                                </li>


                                <li class="catmenu-dropdown megamenu-static-holder"><a href="customer_Women_product.php">Womens Fashion </a>
                                    <!-- <ul class="megamenu hb-megamenu">
                                        <li><a href="customer_Women_product.php?style-filter=Women Tops">Women Tops</a>
                                            <ul>
                                                <li><a href="customer_Women_product.php?category-filter=Women shirt">Women Shirt</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Women T-shirt">Women T-Shirt</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Women Polo">Women Polo</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Jackets, Coats & Vest">Jackets, Coats & Vest</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Blouses">Blouses</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Semi-Formal">Semi-Formal</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Formal">Formal</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Casual">Casual</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Hoodies">Hoodies</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Denims">Denims</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Bikini Tops">Bikini Tops</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="customer_Women_product.php?style-filter=Women Bottoms">Women Bottom</a>
                                            <ul>
                                                <li><a href="customer_Women_product.php?category-filter=Women Shorts">Women Shorts</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Women Pants">Women Pants</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Women Swetpants">Women Swetpants</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Women Skirt">Women Skirt</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Semi-Formal">Semi-Formal</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Formal">Formal</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Casual">Casual</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Hoodies">Hoodies</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Denims">Denims</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Bikini Tops">Bikini Tops</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="customer_Women_product.php?style-filter=Women Sets/Dresses">Women Sets</a>
                                            <ul>
                                                <li><a href="customer_Women_product.php?category-filter=Short+Dresses">Short Dresses</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Long+Dresses=">Long Dresses</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Casual+Dresses">Casual Dresses</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Elegant+Dresses">Elegant Dresses</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Boho+Dresses">Boho Dresses</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Sexy+Dresses">Sexy Dresses</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Cocktail+Dresses">Cocktail Dresses</a></li>
                                                <li><a href="customer_Women_product.php?category-filter=Costumes">Costumes</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <ul>
                                               
                                            </ul>
                                        </li>
                                    </ul> -->
                                </li>


                                <!-- <li><a href="customer_Women_product.php">Womens Fashion</a></li> -->
                                <li><a href="customer_Men_product.php">Mens Fashion </a></li>
                                <li><a href="all_product.php">All Products </a></li>
                                <li><a href="about-us.php">About Us</a></li>
                                <li><a href="#">Contact</a></li>

                            </ul>
                        </nav>
                    </div>

                    <!-- Header Bottom Menu Area End Here -->
                </div>
            </div>
        </div>
    </div>
    <!-- Header Bottom Area End Here -->
    <!-- Begin Mobile Menu Area -->
    <div class="mobile-menu-area d-lg-none d-xl-none col-12">
        <div class="container">
            <div class="row">
                <div class="mobile-menu">
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu Area End Here -->
</header>