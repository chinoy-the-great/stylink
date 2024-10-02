<?php
session_start();
include("include/config.php");

if (!isset($_SESSION["username"])) {
    header("Location: user_login.php");
    exit;
}

$username = $_SESSION["username"];

$stmt2 = $conn->prepare("SELECT * FROM seller_register WHERE username = ?");
$stmt2->bind_param("s", $_SESSION["username"]);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
    $sellerinfo2 = $result2->fetch_assoc();
}
$stmt2->close(); // Close the first statement

// Fetch product data
$productQuery = "SELECT * FROM product_list WHERE username = ?";
$stmt = $conn->prepare($productQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$productResult = $stmt->get_result();
$products = $productResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["product_id"];
  $productName = $_POST["product_name"];
  $productPrice = $_POST["product_price"];
  $productBrand = $_POST["product_brand"];
  $productType = $_POST["product_type"];
  $productCategory = $_POST["product_category"];
  $productDescription = $_POST["product_description"];
  $productStyle = $_POST["product_style"];
  $productTypeclothes = $_POST["product_typeclothes"];
  $productStocks = $_POST["product_stocks"];
  $productArrival = $_POST["new_arrival"];
  $productFeatured = $_POST["featured_product"];

  // Handle Image Upload
  $targetDir = "uploads/cover";
  $targetFile = $targetDir . basename($_FILES["product_image"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["product_image"]["tmp_name"]);
  if ($check !== false) {
    $uploadOk = 1;
  } else {
    $error = "File is not an image.";
    $uploadOk = 0;
  }

  // Allow certain file formats
  $allowedTypes = array("jpg", "jpeg", "png", "gif");
  if (!in_array($imageFileType, $allowedTypes)) {
    $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }


  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    // Display error message and skip the insertion 
    echo $error;
  } else {
    // If everything is ok, try to upload file
    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
      // Update product details in the database, including the image
      $stmt = $conn->prepare("UPDATE product_list SET product_name = ?, product_price = ?, product_brand = ?, product_type = ?, product_category = ?, product_description = ?, product_style = ?, product_typeclothes = ? , product_stocks = ?, new_arrival = ?, featured_product = ?, product_image = ? WHERE product_id = ? AND username = ?");
      $stmt->bind_param(
        "ssssssssssssss",
        $productName,
        $productPrice,
        $productBrand,
        $productType,
        $productCategory,
        $productDescription,
        $productStyle,
        $productTypeclothes,
        $productStocks,
        $productArrival,
        $productFeatured,
        $targetFile,
        $productId,
        $username
      );

      if ($stmt->execute()) {
        $success = "Product updated successfully!";
      } else {
        $error = "Error updating product: " . $stmt->error;
      }
    } else {
      // If image upload failed, only update product name and type
      $stmt = $conn->prepare("UPDATE product_list SET product_name = ?, product_price = ?, product_brand = ?, product_type = ?, product_category = ?, product_description = ?, product_style = ?, product_typeclothes = ? , product_stocks = ?, new_arrival = ?, featured_product = ? WHERE product_id = ? AND username = ?");
      $stmt->bind_param(
        "sssssssssssss",
        $productName,
        $productPrice,
        $productBrand,
        $productType,
        $productCategory,
        $productDescription,
        $productStyle,
        $productTypeclothes,
        $productStocks,
        $productArrival,
        $productFeatured,
        $productId,
        $username
      );
      if ($stmt->execute()) {
        $success = "Product updated successfully!";
      } else {
        $error = "Error updating product: " . $stmt->error;
      }
    }
  }
}


$stmt->close();
// $stmt->close();
$conn->close();

?>




<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Melody Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style1.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <!-- <link rel="shortcut icon" href="http://www.urbanui.com/" /> -->

</head>



