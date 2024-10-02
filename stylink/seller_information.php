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
$productCheckStmt = $conn->prepare("SELECT shop_name, transaction_id, product_id, checkout_status, total_price, username,modePayment  , MAX(checkout_status) FROM checkout_order WHERE shop_name = ? GROUP BY transaction_id");
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
<?php include("include/head_seller.php"); ?>

<?php
include("include/admin_head.php");
?>
<style>
  .product-image {
    position: relative;
  }

  .product-image img {
    width: 100%;
    aspect-ratio: 1 / 1;
    /* Sets a 1:1 aspect ratio directly */
    object-fit: cover;
  }

  .modal-backdrop {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1040;
    background-color: #00000059;

  }
</style>

<link rel="stylesheet" href="css/popup.css">

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php
    include("include/admin_nav.php");
    ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <!-- <div id="settings-trigger"><i class="fas fa-fill-drip"></i></div> -->
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close fa fa-times"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme">
            <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
          </div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme">
            <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
          </div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles primary"></div>
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      <div id="right-sidebar" class="settings-panel">
        <i class="settings-close fa fa-times"></i>
        <ul class="nav nav-tabs" id="setting-panel" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
          </li>
        </ul>
        <div class="tab-content" id="setting-content">
          <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
            <div class="add-items d-flex px-3 mb-0">
              <form class="form w-100">
                <div class="form-group d-flex">
                  <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                  <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task-todo">Add</button>
                </div>
              </form>
            </div>
            <div class="list-wrapper px-3">
              <ul class="d-flex flex-column-reverse todo-list">
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Team review meeting at 3.00 PM
                    </label>
                  </div>
                  <i class="remove fa fa-times-circle"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Prepare for presentation
                    </label>
                  </div>
                  <i class="remove fa fa-times-circle"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">
                      Resolve all the low priority tickets due today
                    </label>
                  </div>
                  <i class="remove fa fa-times-circle"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Schedule meeting for next week
                    </label>
                  </div>
                  <i class="remove fa fa-times-circle"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>
                      Project review
                    </label>
                  </div>
                  <i class="remove fa fa-times-circle"></i>
                </li>
              </ul>
            </div>
            <div class="events py-4 border-bottom px-3">
              <div class="wrapper d-flex mb-2">
                <i class="fa fa-times-circle text-primary mr-2"></i>
                <span>Feb 11 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Creating component page</p>
              <p class="text-gray mb-0">build a js based app</p>
            </div>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="fa fa-times-circle text-primary mr-2"></i>
                <span>Feb 7 2018</span>
              </div>
              <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
              <p class="text-gray mb-0 ">Call Sarah Graves</p>
            </div>
          </div>
          <!-- To do section tab ends -->
          <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
            <div class="d-flex align-items-center justify-content-between border-bottom">
              <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
              <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 font-weight-normal">See All</small>
            </div>
            <ul class="chat-list">
              <li class="list active">
                <div class="profile"><img src="images/faces/face1.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Thomas Douglas</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">19 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face2.jpg" alt="image"><span class="offline"></span></div>
                <div class="info">
                  <div class="wrapper d-flex">
                    <p>Catherine</p>
                  </div>
                  <p>Away</p>
                </div>
                <div class="badge badge-success badge-pill my-auto mx-2">4</div>
                <small class="text-muted my-auto">23 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face3.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Daniel Russell</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">14 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face4.jpg" alt="image"><span class="offline"></span></div>
                <div class="info">
                  <p>James Richardson</p>
                  <p>Away</p>
                </div>
                <small class="text-muted my-auto">2 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face5.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Madeline Kennedy</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">5 min</small>
              </li>
              <li class="list">
                <div class="profile"><img src="images/faces/face6.jpg" alt="image"><span class="online"></span></div>
                <div class="info">
                  <p>Sarah Graves</p>
                  <p>Available</p>
                </div>
                <small class="text-muted my-auto">47 min</small>
              </li>
            </ul>
          </div>
          <!-- chat tab ends -->
        </div>
      </div>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->

      <!-- partial:partials/_navbar.html -->
      <?php
      include("include/admin_sidebar.php");
      ?>

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              Seller Infromation
            </h3>

          </div>
          <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="true">Basic Information
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile-1" role="tab" aria-controls="profile-1" aria-selected="false">Business Information</a>
                    </li>
                  </ul>

                  
                  <div class="tab-content">
                    <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                      <span>
                        Basic Information
                      </span>
                      <!-- <a> <i class="fa fa-edit" data-toggle="modal" data-target="#Update_Info">Edit</i></a> -->
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-edit"></i></button>

                      <br>
                      <br>
                      <div class="media">

                        <div class="media-body">

                          <div class="form-group">
                            <div class="row">
                              <div class="col-lg-6 grid-margin">
                                <div class="row">
                                  <div class="col-lg-3 grid-margin">
                                    <label style="justify-content: flex-end; display: flex; -webkit-box-align: center; -ms-flex-align: center; align-items: center;" for="exampleSelectGender">Shop Logo:</label>
                                  </div>
                                  <div class="col-lg-9 grid-margin">
                                    <img style="border-radius: 50%; width:150px; height:150px;" src="<?php echo isset($sellerRegisterData['shop_image']) ? $sellerRegisterData['shop_image'] : ''; ?>">
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6 grid-margin">
                                <div class="row">
                                  <div class="col-lg-3 grid-margin">
                                    <label style="justify-content: flex-end; display: flex; -webkit-box-align: center; -ms-flex-align: center; align-items: center;" for="exampleSelectGender">Shop Name:</label>
                                  </div>
                                  <div class="col-lg-9 grid-margin">
                                    <label> <?php echo isset($sellerRegisterData['shop_name']) ? $sellerRegisterData['shop_name'] : ''; ?></label>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-lg-3 grid-margin">
                                    <label style="justify-content: flex-end; display: flex; -webkit-box-align: center; -ms-flex-align: center; align-items: center;" for="exampleSelectGender">Phone No:</label>
                                  </div>
                                  <div class="col-lg-9 grid-margin">
                                    <label> <?php echo isset($sellerRegisterData['contact_no']) ? $sellerRegisterData['contact_no'] : ''; ?></label>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-lg-3 grid-margin">
                                    <label style="justify-content: flex-end; display: flex; -webkit-box-align: center; -ms-flex-align: center; align-items: center;" for="exampleSelectGender">Email:</label>
                                  </div>
                                  <div class="col-lg-9 grid-margin">
                                    <label> <?php echo isset($sellerRegisterData['email']) ? $sellerRegisterData['email'] : ''; ?></label>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-lg-3 grid-margin">
                                    <label style="justify-content: flex-end; display: flex; -webkit-box-align: center; -ms-flex-align: center; align-items: center;" for="exampleSelectGender">Shop Address:</label>
                                  </div>
                                  <div class="col-lg-9 grid-margin">
                                    <label>Brgy. <?php echo isset($sellerRegisterData['barangay']) ? $sellerRegisterData['barangay'] : ''; ?> <?php echo isset($sellerRegisterData['municipality']) ? $sellerRegisterData['municipality'] : ''; ?>, <?php echo isset($sellerRegisterData['province']) ? $sellerRegisterData['province'] : 'Laguna'; ?> </label>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-lg-3 grid-margin">
                                    <label style="justify-content: flex-end; display: flex; -webkit-box-align: center; -ms-flex-align: center; align-items: center;" for="exampleSelectGender">Type of Store:</label>
                                  </div>
                                  <div class="col-lg-9 grid-margin">
                                    <label> <?php echo isset($sellerRegisterData['store_type']) ? $sellerRegisterData['store_type'] : ''; ?></label>
                                  </div>
                                </div>
                              </div>







                            </div>
                          </div>


                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="profile-tab">
                      <div class="media">

                        <div class="media-body">
                          <div class="card-body">
                            <span>
                              Business Information
                            </span>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#businessModal"><i class="fa fa-edit"></i></button>

                            <br>
                            <div class="form-group">
                              <p style="color: #999;font-size: 14px;line-height: 18px;">Please make sure your business information is accurate and complete. Registered Name and Address, TIN, and Business Name/Style are required to be indicated in official receipts for the claim of input VAT. Any changes made to your business information will only be applied to upcoming invoices. Shopee will not re-issue any invoices due to incomplete or incorrect information provided by sellers.</p>
                              <br>


                              <label for="exampleInputName1">Registered Name (Shop Owner)</label>
                              <input type="text" readonly name="registered_name" class="form-control" id="exampleInputName1" placeholder="Registered Name" value="<?php echo isset($sellerInformationData['registered_name']) ? $sellerInformationData['registered_name'] : ''; ?>" required>
                            </div>
                            <div class="form-group">
                              <label for="exampleSelectGender">Seller Type</label>


                              <select disabled class="form-control" name="seller_type" id="exampleSelectGender">
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
                                  <input readonly type="text" name="tin_id" class="form-control" id="Taxpayer" placeholder="Taxpayer Identification Number" value="<?php echo isset($sellerInformationData['tin_id']) ? $sellerInformationData['tin_id'] : ''; ?>">
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="exampleSelectGender">Value-Added Tax Registration Status</label>
                              <select disabled class="form-control" name="tax_status" id="exampleSelectGender">
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
                                      <p>(We ensure the file provided is confidential and will be used for academic purposes).</p>

                                      <img style="width:200px; height:200px;" src="<?php echo isset($sellerInformationData['bir_image']) ? $sellerInformationData['bir_image'] : ''; ?>">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-lg-6 grid-margin">
                                  <label for="exampleInputName1">Business Name/Trade Name</label>
                                  <p>If Business Name/Trade Name is not applicable, please enter your Taxpayer Name as indicated on your BIR CoR instead (e.g. Acme, Inc.).</p>
                                  <input readonly type="text" name="trade_mark" class="form-control" id="exampleInputName1" placeholder="Name" value="<?php echo isset($sellerInformationData['trade_mark']) ? $sellerInformationData['trade_mark'] : ''; ?>" required>
                                  <br>
                                  <label for="exampleSelectGender">Submit Sworn Declaration?</label>
                                  <p>Submission of Sworn Declaration is required to be exempted from withholding tax if your total annual gross remittance is less than or equal to ₱500,000.00.</p>
                                  <select disabled class="form-control" name="sworn_declaration" id="exampleSelectGender">
                                    <option value="">Choose...</option>
                                    <option value="Yes" <?php if (isset($sellerInformationData['sworn_declaration']) && $sellerInformationData['sworn_declaration'] == 'Yes') echo 'selected'; ?>>YES</option>
                                    <option value="No" <?php if (isset($sellerInformationData['sworn_declaration']) && $sellerInformationData['sworn_declaration'] == 'No') echo 'selected'; ?>>NO</option>
                                  </select>
                                  <br>
                                </div>
                              </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary mr-2">Submit</button> -->
                            <!-- <a class="btn btn-primary mr-2" href="seller_dashboard.php">Submit</a> -->
                            <!-- <button class="btn btn-light">Cancel</button> -->

                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="contact-1" role="tabpanel" aria-labelledby="contact-tab">
                      <h4>Contact us </h4>
                      <p>
                        Feel free to contact us if you have any questions!
                      </p>
                      <p>
                        <i class="fa fa-phone text-info"></i>
                        +123456789
                      </p>
                      <p>
                        <i class="far fa-envelope-open text-success"></i>
                        contactus@example.com
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <?php if (isset($_GET['success'])) : ?>
          <div class="message-popup success "><?php echo $_GET['success']; ?></div>

        <?php elseif (isset($_GET['error'])) : ?>
          <div class="message-popup error "><?php echo $_GET['error']; ?></div>
        <?php endif; ?>

        <!-- MODALS -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form class="forms-sample" action="update_sellerinfo.php" method="post" enctype="multipart/form-data">
                  <div class="col-12 grid-margin stretch-card">
                    <div class="card-body">
                      <div class="form-group">
                        <div class="row">
                          <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card">
                              <div class="card-body">
                                <h4 class="card-title">Shop Logo</h4>
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
                            <input type="text" readonly name="province" class="form-control" id="exampleInputName1" placeholder="Province" value="<?php echo isset($sellerRegisterData['province']) ? $sellerRegisterData['province'] : 'Laguna'; ?>">
                            <br>
                            <label for="exampleInputEmail3">Municipality</label>

                            <input readonly type="hidden" id="selected_municipalities" value="<?php echo $sellerRegisterData['municipality']; ?>" required>
                            <select class="form-control" id="municipalities12" name="municipality" required>
                            </select>
                            <br>
                            <label for="exampleInputEmail3">Barangay/Sitio</label>
                            <input readonly type="hidden" id="selected_barangays" value="<?php echo $sellerRegisterData['barangay']; ?>" required>
                            <select class="form-control" id="barangays12" name="barangay" required>
                            </select>
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
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="businessModal" tabindex="-1" role="dialog" aria-labelledby="businessModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="businessModalLabel">Update Business Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form class="forms-sample" action="update_sellerbusiness.php" method="post" enctype="multipart/form-data">
                  <div class="col-12 grid-margin stretch-card">
                    <div class="card-body">
                      <span>
                        Business Information
                      </span>
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#businessModal"><i class="fa fa-edit"></i></button>

                      <br>
                      <div class="form-group">
                        <p style="color: #999;font-size: 14px;line-height: 18px;">Please make sure your business information is accurate and complete. Registered Name and Address, TIN, and Business Name/Style are required to be indicated in official receipts for the claim of input VAT. Any changes made to your business information will only be applied to upcoming invoices. Shopee will not re-issue any invoices due to incomplete or incorrect information provided by sellers.</p>
                        <br>


                        <label for="exampleInputName1">Registered Name (Shop Owner)</label>
                        <input type="text" readonly name="registered_name" class="form-control" id="exampleInputName1" placeholder="Registered Name" value="<?php echo isset($sellerInformationData['registered_name']) ? $sellerInformationData['registered_name'] : ''; ?>" required>
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
                            <input readonly type="hidden" id="selected_municipalities13" value="<?php echo $sellerInformationData['municipality']; ?>" required>
                            <select class="form-control" id="municipalities13" name="municipality" required>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label for="exampleInputEmail3">Barangay/Sitio</label>
                            <input readonly type="hidden" id="selected_barangays13" value="<?php echo $sellerInformationData['barangay']; ?>" required>
                            <select class="form-control" id="barangays13" name="barangay" required>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-12">
                            <label for="Taxpayer">Taxpayer Identification Number (TIN)</label>
                            <p>Your 9-digit TIN and 3 to 5 digit branch code. Please use “000” as your branch code if you don’t have one (e.g. 999-999-999-000)</p>
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
                                <p>(We ensure the file provided is confidential and will be used for academic purposes).</p>

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
                      <!-- <a class="btn btn-primary mr-2" href="seller_dashboard.php">Submit</a> -->
                      <!-- <button class="btn btn-light">Cancel</button> -->

                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>









        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
      
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
  <!-- End custom js for this page-->

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
  <script src="js/file-upload.js"></script>
  <script src="js/typeahead.js"></script>
  <script src="js/select2.js"></script>
  <!-- End custom js for this page-->
  <script src="js/popup.js"></script>
