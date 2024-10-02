<?php
ob_start();
session_start();
include("include/head.php");
// include("include/header.php");

include("include/config.php");

// Check if the user is already logged in (assuming you have a 'username' session variable for logged-in users)
if (!isset($_SESSION["username"])) {
  // Redirect to the appropriate page for logged-in users
  header("Location: user_login.php"); // Replace with your dashboard page
  exit;
}

// Check if the user has already registered as a seller (assuming you have a seller_id in the seller_register table)
$checkSellerStmt = $conn->prepare("SELECT username FROM seller_register WHERE username = ?"); // or use username if that's what you use for login
$checkSellerStmt->bind_param("s", $_SESSION["username"]); // Assuming you have the username in the session
$checkSellerStmt->execute();
$checkSellerResult = $checkSellerStmt->get_result();

if ($checkSellerResult->num_rows > 0) {
  // Redirect if already registered as a seller
  header("Location: seller_register1.php"); // Replace with your seller dashboard page
  exit;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the form data
  $shopName = $_POST["shop_name"];
  $province = $_POST["province"];
  $municipality = $_POST["municipality"];
  $barangay = $_POST["barangay"];
  $storeType = $_POST["store_type"];
  $contactNo = $_POST["contact_no"];
  $email = $_POST["email"];
  $facebook = $_POST["facebook"];
  $instagram = $_POST["instagram"];
  $twitter = $_POST["twitter"];

  // Image Upload Handling
  $targetDir = "uploads/";
  $targetFile = $targetDir . basename($_FILES["shop_image"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["shop_image"]["tmp_name"]);
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
  $checkUniqueStmt = $conn->prepare("SELECT * FROM seller_register WHERE shop_name = ? OR email = ? OR contact_no = ?");
  $checkUniqueStmt->bind_param("sss", $shopName, $email, $contactNo);
  $checkUniqueStmt->execute();
  $checkUniqueResult = $checkUniqueStmt->get_result();
  if ($checkUniqueResult->num_rows > 0) {
    $error = "Error: Shop name, email, or contact number already exists.";
    $uploadOk = 0; // Prevent upload if not unique
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    // Display error message and skip the insertion 
    echo $error;
  } else {
    // If everything is ok, try to upload file
    if (move_uploaded_file($_FILES["shop_image"]["tmp_name"], $targetFile)) {

      $username = $_SESSION["username"];

      // Prepare and execute the SQL query with the image path AND username
      $stmt = $conn->prepare("INSERT INTO seller_register (username,shop_name, province, municipality, barangay, store_type, contact_no, email, facebook, instagram, twitter, shop_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssssssssss", $username, $shopName, $province, $municipality, $barangay, $storeType, $contactNo, $email, $facebook, $instagram, $twitter, $targetFile);

      if ($stmt->execute()) {
        // Store shop_name in session after successful registration
        $_SESSION["shop_name"] = $shopName;

        $success = "Registration successful!";
        header("Location: seller_register1.php"); // Replace with your seller dashboard page
        exit;
      } else {
        $error = "Error: " . $stmt->error;
      }
    } else {
      $error = "Sorry, there was an error uploading your file.";
    }
  }
  $checkUniqueStmt->close();
}

$conn->close();
ob_end_flush();
?>

<header>
  <!-- Begin Header Top Area -->
  <div class="header-top">
    <div class="container">
      <div class="row">
        <!-- Begin Header Top Left Area -->
        <div class="col-lg-3 col-md-4">
          <div class="header-top-left">
            <ul class="phone-wrap">
              <li><span></span><a href="seller_register.php">Be A Seller</a></li>
            </ul>
          </div>
        </div>
        <!-- Header Top Left Area End Here -->
        <!-- Begin Header Top Right Area -->
        <div class="col-lg-9 col-md-8">
          <div class="header-top-right">
            <ul class="ht-menu">
              <!-- Begin Setting Area -->
              <li>
                <div class="ht-setting-trigger"><span>Setting</span></div>
                <div class="setting ht-setting">
                  <ul class="ht-setting-list">
                    <li><a href="login-register.html">My Account</a></li>
                    <li><a href="checkout.html">Checkout</a></li>
                    <li><a href="user_login.php">Sign In</a></li>
                    <li><a href="logout.php">Logout</a></li>
                  </ul>
                </div>
              </li>
              <!-- Setting Area End Here -->
              <!-- Begin Currency Area -->
              <li><span></span><a href="customer_wardrobe.php">Wardrobe</a></li>
              <!-- Currency Area End Here -->
              <!-- Begin Language Area -->
              <!-- <li>
                                            <span class="language-selector-wrapper">Language :</span>
                                            <div class="ht-language-trigger"><span>English</span></div>
                                            <div class="language ht-language">
                                                <ul class="ht-setting-list">
                                                    <li class="active"><a href="#"><img src="images/menu/flag-icon/1.jpg" alt="">English</a></li>
                                                    <li><a href="#"><img src="images/menu/flag-icon/2.jpg" alt="">Français</a></li>
                                                </ul>
                                            </div>
                                        </li> -->
              <!-- Language Area End Here -->
            </ul>
          </div>
        </div>
        <!-- Header Top Right Area End Here -->
      </div>
    </div>
  </div>
  <!-- Header Top Area End Here -->
  <!-- Begin Header Middle Area -->
  <div class="header-middle pl-sm-0 pr-sm-0 pl-xs-0 pr-xs-0">
    <div class="container">
      <div class="row">
        <!-- Begin Header Logo Area -->
        <div class="col-lg-3">
          <div class="logo pb-sm-30 pb-xs-30">
            <a href="index.php">
              <!-- <img src="images/menu/logo/1.jpg" alt=""> -->
              <img src="images/menu/logo/4.png" width="200px" alt="">
            </a>
          </div>
        </div>
        <!-- Header Logo Area End Here -->
        <!-- Begin Header Middle Right Area -->
        <div class="col-lg-9 pl-0 ml-sm-15 ml-xs-15">

        </div>
        <!-- Header Middle Right Area End Here -->
      </div>
    </div>
  </div>
  <!-- Header Middle Area End Here -->

  <!-- Header Bottom Area End Here -->
  <!-- Begin Mobile Menu Area -->

  <!-- Mobile Menu Area End Here -->
</header>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Melody Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">

  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="shortcut icon" href="http://www.urbanui.com/" />
</head>


<?php
include("include/head.php");
?>
<style>
  .horizontal-menu .page-body-wrapper {
    padding: 0px;
  }

  .wizard>.content {
    min-height: 35em;
    max-height: 100%;
    height: 100%;

  }

  .wizard {
    display: block;
    width: 100%;
    height: 100%;
    overflow: hidden;
  }

  .wizard>.content>.body {
    float: left;
    position: absolute;
    width: 100%;
    height: 100%;
    padding: 2.5%;
  }

  .content-wrapper {
    background: #ffffff;
    padding: 1.5rem 1.7rem;
    width: 100%;
    -webkit-flex-grow: 1;
    flex-grow: 1;
  }

  .card {
    border: 1px solid #ffffff;
  }

  .btn-info {
    background: #457b75;
    border-color: #457b75;
  }

  .btn-secondary {
    background: #5ac5b9;
    border-color: #5ac5b9;
  }

  .btn-secondary:hover {
    background-color: #457b75;
  }
</style>

<body class="horizontal-menu">
  <div class="container-scroller">
    <!-- partial"partials/_navbar.html -->

    <!-- partial -->
    <div class="container">

      <div class="container-fluid page-body-wrapper">
        <!-- partial"partials/_settings-panel.html -->



        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">
                Seller Registration
              </h3>

            </div>
            <div class="row">
              <div class="col-md-4">
                <h5 class="btn btn-info btn-clipboard col-md-12">Seller Profile</h5>
              </div>
              <div class="col-md-4">
                <h5 class="btn btn-secondary btn-fw col-md-12">Business Information</h5>
              </div>
              <div class="col-md-4">
                <h5 class="btn btn-secondary btn-fw col-md-12">Review Information</h5>
              </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Seller Registration</h4>
                  <!-- <p class="card-description">
                    Basic form elements
                  </p> -->

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

                  <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6 grid-margin stretch-card">
                          <div class="card">
                            <div class="card-body">
                              <h4 class="card-title">Shop Profile</h4>
                              <input required type="file" name="shop_image" class="dropify" />
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6 grid-margin">
                          <label for="exampleInputName1">Shop Name</label>
                          <input type="text" name="shop_name" class="form-control" id="exampleInputName1" placeholder="Name" required>
                          <br>
                          <label for="exampleInputName1">Location</label>
                          <label for="exampleInputName1">Province</label>
                          <input readonly type="text" name="province" class="form-control" id="exampleInputName1" placeholder="Province" value="Laguna">
                          <br>
                          <label for="exampleInputEmail3">Municipality</label>
                          <select class="form-control" id="municipalities" name="municipality" class="form-control" required>
                          </select>
                          <br>

                          <label for="exampleInputEmail3">Barangay/Sitio</label>
                          <select class="form-control" id="barangays" name="barangay" class="form-control" required>
                          </select>

                        </div>

                      </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleSelectGender">Type of Store</label>
                      <select class="form-control" name="store_type" id="exampleSelectGender">
                        <option>Choose...</option>
                        <option value="Rental">Rental</option>
                        <option value="Non-Rental">Non-Rental</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword4">Phone Number</label>
                      <input type="tel" name="contact_no" class="form-control" id="exampleInputPassword4" placeholder="Phone Number">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword4">Email Address</label>
                      <input type="email" name="email" class="form-control" id="exampleInputPassword4" placeholder="Emai Address">
                    </div>
                    <div class="form-group">
                      <label>Social Media(Optional)</label>

                      <div class="row">
                        <div class="col-md-4">
                          <label>Facebook</label>
                          <input type="text" name="facebook" class="form-control" placeholder="Paste Facebook Shop Link...">
                        </div>
                        <div class="col-md-4">
                          <label>Instagram</label>
                          <input type="text" name="instagram" class="form-control" placeholder="Paste Instagram Shop Link...">
                        </div>
                        <div class="col-md-4">
                          <label>Twitter</label>
                          <input type="text" name="twitter" class="form-control" placeholder="Paste Twitter Shop Link...">
                        </div>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
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
  </div>
  <!-- container-scroller -->
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
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- <script src="js/wizard.js"></script> -->
  <script src="js/dropify.js"></script>
  <!-- End custom js for this page-->

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

  <!-- Get municipalities and Barangays -->

  <script>
    $(document).ready(function() {
      // Load municipalities on page load
      loadMunicipalities();

      // When the municipality selection changes:
      $('#municipalities').on('change', function() {
        var municipality = $(this).val();
        if (municipality) {
          loadBarangays(municipality);
        } else {
          $('#barangays').empty().append('<option value="">Select Barangay</option>');
        }
      });
    });

    function loadMunicipalities() {
      $.ajax({
        url: 'get_municipalities.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#municipalities').append($('<option>', {
            value: '',
            text: 'Select Municipality'
          }));
          $.each(data, function(index, name) {
            $('#municipalities').append($('<option>', {
              value: name,
              text: name
            }));
          });
        }
      });
    }

    function loadBarangays(municipality) {
      $.ajax({
        url: 'get_barangays.php',
        type: 'GET',
        dataType: 'json',
        data: {
          municipality: municipality
        },
        success: function(data) {
          $('#barangays').empty().append('<option value="">Select Barangay</option>');
          $.each(data, function(index, name) {
            $('#barangays').append($('<option>', {
              value: name,
              text: name
            }));
          });
        }
      });
    }
  </script>
</body>


<!-- Mirrored from www.urbanui.com/melody/template/pages/forms/wizard.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 15 Sep 2018 06:08:26 GMT -->

</html>