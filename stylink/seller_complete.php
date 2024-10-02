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



// Check if the user has an address stored
$hasshop_nameStmt = $conn->prepare("SELECT * FROM seller_register WHERE username = ?");
$hasshop_nameStmt->bind_param("s", $username);
$hasshop_nameStmt->execute();
$hasshoptResult = $hasshop_nameStmt->get_result();
// $hasProduct = $productResult->num_rows > 0;

$shop_name1 = [];
while ($row = $hasshoptResult->fetch_assoc()) {
  $shop_name1[] = $row;
  $shop_name = $row['shop_name'];
}


// Check if the user has an address stored 
$productCheckStmt = $conn->prepare("SELECT shop_name, transaction_id, product_id, checkout_status, total_price, username,modePayment  , MAX(checkout_status) FROM checkout_order WHERE shop_name = ? AND checkout_status= 'Order Complete' GROUP BY transaction_id");
$productCheckStmt->bind_param("s", $shop_name);
$productCheckStmt->execute();
$productResult = $productCheckStmt->get_result();


$product = [];
while ($row = $productResult->fetch_assoc()) {
  $product[] = $row;
  $productId = $row['product_id'];
  $transidd = $row['transaction_id'];
  $shop_name1 = $row['shop_name'];
}

// Variables to store retrieved data
$order_shop = null; // Initialize to null 
$order_status = null;


// if ($hasproduct) {
//   $formattedPrice = number_format($productRow['product_price'], 2, '.', ',');
// } else {
//   // Provide a default value if the product doesn't exist
//   $formattedPrice = "0.00"; // Or display an error message
// }

// $stmt->close();
$conn->close();

?>

<?php include("include/head_seller.php"); ?>

<?php
  include("include/admin_head.php");
    ?>

<style>
  .badge-warning {
    text-align: center;
    text-transform: capitalize;
    color: #fff;
    /* color: #7c5d00; */
    background-color: #ffc107;
  }

  .badge-info {
    text-align: center;

    text-transform: capitalize;
    color: #fff;
  }
