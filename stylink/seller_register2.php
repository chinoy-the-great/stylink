<?php
ob_start();
session_start();
include("include/head.php");
// include("include/header.php");

include("include/config.php");

// Check if the user is already logged in (assuming you have a 'username' session variable for logged-in users)
if (!isset($_SESSION["username"])) {
  // Redirect to the appropriate page for logged-in users
  header("Location: user_login.php");
  exit;
}
// seller_id in the seller_register table
$sellerData = [
  'province' => 'Laguna'
];

// Variables to store retrieved data
$sellerRegisterData = [];
$sellerInformationData = [];

// Fetch data from seller_register table based on the logged-in user
$stmt1 = $conn->prepare("SELECT * FROM seller_register WHERE username = ?");
$stmt1->bind_param("s", $_SESSION["username"]);
$stmt1->execute();
$result1 = $stmt1->get_result();

if ($result1->num_rows > 0) {
  $sellerRegisterData = $result1->fetch_assoc();
}
$stmt1->close();

// Fetch data from seller_information table based on the logged-in user
$stmt2 = $conn->prepare("SELECT * FROM seller_information WHERE username = ?");
$stmt2->bind_param("s", $_SESSION["username"]);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
  $sellerInformationData = $result2->fetch_assoc();
}
$stmt2->close(); 

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
                  </ul>
                </div>
              </li>
              <!-- Setting Area End Here -->
              <!-- Begin Currency Area -->
              <li>
                <span class="currency-selector-wrapper">Currency :</span>
                <div class="ht-currency-trigger"><span>USD $</span></div>
                <div class="currency ht-currency">
                  <ul class="ht-setting-list">
                    <li><a href="#">EUR €</a></li>
                    <li class="active"><a href="#">USD $</a></li>
                  </ul>
                </div>
              </li>
              <!-- Currency Area End Here -->
              <!-- Begin Language Area -->
              <li>
                <span class="language-selector-wrapper">Language :</span>
                <div class="ht-language-trigger"><span>English</span></div>
                <div class="language ht-language">
                  <ul class="ht-setting-list">
                    <li class="active"><a href="#"><img src="images/menu/flag-icon/1.jpg" alt="">English</a></li>
                    <li><a href="#"><img src="images/menu/flag-icon/2.jpg" alt="">Français</a></li>
                  </ul>
                </div>
              </li>
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
</style>

