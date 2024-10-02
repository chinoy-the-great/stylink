<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");

$colorMap = [
    'Red' => 'Red',
    'Blue' => 'Blue',
    'Green' => 'Green',
    'Yellow' => 'Yellow',
    // Add more colors as needed
];



ob_end_flush();

?>
<link rel="stylesheet" href="css/all_product.css">
<style>
    .filter-container {
        border: 1px solid #ddd;
        /* Add a border to the filter container */
        padding: 10px;
        margin-bottom: 20px;
    }

    .filter-list {
        display: inline-flex;
        /* Allow filter items to wrap */
        flex-wrap: wrap;
        gap: 5px;
        /* Add space between filter items */
    }

    .filter-item {
        background-color: #f0f0f0;
        /* Light background color */
        border: 1px solid #ddd;
        border-radius: 20px;
        /* Rounded corners */
        padding: 5px 10px;
        display: inline-flex;
        /* Keep content aligned within the item */
        align-items: center;
    }

    .remove-filter {
        background: none;
        border: none;
        cursor: pointer;
        margin-left: 5px;
        font-weight: bold;
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
                        <li class="active">All Products</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!-- Begin Li's Content Wraper Area -->
        <div class="content-wraper pt-60 pb-60 pt-sm-30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 order-1 order-lg-2">



                        <div class="shop-products-wrapper">
                            <div class="tab-content">
                                <div id="grid-view" class="tab-pane fade active show" role="tabpanel">
                                    <div class="product-area shop-product-area">
                                        <?php
                                        $filterLabels = []; // Array to store labels for active filters

                                        // Check for size filter
                                        if (isset($_GET['size']) && !empty($_GET['size'])) {
                                            $filterLabels['size'] = htmlspecialchars($_GET['size']);
                                        }
                                        // Check for color filter (CORRECTED)
                                        if (isset($_GET['color'])) {  // Check if any color filters exist
                                            $selectedColors = is_array($_GET['color']) ? $_GET['color'] : [$_GET['color']]; // Handle both single and multiple selections
                                            foreach ($selectedColors as $color) {
                                                $filterLabels['color-' . $color] = htmlspecialchars($color); // Assign unique keys to each color
                                            }
                                        }

                                        // Check for style filter
                                        if (isset($_GET['style-filter']) && !empty($_GET['style-filter'])) {
                                            $filterLabels['style-filter'] = htmlspecialchars($_GET['style-filter']);
                                        }

                                        if (isset($_GET['type-filter']) && !empty($_GET['type-filter'])) {
                                            $filterLabels['type-filter'] = htmlspecialchars($_GET['type-filter']);
                                        }

                                        // Check for category filter
                                        if (isset($_GET['category-filter']) && !empty($_GET['category-filter'])) {
                                            $filterLabels['category-filter'] = htmlspecialchars($_GET['category-filter']);
                                        }

                                        // Check if there are any active filters
                                        if (!empty($filterLabels)) {
                                        ?>
                                            <div class="filter-container" <?php if (empty($filterLabels)) echo 'style="display:none;"'; ?>>
                                                <h8>Filter By
                                                    <span class="filter-list">
                                                        <?php
                                                        // Group color filters together (no change here)
                                                        $colorFilters = array_filter($filterLabels, function ($key) {
                                                            return strpos($key, 'color-') === 0;
                                                        }, ARRAY_FILTER_USE_KEY);

                                                        // Display other filters
                                                        foreach (array_diff_key($filterLabels, $colorFilters) as $filterKey => $filterValue) {
                                                            $filterName = ucfirst(str_replace('-', ' ', $filterKey));
                                                            echo "<span class='filter-item'>$filterName: $filterValue <button class='remove-filter' data-filter='$filterKey'>x</button></span>";
                                                        }

                                                        // Display color filters separately (UPDATED)
                                                        foreach ($colorFilters as $filterKey => $filterValue) {
                                                            echo "<span class='filter-item color-filter' data-filter='$filterKey' style='background-color: $filterValue; border-color: $filterValue;'>$filterValue <button class='remove-filter' data-filter='$filterKey'>x</button></span>";
                                                        }
                                                        ?>
                                                    </span>
                                                </h8>
                                            </div>

                                            <script>
                                                const removeFilterButtons = document.querySelectorAll('.remove-filter');

                                                removeFilterButtons.forEach(button => {
                                                    button.addEventListener('click', () => {
                                                        const filterKey = button.dataset.filter;
                                                        const urlParams = new URLSearchParams(window.location.search);

                                                        // If it's a color filter
                                                        if (filterKey.startsWith('color-')) {
                                                            const colorValue = filterKey.replace('color-', '');
                                                            const selectedColors = urlParams.getAll('color[]');
                                                            const updatedColors = selectedColors.filter(color => color !== colorValue);
                                                            urlParams.delete('color[]');
                                                            updatedColors.forEach(color => urlParams.append('color[]', color));
                                                        } else { // Other filters
                                                            urlParams.delete(filterKey);
                                                        }

                                                        const newUrl = window.location.pathname + '?' + urlParams.toString();
                                                        window.location.href = newUrl;
                                                    });
                                                });
                                            </script>

                                        <?php } ?>



                                        <div class="row">
                                            <?php
                                            $filterLabels = []; // Array to store labels for active filters

                                            // Check for filters (size, color, style, type, category)
                                            if (isset($_GET['size']) && !empty($_GET['size'])) {
                                                $filterLabels['size'] = htmlspecialchars($_GET['size']);
                                            }
                                            if (isset($_GET['color'])) {
                                                $selectedColors = is_array($_GET['color']) ? $_GET['color'] : [$_GET['color']];
                                                foreach ($selectedColors as $color) {
                                                    $filterLabels['color-' . $color] = htmlspecialchars($color);
                                                }
                                            }
                                            if (isset($_GET['style-filter']) && !empty($_GET['style-filter'])) {
                                                $filterLabels['style-filter'] = htmlspecialchars($_GET['style-filter']);
                                            }
                                            if (isset($_GET['type-filter']) && !empty($_GET['type-filter'])) {
                                                $filterLabels['type-filter'] = htmlspecialchars($_GET['type-filter']);
                                            }
                                            if (isset($_GET['category-filter']) && !empty($_GET['category-filter'])) {
                                                $filterLabels['category-filter'] = htmlspecialchars($_GET['category-filter']);
                                            }
                                            // Search Function
                                            function searchProducts($conn, $query)
                                            {
                                                $keywords = explode(' ', $conn->real_escape_string($query));

                                                $whereConditions = [];
                                                $paramTypes = "";
                                                $queryParams = [];

                                                foreach ($keywords as $keyword) {
                                                    $whereConditions[] = "(pl.product_name LIKE ? OR pl.product_description LIKE ?)";
                                                    $paramTypes .= "ss";
                                                    $queryParams[] = '%' . $keyword . '%';
                                                    $queryParams[] = '%' . $keyword . '%';
                                                }

                                                $productQuery = "SELECT * FROM product_list pl";
                                                if (!empty($whereConditions)) {
                                                    $productQuery .= " WHERE " . implode(" AND ", $whereConditions);
                                                }

                                                // Pagination Settings
                                                $itemsPerPage = 12;
                                                $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                                                $offset = ($currentPage - 1) * $itemsPerPage;
                                                $productQuery .= " LIMIT $offset, $itemsPerPage";

                                                $stmt = $conn->prepare($productQuery);
                                                if ($paramTypes !== "") {
                                                    $stmt->bind_param($paramTypes, ...$queryParams);
                                                }
                                                $stmt->execute();


                                                // Calculate Total Pages (Integrate with search and filters)
                                                $totalItemsQuery = "SELECT COUNT(*) as total FROM product_list pl";
                                                if (!empty($whereConditions)) {
                                                    $totalItemsQuery .= " WHERE " . implode(" AND ", $whereConditions);
                                                }
                                                $stmtTotal = $conn->prepare($totalItemsQuery);
                                                if ($paramTypes !== "") {
                                                    $stmtTotal->bind_param($paramTypes, ...$queryParams);
                                                }
                                                $stmtTotal->execute();

                                                // Fetch and free the result from the count query 
                                                $totalResult = $stmtTotal->get_result();  // Fetch result
                                                $totalItems = $totalResult->fetch_assoc()['total'];
                                                $totalPages = ceil($totalItems / $itemsPerPage);
                                                $totalResult->free();  // Free the result

                                                // Now it's safe to get results from the product query
                                                return $stmt->get_result(); // Return the product results
                                            }

                                            if (isset($_GET['query'])) {
                                                $query = $_GET['query'];
                                                $result = searchProducts($conn, $query);

                                                if ($result->num_rows > 0) {
                                                    while ($product = $result->fetch_assoc()) {
                                                        $productId = $product['product_id'];
                                                        $productImage = $product['product_image'];
                                            ?>
                                                        <div class="col-lg-3 col-md-3 col-sm-6 mt-40">
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
                                                                                <a href="single-shop.php?shop_name=<?php echo $product['shop_name']; ?>"><?php echo $product['shop_name']; ?></a>
                                                                            </h5>
                                                                            <h5 class="manufacturer">
                                                                                <a href="all_product.php?type-filter=<?php echo $product['product_typeclothes']; ?>">(<?php echo $product['product_typeclothes']; ?>)</a>
                                                                            </h5>
                                                                        </div>
                                                                        <h4 style="-webkit-box-orient: vertical; -webkit-line-clamp: 2; display: -webkit-box; overflow: hidden;">
                                                                            <a class="product_name" href="single-product.php?product_id=<?php echo $product['product_id']; ?>">
                                                                                <?php echo $product['product_name']; ?>
                                                                            </a>
                                                                        </h4>
                                                                        <div class="price-box">
                                                                            <span class="new-price">₱<?php echo $product['product_price']; ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="add-actions">
                                                                        <ul class="add-actions-link">
                                                                            <li class="add-cart active"><a href="single-product.php?product_id=<?php echo $product['product_id']; ?>">View Product</a></li>
                                                                            <li><a class="links-details" href="wishlist.html"><i class="fa fa-heart-o"></i></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                            <?php
                                                    }
                                                } else {
                                                    echo "No products found.";
                                                }
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div>

                                <div class="paginatoin-area">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 pt-xs-15">
                                            <p>Showing <?= min($offset + 1, $totalItems) ?> - <?= min($offset + $itemsPerPage, $totalItems) ?> of <?= $totalItems ?> item(s)</p>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <ul class="pagination-box pt-xs-20 pb-xs-15">
                                                <?php if ($currentPage > 1) : ?>
                                                    <li><a href="?page=<?= $currentPage - 1 ?>&<?= http_build_query($_GET) ?>" class="Previous"><i class="fa fa-chevron-left"></i> Previous</a></li>
                                                <?php endif; ?>

                                                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++) : ?>
                                                    <li class="<?= ($i == $currentPage) ? 'active' : '' ?>"><a href="?page=<?= $i ?>&<?= http_build_query($_GET) ?>"><?= $i ?></a></li>
                                                <?php endfor; ?>

                                                <?php if ($currentPage < $totalPages) : ?>
                                                    <li><a href="?page=<?= $currentPage + 1 ?>&<?= http_build_query($_GET) ?>" class="Next"> Next <i class="fa fa-chevron-right"></i></a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- shop-products-wrapper end -->
                    </div>
                    <div class="col-lg-2 order-2 order-lg-1">

                        <!--sidebar-categores-box start  -->
                        <div class="sidebar-categores-box">
                            <div class="sidebar-title">
                                <h2>Filter By</h2>
                                <a href="all_product.php">Clear Filter</a>

                            </div>

                            <!-- filter-sub-area start -->

                            <div class="filter-sub-area pt-sm-10 pt-xs-10">
                                <!-- <h5 class="filter-sub-titel">Product Style</h5> -->
                                <div class="category-sub-menu">
                                    <label for="type-filter" class="mt-5">Type:</label>
                                    <select id="type-filter" name="type-filter">
                                        <option value="">Filter Type...</option>
                                        <?php
                                        // // Fetch distinct sizes from your database
                                        $sizeQuery1 = "SELECT DISTINCT product_typeclothes FROM product_list";
                                        $sizeResult1 = $conn->query($sizeQuery1);

                                        while ($row = $sizeResult1->fetch_assoc()) {
                                            $product_typeclothes = $row['product_typeclothes'];
                                            $selected = (isset($_GET['product_typeclothes']) && $_GET['product_typeclothes'] == $product_typeclothes) ? 'selected' : '';
                                            echo "<option value='$product_typeclothes' $selected>$product_typeclothes</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="category-checkbox">
                                    <div class="category-sub-menu">
                                        <label for="size-filter" class="mt-5">Style:</label>
                                        <select id="style-filter" name="style-filter">
                                            <option value="">Filter Style...</option>
                                            <?php
                                            // // Fetch distinct sizes from your database
                                            $sizeQuery = "SELECT DISTINCT product_style FROM product_list";
                                            $sizeResult = $conn->query($sizeQuery);

                                            while ($row = $sizeResult->fetch_assoc()) {
                                                $size1 = $row['product_style'];
                                                $selected = (isset($_GET['product_style']) && $_GET['product_style'] == $size1) ? 'selected' : '';
                                                echo "<option value='$size1' $selected>$size1</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="category-sub-menu">
                                        <label for="size-filter" class="mt-5">Category:</label>
                                        <select id="category-filter" name="category-filter">
                                            <option value="">Filter Style...</option>
                                            <?php
                                            // // Fetch distinct category from your database
                                            $sizeQuery = "SELECT DISTINCT product_category FROM product_list";
                                            $sizeResult = $conn->query($sizeQuery);

                                            while ($row = $sizeResult->fetch_assoc()) {
                                                $size2 = $row['product_category'];
                                                $selected = (isset($_GET['product_category']) && $_GET['product_category'] == $size2) ? 'selected' : '';
                                                echo "<option value='$size2' $selected>$size2</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>


                                    <div class="category-sub-menu">
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

                                    <div class="category-sub-menu">
                                        <label for="color-filter">Filter by Color:</label>
                                        <style>
                                            .color-filter-container {
                                                margin-left: 20px;
                                                display: flex;
                                                align-items: center;
                                            }

                                            .color-swatch {
                                                display: inline-block;
                                                width: 20px;
                                                height: 20px;
                                                border: 1px solid #ccc;
                                                margin-right: 5px;
                                                border-radius: 50%;
                                                /* Add some spacing between swatch and checkbox */
                                            }

                                            input[name="color[]"] {
                                                /* appearance: none; */
                                                /* Hide default checkbox appearance */
                                                width: 20px;
                                                height: 20px;
                                                border: 1px solid #ccc;
                                                background-color: #fff;
                                                border-radius: 50%;
                                                /* Set background color for better visibility */
                                                cursor: pointer;
                                            }

                                            input[name="color[]"]:checked {
                                                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23000' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");

                                            }

                                            input[name="color[]"]:checked+.color-swatch {
                                                border-color: #000;
                                                /* Or any other color you prefer */
                                                box-shadow: 0 0 5px 2px #000;
                                                /* Optional for extra emphasis */
                                            }
                                        </style>
                                        <div class="category-sub-menu">
                                            <!-- <ul>
                                                <li class="has-sub open"><a href="# ">Colors</a>
                                                    <ul style="display: block;">
                                                        <php
                                                        $colorQuery = "SELECT DISTINCT product_color FROM product_colors";
                                                        $colorResult = $conn->query($colorQuery);

                                                        while ($row = $colorResult->fetch_assoc()) {
                                                            $color = $row['product_color'];
                                                            $checked = (isset($_GET['color']) && is_array($_GET['color']) && in_array($color, $_GET['color'])) ? 'checked' : ''; ?>
                                                            <div class='color-filter-container'>
                                                                <label>
                                                                    <input type='checkbox' name='color[]' value='<php echo $color; ?>' $checked>
                                                                    <span class='color-swatch' style='background-color: <php echo $color; ?>;'></span>
                                                                    <php echo $color; ?>
                                                                </label>
                                                            </div>
                                                        <php
                                                        }

                                                        ?>
                                                    </ul>
                                                </li>

                                            </ul> -->

                                            <div class="color-filters">
                                                <?php
                                                $colorQuery = "SELECT DISTINCT product_color FROM product_colors";
                                                $colorResult = $conn->query($colorQuery);
                                                while ($row = $colorResult->fetch_assoc()) {
                                                    $color = $row['product_color'];
                                                    $checked = in_array($color, $selectedColors ?? []) ? 'checked' : '';
                                                    // Use $selectedColors array, default to empty if not set
                                                    echo "
            <div class='color-filter-container'>
                <label>
                    <input type='checkbox' name='color[]' value='$color' $checked>
                    <span class='color-swatch' style='background-color: $color;'></span> 
                    $color
                </label>
            </div>";
                                                }
                                                ?>
                                            </div>
                                        </div>

                                    </div>

                                    <script>
                                        document.getElementById('type-filter').addEventListener('change', function() {
                                            const selectedSize = this.value;
                                            const currentUrl = new URL(window.location.href);

                                            if (selectedSize) {
                                                currentUrl.searchParams.set('type-filter', selectedSize);
                                            } else {
                                                currentUrl.searchParams.delete('type-filter');
                                            }

                                            window.location.href = currentUrl.toString();
                                        });

                                        document.getElementById('size-filter').addEventListener('change', function() {
                                            const selectedSize = this.value;
                                            const currentUrl = new URL(window.location.href);

                                            if (selectedSize) {
                                                currentUrl.searchParams.set('size', selectedSize);
                                            } else {
                                                currentUrl.searchParams.delete('size');
                                            }

                                            window.location.href = currentUrl.toString();
                                        });

                                        document.getElementById('style-filter').addEventListener('change', function() {
                                            const selectedStyle = this.value;
                                            const currentUrl = new URL(window.location.href);

                                            if (selectedStyle) {
                                                currentUrl.searchParams.set('style-filter', selectedStyle);
                                            } else {
                                                currentUrl.searchParams.delete('style-filter');
                                            }

                                            window.location.href = currentUrl.toString();
                                        });

                                        document.getElementById('category-filter').addEventListener('change', function() {
                                            const selectedCategory = this.value;
                                            const currentUrl = new URL(window.location.href);

                                            if (selectedCategory) {
                                                currentUrl.searchParams.set('category-filter', selectedCategory);
                                            } else {
                                                currentUrl.searchParams.delete('category-filter');
                                            }

                                            window.location.href = currentUrl.toString();
                                        });



                                        const checkboxes = document.querySelectorAll('input[name="color[]"]');
                                        const currentUrl = new URL(window.location.href);

                                        checkboxes.forEach(checkbox => {
                                            checkbox.addEventListener('change', function() {
                                                const selectedColors = Array.from(checkboxes)
                                                    .filter(cb => cb.checked)
                                                    .map(cb => cb.value);

                                                if (selectedColors.length > 0) {
                                                    currentUrl.searchParams.set('color', selectedColors);
                                                } else {
                                                    currentUrl.searchParams.delete('color');
                                                }

                                                window.location.href = currentUrl.toString();
                                            });
                                        });
                                    </script>


                                    <!-- <button type="submit">Apply Filter</button> -->

                                </div>
                            </div>

                        </div>
                        <!--sidebar-categores-box end  -->
                        <!-- category-sub-menu start -->
                        <!-- <div class="sidebar-categores-box mb-sm-0 mb-xs-0">
                            <div class="sidebar-title">
                                <h2>Laptop</h2>
                            </div>
                            <div class="category-tags">
                                <ul>
                                    <li><a href="# ">Devita</a></li>
                                    <li><a href="# ">Cameras</a></li>
                                    <li><a href="# ">Sony</a></li>
                                    <li><a href="# ">Computer</a></li>
                                    <li><a href="# ">Big Sale</a></li>
                                    <li><a href="# ">Accessories</a></li>
                                </ul>
                            </div>

                        </div> -->
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

                            <div class="col-lg-7 col-md-6 col-sm-6">
                                <div class="product-details-view-content pt-60">
                                    <div class="product-info">
                                        <h2>Today is a good day Framed poster</h2>
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