</style>

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
              Completed Orders
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
                          <th>Transaction ID</th>
                          <th>Total Item</th>
                          <th>Total Price</th>
                          <th>Customer</th>
                          <th>Payment Method</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($product as $products) :
                          $tranID = $products['transaction_id'];
                          $product_countsmlt = $conn->prepare("SELECT SUM(product_quantity) FROM checkout_order WHERE shop_name = ? AND transaction_id = ?");
                          $product_countsmlt->bind_param("ss", $shop_name, $tranID); // Assuming $shop_name is defined elsewhere
                          $product_countsmlt->execute();

                          $countResult = $product_countsmlt->get_result();
                          $totalProducts = $countResult->fetch_row()[0]; // Get the count value

                          $product_pricesmlt = $conn->prepare("SELECT SUM(total_price) FROM checkout_order WHERE shop_name = ? AND transaction_id = ?");
                          $product_pricesmlt->bind_param("ss", $shop_name, $tranID); // Assuming $shop_name is defined elsewhere
                          $product_pricesmlt->execute();

                          $pricetResult = $product_pricesmlt->get_result();
                          $totalPrice = $pricetResult->fetch_row()[0]; // Get the count value

                          $formattedPrice = number_format($totalPrice, 2, '.', ',');
                        ?>


                          <tr>

                            <td><?php echo $products['transaction_id']; ?></td>
                            <td><?php echo $totalProducts; ?></td>
                            <td>₱<?php echo number_format($totalPrice, 2); ?></td>

                            <td><?php echo $products['username']; ?></td>
                            <td><?php echo $products['modePayment']; ?></td>

                            <?php if ($products['checkout_status'] == "pending") : ?>

                              <td style="display: flex;justify-content:center;"><label class="badge badge-warning badge-pill"><?php echo $products['checkout_status']; ?> </label></td>
                            <?php elseif ($products['checkout_status'] == "Package picked up") : ?>
                              <td style="display: flex;justify-content:center;"><label class="badge badge-info badge-pill"><?php echo $products['checkout_status']; ?> </label></td>
                            <?php elseif ($products['checkout_status'] == "Out for Delivery") : ?>
                              <td style="display: flex;justify-content:center;"><label class="badge badge-info badge-pill"><?php echo $products['checkout_status']; ?> </label></td>
                            <?php elseif ($products['checkout_status'] == "Order Complete") : ?>
                              <td style="display: flex;justify-content:center;"><label class="badge badge-success badge-pill"><?php echo $products['checkout_status']; ?> </label></td>

                            <?php
                            endif;
                            ?>

                            <td>
                              <?php if ($products['checkout_status'] == "Order Complete") : ?>
                                <button class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#<?php echo $products['transaction_id']; ?>">View</button>
                              <?php else : ?>
                                <button class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#<?php echo $products['transaction_id']; ?>">Update</button>

                              <?php endif; ?>
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
                          <div class="modal fade modal-wrapper" id="<?php echo $products['transaction_id']; ?>">

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
                                          <form method="POST" action="update_orderstatus.php" id="updateProductForm">

                                            <input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo $products['transaction_id']; ?>">
                                            <input type="hidden" name="shop_name" id="shop_name" value="<?php echo $shop_name; ?>">

                                            <div class="login-form">
                                              <h4 class="login-title">Order Product</h4>
                                              <div class="row">
                                                <div class="col-lg-12">

                                                  <?php


                                                  // Fetch unique shop names and corresponding order details
                                                  $orderStmt = $conn->prepare("SELECT *  FROM checkout_order WHERE transaction_id = ? AND shop_name = ? ORDER BY transaction_id DESC");
                                                  $orderStmt->bind_param("ss", $tranID, $shop_name);
                                                  $orderStmt->execute();
                                                  $orderResult = $orderStmt->get_result();

                                                  $shopTotalPrice = 0;
                                                  ?>

                                                  <div class="li-comment-section">
                                                    <ul>

                                                      <h5 class="reply-btn1 pt-15 pt-xs-5" style="text-align: end; text-transform: uppercase; color: #457b75;">
                                                        <?php echo $products['checkout_status']; ?>
                                                      </h5>
                                                      <li>
                                                        <!-- <div class="author-avatar pt-15">
                                                          <img src="images/product-details/user.png" alt="User">
                                                        </div> -->
                                                        <div class="comment-body pl-15">




                                                          <!-- <span class="reply-btn pt-15 pt-xs-5">
                        Transaction ID: <php echo $transactionId; ?>
                    </span> -->

                                                          <ul class="comment-list">
                                                            <?php while ($order = $orderResult->fetch_assoc()) :

                                                              // Calculate total for this product
                                                              $orderTotal = $order['product_price'] * $order['product_quantity'];
                                                              // Add to the shop's total
                                                              $shopTotalPrice += $orderTotal;

                                                            ?>
                                                              <li style="margin-bottom:0px;
                             border-color:white; border-bottom: 1px solid #dddddd;">
                                                                <div class="col-lg-12">

                                                                  <div class="col-lg-12">
                                                                    <div class="row">


                                                                      <div class="col-md-8">

                                                                        <!-- <img src="<php echo $product['product_image']; ?>" alt="User"> -->
                                                                        <h5 style="color: #404040;" class="comment-author pt-15"><a href="single-product.php?product_id=">
                                                                            <?php echo $order['product_name']; ?></a>
                                                                        </h5>
                                                                        <span style="color: #404040;">Price: ₱<?php echo number_format($order['product_price'], 2); ?></span><br>

                                                                        <span style="color: #404040;">Quantity: <?php echo $order['product_quantity']; ?></span>
                                                                      </div>
                                                                      <div class="col-md-4">
                                                                        <!-- <span class="reply-btn1 pt-15 pt-xs-5">
                                                    Product Status: <php echo $product['checkout_status']; ?>
                                                </span> -->

                                                                        <br>
                                                                        <br>

                                                                        <h3 style="color: #404040;" class="comment-author pt-15">

                                                                          ₱<?php echo number_format($order['total_price'], 2); ?>
                                                                      </div>
                                                                    </div>


                                                                  </div>

                                                              </li>

                                                            <?php endwhile;

                                                            $orderStmt->close();
                                                            ?>
                                                          </ul>
                                                          <div class="shop-total" style="text-align: end;">
                                                            <br>
                                                            <h4 style="color:#8c8c8c;">Order Total: <span style="color:#457b75;">₱<?php echo number_format($shopTotalPrice, 2); ?></span></h4>
                                                          </div>
                                                        </div>
                                                      </li>
                                                    </ul>
                                                  </div>
                                                </div>
                                                <?php if ($products['checkout_status'] != "Order Complete") : ?>
                                                  <div class="col-md-6 mb-20">
                                                    <label>Update Status</label>
                                                    <!-- <label><php echo $products['checkout_status'] ?></label> -->
                                                    <select name="order_status">
                                                      <?php if ($products['checkout_status'] == "pending") : ?>
                                                        <option value="Preparing to ship" <?php if ($products == 'Preparing to ship') echo 'selected'; ?>>Preparing to ship</option>
                                                        <option value="Package picked up" <?php if ($products == 'Package picked up') echo 'selected'; ?>>Package picked up</option>
                                                        <option value="Out for Delivery" <?php if ($products == 'Out for Delivery') echo 'selected'; ?>>Out for Delivery</option>
                                                        <option value="Order Complete" <?php if ($products == 'Order Complete') echo 'selected'; ?>>Order Complete</option>
                                                      <?php elseif ($products['checkout_status'] == "Package picked up") : ?>
                                                        <option value="Out for Delivery" <?php if ($products == 'Out for Delivery') echo 'selected'; ?>>Out for Delivery</option>
                                                        <option value="Order Complete" <?php if ($products == 'Order Complete') echo 'selected'; ?>>Order Complete</option>
                                                      <?php elseif ($products['checkout_status'] == "Out for Delivery") : ?>
                                                        <option value="Out for Delivery" <?php if ($products == 'Out for Delivery') echo 'selected'; ?>>Out for Delivery</option>
                                                        <option value="Order Complete" <?php if ($products == 'Order Complete') echo 'selected'; ?>>Order Complete</option>
                                                      <?php endif; ?>

                                                    </select>
                                                  </div>
                                                <div class="col-md-6">
                                                  <br>
                                                  <br>
                                                  <button type="submit" class="register-button mt-0">Update Product</button>
                                                </div>
                                                <?php endif; ?>
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