<body class="horizontal-menu">
  <div class="container-scroller">
    <!-- partial"partials/_navbar.html -->

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial"partials/_settings-panel.html -->



      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              Review Information
            </h3>

          </div>
          <div class="row">
            <div class="col-md-4">
              <h5 class="btn btn-secondary btn-clipboard col-md-12">Seller Profile</h5>
            </div>
            <div class="col-md-4">
              <h5 class="btn btn-secondary btn-fw col-md-12">Business Information</h5>
            </div>
            <div class="col-md-4">
              <h5 class="btn btn-info btn-fw col-md-12">Review Information</h5>
            </div>
          </div>


          <!-- <h4 class="card-title">Seller Registration</h4> -->
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
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h3 class="page-title">
                    Shop Profile
                  </h3>
                  <br>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">Shop Profile</h4>
                            <input type="file" name="shop_image" class="dropify" data-default-file="<?php echo isset($sellerRegisterData['shop_image']) ? $sellerRegisterData['shop_image'] : ''; ?>" />
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 grid-margin">
                        <label for="exampleInputName1">Shop Name</label>
                        <input type="text" name="shop_name" class="form-control" id="exampleInputName1" placeholder="Name" value="<?php echo isset($sellerRegisterData['shop_name']) ? $sellerRegisterData['shop_name'] : ''; ?>" required>
                        <br>
                        <label for="exampleInputName1">Location</label>
                        <label for="exampleInputName1">Province</label>
                        <input type="text" name="province" class="form-control" id="exampleInputName1" placeholder="Province" value="<?php echo isset($sellerRegisterData['province']) ? $sellerRegisterData['province'] : 'Laguna'; ?>">
                        <br>
                        <label for="exampleInputEmail3">Municipality</label>
                        <input type="text" name="shop_name" class="form-control" id="exampleInputName1" placeholder="Name" value="<?php echo isset($sellerRegisterData['municipality']) ? $sellerRegisterData['municipality'] : ''; ?>" required>
                        <br>
                        <label for="exampleInputEmail3">Barangay/Sitio</label>
                        <input type="text" name="shop_name" class="form-control" id="exampleInputName1" placeholder="Name" value="<?php echo isset($sellerRegisterData['barangay']) ? $sellerRegisterData['barangay'] : ''; ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelectGender">Type of Store</label>
                    <select class="form-control" name="store_type" id="exampleSelectGender">
                      <option>Choose...</option>
                      <option value="Rental" <?php if (isset($sellerRegisterData['store_type']) && $sellerRegisterData['store_type'] == 'Rental') echo 'selected'; ?>>Rental</option>
                      <option value="Non-Rental" <?php if (isset($sellerRegisterData['store_type']) && $sellerRegisterData['store_type'] == 'Non-Rental') echo 'selected'; ?>>Non-Rental</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword4">Phone Number</label>
                    <input type="tel" name="contact_no" class="form-control" id="exampleInputPassword4" placeholder="Phone Number" value="<?php echo isset($sellerRegisterData['contact_no']) ? $sellerRegisterData['contact_no'] : ''; ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword4">Email Address</label>
                    <input type="email" name="email" class="form-control" id="exampleInputPassword4" placeholder="Email Address" value="<?php echo isset($sellerRegisterData['email']) ? $sellerRegisterData['email'] : ''; ?>">
                  </div>
                  <div class="form-group">
                    <label>Social Media(Optional)</label>
                    <div class="row">
                      <div class="col-md-4">
                        <label>Facebook</label>
                        <input type="text" name="facebook" class="form-control" placeholder="Paste Facebook Shop Link..." value="<?php echo isset($sellerRegisterData['facebook']) ? $sellerRegisterData['facebook'] : ''; ?>">
                      </div>
                      <div class="col-md-4">
                        <label>Instagram</label>
                        <input type="text" name="instagram" class="form-control" placeholder="Paste Instagram Shop Link..." value="<?php echo isset($sellerRegisterData['instagram']) ? $sellerRegisterData['instagram'] : ''; ?>">
                      </div>
                      <div class="col-md-4">
                        <label>Twitter</label>
                        <input type="text" name="twitter" class="form-control" placeholder="Paste Twitter Shop Link..." value="<?php echo isset($sellerRegisterData['twitter']) ? $sellerRegisterData['twitter'] : ''; ?>">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h3 class="page-title">
                    Business Information
                  </h3>
                  <br>
                  <div class="form-group">
                    <label for="exampleInputName1">Registered Name (Shop Owner)</label>
                    <input type="text" name="registered_name" class="form-control" id="exampleInputName1" placeholder="Registered Name" value="<?php echo isset($sellerInformationData['registered_name']) ? $sellerInformationData['registered_name'] : ''; ?>" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelectGender">Seller Type</label>
                  

                    <select class="form-control" name="seller_type" id="exampleSelectGender">
                          <option value="">Choose...</option>
                          <option value="Sole Proprietorship " <?php if (isset($sellerInformationData['seller_type']) && $sellerInformationData['seller_type'] == 'Sole Proprietorship ') echo 'selected'; ?>>Sole Proprietorship</option>
                          <option value="Partnership/ Corporation" <?php if (isset($sellerInformationData['seller_type']) && $sellerInformationData['seller_type'] == 'Partnership/ Corporation') echo 'selected'; ?>>Partnership/ Corporation</option>
                        </select>

                  </div>
                  <div class="form-group">
                    <label for="exampleInputName1">Registered Address</label>
                    <div class="row">
                      <div class="col-md-4">
                        <label for="exampleInputName1">Province</label>
                        <input type="text" name="province" class="form-control" id="exampleInputName1" placeholder="Province" value="<?php echo isset($sellerInformationData['province']) ? $sellerInformationData['province'] : 'Laguna'; ?>" readonly>
                      </div>
                      <div class="col-md-4">
                        <label for="exampleInputEmail3">Municipality</label>
                        <input type="text" name="municipality" class="form-control" id="exampleInputName1" placeholder="Municipality" value="<?php echo isset($sellerInformationData['municipality']) ? $sellerInformationData['municipality'] : ''; ?>" readonly>

                      </div>
                      <div class="col-md-4">
                        <label for="exampleInputEmail3">Barangay/Sitio</label>
                        <input type="text" name="barangay" class="form-control" id="exampleInputName1" placeholder="Barangay" value="<?php echo isset($sellerInformationData['barangay']) ? $sellerInformationData['barangay'] : ''; ?>" readonly>

                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-12">
                        <label for="Taxpayer">Taxpayer Identification Number (TIN)</label>
                        <p>Your 9-digit TIN and 3 to 5 digital branch code. Please use “000” as your branch code if you don’t have any one</p>
                        <input type="text" name="tin_id" class="form-control" id="Taxpayer" placeholder="Taxpayer Identification Number" value="<?php echo isset($sellerInformationData['tin_id']) ? $sellerInformationData['tin_id'] : ''; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleSelectGender">Value-Added Tax Registration Status</label>
                    <select class="form-control" name="tax_status" id="exampleSelectGender">
                      <option value="">Choose...</option>
                      <option value="VAT Registered" <?php if (isset($sellerInformationData['tax_status']) && $sellerInformationData['tax_status'] == 'VAT Registered') echo 'selected'; ?>>VAT Registered</option>
                      <option value="Non-VAT Registered" <?php if (isset($sellerInformationData['tax_status']) && $sellerInformationData['tax_status'] == 'Non-VAT Registered') echo 'selected'; ?>>Non-VAT Registered</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">BIR Certificate Of Registration</h4>
                            <p>(We ensure the picture provided is confidential and will be used for academic purposes).</p>
                            <input type="file" name="bir_image" class="dropify" data-default-file="<?php echo isset($sellerInformationData['bir_image']) ? $sellerInformationData['bir_image'] : ''; ?>" />
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 grid-margin">
                        <label for="exampleInputName1">Business Name/Trade Name</label>
                        <p>If Business Name/Trade Name is not applicable, please enter your Taxpayer Name as indicated on your BIR CoR instead (e.g. Acme, Inc.).</p>
                        <input type="text" name="trade_mark" class="form-control" id="exampleInputName1" placeholder="Name" value="<?php echo isset($sellerInformationData['trade_mark']) ? $sellerInformationData['trade_mark'] : ''; ?>" required>
                        <br>
                        <label for="exampleSelectGender">Submit Sworn Declaration?</label>
                        <p>Submission of Sworn Declaration is required to be exempted from withholding tax if your total annual gross remittance is less than or equal to ₱500,000.00.</p>
                        <select class="form-control" name="sworn_declaration" id="exampleSelectGender">
                          <option value="">Choose...</option>
                          <option value="Yes" <?php if (isset($sellerInformationData['sworn_declaration']) && $sellerInformationData['sworn_declaration'] == 'Yes') echo 'selected'; ?>>YES</option>
                          <option value="No" <?php if (isset($sellerInformationData['sworn_declaration']) && $sellerInformationData['sworn_declaration'] == 'No') echo 'selected'; ?>>NO</option>
                        </select>
                        <br>
                      </div>
                    </div>
                  </div>
                  <!-- <button type="submit" class="btn btn-primary mr-2">Submit</button> -->
                  <a class="btn btn-primary mr-2" href="seller_dashboard.php">Submit</a>
                  <!-- <button class="btn btn-light">Cancel</button> -->

                </div>
              </div>
            </div>
          </form>


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
  <script src="js/formpickers.js"></script>
  <script src="js/form-addons.js"></script>
  <script src="js/x-editable.js"></script>
  <script src="js/dropify.js"></script>
  <script src="js/dropzone.js"></script>
  <script src="js/jquery-file-upload.js"></script>
  <script src="js/formpickers.js"></script>
  <script src="js/form-repeater.js"></script>
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