<?php
session_start();
include("include/config.php");
if (!isset($_SESSION["username"])) {
  // Redirect to the appropriate page for logged-in users
  header("Location: user_login.php");
  exit;
}
$username = $_SESSION["username"];

// include("include/head.php");
// include("include/header.php");

$sellerinfo2 = [];
$stmt2 = $conn->prepare("SELECT * FROM seller_register WHERE username = ?");
$stmt2->bind_param("s", $_SESSION["username"]);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
  $sellerinfo2 = $result2->fetch_assoc();
}
$stmt2->close(); // Close the first statement

// Fetch categories for the current user
$stmt = $conn->prepare("SELECT * FROM product_category WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$categories = [];
while ($row = $result->fetch_assoc()) {
  $categories[] = $row;
}


// Fetch existing sizes for this product
$stmt = $conn->prepare("SELECT * FROM product_allsizes WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  $sizes[] = $row;
}
$stmt->close();

// Fetch existing colors for this product
$stmt = $conn->prepare("SELECT * FROM product_allcolors WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  $colors[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get product data from the form
  $productId = $_POST["product_id"];
  $productName = $_POST["product_name"];
  $productPrice = $_POST["product_price"];
  $productBrand = $_POST["product_brand"];
  $productType = $_POST["product_type"];
  $productCategory = $_POST["product_category"];

  // Image Upload Handling
  $targetDir = "uploads/";
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

  // Check for Unique Values (before insertion)
  $checkUniqueStmt = $conn->prepare("SELECT * FROM product_list WHERE product_id = ? ");
  $checkUniqueStmt->bind_param("s", $productId);
  $checkUniqueStmt->execute();
  $checkUniqueResult = $checkUniqueStmt->get_result();
  //Check if product_id already exists in database
  if ($checkUniqueStmt->num_rows > 0) {
    $error = "Error: Product ID already exists.";
    $uploadOk = 0; // Prevent upload if not unique
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    // Display error message and skip the insertion 
    echo $error;
  } else {
    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
      // Insert into product_list table
      $stmt = $conn->prepare("INSERT INTO product_list (product_id, product_name, product_price, product_brand, product_type, product_category, username, product_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssdsssss", $productId, $productName, $productPrice, $productBrand, $productType, $productCategory, $username, $targetFile);

      if ($stmt->execute()) {
        // 2. Handle Checked Sizes
        if (isset($_POST['product_size'])) {
          $selectedSizes = $_POST['product_size'];
          $insertSizeStmt = $conn->prepare("INSERT INTO product_sizes (product_id, product_size) VALUES (?, ?)");
          foreach ($selectedSizes as $size) {
            $insertSizeStmt->bind_param("ss", $productId, $size);
            $insertSizeStmt->execute();
          }
        }

        // 3. Handle Checked Colors
        if (isset($_POST['product_color'])) {
          $selectedColors = $_POST['product_color'];
          $insertColorStmt = $conn->prepare("INSERT INTO product_colors (product_id, product_color) VALUES (?, ?)");
          foreach ($selectedColors as $color) {
            $insertColorStmt->bind_param("ss", $productId, $color);
            $insertColorStmt->execute();
          }
        }

        $success = "Product added successfully.";
      } else {
        $error = "Error: " . $stmt->error;
      }
    } else {
      $error = "Sorry, there was an error uploading your file.";
    }
  }
  $checkUniqueStmt->close();
}


// Check if the user has an address stored
$productCheckStmt = $conn->prepare("SELECT * FROM product_list WHERE username = ?");
$productCheckStmt->bind_param("s", $username);
$productCheckStmt->execute();
$productResult = $productCheckStmt->get_result();
// $hasProduct = $productResult->num_rows > 0;

$product = [];
while ($row = $productResult->fetch_assoc()) {
  $product[] = $row;
  $productId = $row['product_id'];
}



$stmt->close();


// if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['product_id'])) {
//   $product_id = $_GET['product_id'];

//   // Prepare and execute a parameterized query
//   $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
//   $stmt->execute([$product_id]);

//   // Fetch the product details
//   $product = $stmt->fetch(PDO::FETCH_ASSOC);

//   // Return product details as JSON
//   header('Content-Type: application/json');
//   echo json_encode($product);
//   exit; // Stop further execution
// }