</body>

<script>
  $(document).ready(function() {
    // Load municipalities and set the initial value
    loadMunicipalities12(function() {
      var initialMunicipality = $("#selected_municipalities").val();
      $("#municipalities12").val(initialMunicipality);

      // Load and set initial barangay 
      if (initialMunicipality) {
        loadBarangays12(initialMunicipality, function() {
          var initialBarangay = $("#selected_barangays").val();
          $("#barangays12").val(initialBarangay);
        });
      }
    });

    // When the municipality selection changes:
    $("#municipalities12").on("change", function() {
      var municipality = $(this).val();
      if (municipality) {
        loadBarangays12(municipality);
      } else {
        $("#barangays12").empty().append('<option value="">Select Barangay</option>');
      }
    });
  });

  function loadMunicipalities12(callback) {
    $.ajax({
      url: "get_municipalities.php",
      type: "GET",
      dataType: "json",
      success: function(data) {
        $("#municipalities12").empty().append('<option value="">Select Municipality</option>');
        $.each(data, function(index, name) {
          $("#municipalities12").append($("<option>", {
            value: name,
            text: name
          }));
        });
        if (typeof callback === "function") {
          callback(); // Execute the callback after loading municipalities
        }
      },
    });
  }

  function loadBarangays12(municipality, callback) {
    $.ajax({
      url: "get_barangays.php",
      type: "GET",
      dataType: "json",
      data: {
        municipality: municipality
      },
      success: function(data) {
        $("#barangays12").empty().append('<option value="">Select Barangay</option>');
        $.each(data, function(index, name) {
          $("#barangays12").append($("<option>", {
            value: name,
            text: name
          }));
        });
        if (typeof callback === "function") {
          callback(); // Execute the callback after loading barangays
        }
      },
    });
  }
