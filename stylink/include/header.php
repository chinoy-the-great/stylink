<?php
?>
<style>
    .hb-menu {
        display: flex;
        justify-content: space-between; /* Spread out navigation and search bar */
        align-items: center;
    }

    .hb-searchbox {
        display: flex;
        align-items: center;
    }

    .search-container {
        position: relative;
    }

    .search-container input {
        height: 40px;
        padding-left: 40px; /* Space for the search icon */
        border-radius: 20px; /* Adds rounded corners */
        border: 1px solid #ccc;
        background-color: #fff; /* Solid background */
        color: #333; /* Text color */
        transition: all 0.3s ease; /* Smooth transition for focus */
    }

    .search-container input:focus {
        background-color: #fff; /* Keeps the background solid when focused */
        border-color: #888; /* Optional: Change border color when focused */
        opacity: 1; /* Ensure no transparency on focus */
        outline: none; /* Remove the blue outline */
    }

    .search-container .search-icon {
        position: absolute;
        left: 10px; /* Position the search icon */
        background: none; /* No background */
        border: none; /* No border */
        font-size: 18px;
        color: #dc6e63; /* Icon color */
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .search-container .search-icon:hover {
        background: none; /* No background on hover */
        border: none; /* Ensure no border on hover */
        color: #888; /* Change icon color slightly on hover */
    }

    .divider-icon {
        /*margin-left: 5px;*/
        /*margin-right: 5px;*/
    }

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
                    <!-- Begin Header Middle Right Area -->

                    <!-- Header Middle Right Area End Here -->
                </div>
                <!-- Header Middle Right Area End Here -->
            </div>
        </div>
    </div>
    <!-- Header Middle Area End Here -->
    <!-- Begin Header Bottom Area -->

    <!-- removed header-bottom from class tag to remove navbar color-->
    <div class="mb-0 header-sticky stick d-none d-lg-block d-xl-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <!-- Begin Header Bottom Menu Area -->
                    <div class="hb-menu">
                        <nav>
                            <ul>
                                <li>
                                    <a href="index.php">
                                        <div class="grid place-items-center">
                                            <img src="images/menu/logo/5.png" width="100px" alt="">
                                        </div>
                                    </a>
                                </li>
                                <li class="catmenu-dropdown megamenu-holder">
                                    <a href="shop_view.php">Shop</a>
                                    <ul class="megamenu hb-megamenu">
                                        <li><a href="#">Shop Category</a>
                                            <ul>
                                                <li><a href="Rental-shop.php">Rental Shop</a></li>
                                                <li><a href="Non-Rental-shop.php">Non-Rental Shop</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="catmenu-dropdown megamenu-static-holder"><a href="customer_Women_product.php">Womens Fashion</a></li>
                                <li><a href="customer_Men_product.php">Mens Fashion</a></li>
                                <li><a href="all_product.php">All Products</a></li>
                                <li><a href="about-us.php">About Us</a></li>
                                <li><a href="#">Contact</a></li>
                            </ul>
                        </nav>

                        <!-- Add Search Bar Here -->
                        <form action="all_product.php" method="GET" class="hb-searchbox">
                            <div class="search-container">
                                <input type="text" name="Search" placeholder="Search..." value="<?php echo isset($_GET['Search']) ? $_GET['Search'] : ''; ?>">
                                <button class="li-btn search-icon" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Divider Icon -->
                        <div class="divider-icon">
                            <i class="fa fa-ellipsis-v"></i>
                        </div>

                        <div class="header-middle-right">
                            <ul class="hm-menu">
                                <!-- Begin Header Middle Wishlist Area -->
                                <li class="hm-wishlist">
                                    <a href="#">
                                        <i class="fa fa-heart-o"></i>
                                    </a>
                                </li>
                                <li class="hm-wishlist">
                                    <a href="customer_cart.php">
                                        <i class="fa fa-shopping-cart"></i>
                                    </a>
                                </li>
                                <!-- Header Middle Wishlist Area End Here -->
                            </ul>
                        </div>

                        <!-- Header Bottom Menu Area End Here -->
                    </div>
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