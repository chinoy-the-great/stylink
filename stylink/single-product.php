<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");

$username = $_SESSION["username"];

// $productInfo = [];
if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    // Fetch data from product_list table based on the product_id
    $stmt1 = $conn->prepare("SELECT * FROM product_list WHERE product_id = ?");
    $stmt1->bind_param("s", $productId);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    if ($result1->num_rows > 0) {
        $productInfo = $result1->fetch_assoc();

        // Get the product type from $productInfo
        $selectedProductType = $productInfo['product_type'];
        // $productInfo now contains all the details for the specific product
    } else {
        // Handle the case where no product is found with the given product_id
        echo "Product not found!";
    }

    if (isset($productId)) { // Make sure $productId is defined
        $sizeStmt = $conn->prepare("SELECT * FROM product_sizes WHERE product_id = ?");
        $sizeStmt->bind_param("s", $productId);
        $sizeStmt->execute();
        $sizeResult = $sizeStmt->get_result();
    }
    // Fetch existing colors for this product
    $stmtColor = $conn->prepare("SELECT * FROM product_colors WHERE product_id = ?");
    $stmtColor->bind_param("s", $productId);
    $stmtColor->execute();
    $resultColor = $stmtColor->get_result();

    $colors = [];
    while ($row = $resultColor->fetch_assoc()) {
        $colors[] = $row; // Fetch the entire row, including color_name and product_image
    }
    $stmtColor->close();

    $hasColorImages = false;
    if (!empty($colors)) {
        foreach ($colors as $color) {
            if (!empty($color['product_image'])) { // Check if there's an image path
                $hasColorImages = true;
                break; // No need to check further
            }
        }
    }


    $stmt2 = $conn->prepare("SELECT product_image FROM product_colors WHERE product_id = ?"); // Added username check
    $stmt2->bind_param("s", $productId); // Bind username parameter
    $stmt2->execute();
    $resultcolor = $stmt2->get_result();

    $color_imagesall = [];
    while ($row = $resultcolor->fetch_assoc()) {
        $color_imagesall[] = $row['product_image'];
    }
    $stmt2->close();




    $stmt1->close();
} else {
    // Handle the case where product_id is not provided in the URL
    echo "Missing product ID.";
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $productId = $_POST["product_id"];
        $productName = $_POST["product_name"];
        $productPrice = $_POST["product_price"];
        $productSize = $_POST["product_size"];
        $productColor = $_POST["product_color"];
        $productQuantity = $_POST["product_quantity"];
        $shop_Name = $_POST["shop_name"];


        // Basic Input Validation
        if (empty($productId)) {
            $error = "Please fill out all required fields.";
        } else {
            // Check if the item already exists in the cart for this user
            $checkStmt = $conn->prepare("SELECT * FROM customer_cart WHERE username = ? AND product_id = ? AND product_size = ? AND product_color = ?");
            $checkStmt->bind_param("ssss", $username, $productId, $productSize, $productColor);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                // Item already exists, update quantity
                $updateStmt = $conn->prepare("UPDATE customer_cart SET product_quantity = product_quantity + ? WHERE username = ? AND product_id = ? AND product_size = ? AND product_color = ?");
                $updateStmt->bind_param("issss", $productQuantity, $username, $productId, $productSize, $productColor);

                if ($updateStmt->execute()) {
                    $success = "Item quantity updated in cart.";
                } else {
                    $error = "Error updating item quantity: " . $updateStmt->error;
                }

                $updateStmt->close();
            } else {
                // Item doesn't exist, insert new row
                $stmt = $conn->prepare("INSERT INTO customer_cart (username, product_id, product_name, product_price, product_size, product_color, product_quantity, shop_name) VALUES (?, ?, ?, ?, ?, ?, ? , ?)");
                $stmt->bind_param("ssssssds", $username, $productId, $productName, $productPrice, $productSize, $productColor, $productQuantity, $shop_Name);

                if ($stmt->execute()) {
                    $success = "Item added to cart.";
                } else {
                    $error = "Error adding item to cart: " . $stmt->error;
                }

                $stmt->close();
            }
            $success = "Product added to Cart.";
        }
    } else {
        $error = "You need to be logged in to add items to the cart.";
    }
}


