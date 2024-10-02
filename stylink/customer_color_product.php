<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");

// Get selected filters from query parameters
$selectedColor = $_GET['Product_ColorName'] ?? null;
$selectedSizes = $_GET['size'] ?? []; // Use $_GET['size'] (singular) for the select element

// Construct the base product query
$productQuery = "SELECT DISTINCT pl.* 
                 FROM product_list pl
                 JOIN product_colors pc ON pl.product_id = pc.product_id
                 WHERE pl.product_type = 'Men Fashion'";

// Dynamically add filter conditions
$queryParams = [];
if ($selectedColor) {
    $productQuery .= " AND pc.product_color = ?";
    $queryParams[] = $selectedColor;
}

if (!empty($selectedSizes)) {
    $sizePlaceholders = implode(',', array_fill(0, count($selectedSizes), '?'));
    $productQuery .= " AND pl.product_id IN (
                        SELECT product_id 
                        FROM product_sizes 
                        WHERE product_size IN ($sizePlaceholders)
                      )";
    $queryParams = array_merge($queryParams, $selectedSizes);
}

// Prepare and execute the query 
$stmtProduct = $conn->prepare($productQuery);

// Dynamically bind parameters based on types (all strings here)
$types = str_repeat('s', count($queryParams));
$stmtProduct->bind_param($types, ...$queryParams);
$stmtProduct->execute();
$productResult = $stmtProduct->get_result();

ob_end_flush();

