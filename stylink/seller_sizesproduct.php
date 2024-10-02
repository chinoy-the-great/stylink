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

// Form handling for adding categories
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_size"])) {
  $categoryName = $_POST["product_size"];

  // Basic input validation (add more as needed)
  if (empty($categoryName)) {
    $error = "Please enter a Size Variation.";
  } else {
    // Check if the category already exists for this user
    $checkStmt = $conn->prepare("SELECT * FROM product_allsizes WHERE username = ? AND size_name = ?");
    $checkStmt->bind_param("ss", $username, $categoryName);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
      $error = "Size already exists.";
    } else {
      // Insert the new category
      $stmt = $conn->prepare("INSERT INTO product_allsizes (username, size_name) VALUES (?, ?)");
      $stmt->bind_param("ss", $username, $categoryName);

      if ($stmt->execute()) {
        $success = "Size added successfully.";
      } else {
        $error = "Error: " . $stmt->error;
      }

      $stmt->close();
    }
  }
}

// Handle category deletion
if (isset($_GET['delete'])) {
  $categoryId = $_GET['delete'];

  // Delete the category from the database
  $deleteStmt = $conn->prepare("DELETE FROM product_allsizes WHERE id = ? AND username = ?");
  $deleteStmt->bind_param("is", $categoryId, $username);
  if ($deleteStmt->execute()) {
    $success = "Size deleted successfully.";
  } else {
    $error = "Error deleting category: " . $deleteStmt->error;
  }
  $deleteStmt->close();
}



// Fetch existing categories for this user
$stmt = $conn->prepare("SELECT * FROM product_allsizes WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$categories = [];
while ($row = $result->fetch_assoc()) {
  $categories[] = $row;
}

$stmt->close();
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
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="shortcut icon" href="http://www.urbanui.com/" />

</head>



<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row default-layout-navbar">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="index-2.html"><img src="images/menu/logo/4.png" alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="index-2.html"><img src="images/logo-mini.svg" alt="logo" /></a>
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
              Add Category
            </h3>

          </div>


          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <!-- <h4 class="card-title">Seller Registration</h4> -->
                <!-- <p class="card-description">
                    Basic form elements
                  </p> -->




                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6">
                      <form class="forms-sample" action="" method="post">

                        <?php if (isset($error)) : ?>
                          <div class="alert alert-danger">
                            <p><?php echo $error; ?></p>
                          </div>
                        <?php endif; ?>

                        <?php if (isset($success)) : ?>
                          <div class="alert alert-success">
                            <p><?php echo $success; ?></p>
                          </div>
                        <?php endif; ?>


                        <label for="exampleInputName1">Product Size</label>
                        <input type="text" name="product_size" class="form-control" id="exampleInputName1" placeholder="Product Size" required>
                        <br>
                        <br>
                        <button type="submit" class="btn btn-primary mr-2">Add Size</button>

                      </form>
                    </div>
                    <div class="col-md-6">
                      <div class="table-responsive">
                        <table id="order-listing" class="table">
                          <thead>
                            <tr>
                              <th>Size Variation</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($categories as $category) : ?>
                              <tr>
                                <td><?php echo $category['size_name']; ?></td>
                                <td>
                                  <a href="seller_sizesproduct.php?delete=<?php echo $category['id']; ?>" class="btn btn-outline-danger btn-fw" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>

                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>


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

  <script src="js/data-table.js"></script>
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


  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dropify.js"></script>
  <script src="js/data-table.js"></script>
  <!-- End custom js for this page-->

  <!-- End custom js for this page-->
</body>


</html>