<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row default-layout-navbar">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="index.php"><img src="images/menu/logo/4.png" alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="index.php"><img src="images/logo-mini.svg" alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="fas fa-bars"></span>
        </button>
        <ul class="navbar-nav">

        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <!-- <li class="nav-item d-none d-lg-flex">
            <a class="nav-link" href="#">
              <span class="btn btn-primary">+ Create new</span>
            </a>
          </li> -->


          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="<?php echo isset($sellerinfo2['shop_image']) ? $sellerinfo2['shop_image'] : ''; ?>" alt="Seller Image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="fas fa-cog text-primary"></i>
                Settings
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item">
                <i class="fas fa-power-off text-primary"></i>
                Logout
              </a>
            </div>
          </li>
          <!-- <li class="nav-item nav-settings d-none d-lg-block">
            <a class="nav-link" href="#">
              <i class="fas fa-ellipsis-h"></i>
            </a>
          </li> -->
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="fas fa-bars"></span>
        </button>
      </div>
    </nav>


    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

      <?php
      include("include/admin_sidebar.php");
      ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              Edit Product
            </h3>

          </div>
          <!-- <div class="col-12 grid-margin stretch-card"> -->
          <!-- <form class="forms-sample" action="" method="post" enctype="multipart/form-data"> -->
          <?php
          $productRow = $productResult->fetch_assoc();
          ?>
          <?php foreach($products as $productRow): ?>

          <form class="forms-sample" method="POST" id="commentForm1" action="update_product.php" enctype="multipart/form-data">
            <div class="card">
              <div class="card-body">
                <?php if (isset($error)) : ?>
                  <div class="error-message">
                    <p><?php echo $error; ?></p>
                  </div>
                <?php endif; ?>

                <?php if (isset($success)) : ?>
                  <div class="success-message">
                    <p><?php echo $success; ?></p>
                  </div>
                <?php endif; ?>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Cover Product Image<code>*</code></h4>
                          <input type="file" name="product_image" class="dropify" data-default-file="<?php echo isset($productRow['product_image']) ? $productRow['product_image'] : ''; ?>" />

                        </div>
                        <br>
                      </div> <br><br>
                      <label for="exampleInputName1">Product Description</label>
                      <div class="row">
                        <div class="col-md-12"><br>
                          <textarea class="form-control" name="product_description" id="exampleTextarea1" rows="20"><?php echo $productRow['product_description']; ?></textarea><br>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="row">
                        <div class="col-md-6">
                          <label for="exampleInputName1">Product ID#</label>
                          <input type="text" name="product_id" id="product_id" class="form-control" value="<?php echo $productRow['product_id']; ?>" readonly>
                          <input type="hidden" name="shop_name" value="<?php echo isset($sellerinfo2['shop_name']) ? $sellerinfo2['shop_name'] : ''; ?>">
                        </div>

                      </div>
                      <br>
                      <br>
                      <label for="exampleSelectGender">Type of Clothes<code>*</code></label>
                      <select class="form-control" name="product_typeclothes" id="exampleSelectGender" required>
                        <option value="" disabled selected hidden>Choose...</option>
                        <option value="Rental" <?php if (isset($productRow['product_typeclothes']) && $productRow['product_typeclothes'] == 'Rental') echo 'selected'; ?>>Rental</option>
                        <option value="Non-Rental" <?php if (isset($productRow['product_typeclothes']) && $productRow['product_typeclothes'] == 'Non-Rental') echo 'selected'; ?>>Non-Rental</option>
                      </select><br>
                      <label for="exampleInputName1">Product Name<code>*</code></label>
                      <input type="text" name="product_name" class="form-control" id="exampleInputName1" placeholder="Product Name" value="<?php echo $productRow['product_name']; ?>" required><br>
                      <label for="exampleInputName1">Product Price<code>*</code></label>
                      <input type="number" name="product_price" class="form-control" id="exampleInputName1" placeholder="Price" value="<?php echo $productRow['product_price']; ?>" required><br>
                      <label for="exampleInputName1">Product Stocks<code>*</code></label>
                      <input type="number" name="product_stocks" class="form-control" id="exampleInputName1" placeholder="Stocks" value="<?php echo $productRow['product_stocks']; ?>" required><br>
                      <label for="exampleInputName1">Product Brand(Optional)</label>
                      <input type="text" name="product_brand" class="form-control" id="exampleInputName1" placeholder="Brand Name" value="<?php echo $productRow['product_brand']; ?>"><br>
                      <label for="exampleSelectGender">Type of Product<code>*</code></label>
                      <select class="form-control" name="product_type" id="exampleSelectGender" required>
                        <option value="" disabled selected hidden>Choose...</option>
                        <option value="Men Fashion" <?php if (isset($productRow['product_type']) && $productRow['product_type'] == 'Men Fashion') echo 'selected'; ?>>Men Fashion</option>
                        <option value="Women Fashion" <?php if (isset($productRow['product_type']) && $productRow['product_type'] == 'Women Fashion') echo 'selected'; ?>>Women Fashion</option>
                        <!-- <option value="Women Fashion">Women Fashion</option> -->
                        <option value="Both" <?php if (isset($productRow['product_type']) && $productRow['product_type'] == 'Both') echo 'selected'; ?>>Both</option>
                      </select>
                      <br>
                      <label for="exampleSelectGender">Clothing Style<code>*</code></label>
                      <select class="form-control" name="product_style" id="exampleSelectGender" required>
                        <option value="" disabled selected hidden>Choose...</option>
                        <option value="Tops" <?php if (isset($productRow['product_style']) && $productRow['product_style'] == 'Tops') echo 'selected'; ?>>Tops</option>
                        <option value="Bottoms" <?php if (isset($productRow['product_style']) && $productRow['product_style'] == 'Bottoms') echo 'selected'; ?>>Bottoms</option>
                      </select>
                      <br>
                      <label>Category<code>*</code></label>
                      <select class="js-example-basic-single w-100" name="product_category" required>
                        <option value="" disabled selected hidden>Choose...</option>
                        <?php
                        // Fetch product data
                        $productQuery = "SELECT * FROM product_category";
                        $productResult = $conn->query($productQuery);
                        if ($productResult->num_rows > 0) {
                          while ($product = $productResult->fetch_assoc()) {
                            // Move the option output INSIDE the loop 
                        ?>
                            <option value="Bottoms" <?php if (isset($productRow['product_style']) && $productRow['product_style'] == 'Bottoms') echo 'selected'; ?>>Bottoms</option>

                            <option value="<?php echo $product['category_name']; ?>" <?php if (isset($productRow['product_category']) && $productRow['product_category'] == $product['category_name']) echo 'selected'; ?>>
                              <?php echo $product['category_name']; ?>
                            </option>
                        <?php
                          }
                        } else {
                          echo "No products found.";
                        }
                        ?>
                      </select>
                      <br>

                      <br>


                      <br>
                      <div class="row">
                        <div class="col-md-12">
                          <h5 for="exampleSelectGender">Other Information</h5>
                          <br>

                          <label for="exampleSelectGender">New Arrival<code>*</code></label>
                          <select class="form-control" name="new_arrival" id="exampleSelectGender" required>
                            <option value="" disabled selected hidden>Choose...</option>
                            <option value="Yes" <?php if (isset($productRow['new_arrival']) && $productRow['new_arrival'] == 'Yes') echo 'selected'; ?>>Yes</option>
                            <option value="No" <?php if (isset($productRow['new_arrival']) && $productRow['new_arrival'] == 'No') echo 'selected'; ?>>No</option>

                            <br>
                          </select>
                          <br>
                          <label for="exampleSelectGender">Featured Products<code>*</code></label>
                          <select class="form-control" name="featured_product" id="exampleSelectGender" required>
                            <option value="" disabled selected hidden>Choose...</option>
                            <option value="Yes" <?php if (isset($productRow['featured_product']) && $productRow['featured_product'] == 'Yes') echo 'selected'; ?>>Yes</option>
                            <option value="No" <?php if (isset($productRow['featured_product']) && $productRow['featured_product'] == 'No') echo 'selected'; ?>>No</option>
                            <br>
                          </select>
                          <br>

                        </div>





                      </div>

                      <div class="col-md-4">
                        <button type="submit" class="btn btn-primary mr-2 col-md-12">Update Product</button>
                      </div>

                    </div>
                  </div>

                  <div class="form-group">

                    <!-- <div class="col-lg-6 grid-margin">
                      <label for="exampleSelectGender">Type of Product</label>
                      <select class="form-control" name="product_type" id="exampleSelectGender" required>
                        <option>Choose...</option>
                        <option value="Men Fashion">Men Fashion</option>
                        <option value="Women Fashion">Women Fashion</option>
                        <option value="Both">Both</option>
                      </select>

                      <br>
                      <label for="exampleSelectGender">Clothing Style</label>
                      <select class="form-control" name="product_style" id="exampleSelectGender" required>
                        <option>Choose...</option>
                        <option value="Tops">Tops</option>
                        <option value="Bottoms">Bottoms</option>
                        <option value="Accessories">Accessories</option>
                      </select>

                      <br>


                      <label>Category</label>
                      <select class="js-example-basic-single w-100">

                        <?php
                        // Fetch product data
                        $productQuery = "SELECT * FROM product_category";
                        $productResult = $conn->query($productQuery);

                        if ($productResult->num_rows > 0) {
                          while ($product = $productResult->fetch_assoc()) {
                            // Move the option output INSIDE the loop 
                        ?>
                            <option value="<?php echo $product['category_name']; ?>">
                              <?php echo $product['category_name']; ?>
                            </option>
                        <?php
                          }
                        } else {
                          echo "No products found.";
                        }
                        ?>

                        <php
                        // Fetch categories for the current user
                        $stmt = $conn->prepare("SELECT * FROM product_category WHERE username = ?");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $categories = [];
                        while ($row = $result->fetch_assoc()) {
                          $categories[] = $row;
                        }
                        foreach ($categories as $category) : ?>
                          <option value=" <php echo $category['category_name']; ?>"><php echo $category['category_name']; ?></option>
                        <php endforeach; ?>
                      </select>

                      <label for="exampleSelectGender">Category</label>

                      <select class="form-control" name="product_category" id="exampleSelectGender" required>
                        <option value="">Choose...</option> <php foreach ($categories as $category) : ?>
                          <option value="<php echo $category['category_name']; ?>"><php echo $category['category_name']; ?></option>
                        <php endforeach; ?>
                      </select>
                      <br>
                      <br>
                      <h4 for="exampleSelectGender">Variations</h3>
                        <br>


                        <div class="row">
                          <div class="col-md-12">
                            <div class="table-responsive">
                              <table id="order-listing-size" class="table">
                                <thead>
                                  <tr>
                                    <th><input type="checkbox" id="selectAllSizes"></th>
                                    <th>Product Sizes</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php if (!empty($sizes)) : ?>
                                    <?php foreach ($sizes as $size) : ?>
                                      <tr>
                                        <td><input type="checkbox" name="product_size[]" value="<?php echo $size['size_name']; ?>"></td>
                                        <td><?php echo $size['size_name']; ?></td>
                                      </tr>
                                    <?php endforeach; ?>
                                  <?php else : ?>
                                    <p class="text-muted">No Product Sizes Available</p>
                                    <a class="btn btn-outline-primary" href="seller_sizesproduct.php">Add Size Variation</a>
                                  <?php endif; ?>
                                </tbody>
                              </table>
                            </div>
                          </div>

                        </div>
                    </div> -->

                  </div>
                </div>

                <div class="form-group">

                </div>
              </div>
            </div>
            <!-- </div> -->
            <br>

            <div class="form-group"><br>
              <div class="row">
                <div class="col-md-4">
                </div>
                <div class="col-md-4">

                </div>
                <!-- <div class="col-md-4">
                  <button type="submit" class="btn btn-primary mr-2 col-md-12">Add Product</button>
                </div> -->
              </div>
            </div>
          </form>
          <?php endforeach; ?>

        </div>
        <!-- content-wrapper ends -->
        <!-- partial"partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2018 <a href="https://www.urbanui.com/" target="_blank">Urbanui</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="far fa-heart text-danger"></i></span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- generate product ID -->
  <script>
    function generateProductId() {
      // Generate random string (you can customize the characters and length)
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      let productId = '';
      for (let i = 0; i < 5; i++) {
        productId += characters.charAt(Math.floor(Math.random() * characters.length));
      }

      // Display in input field
      document.getElementById('product_id').value = productId;
      document.getElementById('product_id1').value = productId;
      document.getElementById('product_id2').value = productId;

      // Send to PHP via AJAX (if you want to store in database)
      $.ajax({
        type: "POST",
        url: "save_product_id.php", // Create this file
        data: {
          product_id: productId,
          username: "<?php echo $_SESSION['username']; ?>"
        },
        success: function(response) {
          // Handle the response from PHP if needed (e.g., display a success message)
          console.log(response);
        }
      });
    }
  </script>
  <!-- AUtomatically Add Sizes in Database -->

  <script>
    // Update JavaScript to store sizes and colors in hidden input fields
    document.getElementById('addSizeBtn').addEventListener('click', function() {
      const sizeInput = document.getElementById('product_size');
      const sizeValue = sizeInput.value.trim();

      if (sizeValue !== "") {
        const sizesInput = document.getElementById('product_sizes'); // Hidden input for sizes
        sizesInput.value += (sizesInput.value === "" ? "" : ",") + sizeValue;

        // Update the table for visual display
        const tableBody = document.getElementById('order-listing-size').getElementsByTagName('tbody')[0];
        const newRow = tableBody.insertRow();
        const sizeCell = newRow.insertCell();
        const actionCell = newRow.insertCell();

        sizeCell.textContent = sizeValue;
        actionCell.innerHTML = '<button type="button" class="btn btn-outline-danger btn-fw" onclick="deleteSizeRow(this)">Delete</button>';

        sizeInput.value = ""; // Clear input after adding
      }
    });

    document.getElementById('addColorBtn').addEventListener('click', function() {
      // Similar logic for adding colors (modify as needed)
      const colorInput = document.getElementById('product_color');
      const colorValue = colorInput.value.trim();

      if (colorValue !== "") {
        const colorsInput = document.getElementById('product_colors'); // Hidden input for colors
        colorsInput.value += (colorsInput.value === "" ? "" : ",") + colorValue;

        // Update the table for visual display
        const tableBody = document.getElementById('order-listing-color').getElementsByTagName('tbody')[0];
        const newRow = tableBody.insertRow();
        const colorCell = newRow.insertCell();
        const actionCell = newRow.insertCell();

        colorCell.textContent = colorValue;
        actionCell.innerHTML = '<button type="button" class="btn btn-outline-danger btn-fw" onclick="deleteColorRow(this)">Delete</button>';

        colorInput.value = ""; // Clear input after adding
      }
    });
  </script>
  <script>
    document.getElementById('addColorBtn').addEventListener('click', function() {
      const sizeInput = document.getElementById('product_color');
      const sizeValue = sizeInput.value.trim();

      if (sizeValue !== "") {
        const tableBody = document.getElementById('order-listing-color').getElementsByTagName('tbody')[0];

        const newRow = tableBody.insertRow();
        const sizeCell = newRow.insertCell();
        const actionCell = newRow.insertCell();

        sizeCell.textContent = sizeValue;
        actionCell.innerHTML = '<a class="btn btn-outline-danger btn-fw" >Delete</a>'; // Placeholder delete link

        sizeInput.value = ""; // Clear input after adding
      }
    });

    document.getElementById('order-listing-color').addEventListener('click', function(event) {
      if (event.target.tagName === 'A') { // Check if the click is on an <a> (delete link)
        const rowToDelete = event.target.parentNode.parentNode; // Get the parent row
        rowToDelete.remove(); // Remove the row from the table
      }
    });
  </script>

  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
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

  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>

  <script src="js/file-upload.js"></script>
  <!-- <script src="js/typeahead.js"></script> -->
  <script src="js/select2.js"></script>

  <!-- End custom js for this page-->


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


</html>