?>
<style>
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
<style>
    /* Basic Colors */
    .PINK {
        background-color: #ee7ca2;
    }

    .BLACK {
        background-color: #000000;
    }

    .WHITE {
        background-color: #ffffff;
    }

    .RED {
        background-color: #ff0000;
    }

    .GREEN {
        background-color: #00ff00;
    }

    .BLUE {
        background-color: #24369f;
    }

    .YELLOW {
        background-color: #ffff00;
    }

    .PURPLE {
        background-color: #800080;
    }

    .ORANGE {
        background-color: #ffa500;
    }

    .GRAY {
        background-color: #808080;
    }

    .VIOLET {
        background-color: #55006a;
    }

    /* Modern & Aesthetic Colors */
    .TEAL {
        background-color: #008080;
    }

    .AQUA {
        background-color: #00ffff;
    }

    .CORAL {
        background-color: #ff7f50;
    }

    .FUCHSIA {
        background-color: #ff00ff;
    }

    .INDIGO {
        background-color: #4b0082;
    }

    .LIME {
        background-color: #00ff00;
    }

    .OLIVE {
        background-color: #808000;
    }

    .TURQUOISE {
        background-color: #40e0d0;
    }

    /* Earthy & Neutral Tones */
    .BEIGE {
        background-color: #f5f5dc;
    }

    .BROWN {
        background-color: #a52a2a;
    }

    .CREAM {
        background-color: #fffdd0;
    }

    .KHAKI {
        background-color: #f0e68c;
    }

    .MAUVE {
        background-color: #e0b0ff;
    }

    .TAN {
        background-color: #d2b48c;
    }

    .TAUPE {
        background-color: #483c32;
    }

    .ECRU {
        background-color: #c2b280;
    }

    /* Coffee-Inspired Colors */
    .COFFEE {
        background-color: #6f4e37;
    }

    .LATTE {
        background-color: #c59475;
    }

    .MOCHA {
        background-color: #855944;
    }

    .ESPRESSO {
        background-color: #4e2f27;
    }

    .CAPPUCCINO {
        background-color: #9f8170;
    }

    /* Basic Colors & Shades */
    .BLACK {
        background-color: #000000;
    }

    .BLACK-LIGHT {
        background-color: #333333;
    }

    .BLACK-DARK {
        background-color: #1a1a1a;
    }

    .WHITE {
        background-color: #ffffff;
    }

    .WHITE-LIGHT {
        background-color: #f2f2f2;
    }

    .WHITE-DARK {
        background-color: #e6e6e6;
    }

    .RED {
        background-color: #ff0000;
    }

    .RED-LIGHT {
        background-color: #ff6666;
    }

    .RED-DARK {
        background-color: #cc0000;
    }

    .GREEN {
        background-color: #00ff00;
    }

    .GREEN-LIGHT {
        background-color: #99ff99;
    }

    .GREEN-DARK {
        background-color: #00cc00;
    }

    .BLUE {
        background-color: #24369f;
    }

    .BLUE-LIGHT {
        background-color: #6477cf;
    }

    .BLUE-DARK {
        background-color: #192564;
    }

    .YELLOW {
        background-color: #ffff00;
    }

    .YELLOW-LIGHT {
        background-color: #ffff99;
    }

    .YELLOW-DARK {
        background-color: #cccc00;
    }

    .PURPLE {
        background-color: #800080;
    }

    .PURPLE-LIGHT {
        background-color: #cc99ff;
    }

    .PURPLE-DARK {
        background-color: #4b0082;
    }

    .ORANGE {
        background-color: #ffa500;
    }

    .ORANGE-LIGHT {
        background-color: #ffc878;
    }

    .ORANGE-DARK {
        background-color: #d98c00;
    }

    .GRAY {
        background-color: #808080;
    }

    .GRAY-LIGHT {
        background-color: #cccccc;
    }

    .GRAY-DARK {
        background-color: #555555;
    }

    /* Modern & Aesthetic Colors & Shades */
    .TEAL {
        background-color: #008080;
    }

    .TEAL-LIGHT {
        background-color: #4db8b8;
    }

    .TEAL-DARK {
        background-color: #006666;
    }

    .AQUA {
        background-color: #00ffff;
    }

    .AQUA-LIGHT {
        background-color: #66ffff;
    }

    .AQUA-DARK {
        background-color: #00cccc;
    }

    /* ... (Other modern & aesthetic colors & shades) ... */

    /* Earthy & Neutral Tones & Shades */
    .BEIGE {
        background-color: #f5f5dc;
    }

    .BEIGE-LIGHT {
        background-color: #f8f8e8;
    }

    .BEIGE-DARK {
        background-color: #d9d9b3;
    }

    .BROWN {
        background-color: #a52a2a;
    }

    .BROWN-LIGHT {
        background-color: #c55a5a;
    }

    .BROWN-DARK {
        background-color: #800000;
    }

    /* ... (Other earthy & neutral colors & shades) ... */

    /* Coffee-Inspired Colors & Shades */
    .COFFEE {
        background-color: #6f4e37;
    }

    .COFFEE-LIGHT {
        background-color: #a17c6b;
    }

    .COFFEE-DARK {
        background-color: #513525;
    }

    .LATTE {
        background-color: #c59475;
    }

    .LATTE-LIGHT {
        background-color: #d8b9a2;
    }

    .LATTE-DARK {
        background-color: #9c6d4b;
    }

    /* Modern & Aesthetic Colors & Shades */
    .TEAL {
        background-color: #008080;
    }

    .TEAL-LIGHT {
        background-color: #4db8b8;
    }

    .TEAL-DARK {
        background-color: #006666;
    }

    .AQUA {
        background-color: #00ffff;
    }

    .AQUA-LIGHT {
        background-color: #66ffff;
    }

    .AQUA-DARK {
        background-color: #00cccc;
    }

    .CORAL {
        background-color: #ff7f50;
    }

    .CORAL-LIGHT {
        background-color: #ffb399;
    }

    .CORAL-DARK {
        background-color: #e6602e;
    }

    .FUCHSIA {
        background-color: #ff00ff;
    }

    .FUCHSIA-LIGHT {
        background-color: #ff99ff;
    }

    .FUCHSIA-DARK {
        background-color: #cc00cc;
    }

    .INDIGO {
        background-color: #4b0082;
    }

    .INDIGO-LIGHT {
        background-color: #8a55d7;
    }

    .INDIGO-DARK {
        background-color: #360059;
    }

    .LIME {
        background-color: #00ff00;
    }

    .LIME-LIGHT {
        background-color: #99ff99;
    }

    .LIME-DARK {
        background-color: #00cc00;
    }

    .OLIVE {
        background-color: #808000;
    }

    .OLIVE-LIGHT {
        background-color: #c0c066;
    }

    .OLIVE-DARK {
        background-color: #666600;
    }

    .TURQUOISE {
        background-color: #40e0d0;
    }

    .TURQUOISE-LIGHT {
        background-color: #79f2e6;
    }

    .TURQUOISE-DARK {
        background-color: #20b2aa;
    }

    /* Earthy & Neutral Tones & Shades */
    .BEIGE {
        background-color: #f5f5dc;
    }

    .BEIGE-LIGHT {
        background-color: #f8f8e8;
    }

    .BEIGE-DARK {
        background-color: #d9d9b3;
    }

    .BROWN {
        background-color: #a52a2a;
    }

    .BROWN-LIGHT {
        background-color: #c55a5a;
    }

    .BROWN-DARK {
        background-color: #800000;
    }

    .CREAM {
        background-color: #fffdd0;
    }

    .CREAM-LIGHT {
        background-color: #ffffe6;
    }

    .CREAM-DARK {
        background-color: #ece0a1;
    }

    .KHAKI {
        background-color: #f0e68c;
    }

    .KHAKI-LIGHT {
        background-color: #f7f2d4;
    }

    .KHAKI-DARK {
        background-color: #bdb76b;
    }

    .MAUVE {
        background-color: #e0b0ff;
    }

    .MAUVE-LIGHT {
        background-color: #e6ccff;
    }

    .MAUVE-DARK {
        background-color: #b380cc;
    }

    .TAN {
        background-color: #d2b48c;
    }

    .TAN-LIGHT {
        background-color: #d9c4a8;
    }

    .TAN-DARK {
        background-color: #998560;
    }

    .TAUPE {
        background-color: #483c32;
    }

    .TAUPE-LIGHT {
        background-color: #78685a;
    }

    .TAUPE-DARK {
        background-color: #30251c;
    }

    .ECRU {
        background-color: #c2b280;
    }

    .ECRU-LIGHT {
        background-color: #d4c49c;
    }

    .ECRU-DARK {
        background-color: #948550;
    }

    /* Coffee-Inspired Colors & Shades */
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
                        <li class="active">Mens Clothing</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!-- Begin Li's Content Wraper Area -->
        <div class="content-wraper pt-60 pb-60 pt-sm-30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 order-1 order-lg-2">
                        <div class="shop-products-wrapper">
                            <div class="tab-content">
                                <div id="grid-view" class="tab-pane fade active show" role="tabpanel">
                                    <div class="product-area shop-product-area">
                                        <div class="row">
                                            <?php
                                            // Fetch product data (filtered by product type AND selected color)
                                            $productColor = $_GET['Product_ColorName'];
                                            $productQuery = "SELECT * FROM product_list pl 
                                            JOIN product_colors pc ON pl.product_id = pc.product_id
                                            WHERE pl.product_type = 'Men Fashion' 
                                                AND pc.product_color = ?";

                                            $stmtProduct = $conn->prepare($productQuery);
                                            $stmtProduct->bind_param("s", $productColor);
                                            $stmtProduct->execute();
                                            $productResult = $stmtProduct->get_result();

                                            if ($productResult->num_rows > 0) {
                                                while ($product = $productResult->fetch_assoc()) {
                                                    $productId = $product['product_id'];
                                                    $productImage = $product['product_image'];
                                            ?>
                                                    <div class="col-lg-4 col-md-4 col-sm-6 mt-40">
                                                        <div class="single-product-wrap">
                                                            <div class="product-image">
                                                                <a href="single-product.php?product_id=<?php echo $productId; ?>">
                                                                <img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="product_desc">
                                                            <div class="product_desc_info">
                                                                <div class="product-review">
                                                                </div>
                                                                <h4><a class="product_name" href="single-product.php?product_id=<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></a></h4>
                                                                <div class="price-box">
                                                                    <span class="new-price">₱<?php echo $product['product_price']; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="add-actions">
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            } else {
                                                echo "No products found for the selected color.";
                                            }

                                            $stmtProduct->close();
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="paginatoin-area">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 pt-xs-15">
                                            <p>Showing 1-12 of 13 item(s)</p>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <ul class="pagination-box pt-xs-20 pb-xs-15">
                                                <li><a href="#" class="Previous"><i class="fa fa-chevron-left"></i> Previous</a>
                                                </li>
                                                <li class="active"><a href="#">1</a></li>
                                                <li><a href="#">2</a></li>
                                                <li><a href="#">3</a></li>
                                                <li>
                                                    <a href="#" class="Next"> Next <i class="fa fa-chevron-right"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 order-2 order-lg-1">

                        <!--sidebar-categores-box start  -->
                        <div class="sidebar-categores-box">
                            <div class="sidebar-title">
                                <h2>Filter By</h2>
                            </div>

                            <!-- filter-sub-area start -->
                            <div class="filter-sub-area pt-sm-10 pt-xs-10">
                                <h5 class="filter-sub-titel">Size</h5>
                                <div class="size-checkbox">
                                    <div class="filter-section">
                                        <label for="size-filter">Filter by Size:</label>
                                        <select id="size-filter" name="size-filter">
                                            <option value="">All Sizes</option>
                                            <?php
                                            // Fetch distinct sizes from your database
                                            $sizeQuery = "SELECT DISTINCT product_size FROM product_sizes";
                                            $sizeResult = $conn->query($sizeQuery);

                                            while ($row = $sizeResult->fetch_assoc()) {
                                                $size = $row['product_size'];
                                                $selected = (isset($_GET['size']) && $_GET['size'] == $size) ? 'selected' : '';
                                                echo "<option value='$size' $selected>$size</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="filter-section">
                                        <label for="color-filter">Filter by Color:</label>
                                        <select id="color-filter" name="color-filter">
                                            <option value="">All Colors</option>
                                            <?php
                                            $colorQuery = "SELECT DISTINCT product_color FROM product_colors";
                                            $colorResult = $conn->query($colorQuery);

                                            while ($row = $colorResult->fetch_assoc()) {
                                                $color = $row['product_color'];
                                                $selected = (isset($_GET['Product_ColorName']) && $_GET['Product_ColorName'] == $color) ? 'selected' : '';

                                                // Create a color swatch element with inline styling
                                                echo "<option value='$color' $selected>
                                                <span class='color-swatch' style='background-color: $color;'></span> 
                                                $color
                                            </option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- <button type="submit">Apply Filter</button> -->
                            <div class="filter-sub-area pt-sm-10 pt-xs-10">
                                <h5 class="filter-sub-titel">Color</h5>
                                <div class="color-categoriy">

                                    <ul>
                                        <!-- <ul > -->
                                        <?php
                                        // Fetch product data
                                        $productQuerycolor = "SELECT DISTINCT color_name FROM product_allcolors";
                                        $productResultColor = $conn->query($productQuerycolor);

                                        if ($productResultColor->num_rows > 0) {
                                            while ($productColor = $productResultColor->fetch_assoc()) {

                                                $productColor['color_name'] = strtoupper($productColor['color_name']); // Convert to uppercase
                                                // i want the productColor[color_name] make it all caps
                                        ?>

                                                <li style="margin-right: 17px; margin-top: 8px; border-radius: 50%;
                                                            cursor: pointer;">
                                                    <a href="customer_color_product.php?Product_ColorName=<?php echo $productColor['color_name']; ?>" style="margin-left: 0px;">
                                                        <span style=" height: 20px; width: 20px; border-radius: 50%; cursor: pointer;" class="<?php echo $productColor['color_name']; ?>">
                                                        </span>

                                                    </a>


                                                    <a style=" text-transform: capitalize;" href="customer_color_product.php?Product_ColorName=<?php echo $productColor['color_name']; ?>"> <?php echo $productColor['color_name']; ?>
                                                    </a>
                                                </li>

                                                <!-- <li><span class="white"></span><a href="#">White (1)</a></li>
                                                <li><span class="black"></span><a href="#">Black (1)</a></li>
                                                <li><span class="Orange"></span><a href="#">Orange (3) </a></li>
                                                <li><span class="Blue"></span><a href="#">Blue (2) </a></li> -->
                                        <?php
                                            }
                                        } else {
                                            echo "No products Color found.";
                                        }

                                        // ... (Rest of your PHP code) ...
                                        ?>
                                    </ul>

                                    <!-- <form action="#">
                                        <ul>
                                            <li><span class="white"></span><a href="#">White (1)</a></li>
                                            <li><span class="black"></span><a href="#">Black (1)</a></li>
                                            <li><span class="Orange"></span><a href="#">Orange (3) </a></li>
                                            <li><span class="Blue"></span><a href="#">Blue (2) </a></li>
                                        </ul>
                                    </form> -->

                                </div>


                            </div>

                            <!-- filter-sub-area end -->
                            <!-- filter-sub-area start -->
                            <div class="filter-sub-area pt-sm-10 pb-sm-15 pb-xs-15">
                                <h5 class="filter-sub-titel">Dimension</h5>
                                <div class="categori-checkbox">
                                    <form action="#">
                                        <ul>
                                            <li><input type="checkbox" name="product-categori"><a href="#">40x60cm (6)</a></li>
                                            <li><input type="checkbox" name="product-categori"><a href="#">60x90cm (6)</a></li>
                                            <li><input type="checkbox" name="product-categori"><a href="#">80x120cm (6)</a></li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                            <!-- filter-sub-area end -->
                        </div>
                        <!--sidebar-categores-box end  -->
                        <!-- category-sub-menu start -->

                    </div>
                </div>
            </div>
        </div>
        <!-- Content Wraper Area End Here -->
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
                                            <a href="https://www.plus.google.com/discover" data-toggle="tooltip" target="_blank" title="Google +">
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


                        </div>
                    </div>
                </div>
            </div>
        </div>
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

<!-- shop-left-sidebar31:48-->

</html>