// $stmt1->close();

// Fetch product data
// $productQuery = "SELECT * FROM product_list LIMIT 15";
// $productResult = $conn->query($productQuery);

// Base product query (with LIMIT 15 for selecting only 15 products)
$productQuery = "SELECT * FROM product_list pl WHERE pl.product_type = ?";
// Prepare the statement
$stmtProduct = $conn->prepare($productQuery);
// Bind the product type parameter
$stmtProduct->bind_param("s", $selectedProductType);
// Execute the query
$stmtProduct->execute();
$productResult = $stmtProduct->get_result();



ob_end_flush();

include("include/head.php");
?>
<!-- single-product31:30-->



<style>
    .product-details-thumbs .slick-arrow,
    .tab-style-right .slick-arrow,
    .tab-style-left .slick-arrow {
        font-size: 20px;
        position: absolute;
        top: 50%;
        left: 0;
        right: auto;
        background: #393939;
    }

    .sm-image img {

        width: 100%;
        aspect-ratio: 1 / 1;
        /* Sets a 1:1 aspect ratio directly */
        object-fit: cover;
    }

    .lg-image {
        /* aspect-ratio: 1 / 2; */
        width: 100%;
        object-fit: cover;
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

                        <?php if (isset($productInfo['product_typeclothes']) && $productInfo['product_typeclothes'] == 'Rental') : ?>
                            <li><a href="Rental-shop.php"><?php echo isset($productInfo['product_typeclothes']) ? $productInfo['product_typeclothes'] : ''; ?></a></li>
                        <?php else : ?>
                            <li><a href="Non-Rental-shop.php"><?php echo isset($productInfo['product_typeclothes']) ? $productInfo['product_typeclothes'] : ''; ?></a></li>
                        <?php endif; ?>


                        <?php if (isset($productInfo['product_type']) && $productInfo['product_type'] == 'Men Fashion') : ?>
                            <li><a href="customer_Men_product.php"><?php echo isset($productInfo['product_type']) ? $productInfo['product_type'] : ''; ?></a></li>
                        <?php else : ?>
                            <li><a href="customer_Women_product.php"><?php echo isset($productInfo['product_type']) ? $productInfo['product_type'] : ''; ?></a></li>
                        <?php endif; ?>

                        <?php


                        // Start product_style
                        $Men_Tops = 'Men Tops';
                        $Men_Bottom = 'Men Bottoms';
                        $Men_Sets = 'Men Sets';

                        $Women_Top = 'Women Tops';
                        $Women_Bottom = 'Women Bottoms';
                        $Women_Sets = 'Women Sets/Dresses';

                        // End product_style





                        ?>

                        <?php if (isset($productInfo['product_style']) && $productInfo['product_style'] == $Men_Tops) : ?>
                            <li><a href="customer_Men_product.php?style-filter=Men Tops"><?php echo isset($productInfo['product_style']) ? $productInfo['product_style'] : ''; ?></a></li>

                        <?php elseif (isset($productInfo['product_style']) && $productInfo['product_style'] == $Men_Bottom) : ?>
                            <li><a href="customer_Men_product.php?style-filter=Men Bottoms"><?php echo isset($productInfo['product_style']) ? $productInfo['product_style'] : ''; ?></a></li>

                        <?php elseif (isset($productInfo['product_style']) && $productInfo['product_style'] == $Men_Sets) : ?>
                            <li><a href="customer_Men_product.php?style-filter=Men Sets"><?php echo isset($productInfo['product_style']) ? $productInfo['product_style'] : ''; ?></a></li>



                        <?php elseif (isset($productInfo['product_style']) && $productInfo['product_style'] == $Women_Top) : ?>
                            <li><a href="customer_Women_product.php?style-filter=Women Tops"><?php echo isset($productInfo['product_style']) ? $productInfo['product_style'] : ''; ?></a></li>

                        <?php elseif (isset($productInfo['product_style']) && $productInfo['product_style'] == $Women_Bottom) : ?>
                            <li><a href="customer_Women_product.php?style-filter=Women Bottoms"><?php echo isset($productInfo['product_style']) ? $productInfo['product_style'] : ''; ?></a></li>

                        <?php elseif (isset($productInfo['product_style']) && $productInfo['product_style'] == $Women_Sets) : ?>
                            <li><a href="customer_Women_product.php?style-filter=Women Sets/Dresses"><?php echo isset($productInfo['product_style']) ? $productInfo['product_style'] : ''; ?></a></li>
                        <?php endif; ?>



                        <?php if (isset($productInfo['product_type']) && $productInfo['product_type'] == 'Men Fashion') : ?>
                            <li><a href="customer_Men_product.php?category-filter=<?php echo isset($productInfo['product_category']) ? $productInfo['product_category'] : ''; ?>"><?php echo isset($productInfo['product_category']) ? $productInfo['product_category'] : ''; ?></a></li>
                        <?php else : ?>
                            <li><a href="customer_Women_product.php?category-filter=<?php echo isset($productInfo['product_category']) ? $productInfo['product_category'] : ''; ?>"><?php echo isset($productInfo['product_category']) ? $productInfo['product_category'] : ''; ?></a></li>
                        <?php endif; ?>




                        <!-- <li><a href="customer_Women_product.php?style-filter=Women+Bottoms"><?php echo isset($productInfo['product_style']) ? $productInfo['product_style'] : ''; ?></a></li> -->



                        <li class="active"> <?php echo isset($productInfo['product_name']) ? $productInfo['product_name'] : ''; ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!-- content-wraper start -->

        <br>
        <br>
        <div class="content-wraper">
            <div class="container">
                <div class="row single-product-area">
                    <div class="col-lg-6 col-md-6">
                        <div class="product-details-left">
                            <div class="product-details-images slider-navigation-1">
                                <?php if ($hasColorImages) : ?>
                                    <?php foreach ($colors as $color) : ?>
                                        <div class="lg-image">
                                            <a class="popup-img venobox vbox-item" href="<?php echo $color['product_image']; ?>" data-gall="myGallery">
                                                <img src="<?php echo $color['product_image']; ?>" alt="product image">
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php elseif (isset($productInfo['product_image'])) : ?>
                                    <div class="lg-image">
                                        <a class="popup-img venobox vbox-item" href="<?php echo isset($productInfo['product_image']) ? $productInfo['product_image'] : ''; ?>" data-gall="myGallery">
                                            <img src="<?php echo isset($productInfo['product_image']) ? $productInfo['product_image'] : ''; ?>" alt="product image">
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <p>No images available for this product.</p>
                                <?php endif; ?>
                            </div>


                            <div class="product-details-thumbs slider-thumbs-1">
                                <?php if ($hasColorImages) : ?>
                                    <?php foreach ($colors as $color) : ?>
                                        <div class="sm-image" data-color="<?php echo $color['product_color']; ?>">
                                            <img src="<?php echo $color['product_image']; ?>" alt="product image thumb111">
                                        </div>
                                    <?php endforeach; ?>
                                <?php elseif (isset($productInfo['product_image'])) : ?>
                                    <!-- <div class="sm-image" data-color="<php echo $color['product_color']; ?>">
                                        <img src="<php echo $productInfo['product_image']; ?>" alt="product image thumb111">
                                    </div> -->
                                <?php else : ?>
                                    <p>No images available for this product.</p>
                                <?php endif; ?>
                            </div>



                        </div>

                    </div>
                    <!--// Product Details Left -->



                    <div class="col-lg-6 col-md-6">
                        <div class="product-details-view-content pt-60">
                            <div class="product-info">

                                <?php
                                // ... (rest of your single-product.php code)

                                // Display messages from add_to_cart.php
                                if (isset($_GET['error'])) {
                                    $error = urldecode($_GET['error']);
                                } elseif (isset($_GET['success'])) {
                                    $success = urldecode($_GET['success']);
                                }
                                if (isset($error)) : ?>
                                    <div class="alert alert-danger error-message">
                                        <p><?php echo $error; ?></p>
                                    </div>
                                <?php elseif (isset($success)) : ?>
                                    <div class="alert alert-success success-message">
                                        <span><?php echo $success; ?></span>
                                    </div>

                                <?php endif; ?>

                                <?php
                                if (isset($_SESSION['success_message'])) {
                                    echo '<div class="alert alert-success success-message">' . $_SESSION['success_message'] . '</div>';
                                    // Optional: Clear the message after it's been displayed
                                    unset($_SESSION['success_message']);
                                }
                                ?>



                                <h2>
                                    <!-- Product Name -->
                                    <?php echo isset($productInfo['product_name']) ? $productInfo['product_name'] : ''; ?>
                                </h2>
                                <span class="product-details-ref"> Types of Clothes: <?php echo isset($productInfo['product_style']) ? $productInfo['product_style'] : ''; ?></span>
                                <Br>
                                <span class="product-details-ref"> Type: <?php echo isset($productInfo['product_typeclothes']) ? $productInfo['product_typeclothes'] : ''; ?></span>
                                <div class="rating-box pt-20">
                                    <ul class="rating rating-with-review-item">
                                    </ul>
                                </div>
                                <div class="price-box pt-20 " >
                                    <span class="new-price new-price-2" style="font-size: 35px;">
                                        <!-- Show product_price -->
                                        ₱<?php echo isset($productInfo['product_price']) ? $productInfo['product_price'] : ''; ?>
                                    </span>
                                </div>
                                <div class="product-desc">
                                    <p>
                                        <span>

                                            <!-- Show product_description -->

                                        </span>
                                    </p>
                                </div>
                                <div class="product-variants">
                                    <div class="produt-variants-size">
                                        <label>Size</label>
                                        <select class="nice-select" id="sizeSelect" onchange="updateSizeInput()">
                                            <?php
                                            if (isset($sizeResult) && $sizeResult->num_rows > 0) {
                                                while ($size = $sizeResult->fetch_assoc()) {
                                                    echo '<option value="' . $size['product_size'] . '">' . $size['product_size'] . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No sizes available</option>';
                                            }
                                            ?>

                                        </select>


                                        <br>

                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="single-add-to-cart">
                                    <div class="product-variants">
                                        <div class="produt-variants-size">
                                            <label>Color</label>
                                            <select class="nice-select" id="colorSelect" onchange="updateColorInput()">
                                                <?php
                                                if (!empty($colors)) {
                                                    foreach ($colors as $color) {
                                                        echo '<option value="' . $color['product_color'] . '">' . $color['product_color'] . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">No Color available</option>';
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>

                                </div>
                                <script>
                                    $(document).ready(function() {
                                        // Initially hide all small images except the first one
                                        $('.sm-image:not(:first)').hide();

                                        // When a color option is selected, show the corresponding small image
                                        $('#colorSelect').on('change', function() {
                                            var selectedColor = $(this).val();
                                            $('.sm-image').hide();
                                            $('.sm-image[data-color="' + selectedColor + '"]').show();

                                            // Update the large image based on the selected small image
                                            var selectedImageSrc = $('.sm-image[data-color="' + selectedColor + '"]').find('img').attr('src');
                                            $('.lg-image img').attr('src', selectedImageSrc);

                                            // Update the venobox link for the large image (if you're using it)
                                            $('.venobox').attr('href', selectedImageSrc);
                                        });
                                    });
                                </script>

                                <div class="single-add-to-cart">
                                    <!-- <form action="add_to_cart.php" method="post" class="cart-quantity"> -->

                                    <form method="post" class="cart-quantity" id="productForm">
                                        <div class="quantity">

                                            <input type="hidden" name="product_price" value="<?php echo $productInfo['product_price']; ?>">
                                            <input type="hidden" name="product_id" value="<?php echo isset($productInfo['product_id']) ? $productInfo['product_id'] : ''; ?>">
                                            <input type="hidden" name="product_name" value="<?php echo isset($productInfo['product_name']) ? $productInfo['product_name'] : ''; ?>">

                                            <input type="hidden" name="product_size" id="sizeInput">
                                            <input type="hidden" name="product_color" id="colorInput">
                                            <input type="hidden" name="product_typeclothes" value="<?php echo isset($productInfo['product_typeclothes']) ? $productInfo['product_typeclothes'] : ''; ?>">
                                            <input type="hidden" name="shop_name" value="<?php echo isset($productInfo['shop_name']) ? $productInfo['shop_name'] : ''; ?>">

                                            <input type="hidden" name="product_style" value="<?php echo isset($productInfo['product_style']) ? $productInfo['product_style'] : ''; ?>">

                                            <label>Quantity</label>
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box" name="product_quantity" value="1" type="text">
                                                <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                                <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                                            </div>
                                        </div>
                                        <span class="product-details-ref"> Stocks: <?php echo isset($productInfo['product_stocks']) ? $productInfo['product_stocks'] : ''; ?></span>

                                        <br>
                                        <br>
                                        <br>

                                        <?php
                                        $productId = $productInfo['product_id'];
                                        // Check if the product belongs to the current user (using mysqli)
                                        // $stmt11 = $conn->prepare("SELECT COUNT(*) FROM product_list WHERE product_id = ? AND username = ?");
                                        $stmt11 = $conn->prepare("SELECT COUNT(*) FROM product_list WHERE product_id = ? AND username = ?");
                                        $stmt11->bind_param("ss", $productId, $username); // Bind parameters correctly
                                        $stmt11->execute();
                                        $stmt11->bind_result($count); // Bind result
                                        $stmt11->fetch();
                                        $isOwnProduct = $count > 0;
                                        // Display appropriate content
                                        if (!$isOwnProduct) : ?>

                                            <div class="row">
                                                <?php if ($productInfo['product_typeclothes'] === 'Non-Rental') : ?>
                                                    <div class="col-md-6">
                                                        <button class="add-to-cart1 col-md-12" id="addToWardrobeBtn" type="button">Add to Wardrobe</button>

                                                    </div>
                                                <?php elseif ($productInfo['product_typeclothes'] === 'Rental') : ?>
                                                    <div class="col-md-6">
                                                    <button class="add-to-cart col-md-12" id="addToCartBtn1" type="button">Buy Now</button>
                                                      
                                                    <!-- <button class="add-to-cart col-md-12" id="buyNowBtn1" type="button">1Buy Now</button> -->
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-md-6">
                                                    <button class="add-to-cart-outline col-md-12" id="addToCartBtn" type="button">Add to Cart</button>
                                                </div>
                                            </div>
                                            <br>
                                            <?php if ($productInfo['product_typeclothes'] === 'Non-Rental') : ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <!-- <button class="add-to-cart col-md-12" id="buyNowBtn" type="button">Buy Now</button> -->
                                                        <button class="add-to-cart col-md-12" id="addToCartBtn1" type="button">Buy Now</button>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <button type="submit" style="display: none;"></button>
                                            <p> <?php echo isset($productInfo['username']) ? $productInfo['username'] : ''; ?></p>
                                            <p> <?php echo $username ?></p>


                                        <?php else : ?>
                                            <span>You are the Seller</span>
                                        <?php endif; ?>



                                    </form>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const form = document.getElementById('productForm');
                                            const addToCartBtn = document.getElementById('addToCartBtn');
                                            const addToCartBtn1 = document.getElementById('addToCartBtn1');
                                            const addToWardrobeBtn = document.getElementById('addToWardrobeBtn');
                                            const buyNowBtn = document.getElementById('buyNowBtn');
                                            const buyNowBtn1 = document.getElementById('buyNowBtn1');

                                            addToCartBtn.addEventListener('click', function() {
                                                form.action = 'add_to_cart.php';
                                                form.submit();
                                            });

                                            addToCartBtn1.addEventListener('click', function() {
                                                form.action = 'buy_now.php';
                                                form.submit();
                                            });

                                            addToWardrobeBtn.addEventListener('click', function() {
                                                form.action = 'add_to_wardrobe.php';
                                                form.submit();
                                            });

                                            buyNowBtn.addEventListener('click', function() {
                                                form.action = 'buy_now.php';
                                                form.submit();
                                            });
                                            buyNowBtn1.addEventListener('click', function() {
                                                form.action = 'buy_now.php';
                                                form.submit();
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="product-additional-info pt-25">
                                    <a class="wishlist-btn" href="#"><i class="fa fa-heart-o"></i>Add to wishlist</a>
                                    <div class="product-social-sharing pt-25">
                                        <ul>
                                            <!-- <li class="facebook"><a href="#"><i class="fa fa-facebook"></i>Facebook</a></li>
                                            <li class="twitter"><a href="#"><i class="fa fa-twitter"></i>Twitter</a></li>
                                            <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i>Google +</a></li>
                                            <li class="instagram"><a href="#"><i class="fa fa-instagram"></i>Instagram</a></li> -->
                                        </ul>
                                    </div>
                                </div>
                                <!-- <div class="block-reassurance">
                                    <ul>
                                        <li>
                                            <div class="reassurance-item">
                                                <div class="reassurance-icon">
                                                    <i class="fa fa-check-square-o"></i>
                                                </div>
                                                <p>Security policy (edit with Customer reassurance module)</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="reassurance-item">
                                                <div class="reassurance-icon">
                                                    <i class="fa fa-truck"></i>
                                                </div>
                                                <p>Delivery policy (edit with Customer reassurance module)</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="reassurance-item">
                                                <div class="reassurance-icon">
                                                    <i class="fa fa-exchange"></i>
                                                </div>
                                                <p> Return policy (edit with Customer reassurance module)</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wraper end -->
    <!-- Begin Product Area -->
    <div class="product-area pt-35">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="li-product-tab">
                        <ul class="nav li-product-menu">
                            <li><a class="active" data-toggle="tab" href="#description"><span>Description</span></a></li>
                            <!-- <li><a data-toggle="tab" href="#product-details"><span>Product Details</span></a></li> -->
                            <li><a data-toggle="tab" href="#reviews"><span>Reviews</span></a></li>
                        </ul>
                    </div>
                    <!-- Begin Li's Tab Menu Content Area -->
                </div>
            </div>
            <div class="tab-content">
                <div id="description" class="tab-pane active show" role="tabpanel">
                    <div class="product-description">
                        <span><?php echo isset($productInfo['product_description']) ? $productInfo['product_description'] : ''; ?></span>
                    </div>
                </div>
                <div id="product-details" class="tab-pane" role="tabpanel">
                    <div class="product-details-manufacturer">
                        <a href="#">
                            <img src="images/product-details/1.jpg" alt="Product Manufacturer Image">
                        </a>
                        <p><span>Reference</span> demo_7</p>
                        <p><span>Reference</span> demo_7</p>
                    </div>
                </div>
                <div id="reviews" class="tab-pane" role="tabpanel">
                    <div class="product-reviews">
                        <div class="product-details-comment-block">
                            <div class="comment-review">
                                <span>Grade</span>
                                <ul class="rating">
                                    <li><i class="fa fa-star-o"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                    <li class="no-star"><i class="fa fa-star-o"></i></li>
                                    <li class="no-star"><i class="fa fa-star-o"></i></li>
                                </ul>
                            </div>
                            <div class="comment-author-infos pt-25">
                                <span>HTML 5</span>
                                <em>01-12-18</em>
                            </div>
                            <div class="comment-details">
                                <h4 class="title-block">Demo</h4>
                                <p>Plaza</p>
                            </div>
                            <div class="review-btn">
                                <a class="review-links" href="#" data-toggle="modal" data-target="#mymodal">Write Your Review!</a>
                            </div>
                            <!-- Begin Quick View | Modal Area -->
                            <div class="modal fade modal-wrapper" id="mymodal">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <h3 class="review-page-title">Write Your Review</h3>
                                            <div class="modal-inner-area row">
                                                <div class="col-lg-6">
                                                    <div class="li-review-product">
                                                        <img src="images/product/large-size/3.jpg" alt="Li's Product">
                                                        <div class="li-review-product-desc">
                                                            <p class="li-product-name">Today is a good day Framed poster</p>
                                                            <p>
                                                                <span>Beach Camera Exclusive Bundle - Includes Two Samsung Radiant 360 R3 Wi-Fi Bluetooth Speakers. Fill The Entire Room With Exquisite Sound via Ring Radiator Technology. Stream And Control R3 Speakers Wirelessly With Your Smartphone. Sophisticated, Modern Design </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="li-review-content">
                                                        <!-- Begin Feedback Area -->
                                                        <div class="feedback-area">
                                                            <div class="feedback">
                                                                <h3 class="feedback-title">Our Feedback</h3>
                                                                <form action="#">
                                                                    <p class="your-opinion">
                                                                        <label>Your Rating</label>
                                                                        <span>
                                                                            <select class="star-rating">
                                                                                <option value="1">1</option>
                                                                                <option value="2">2</option>
                                                                                <option value="3">3</option>
                                                                                <option value="4">4</option>
                                                                                <option value="5">5</option>
                                                                            </select>
                                                                        </span>
                                                                    </p>
                                                                    <p class="feedback-form">
                                                                        <label for="feedback">Your Review</label>
                                                                        <textarea id="feedback" name="comment" cols="45" rows="8" aria-required="true"></textarea>
                                                                    </p>
                                                                    <div class="feedback-input">
                                                                        <p class="feedback-form-author">
                                                                            <label for="author">Name<span class="required">*</span>
                                                                            </label>
                                                                            <input id="author" name="author" value="" size="30" aria-required="true" type="text">
                                                                        </p>
                                                                        <p class="feedback-form-author feedback-form-email">
                                                                            <label for="email">Email<span class="required">*</span>
                                                                            </label>
                                                                            <input id="email" name="email" value="" size="30" aria-required="true" type="text">
                                                                            <span class="required"><sub>*</sub> Required fields</span>
                                                                        </p>
                                                                        <div class="feedback-btn pb-15">
                                                                            <a href="#" class="close" data-dismiss="modal" aria-label="Close">Close</a>
                                                                            <a href="#">Submit</a>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- Feedback Area End Here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Quick View | Modal Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Area End Here -->
    <!-- Begin Li's Laptop Product Area -->
    <section class="product-area li-laptop-product pt-30 pb-50">
        <div class="container">
            <div class="row">
                <!-- Begin Li's Section Area -->
                <div class="col-lg-12">
                    <div class="li-section-title">
                        <h2 style="color: #404040;">
                            <span>15 other products in the same category:</span>
                        </h2>
                    </div>
                    <div class="row">
                        <div class="product-active owl-carousel">

                            <?php

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
                                                            <a href="single-shop.php?shop_name=<?php echo $product['shop_name']; ?>"><?php echo $product['shop_name']; ?></a>
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
                                                    <h4 style=" -webkit-box-orient: vertical; -webkit-line-clamp: 2; display: -webkit-box; overflow: hidden; "><a class="product_name" href="single-product.php?product_id=<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></a></h4>
                                                    <div class="price-box" >
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
                <!-- Li's Section Area End Here -->
            </div>
        </div>
    </section>
    <!-- Li's Laptop Product Area End Here -->
    <!-- Begin Footer Area -->
    <?php include("include/footer.php"); ?>
    <!-- Footer Area End Here -->
    <!-- Begin Quick View | Modal Area -->
    <div class="modal fade modal-wrapper" id="exampleModalCenter">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="modal-inner-area row">
                        <div class="col-lg-5 col-md-6 col-sm-6">
                            <!-- Product Details Left -->
                            <div class="product-details-left">
                                <div class="product-details-images slider-navigation-1">
                                    <div class="lg-image">
                                        <img src="images/product/large-size/1.jpg" alt="product image">
                                    </div>
                                    <div class="lg-image">
                                        <img src="images/product/large-size/2.jpg" alt="product image">
                                    </div>
                                    <div class="lg-image">
                                        <img src="images/product/large-size/3.jpg" alt="product image">
                                    </div>
                                    <div class="lg-image">
                                        <img src="images/product/large-size/4.jpg" alt="product image">
                                    </div>
                                    <div class="lg-image">
                                        <img src="images/product/large-size/5.jpg" alt="product image">
                                    </div>
                                    <div class="lg-image">
                                        <img src="images/product/large-size/6.jpg" alt="product image">
                                    </div>
                                </div>
                                <div class="product-details-thumbs slider-thumbs-1">
                                    <div class="sm-image"><img src="images/product/small-size/1.jpg" alt="product image thumb"></div>
                                    <div class="sm-image"><img src="images/product/small-size/2.jpg" alt="product image thumb"></div>
                                    <div class="sm-image"><img src="images/product/small-size/3.jpg" alt="product image thumb"></div>
                                    <div class="sm-image"><img src="images/product/small-size/4.jpg" alt="product image thumb"></div>
                                    <div class="sm-image"><img src="images/product/small-size/5.jpg" alt="product image thumb"></div>
                                    <div class="sm-image"><img src="images/product/small-size/6.jpg" alt="product image thumb"></div>
                                </div>
                            </div>
                            <!--// Product Details Left -->
                        </div>

                        <div class="col-lg-7 col-md-6 col-sm-6">
                            <div class="product-details-view-content pt-60">
                                <div class="product-info">
                                    <h2>
                                        <!-- Product Name -->

                                    </h2>
                                    <span class="product-details-ref">Reference: demo_15</span>
                                    <div class="rating-box pt-20">
                                        <ul class="rating rating-with-review-item">
                                            <li><i class="fa fa-star-o"></i></li>
                                            <li><i class="fa fa-star-o"></i></li>
                                            <li><i class="fa fa-star-o"></i></li>
                                            <li class="no-star"><i class="fa fa-star-o"></i></li>
                                            <li class="no-star"><i class="fa fa-star-o"></i></li>
                                            <li class="review-item"><a href="#">Read Review</a></li>
                                            <li class="review-item"><a href="#">Write Review</a></li>
                                        </ul>
                                    </div>
                                    <div class="price-box pt-20">
                                        <span class="new-price new-price-2">$57.98</span>
                                    </div>
                                    <div class="product-desc">
                                        <p>
                                            <span>100% cotton double printed dress. Black and white striped top and orange high waisted skater skirt bottom. Lorem ipsum dolor sit amet, consectetur adipisicing elit. quibusdam corporis, earum facilis et nostrum dolorum accusamus similique eveniet quia pariatur.
                                            </span>
                                        </p>
                                    </div>
                                    <div class="product-variants">
                                        <div class="produt-variants-size">
                                            <label>Dimension</label>
                                            <select class="nice-select">
                                                <option value="1" title="S" selected="selected">40x60cm</option>
                                                <option value="2" title="M">60x90cm</option>
                                                <option value="3" title="L">80x120cm</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="single-add-to-cart">
                                        <form action="#" class="cart-quantity">
                                            <div class="quantity">
                                                <label>Quantity</label>
                                                <div class="cart-plus-minus">
                                                    <input class="cart-plus-minus-box" value="1" type="text">
                                                    <div class="dec qtybutton"><i class="fa fa-angle-down"></i></div>
                                                    <div class="inc qtybutton"><i class="fa fa-angle-up"></i></div>
                                                </div>
                                            </div>
                                            <button class="add-to-cart" type="submit">Add to cart</button>
                                        </form>
                                    </div>
                                    <div class="product-additional-info pt-25">
                                        <a class="wishlist-btn" href="wishlist.html"><i class="fa fa-heart-o"></i>Add to wishlist</a>
                                        <div class="product-social-sharing pt-25">
                                            <ul>
                                                <li class="facebook"><a href="#"><i class="fa fa-facebook"></i>Facebook</a></li>
                                                <li class="twitter"><a href="#"><i class="fa fa-twitter"></i>Twitter</a></li>
                                                <li class="google-plus"><a href="#"><i class="fa fa-google-plus"></i>Google +</a></li>
                                                <li class="instagram"><a href="#"><i class="fa fa-instagram"></i>Instagram</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick View | Modal Area End Here -->
    </div>
    <!-- Body Wrapper End Here -->

    <!-- Updateautomaticaly the Select Size -->
    <script>
        function updateSizeInput() {
            const sizeSelect = document.getElementById('sizeSelect');
            document.getElementById('sizeInput').value = sizeSelect.value;
        }

        // Set initial value on page load
        updateSizeInput();
    </script>
    <script>
        function updateColorInput() {
            const colorSelect = document.getElementById('colorSelect');
            document.getElementById('colorInput').value = colorSelect.value;
        }

        // Set initial value on page load
        updateColorInput();
    </script>

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

<!-- single-product31:32-->

</html>