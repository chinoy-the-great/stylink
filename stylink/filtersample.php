
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

<!DOCTYPE html>
<html>

<head>
    <title>Shop Page</title>
    <link rel="stylesheet" href="your_stylesheet.css">
</head>

<body>
    <div class="sidebar-categores-box">
        <div class="sidebar-title">
            <h2>Filter By</h2>
        </div>
        <div class="filter-sub-area pt-sm-10 pt-xs-10">
            <div class="category-checkbox">
                <div class="category-sub-menu">
                    <label for="size-filter" class="mt-5">Style:</label>
                    <select id="style-filter" name="style-filter">
                        <option value="">Filter Style...</option>
                        <?php
                        // // Fetch distinct sizes from your database
                        $sizeQuery = "SELECT DISTINCT product_style FROM product_list WHERE product_type = 'Men Fashion'";
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
                                            $sizeQuery = "SELECT DISTINCT product_category FROM product_list WHERE product_type = 'Men Fashion'";
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

                                        <div class="category-sub-menu">

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

            </div>
        </div>
    </div>

    <div class="product-area shop-product-area">
        <div id="product-container">
        </div>
    </div>

    <script>
        const filterForm = document.querySelector('.sidebar-categores-box');
        const productContainer = document.getElementById('product-container');

        function updateProducts() {
            const formData = new FormData(filterForm);

            fetch('fetch_products.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    productContainer.innerHTML = data;
                })
                .catch(error => {
                    productContainer.innerHTML = "Error loading products.";
                    console.error('Error:', error);
                });
        }

        updateProducts(); // Initial product load

        filterForm.addEventListener('change', updateProducts);
    </script>

</body>

</html>