</script>


<script>
  $(document).ready(function() {
    // Load municipalities and set the initial value
    loadMunicipalities13(function() {
      var initialMunicipality = $("#selected_municipalities13").val();
      $("#municipalities13").val(initialMunicipality);

      // Load and set initial barangay 
      if (initialMunicipality) {
        loadBarangays13(initialMunicipality, function() {
          var initialBarangay = $("#selected_barangays13").val();
          $("#barangays13").val(initialBarangay);
        });
      }
    });

    // When the municipality selection changes:
    $("#municipalities13").on("change", function() {
      var municipality = $(this).val();
      if (municipality) {
        loadBarangays13(municipality);
      } else {
        $("#barangays13").empty().append('<option value="">Select Barangay</option>');
      }
    });
  });

  function loadMunicipalities13(callback) {
    $.ajax({
      url: "get_municipalities.php",
      type: "GET",
      dataType: "json",
      success: function(data) {
        $("#municipalities13").empty().append('<option value="">Select Municipality</option>');
        $.each(data, function(index, name) {
          $("#municipalities13").append($("<option>", {
            value: name,
            text: name
          }));
        });
        if (typeof callback === "function") {
          callback(); // Execute the callback after loading municipalities
        }
      },
    });
  }

  function loadBarangays13(municipality, callback) {
    $.ajax({
      url: "get_barangays.php",
      type: "GET",
      dataType: "json",
      data: {
        municipality: municipality
      },
      success: function(data) {
        $("#barangays13").empty().append('<option value="">Select Barangay</option>');
        $.each(data, function(index, name) {
          $("#barangays13").append($("<option>", {
            value: name,
            text: name
          }));
        });
        if (typeof callback === "function") {
          callback(); // Execute the callback after loading barangays
        }
      },
    });
  }
</script>
<!-- Mirrored from www.urbanui.com/melody/template/pages/forms/basic_elements.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 15 Sep 2018 06:07:34 GMT -->

</html>