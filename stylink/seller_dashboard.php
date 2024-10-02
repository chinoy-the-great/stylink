<?php
session_start();
include("include/config.php");


if (!isset($_SESSION["username"])) {
  // Redirect to the appropriate page for logged-in users
  header("Location: index.php");
  exit;
} else {




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

  $shop_name =  $sellerinfo2['shop_name'];

  $sellerinfo3 = 0; // Initialize count to 0 in case of no orders
  // Prepare and execute the SQL query
  $stmt3 = $conn->prepare("SELECT SUM(product_quantity) AS total_orders FROM checkout_order WHERE shop_name = ?");
  $stmt3->bind_param("s", $shop_name);
  $stmt3->execute();
  $result3 = $stmt3->get_result();

  // Fetch the total count if rows exist
  if ($result3->num_rows > 0) {
    $row = $result3->fetch_assoc();
    $sellerinfo3 = $row['total_orders'];
  }

  $stmt3->close();

  $sellerinfo4 = 0; // Initialize count to 0 in case of no customers

  // Prepare and execute the SQL query
  $stmt4 = $conn->prepare("SELECT COUNT(DISTINCT username) AS total_customers FROM checkout_order WHERE shop_name = ?");
  $stmt4->bind_param("s", $shop_name);
  $stmt4->execute();
  $result4 = $stmt4->get_result(); // Use $result4 here for clarity

  // Fetch the total count if rows exist
  if ($result4->num_rows > 0) {
    $row = $result4->fetch_assoc();
    $sellerinfo4 = $row['total_customers'];
  }

  $stmt4->close();

  $sellerinfo5 = 0; // Initialize count to 0 in case of no orders
  // Prepare and execute the SQL query
  $stmt5 = $conn->prepare("SELECT COUNT(*) AS total_completeorders FROM checkout_order WHERE shop_name = ? AND checkout_status = 'Order Complete'");
  $stmt5->bind_param("s", $shop_name);
  $stmt5->execute();
  $result5 = $stmt5->get_result();

  // Fetch the total count if rows exist
  if ($result5->num_rows > 0) {
    $row = $result5->fetch_assoc();
    $sellerinfo5 = $row['total_completeorders'];
  }

  $stmt5->close();

  $sellerinfo6 = 0; // Initialize count to 0 in case of no orders
  // Prepare and execute the SQL query
  $stmt6 = $conn->prepare("SELECT COUNT(*) AS total_penorders FROM checkout_order WHERE shop_name = ? AND checkout_status = 'Pending'");
  $stmt6->bind_param("s", $shop_name);
  $stmt6->execute();
  $result6 = $stmt6->get_result();

  // Fetch the total count if rows exist
  if ($result6->num_rows > 0) {
    $row = $result6->fetch_assoc();
    $sellerinfo6 = $row['total_penorders'];
  }

  $stmt6->close();

  $sellerinfo7 = 0; // Initialize count to 0 in case of no orders
  // Prepare and execute the SQL query
  $stmt7 = $conn->prepare("SELECT SUM(total_price) AS total_sales FROM checkout_order WHERE shop_name = ?");
  $stmt7->bind_param("s", $shop_name);
  $stmt7->execute();
  $result7 = $stmt7->get_result();

  // Fetch the total count if rows exist
  if ($result7->num_rows > 0) {
    $row = $result7->fetch_assoc();
    $sellerinfo7 = $row['total_sales'];

    $formattedsales = number_format($sellerinfo7, 2, '.', ',');
  }

  $stmt7->close();



  // Query to find the top-selling product
  $stmt8 = $conn->prepare("SELECT product_name, COUNT(*) AS sales_count 
                         FROM checkout_order 
                         WHERE shop_name = ? 
                         GROUP BY product_name 
                         ORDER BY sales_count DESC 
                         LIMIT 1");
  $stmt8->bind_param("s", $shop_name);
  $stmt8->execute();
  $result8 = $stmt8->get_result();

  // Fetch the top product information
  $topProduct = null;
  if ($result8->num_rows > 0) {
    $row = $result8->fetch_assoc();
    $topProduct = $row['product_name'];
  }

  $stmt8->close();
}
?>
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
              Dashboard <?php echo isset($sellerinfo2['shop_name']) ? $sellerinfo2['shop_name'] : ''; ?>
            </h3>
          </div>
          <div class="row grid-margin">
            <div class="col-12">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="statistics-item">
                      <p>
                        <i class="icon-sm fa fa-user mr-2"></i>
                        New Customers
                      </p>
                      <h2><?php echo $sellerinfo4; ?></h2>
                      <!-- <label class="badge badge-outline-success badge-pill">2.7% increase</label> -->
                    </div>
                    <div class="statistics-item">
                      <p>
                        <i class="icon-sm fas fa-hourglass-half mr-2"></i>
                        Total Orders
                      </p>
                      <h2><?php echo $sellerinfo3; ?></h2>
                      <!-- <label class="badge badge-outline-danger badge-pill">30% decrease</label> -->
                    </div>
                    <div class="statistics-item">
                      <p>
                        <i class="icon-sm fas fa-cloud-download-alt mr-2"></i>
                        Pending Orders
                      </p>
                      <h2><?php echo $sellerinfo6; ?></h2>
                      <!-- <label class="badge badge-outline-success badge-pill">12% increase</label> -->
                    </div>
                    <div class="statistics-item">
                      <p>
                        <i class="icon-sm fas fa-check-circle mr-2"></i>
                        Completed Orders
                      </p>
                      <h2><?php echo $sellerinfo5; ?></h2>
                      <!-- <label class="badge badge-outline-success badge-pill">57% increase</label> -->
                    </div>
                    <div class="statistics-item">
                      <p>
                        <i class="icon-sm fas fa-chart-line mr-2"></i>
                        Sales
                      </p>
                      <h2>₱<?php echo $formattedsales; ?></h2>
                      <!-- <label class="badge badge-outline-success badge-pill">10% increase</label> -->
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">
                    <i class="fas fa-gift"></i>
                    Orders
                  </h4>
                  <canvas id="orders-chart"></canvas>
                  <div id="orders-chart-legend" class="orders-chart-legend"></div>
                </div>
              </div>
            </div>

            <!-- <div class="col-md-5 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h3> Top Selling Product</h3>
                  <div class="d-flex flex-row">
                    <?php
                    // Corrected query to fetch product details based on the top-selling product name
                    $stmt9 = $conn->prepare("SELECT * FROM product_list WHERE product_name = ?");
                    $stmt9->bind_param("s", $topProduct);
                    $stmt9->execute();
                    $result9 = $stmt9->get_result();

                    // Fetch the product details (assuming you have a column named 'product_image')
                    $sellerinfo9 = []; // Initialize the variable to ensure it is set
                    if ($result9->num_rows > 0) {
                      $sellerinfo9 = $result9->fetch_assoc();
                    }
                    $stmt9->close();
                    // Initialize count to 0 in case of no orders
                    $totalSold = 0; // Use a more descriptive variable name
                    // Check if 'product_id' exists in the fetched product details
                    if (isset($sellerinfo9["product_id"])) {
                      $product_id = $sellerinfo9["product_id"];

                      // Prepare and execute the SQL query
                      $stmt10 = $conn->prepare("SELECT SUM(product_quantity) AS total_sold 
                      FROM checkout_order 
                      WHERE product_id = ? AND shop_name = ?");
                      $stmt10->bind_param("ss", $product_id, $shop_name);
                      $stmt10->execute();
                      $result10 = $stmt10->get_result();

                      // Fetch the total count if rows exist
                      if ($result10->num_rows > 0) {
                        $row = $result10->fetch_assoc();
                        $totalSold = $row['total_sold'];
                      }
                      $stmt10->close();
                    } // No else block needed, as $totalSold remains 0 if product_id not found

                    // Output section (simplified for clarity):
                    if (!empty($topProduct) && isset($sellerinfo9['product_id'])) { ?>
                      <img src="<?= $sellerinfo9['product_image'] ?>" alt="<?= $topProduct ?>">
                      <div class="ml-3">
                        <label><?= $topProduct ?></label>
                        <p>Total Sales: <?= $totalSold ?></p>
                        <p>Stocks: <?= $sellerinfo9['product_stocks'] ?? '' ?></p>
                        <p><a href="single-product.php?product_id=<?= $sellerinfo9['product_id'] ?>">View Product</a></p>
                      </div>
                    <?php } else {
                      echo "No products sold yet.";
                    } ?>

                  </div>
                </div>
              </div>
            </div> -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">
                    <i class="fas fa-calendar-alt"></i>
                    Calendar
                  </h4>
                  <div id="inline-datepicker-example" class="datepicker"></div>
                </div>
              </div>
            </div>

          </div>
          <!-- <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">
                  <i class="fas fa-calendar-alt"></i>
                  Calendar
                </h4>
                <div id="inline-datepicker-example" class="datepicker"></div>
              </div>
            </div>
          </div> -->
          <!-- <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body d-flex flex-column">
                <h4 class="card-title">
                  <i class="fas fa-chart-pie"></i>
                  Sales status
                </h4>
                <div class="flex-grow-1 d-flex flex-column justify-content-between">
                  <canvas id="sales-status-chart" class="mt-3"></canvas>
                  <div class="pt-4">
                    <div id="sales-status-chart-legend" class="sales-status-chart-legend"></div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->

          <?php
          include("include/admin_footer.php");
          ?>
        </div>
      </div>


    </div>




    <!-- partial -->
  </div>
  <!-- main-panel ends -->

  </div>
  <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->


</body>
<script src="js/dashboard1.js"></script>

</html>