<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");

if (!isset($_SESSION["username"])) {
    // Redirect to the appropriate page for logged-in users
    header("Location: user_login.php");
    exit;
}
$username = $_SESSION["username"];

// Variables to store retrieved data
$useData = [];
// $sellerInformationData = [];

// Fetch data from seller_register table based on the logged-in user
$stmt1 = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt1->bind_param("s", $_SESSION["username"]);
$stmt1->execute();
$result1 = $stmt1->get_result();

if ($result1->num_rows > 0) {
    $userData = $result1->fetch_assoc();
}
$stmt1->close();

ob_end_flush();

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Melody Admin</title>
    <!-- <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/all.min.css"> -->
    <!-- <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css"> -->
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <link rel="stylesheet" href="css/popup.css">
    <!-- <link rel="shortcut icon" href="images/favicon.png" /> -->
</head>

<style>
    .login-form {
        /* background-color: #ffffff; */
        padding: 30px;
        -webkit-box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0);
        /* box-shadow: 0px 5px 4px 0px rgba(0, 0, 0, 0.1); */
    }

    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        margin-bottom: 20px;
        object-fit: cover;
    }

    #profileImage,
    #valid_id_front,
    #valid_id_back {
        display: none;
    }

    /* Hide the default file input */
    .change_img {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .profile_cont {
        display: flex;
        align-items: center;
        justify-content: center;
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
                        <li class="active">My Account</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!-- Begin Li's Main Blog Page Area -->
        <div class="li-main-blog-page li-main-blog-details-page pt-60 pb-60 pb-sm-45 pb-xs-45">
            <div class="container">
                <div class="row">
                    <!-- Begin Li's Blog Sidebar Area -->
                    <div class="col-lg-3 order-lg-1 order-2">
                        <div class="li-blog-sidebar-wrapper">
                            <div class="li-blog-sidebar">
                                <div class="li-sidebar-search-form">
                                    <!-- <form action="#">
                                        <input type="text" class="li-search-field" placeholder="search here">
                                        <button type="submit" class="li-search-btn"><i class="fa fa-search"></i></button>
                                    </form> -->
                                </div>
                            </div>
                            <div class="li-blog-sidebar pt-25">
                                <h4 class="li-blog-sidebar-title">My Account</h4>
                                <ul class="li-blog-archive">
                                    <li><a href="customer_profile.php">My Profile</a></li>
                                    <li><a href="customer_purchase.php">My Purchase</a></li>

                                </ul>
                            </div>

                        </div>
                    </div>
                    <!-- Li's Blog Sidebar Area End Here -->
                    <!-- Begin Li's Main Content Area -->
                    <div class="col-lg-9 order-lg-2 order-1">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="border-bottom text-center pb-4">
                                                    <!-- <img src="images/faces/face12.jpg" alt="profile" class="img-lg rounded-circle mb-3" /> -->
                                                    <?php
                                                    function display_account($conn)
                                                    {
                                                        // Check if username is set in the session
                                                        if (isset($_SESSION['username'])) {
                                                            $username = $_SESSION['username'];

                                                            // Prepare the query with username parameter (using a try-catch block for better error handling)
                                                            try {
                                                                $query = $conn->prepare("SELECT * FROM `users` WHERE username = ?");
                                                                $query->bind_param("s", $username);

                                                                if ($query->execute()) {
                                                                    $result = $query->get_result();
                                                                    return $result;  // Return the result set
                                                                } else {
                                                                    throw new Exception("Error executing query: " . $conn->error);
                                                                }
                                                            } catch (Exception $e) {
                                                                // Log the error for debugging
                                                                error_log($e->getMessage());

                                                                // Return an empty result or handle the error as you see fit
                                                                return false; // or return an empty array, etc.
                                                            }
                                                        } else {
                                                            // Handle the case where the username is not set in the session
                                                            return false; // or return an empty array
                                                        }
                                                    }

                                                    // (Assuming you have a $db object that provides the database connection)
                                                    $accountData = display_account($conn);

                                                    // Check if the function returned a valid result set
                                                    // if ($accountData) {
                                                    while ($fetch1 = $accountData->fetch_array()) {
                                                    ?>
                                                        <form action="update_profileimage.php" method="post" enctype="multipart/form-data">
                                                            <div class="profile_cont">
                                                                <img id="profileDisplay" src="<?php
                                                                                                if (!empty($fetch1['profile_image'])) {
                                                                                                    echo $fetch1['profile_image'];
                                                                                                } else {
                                                                                                    echo 'images/profile_container.png';
                                                                                                } ?>" alt="Profile Picture" class="profile-pic">
                                                            </div>

                                                            <input type="file" id="profileImage" value=" <?php echo $fetch1['profile_image'] ?>" name="profile_image" accept="images/*">
                                                            <div class="row profile_cont">

                                                                <div class="col-md-12 profile_cont">

                                                                    <label class="change_img1" style="margin-right: 10px;" for="profileImage">Change Picture</label><br>
                                                                    <button type="submit" class="change_img">Save Profile Pic</button>
                                                                </div>
                                                            </div>
                                                        </form>

                                                    <?php
                                                    }
                                                    ?>
                                                    <script>
                                                        const profileImage = document.getElementById('profileImage');
                                                        const profileDisplay = document.getElementById('profileDisplay');

                                                        const valid_id = document.getElementById('valid_id');
                                                        const validIDDisplay = document.getElementById('validIDDisplay');

                                                        profileImage.addEventListener('change', () => {
                                                            const file = profileImage.files[0];
                                                            if (file) {
                                                                const reader = new FileReader();
                                                                reader.onload = (e) => {
                                                                    profileDisplay.src = e.target.result;
                                                                }
                                                                reader.readAsDataURL(file);
                                                            }
                                                        });

                                                        valid_id.addEventListener('change', () => {
                                                            const file = valid_id.files[0];
                                                            if (file) {
                                                                const reader = new FileReader();
                                                                reader.onload = (e) => {
                                                                    validIDDisplay.src = e.target.result;
                                                                }
                                                                reader.readAsDataURL(file);
                                                            }
                                                        });
                                                    </script>


                                                </div>
                                                <!-- <div class="border-bottom py-4">
                        <p>Skills</p>
                        <div>
                          <label class="badge badge-outline-dark">Chalk</label>
                          <label class="badge badge-outline-dark">Hand lettering</label>
                          <label class="badge badge-outline-dark">Information Design</label>
                          <label class="badge badge-outline-dark">Graphic Design</label>
                          <label class="badge badge-outline-dark">Web Design</label>  
                        </div>                                                               
                      </div> -->
                                                <!-- <div class="border-bottom py-4">
                        <div class="d-flex mb-3">
                          <div class="progress progress-md flex-grow">
                            <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="55" style="width: 55%" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                        <div class="d-flex">
                          <div class="progress progress-md flex-grow">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="75" style="width: 75%" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div> -->


                                                <div class="py-4">
                                                    <p class="clearfix">
                                                        <span class="float-left">
                                                            Full Name
                                                        </span>
                                                        <span class="float-right text-muted">
                                                            <?php echo isset($userData['first_name']) ? $userData['first_name'] : ''; ?> <?php echo isset($userData['last_name']) ? $userData['last_name'] : ''; ?>
                                                        </span>
                                                    </p>

                                                    <p class="clearfix">
                                                        <span class="float-left">
                                                            Username
                                                        </span>
                                                        <span class="float-right text-muted">
                                                            <?php echo isset($userData['username']) ? $userData['username'] : ''; ?>
                                                        </span>
                                                    </p>

                                                </div>
                                                <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#exampleModalCenter">Change Password</button>

                                                <?php if (isset($_GET['success'])) : ?>
                                                    <div class="message-popup success "><?php echo $_GET['success']; ?></div>

                                                <?php elseif (isset($_GET['error'])) : ?>
                                                    <div class="message-popup error "><?php echo $_GET['error']; ?></div>
                                                <?php endif; ?>



                                                <!-- Change Password Quick View | Modal Area -->
                                                <div class="modal fade modal-wrapper" id="exampleModalCenter">
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

                                                                                <form method="POST" action="update_password.php">
                                                                                    <div class="login-form">
                                                                                        <h4 class="login-title">Change Password</h4>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 col-12 mb-20">
                                                                                                <label>Old Password</label>
                                                                                                <input class="mb-0 password-input" type="password" id="oldPassword" name="old_password" placeholder="Enter Old Password here..." required>
                                                                                            </div>
                                                                                            <div class="col-12 mb-20">
                                                                                                <label>New Password</label>
                                                                                                <input class="mb-0 password-input" type="password" id="newPassword" name="new_password" placeholder="Enter New Password here..." required>
                                                                                            </div>
                                                                                            <div class="col-12 mb-20">
                                                                                                <label>Confirm New Password</label>
                                                                                                <input class="mb-0 password-input" type="password" id="confirmPassword" name="confirm_password" placeholder="Enter Confirm Password here..." required>
                                                                                            </div>
                                                                                            <div class="col-md-8" style="display: flex; align-items: center;">
                                                                                                <input type="checkbox" id="showPasswordCheckbox" onclick="toggleAllPasswordVisibility()">
                                                                                                <label for="showPasswordCheckbox" style="text-align: center; margin-left:5px;">Show Password</label>
                                                                                            </div>
                                                                                            <div class="col-md-12">
                                                                                                <button type="submit" class="register-button mt-0">Update Password</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>

                                                                                <script>
                                                                                    function toggleAllPasswordVisibility() {
                                                                                        var x = document.getElementsByClassName("password-input");
                                                                                        for (var i = 0; i < x.length; i++) {
                                                                                            x[i].type = (x[i].type === "password") ? "text" : "password";
                                                                                        }
                                                                                    }
                                                                                </script>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Change Password Quick View | Modal Area End Here -->

                                                <!-- Address Quick View | Modal Area -->
                                                <div class="modal fade modal-wrapper" id="AddressModalCenter">
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
                                                                                <?php
                                                                                // Check if the user has an address stored
                                                                                $addressCheckStmt = $conn->prepare("SELECT * FROM customer_address WHERE username = ?");
                                                                                $addressCheckStmt->bind_param("s", $username);
                                                                                $addressCheckStmt->execute();
                                                                                $addressResult = $addressCheckStmt->get_result();
                                                                                $hasAddress = $addressResult->num_rows > 0;
                                                                                $addressRow = $addressResult->fetch_assoc();

                                                                                // Get the municipality and barangay from the database for the current user
                                                                                $municipalityQuery = $conn->prepare("SELECT municipality FROM customer_address WHERE username = ?");
                                                                                $municipalityQuery->bind_param("s", $username);
                                                                                $municipalityQuery->execute();
                                                                                $municipalityResult = $municipalityQuery->get_result();
                                                                                $userMunicipality = $municipalityResult->fetch_assoc()['municipality'];

                                                                                $barangayQuery = $conn->prepare("SELECT barangay FROM customer_address WHERE username = ?");
                                                                                $barangayQuery->bind_param("s", $username);
                                                                                $barangayQuery->execute();
                                                                                $barangayResult = $barangayQuery->get_result();
                                                                                $userBarangay = $barangayResult->fetch_assoc()['barangay'];

                                                                                ?>


                                                                                <form method="POST" action="update_address.php">
                                                                                    <div class="login-form">

                                                                                        <h4 class="login-title">Update Address</h4>
                                                                                        <div class="row">
                                                                                            <div class="col-md-6 col-12 mb-20">
                                                                                                <label>First Name</label>
                                                                                                <input class="mb-0" type="text" name="first_name" value="<?php echo $addressRow['first_name']; ?>" required>
                                                                                            </div>
                                                                                            <div class="col-md-6 col-12 mb-20">
                                                                                                <label>Last Name</label>
                                                                                                <input class="mb-0" type="text" name="last_name" value="<?php echo $addressRow['last_name']; ?>" required>
                                                                                            </div>
                                                                                            <div class="col-12 mb-20">
                                                                                                <label>Contact No:</label>
                                                                                                <input class="mb-0 " type="tel" name="phone_no" value="<?php echo $addressRow['phone_no']; ?>" required>
                                                                                            </div>

                                                                                            <div class="col-12 mb-10">
                                                                                                <label>Delivery Address</label>

                                                                                                <div class="row">
                                                                                                    <div class="col-4 mb-20">
                                                                                                        <label>Province</label>
                                                                                                        <input class="mb-0 " readonly type="text" name="province" value="<?php echo $addressRow['province']; ?>" required>
                                                                                                    </div>
                                                                                                    <div class="col-4 mb-20">
                                                                                                        <label for="municipalities">Municipality</label>
                                                                                                        <input readonly type="hidden" id="selected_municipalities" value="<?php echo $addressRow['municipality']; ?>" required>
                                                                                                        <select class="form-control" id="municipalities12" name="municipality" required>
                                                                                                        </select>

                                                                                                    </div>
                                                                                                    <div class="col-4 mb-20">
                                                                                                        <label for="barangays">Barangay/Sitio</label>
                                                                                                        <input readonly type="hidden" id="selected_barangays" value="<?php echo $addressRow['barangay']; ?>" required>
                                                                                                        <select class="form-control" id="barangays12" name="barangay" required>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>



                                                                                            </div>

                                                                                            <div class="col-12 mb-20">
                                                                                                <label>Full Address:</label>
                                                                                                <input class="mb-0 " type="text" name="full_address" value="<?php echo $addressRow['full_address']; ?>" required>
                                                                                            </div>

                                                                                            <div class="col-md-12">
                                                                                                <button type="submit" class="register-button mt-0">Update Delivery Address</button>
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
                                                <!-- Address Quick View | Modal Area End Here -->
                                                </span>
                                            </div>

                                            <div class="col-lg-8 pl-lg-12">



                                                <div class="ml-12">
                                                    <?php
                                                    // Check if the user has an address stored
                                                    $addressCheckStmt = $conn->prepare("SELECT * FROM customer_address WHERE username = ?");
                                                    $addressCheckStmt->bind_param("s", $username);
                                                    $addressCheckStmt->execute();
                                                    $addressResult = $addressCheckStmt->get_result();
                                                    $hasAddress = $addressResult->num_rows > 0;

                                                    // Fetch the address row ONLY if the user has an address
                                                    if ($hasAddress) {
                                                        $addressRow = $addressResult->fetch_assoc();
                                                    }
                                                    ?>

                                                    <div class="col-lg-12 col-12">
                                                        <?php if ($hasAddress) : ?>
                                                            <div class="li-blog-blockquote">
                                                                <blockquote style="padding:20px;margin: 0px 0;">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <h5>SHIPPING ADDRESS</h5>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <a>
                                                                                <i class="fa fa-edit" data-toggle="modal" data-target="#AddressModalCenter">Edit</i></a>
                                                                        </div>

                                                                    </div>
                                                                    <p>
                                                                        <b><?php echo $addressRow['first_name'] . ' ' . $addressRow['last_name']; ?></b>
                                                                        <?php echo $addressRow['phone_no']; ?><br>
                                                                        <?php echo $addressRow['full_address']; ?><br>
                                                                        <?php echo $addressRow['barangay'] . ', ' . $addressRow['municipality']; ?><br>
                                                                        <?php echo $addressRow['province']; ?>
                                                                    </p>
                                                                </blockquote>
                                                                <!-- <button class="btn outline-btn-primary" data-toggle="modal" data-target="#AddressModalCenter">Update Address</button> -->

                                                            </div>
                                                        <?php else : // If the user doesn't have an address, show the form  isset($_SESSION["username"])
                                                        ?>
                                                            <form action="customer_address1.php" method="post">
                                                                <div class="checkbox-form">
                                                                    <h3>SHIPPING ADDRESS</h3>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="checkout-form-list">
                                                                                <label>First Name <span class="required">*</span></label>
                                                                                <input placeholder="" name="first_name" type="text" required>

                                                                                <input type="hidden" name="username" value="<?php echo $username; ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="checkout-form-list">
                                                                                <label>Last Name <span class="required">*</span></label>
                                                                                <input placeholder="" name="last_name" type="text" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="checkout-form-list">
                                                                                <label>Phone Number <span class="required">*</span></label>
                                                                                <input placeholder="eg. 09123456789" name="phone_no" max="11" type="tel" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="country-select clearfix">
                                                                                <label>State/Province <span class="required">*</span></label>
                                                                                <input readonly type="text" name="province" class="form-control" id="exampleInputName" placeholder="Province" value="Laguna">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="country-select clearfix">
                                                                                <label>City/Municipality <span class="required">*</span></label>
                                                                                <select class="js-example-basic-single1 w-100" name="customer_municipal" id="municipalities" name="municipality" class="form-control" required>
                                                                                </select>
                                                                                <option value="" disabled selected hidden>Choose...</option>

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="country-select clearfix">
                                                                                <label>Barangay/Sitio <span class="required">*</span></label>
                                                                                <select class="js-example-basic-single1 w-100" name="customer_barangay" id="barangays" name="barangay" class="form-control" required>
                                                                                    <option value="" disabled selected hidden>Choose...</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="checkout-form-slist">
                                                                                <label>Address <span class="required">*</span></label>
                                                                                <input placeholder="Street address" name="full_address" type="text" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="order-button-payment">
                                                                        <input type="submit" placeholder="Add Address">
                                                                    </div>


                                                                    <!-- <div class="different-address">
            <div class="order-notes">
                <div class="checkout-form-list">
                    <label>Order Notes</label>
                    <textarea id="checkout-mess" cols="30" rows="10" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                </div>
            </div>
        </div> -->
                                                                </div>
                                                            </form>

                                                        <?php endif; // If the user doesn't have an address, show the form  isset($_SESSION["username"])
                                                        ?>




                                                    </div>

                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Li's Main Content Area End Here -->
                </div>
            </div>
        </div>
        <!-- Li's Main Blog Page Area End Here -->

        <?php include("include/footer.php"); ?>

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
    <script src="js/popup.js"></script>
    <!-- <script src="js/get_municipal.js"></script> -->
    <!-- <script src="js/get_municipal1.js"></script> -->
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
        // Load municipalities on page load
        loadMunicipalities();

        // When the municipality selection changes:
        $("#municipalities").on("change", function() {
            var municipality = $(this).val();
            if (municipality) {
                loadBarangays(municipality);
            } else {
                $("#barangays")
                    .empty()
                    .append('<option value="">Select Barangay</option>');
            }
        });
    });

    function loadMunicipalities() {
        $.ajax({
            url: "get_municipalities.php",
            type: "GET",
            dataType: "json",
            success: function(data) {
                $("#municipalities").append(
                    $("<option>", {
                        value: "",
                        text: "Select Municipality",
                    })
                );
                $.each(data, function(index, name) {
                    $("#municipalities").append(
                        $("<option>", {
                            value: name,
                            text: name,
                        })
                    );
                });
            },
        });
    }

    function loadBarangays(municipality) {
        $.ajax({
            url: "get_barangays.php",
            type: "GET",
            dataType: "json",
            data: {
                municipality: municipality,
            },
            success: function(data) {
                $("#barangays")
                    .empty()
                    .append('<option value="">Select Barangay</option>');
                $.each(data, function(index, name) {
                    $("#barangays").append(
                        $("<option>", {
                            value: name,
                            text: name,
                        })
                    );
                });
            },
        });
    }
</script>
<!-- blog-details-left-sidebar32:00-->

</html>