// $stmt->close();
$conn->close();

?>



<?php include("include/head_seller.php"); ?>
<?php
  include("include/admin_head.php");
    ?>



<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php
      include("include/admin_nav.php");
      ?>


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
              Manage Product
            </h3>
            <!-- <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Tables</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data table</li>
              </ol>
            </nav> -->
          </div>
          <div class="card">
            <div class="card-body">
              <!-- <h4 class="card-title">Data table</h4> -->
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <style>

                    </style>
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                          <!-- <th>Product ID</th> -->
                          <th>Product Name</th>
                          <th>Price</th>
                          <th>Stocks</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($product as $products) : ?>
                          <tr>

                            <!-- <td><?php echo $products['product_id']; ?></td> -->
                            <td><?php echo $products['product_name']; ?></td>
                            <td>₱<?php echo $products['product_price']; ?></td>
                            <td><?php echo $products['product_stocks']; ?></td>
                            <td>
                              <button class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#<?php echo $products['product_id']; ?>">Edit</button>
                            </td>
                          </tr>
                          <?php

                          $product_id_edit = $products['product_id'];
                          // Check if the user has an address stored
                          $product_Stmt = $conn->prepare("SELECT * FROM product_list WHERE product_id = ?");
                          $product_Stmt->bind_param("s", $product_id_edit);
                          $product_Stmt->execute();
                          $productResult = $product_Stmt->get_result();
                          $hasproduct = $productResult->num_rows > 0;
                          $productRow = $productResult->fetch_assoc();



                          if ($hasproduct) {
                            $formattedPrice = number_format($productRow['product_price'], 2, '.', ',');
                          } else {
                            // Provide a default value if the product doesn't exist
                            $formattedPrice = "0.00"; // Or display an error message
                          }
                          ?>
                          <?php if (isset($_GET['success'])) : ?>
                            <div class="message-popup success "><?php echo $_GET['success']; ?></div>

                          <?php elseif (isset($_GET['error'])) : ?>
                            <div class="message-popup error "><?php echo $_GET['error']; ?></div>
                          <?php endif; ?>
                          <!-- Update Product Quick View | Modal Area -->
                          <div class="modal fade modal-wrapper" id="<?php echo $products['product_id']; ?>">

                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                <div class="modal-body">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                  <div class="modal-inner-area row">
                                    <div class="col-lg-12 col-md-6 col-sm-6">
                                      <div class="product-details-view-content">
                                        <div class="product-info">
                                          <style>
                                            .login-form {
                                              /* background-color: #ffffff; */
                                              padding: 30px;
                                              -webkit-box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0);
                                              /* box-shadow: 0px 5px 4px 0px rgba(0, 0, 0, 0.1); */
                                            }
                                          </style>
                                          <form method="POST" action="update_product.php" id="updateProductForm">

                                            <input type="text" name="product_id" id="productId" value="<?php echo $productRow['product_id']; ?>">
                                            <div class="login-form">
                                              <h4 class="login-title">Update Product1</h4>
                                              <div class="row">
                                                <div class="col-md-12 col-12 mb-20">
                                                  <label>Product Name</label>
                                                  <input class="mb-0" type="text" name="product_name" id="productName" value="<?php echo $productRow['product_name']; ?>" required>
                                                </div>
                                                <div class="col-12 mb-20">
                                                  <label>Product Price</label>
                                                  <input class="mb-0" type="number" name="product_price" id="productPrice" value="<?php echo $productRow['product_price']; ?>" required>
                                                </div>
                                                <div class="col-12 mb-20">
                                                  <label>Stocks</label>
                                                  <input class="mb-0" type="number" name="product_stocks" id="productStocks" value="<?php echo $productRow['product_stocks']; ?>" required>
                                                </div>
                                                <div class="col-md-12">
                                                  <button type="submit" class="register-button mt-0">Update Product</button>
                                                </div>
                                              </div>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </div>


                        <?php endforeach; ?>
                      </tbody>
                    </table>




                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        
    <?php
include("include/admin_footer.php");
?>
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

  <!-- endinject -->

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
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <script src="js/alerts1.js"></script>
  <!-- End custom js for this page-->
  <script src="js/popup.js"></script>
</body>


</html>