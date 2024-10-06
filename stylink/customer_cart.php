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
    // $productStocks = $row['product_stocks'];

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

<!-- shopping-cart31:32-->

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
                        <li class="active">Shopping Cart</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!--Shopping Cart Area Strat-->
        <div class="Shopping-cart-area pt-60 pb-60">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php if ($cartItemCount > 0) : ?>
                            <div class=" table-responsive">
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

                                <table id="order-listing" class="table">
                                    <thead>
                                        <tr>
                                            <th>Product Image</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>

                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach ($categories as $category) : ?>
                                            <tr>
                                                <!-- <td class="li-product-thumbnail"><a href="#"><img src="images/product/small-size/5.jpg" alt="Li's Product Image"></a></td> -->
                                                <td class="product-image1">
                                                    <?php


                                                    $selectedImage1 = $category['product_id'];
                                                    $selectedColor = $category['product_color'];
                                                    $productStocks = $category['product_stocks'];





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


                                                    ?>
                                                    <a href="single-product.php?product_id=<?php echo $category['product_id']; ?>">
                                                        <img src="<?php echo $productImage; ?>" alt="<?php echo $productImage; ?>">

                                                    </a>
                                                    <?php
                                                    // $conn->close();
                                                    ?>


                                                    <!-- <php echo $category['product_id']; ?> -->
                                                </td>
                                                <td>
                                                    <h5><a href="single-product.php?product_id=<?php echo $category['product_id']; ?>"><?php echo $category['product_name']; ?></a></h5>
                                                    
                                                    <a href="#">Color: <?php echo $category['product_color']; ?></a><br>
                                                    <a href="#">Size: <?php echo $category['product_size']; ?></a><br>
                                                    <a href="#">Stocks: <?php echo $category['product_stocks']; ?></a><br>
                                                </td>
                                                <td class="li-product-name" style="text-align: start;">
                                                    <span id="product_price_<?php echo $productId; ?>" data-price="<?php echo $category['product_price']; ?>">
                                                        ₱<?php echo $category['product_price']; ?>
                                                    </span>
                                                </td>
                                                <td class="quantity">
                                                    <div class="quantity-control">
                                                        <button class="decrease-btn">-</button>
                                                        <input readonly class="cart-plus-minus-box" id="product_quantity_<?php echo $productId; ?>" value="<?php echo $category['product_quantity']; ?>" type="text" data-product-id="<?php echo $category['product_id']; ?>" data-product-color="<?php echo $category['product_color']; ?>" data-product-size="<?php echo $category['product_size']; ?>" data-product-stocks="<?php echo $productStocks; ?>">
                                                        <button class="increase-btn">+</button>
                                                    </div>
                                                </td>
                                                <td>

                                                    <span id="total_price_<php echo $productId; ?>">₱<?php
                                                                                                        $total = $category['product_price'] * $category['product_quantity'];
                                                                                                        $overallTotal += $total; // Accumulate total into overallTotal
                                                                                                        echo number_format($total, 2); // Display total with 2 decimal places
                                                                                                        ?>
                                                    </span>

                                                </td>
                                                <td>
                                                    <a href="delete_cart.php?product_id=<?php echo $category['product_id']; ?>&product_color=<?php echo $category['product_color']; ?>&product_size=<?php echo $category['product_size']; ?>" class="btn btn-outline-danger btn-fw" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                    </tbody>
                                </table>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const decreaseBtns = document.querySelectorAll('.decrease-btn');
                                        const increaseBtns = document.querySelectorAll('.increase-btn');
                                        const overallTotalSpan = document.getElementById('overall_total');

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
                                        }


                                    });
                                </script>

                            </div>

                            <form action="#">
                                <div class="row">
                                    <div class="col-md-5 ml-auto">
                                        <div class="cart-page-total">
                                            <h2>Cart totals</h2>
                                            <ul>
                                                <!-- <li>Subtotal <span></span></li> -->

                                                <li>Overall Total: <span id="overall_total"></span></li>
                                                <li>Total Item Count: <span id="total_count"><?php echo $totalCount; ?></span></li>
                                                <div class="li-product-cart-total"></div>
                                            </ul>
                                            <a href="checkout.php">Proceed to checkout</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php else : ?>
                            <span>No Product Added Yet</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!--Shopping Cart Area End-->

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
</body>

<!-- shopping-cart31:32-->

</html>