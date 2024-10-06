<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");

if (!isset($_SESSION["username"])) {
    header("Location: user_login.php");
    exit;
}

$username = $_SESSION["username"];
// Fetch product IDs, colors, and styles from customer_wardrobe (including id)
$stmt = $conn->prepare("SELECT id, product_id, product_color, product_style
                        FROM customer_wardrobe 
                        WHERE username = ? 
                        AND (product_style = 'Men Tops' OR product_style = 'Women Tops')");
$stmt->bind_param("s", $username);
$stmt->execute();
$resultTop = $stmt->get_result();

$productImages = [];
while ($row = $resultTop->fetch_assoc()) {
    $key = $row['id'] . '-' . $row['product_color'];

    // Check for image in product_colors
    $stmtColorImage = $conn->prepare("SELECT product_image 
                                     FROM product_colors 
                                     WHERE product_id = ? AND product_color = ?");
    $stmtColorImage->bind_param("ss", $row['product_id'], $row['product_color']);
    $stmtColorImage->execute();
    $colorImageResult = $stmtColorImage->get_result();

    if ($colorImageResult->num_rows > 0) {
        $colorImageRow = $colorImageResult->fetch_assoc();
        $productImages[$key] = ['image' => $colorImageRow['product_image'], 'id' => $row['product_id']];
    } else {
        // If no color-specific image, fetch from product_list 
        $stmtProductImage = $conn->prepare("SELECT product_image, product_id
                                            FROM product_list 
                                            WHERE product_id = ? 
                                            AND product_style = ?"); // Use the correct style
        $stmtProductImage->bind_param("ss", $row['product_id'], $row['product_style']);
        $stmtProductImage->execute();
        $productImageResult = $stmtProductImage->get_result();

        if ($productImageResult->num_rows > 0) {
            $productImageRow = $productImageResult->fetch_assoc();
            $productImages[$key] = ['image' => $productImageRow['product_image'], 'id' => $row['product_id']];
        }
    }
}
// Filter $productIds to only include IDs with images in $productImages (no need for $productIds2)
$productIds = array_keys($productImages);



$stmt = $conn->prepare("SELECT id, product_id, product_color FROM customer_wardrobe WHERE username = ? AND product_style = 'Men Bottoms' OR product_Style = 'Women Bottoms'");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$productImages2 = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['id'] . '-' . $row['product_color'];

    // Check for image in product_colors
    $stmtColorImage = $conn->prepare("SELECT product_image 
                                     FROM product_colors 
                                     WHERE product_id = ? AND product_color = ?");
    $stmtColorImage->bind_param("ss", $row['product_id'], $row['product_color']);
    $stmtColorImage->execute();
    $colorImageResult = $stmtColorImage->get_result();

    if ($colorImageResult->num_rows > 0) {
        $colorImageRow = $colorImageResult->fetch_assoc();
        $productImages2[$key] = ['image' => $colorImageRow['product_image'], 'id' => $row['product_id']];
    } else {
        // If no color-specific image, fetch from product_list 
        $stmtProductImage = $conn->prepare("SELECT product_image, product_id
                                            FROM product_list 
                                            WHERE product_id = ? 
                                            AND product_style = ?"); // Use the correct style
        $stmtProductImage->bind_param("ss", $row['product_id'], $row['product_style']);
        $stmtProductImage->execute();
        $productImageResult = $stmtProductImage->get_result();

        if ($productImageResult->num_rows > 0) {
            $productImageRow = $productImageResult->fetch_assoc();
            $productImages2[$key] = ['image' => $productImageRow['product_image'], 'id' => $row['product_id']];
        }
    }
}
// Filter $productIds to only include IDs with images in $productImages (no need for $productIds2)
$productIds2 = array_keys($productImages2);



// Check wardrobe_bottom Table (Filter by Username)
$sql_bottom = "SELECT COUNT(*) FROM wardrobe_bottom WHERE username = ?";
$stmt_bottom = $conn->prepare($sql_bottom);
$stmt_bottom->bind_param("s", $username);
$stmt_bottom->execute();
$result_bottom = $stmt_bottom->get_result();
$row_bottom = $result_bottom->fetch_row();
$has_bottom = ($row_bottom[0] > 0);

// Check wardrobe_top Table (Filter by Username)
$sql_top = "SELECT COUNT(*) FROM wardrobe_top WHERE username = ?";
$stmt_top = $conn->prepare($sql_top);
$stmt_top->bind_param("s", $username);
$stmt_top->execute();
$result_top = $stmt_top->get_result();
$row_top = $result_top->fetch_row();
$has_top = ($row_top[0] > 0);




ob_end_flush();

?>
<!-- blog-2-column31:55-->



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
    .show_image img {
        width: 50%;
        aspect-ratio: 1 / 1;
        /* Sets a 1:1 aspect ratio directly */
        object-fit: cover;
    }

    .show_image_top,
    .show_image_bottom {
        display: flex;
        justify-content: center;
    }

    input[type=checkbox] {
        height: 30px;
        width: 30px;
        /* width: fit-content; */
    }

    .add-actions-link {
        display: inline-block;
        margin-top: 0px;
        padding-top: 20px;
        -webkit-transition: all 300ms ease-in 0s;
        transition: all 300ms ease-in 0s;
        width: 100%;
    }

    /* Basic Styles */
    .btn-container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background-color: #f5f5f5;
        padding: 10px;
        border-radius: 8px;
    }

    .btn-container-link {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
    }

    .links-details {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        margin: 0 5px;
        background-color: #fff;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .links-details:hover {
        background-color: #e0e0e0;
    }

    .links-details i {
        margin-right: 5px;
        font-size: 18px;
    }


    input[type=checkbox] {
        width: 18px;
        height: 18px;
    }

    /* Checkbox Round Styling */
    .top_id_send,
    .bottom_id_send {
        /* Customize checkbox appearance */
        accent-color: #457b75;
        /* Blue */
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: solid 1px #007bff;
        /* Make the checkbox round */
        appearance: none;
        -webkit-appearance: none;

    }

    /* Optional: Styling for Checked State */
    .top_id_send:checked,
    .bottom_id_send:checked {
        background-color: #007bff;
        /* Fill with blue when checked */
    }

    .top_id_send:checked::before,
    .bottom_id_send::before {
        content: '✓';
        /* You can use a checkmark icon from a font library instead */
        display: block;
        text-align: center;
        color: white;
        font-size: 14px;
    }

    /* Label Styling */
    .top_id_send+label,
    .bottom_id_send+label {
        /* Targets the label immediately after the checkbox */
        cursor: pointer;
        /* Indicate interactivity */
        margin-left: 8px;
        /* Add some space between the checkbox and label */
    }

    /* Optional: Styling for Checked State */
    .top_id_send:checked+label,
    .bottom_id_send:checked+label {
        font-weight: bold;
        /* Add other styles if desired */
    }

    /* Optional: Styling for Hover State */
    .top_id_send:hover+label .bottom_id_send:hover+label {
        text-decoration: underline;
    }

    /* Mobile-Specific Styles (Media Query) */
    @media (max-width: 768px) {

        /* Adjust the breakpoint as needed */
        .btn-container {
            flex-direction: column;
            /* Stack elements vertically */
            align-items: flex-start;
            /* Align to the left */
        }

        .links-details {
            margin: 5px 0;
            /* Add vertical spacing */
            width: 100%;
            /* Make links full width */
            justify-content: center;
            /* Center content horizontally */
        }
    }

    .save-button:disabled {
        cursor: not-allowed;
        /* Change to the "not-allowed" symbol */
        /* Optionally add other styles like opacity or background color */
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
                        <a class="nav-link active" id="home-tab1" href="customer_wardrobe.php" aria-controls="home-1" aria-selected="true">Product Wardrobe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab1" href="customer_customizewardrobe.php" aria-controls="profile-1" aria-selected="false">Customize Styling</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="profile-tab1" href="customer_favoritewardrobe.php" aria-controls="profile-1" aria-selected="false">Favorite Outfits</a>
                    </li>
                </ul>

                <div class="row single-product-area">
                    <div class="col-lg-6 col-md-6">
                        <h4>Top</h4>
                        <!-- Product Details Left -->
                        <div class="product-details-left sp-tab-style-left-page">
                            <div class="product-details-images slider-navigation-1">
                                <?php foreach ($productImages as $productData) : ?>
                                    <div class="lg-image">
                                        <img src="<?php echo $productData['image']; ?>" alt="product image">
                                        <span><?php echo $productData['id']; ?></span>
                                        <br>
                                        <a href="single-product.php?product_id=<?php echo $productData['id']; ?>">
                                            <button class="add-to-cart-outline col-md-12" id="addToCartBtn" type="button">View Product</button>
                                        </a>
                                        <br>
                                        <a href="remove_product.php?product_id=<?php echo $productData['id']; ?>">
                                            <button class="add-to-cart-outline col-md-12" type="button">Remove Product</button>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="tab-style-left">
                                <?php foreach ($productImages as $productData) : ?>
                                    <div class="sm-image"> <img src="<?php echo $productData['image']; ?>" alt="product thumb"></div>
                                <?php endforeach; ?>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <h4>Bottom</h4>
                        <!-- Product Details Right Side -->
                        <div class="product-details-left sp-tab-style-left-page">
                            <div class="product-details-images slider-navigation-1">
                                <!-- <php foreach ($productIds2 as $productId2) : ?>
                                    <div class="lg-image">
                                        <img src="<php echo $productImages2[$productId2]; ?>" alt="product image">
                                        <br>
                                        <php echo $key1; ?>
                                        <a href="single-product.php?product_id=<?php echo $product_id; ?>">
                                            <button class="add-to-cart-outline col-md-12" id="addToCartBtn" type="button">View Product</button>
                                        </a>
                                    </div>
                                <php endforeach; ?> -->

                                <?php foreach ($productImages2 as $productData) : ?>
                                    <div class="lg-image">
                                        <img src="<?php echo $productData['image']; ?>" alt="product image">
                                        <!-- <span><?php echo $productData['id']; ?></span> -->
                                        <br>
                                        <a href="single-product.php?product_id=<?php echo $productData['id']; ?>">
                                            <button class="add-to-cart-outline col-md-12" id="addToCartBtn" type="button">View Product</button>
                                        </a>
                                        <br>
                                        <a href="remove_product.php?product_id=<?php echo $productData['id']; ?>">
                                            <button class="add-to-cart-outline col-md-12" type="button">Remove Product</button>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="tab-style-left">
                                <?php foreach ($productImages2 as $productData) : ?>
                                    <div class="sm-image"> <img src="<?php echo $productData['image']; ?>" alt="product image thumb"></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <!--// Product Details Left -->
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <center>
                            <!-- <h4 class="card-title">Style Outfit Randomizer</h4> -->
                            <br>
                            <!-- <button class="add-to-cart-outline" id="random_style_generator">Randomizer Outfit</button> -->
                            <br>
                            <br>

                            <div class="show_generate_outfit">
                                <form action="add_favorite_product.php" method="post">
                                    <input type="hidden" id="top_id_input" name="top_id" value="">
                                    <input type="hidden" id="top_color_input" name="top_color" value="">

                                    <input type="hidden" id="bottom_id_input" name="bottom_id" value="">
                                    <input type="hidden" id="bottom_color_input" name="bottom_color" value="">
                                    <button hidden type="submit" class="btn btn-outline-dark save-to-favorite" id="save-to-favorite" style="display: none;"></button>
                                    <div class="row">
                                        <div class="col-md-12 show_image_top"></div>
                                        <div class="col-md-12 show_image_bottom"></div>
                                    </div>
                                </form>
                            </div>

                        </center>
                    </div>
                    <div class="col-md-6">

                        <!-- <div class="contact-page-side-content about-text-wrap">
                            <p><i>
                                    Note:
                                    For more Random Outfit style you need to upload more outfit Tops and Bottom Clothes.
                                </i>
                            </p>
                        </div> -->
                    </div>
                </div>


                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const generateButton = document.getElementById('random_style_generator');
                        const saveButton = document.getElementById('save-to-favorite');
                        const topImageContainer = document.querySelector('.show_image_top');
                        const bottomImageContainer = document.querySelector('.show_image_bottom');
                        const topIdInput = document.getElementById('top_id_input');
                        const bottomIdInput = document.getElementById('bottom_id_input');
                        const topColorInput = document.getElementById('top_color_input');
                        const bottomColorInput = document.getElementById('bottom_color_input');

                        saveButton.style.display = 'none'; // Initially hide the save button

                        generateButton.addEventListener('click', function() {
                            // Get the keys of the arrays (which are your unique product IDs)
                            const productIdsArray = <?php echo json_encode(array_keys($productImages)); ?>;
                            const productIds2Array = <?php echo json_encode(array_keys($productImages2)); ?>;

                            // Randomly select a combined key (id-color) from each array
                            const randomTopKey = productIdsArray[Math.floor(Math.random() * productIdsArray.length)];
                            const randomBottomKey = productIds2Array[Math.floor(Math.random() * productIds2Array.length)];

                            const topKeyParts = randomTopKey.split("-"); // Assuming your key format is "id-color"
                            const bottomKeyParts = randomBottomKey.split("-");

                            // Extracting ID and Color
                            const randomTopId1 = topKeyParts[0];
                            const randomTopColor = topKeyParts[1];
                            const randomBottomId1 = bottomKeyParts[0];
                            const randomBottomColor = bottomKeyParts[1];


                            // Randomly select a product ID from each array
                            const randomTopIndex = Math.floor(Math.random() * productIdsArray.length);
                            const randomTopId = productIdsArray[randomTopIndex];
                            const randomTopImage = <?php echo json_encode($productImages); ?>[randomTopId];

                            const randomBottomIndex = Math.floor(Math.random() * productIds2Array.length);
                            const randomBottomId = productIds2Array[randomBottomIndex];
                            const randomBottomImage = <?php echo json_encode($productImages2); ?>[randomBottomId];


                            // Create image elements and set their sources
                            const topImg = document.createElement('img');
                            topImg.src = randomTopImage;
                            topImg.alt = 'Random Top';
                            topImg.classList.add('lg-image-cloth');

                            const bottomImg = document.createElement('img');
                            bottomImg.src = randomBottomImage;
                            bottomImg.alt = 'Random Bottom';
                            bottomImg.classList.add('lg-image-cloth');

                            // Clear previous content
                            topImageContainer.innerHTML = '';
                            bottomImageContainer.innerHTML = '';

                            // Store in Separate Input Fields
                            topIdInput.value = randomTopId1;
                            topColorInput.value = randomTopColor;
                            bottomIdInput.value = randomBottomId1;
                            bottomColorInput.value = randomBottomColor;

                            // Append new images
                            topImageContainer.appendChild(topImg);
                            bottomImageContainer.appendChild(bottomImg);

                            saveButton.style.display = 'block'; // Show the save button
                        });
                    });
                </script>



            </div>
        </div>

        <?php include("include/footer.php"); ?>

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

    <script src="js/data-table.js"></script>

</body>

<!-- blog-2-column31:55-->

</html>