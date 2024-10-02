<?php
ob_start();
session_start();
include("include/config.php");
include("include/head.php");
include("include/header.php");

// Form Handling (Only if the form is submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Basic Input Validation
    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
        // header("Location: user_login.php?error= No Account Register ");

    } else {
        // Retrieve user from database (using prepared statements) along with the role
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $userRole = $row['role']; // Assuming you have a 'role' column in your users table

            // Verify password
            if (password_verify($password, $row['password'])) {
                // Successful login
                $_SESSION["username"] = $username; // Store username in session

                // Redirect based on role
                if ($userRole == 'Admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($userRole == 'Seller') {
                    header("Location: seller_dashboard.php");
                } elseif ($userRole == 'Customer') {
                    header("Location: index.php");
                } else {
                    $error = "Invalid user role.";
                    header("Location: user_login.php?error= No Account Register ");
                }
                exit;
            } else {
                $error = "Incorrect password.";
                header("Location: user_login.php?error= Incorrect password.");
            }
        } else {
            $error = "Incorect Username/Password.";
            header("Location: user_login.php?error= No Username Found/Register.");
            
        }
    }
}

$conn->close();
ob_end_flush();
?>




<body>
    <?php if (isset($_GET['success'])) : ?>
        <div class="success-message">
            <p><?php echo $_GET['success']; ?></p>
        </div>
    <?php elseif (isset($_GET['error'])) : ?>
        <div class="error-message">
            <p><?php echo $_GET['error']; ?></p>
        </div>
    <?php endif; ?>
    <!-- Begin Body Wrapper -->
    <div class="body-wrapper">
        <!-- Begin Header Area -->

        <!-- Header Area End Here -->
        <!-- Begin Li's Breadcrumb Area -->
        <div class="breadcrumb-area">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li class="active">Register</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!-- Begin Login Content Area -->
        <div class="page-section mb-60">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12 mb-30">

                        <form action="" method="Post">
                            <div class="login-form">
                                <h4 class="login-title">Login</h4>
                                <div class="row">
                                    <div class="col-md-12 col-12 mb-20">
                                        <label>Username</label>
                                        <input class="mb-0" type="text" name="username" placeholder="Username" required>
                                    </div>
                                    <div class="col-12 mb-20">
                                        <label>Password</label>
                                        <input class="mb-0" type="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="check-box d-inline-block ml-0 ml-md-2 mt-10">
                                            <input type="checkbox" id="remember_me">
                                            <label for="remember_me">Terms And Condition</label><br>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="register-button mt-0">Login</button>
                                    </div>
                                    <div class="col-md-6 mb-20">
                                        <label>Don't have an Account? Click <a href="user_register.php">Register</a></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
        <!-- Login Content Area End Here -->
        <?php
        include("include/footer.php");
        ?>
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
</body>

<!-- login-register31:27-->

</html>