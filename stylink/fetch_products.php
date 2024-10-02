<?php
// Database connection (replace with your credentials)
include("include/config1.php");


// Filter Parameters
$filterLabels = [];
$productQuery = "SELECT * FROM product_list pl"; // Base product query
$queryParams = [];
$paramTypes = "";
$whereConditions = [];


// Handle Shop Name Filtering
if (isset($_GET['shop_name']) && !empty($_GET['shop_name'])) {
    $selectedShopName = $conn->real_escape_string($_GET['shop_name']);
    $whereConditions[] = "pl.shop_name = ?";
    $queryParams[] = $selectedShopName;
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

    $productQuery .= " JOIN product_colors pc ON pl.product_id = pc.product_id";

    // Use a placeholder for IN clause conditions
    $whereConditions[] = "pc.product_color IN (" . implode(',', array_fill(0, count($selectedColors), '?')) . ")";
    $queryParams = array_merge($queryParams, $selectedColors); // Add selected colors to query params
    $paramTypes .= str_repeat('s', count($selectedColors));  // Add type specifiers for each color
}


// Add WHERE condition for product type
$whereConditions[] = "pl.product_type = 'Men Fashion'";

// Combine WHERE conditions
if (!empty($whereConditions)) {
    $productQuery .= " WHERE " . implode(" AND ", $whereConditions);
}
// Execute the query, fetch products
$stmtProduct = $conn->prepare($productQuery);
if (!empty($queryParams)) {
    $stmtProduct->bind_param($paramTypes, ...$queryParams);
}
$stmtProduct->execute();
$productResult = $stmtProduct->get_result();

// Generate HTML for products
if ($productResult->num_rows > 0) {
    ob_start(); // Start output buffering

    while ($product = $productResult->fetch_assoc()) {
        $productId = $product['product_id'];
        $productImage = $product['product_image'];
?>
        <div class="col-lg-3 col-md-3 col-sm-6 mt-0">
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
                                <a href="shop_view.php?Shop Name=<?php echo $product['shop_name']; ?>"><?php echo $product['shop_name']; ?></a>
                            </h5>
                        </div>
                        <h4><a class="product_name" href="single-product.php?product_id=<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></a></h4>
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

    $productHtml = ob_get_clean(); // Get the buffered output
    echo $productHtml; // Send the HTML to the AJAX request

} else {
    echo "No products found.";
}

$conn->close();
?>