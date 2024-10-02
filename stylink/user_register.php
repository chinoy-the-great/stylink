<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");
include("include/header.php");


// Form Handling (Only if the form is submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from form
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Basic Input Validation (Add more as needed)
    if (empty($firstName) || empty($lastName) || empty($username) || empty($password) || empty($confirmPassword)) {
        // echo "All fields are required.";
        header("Location: user_register.php?error=All fields are required.");

    } elseif ($password != $confirmPassword) {
        // echo "Passwords do not match.";
        header("Location: user_register.php?error=Passwords do not match.");

    } else {
        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database (Use prepared statements to prevent SQL injection)
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password, role) VALUES (?, ?, ?, ?,'Customer')");
        $stmt->bind_param("ssss", $firstName, $lastName, $username, $hashedPassword);

        if ($stmt->execute()) {
            // Store username in session for future use (after successful registration)
            $_SESSION["username"] = $username;

            // Redirect or show a success message
            header("Location: user_register.php?success=Account Created Successfully");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
ob_end_flush();
?>

<style>
    /* Success Message */
    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
        border-radius: 4px;
        text-align: center;
    }
    .success-message p{
        color: #155724;
        text-align: center;

    }
    .error-message {
        background-color: #ffa3a3;
        color: #b80b43;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
        border-radius: 4px;
        text-align: center;
    }
    .error-message p{
        color: #b80b43;
        text-align: center;

    }
</style>

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
                 
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
                        <form action="" method="POST">
                            <div class="login-form">
                                <h2 class="login-title1" style="text-align: center;">Tell us who you are</h2>
                                <h4 class="login-title">Register</h4>
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-20">
                                        <label>First Name</label>
                                        <input class="mb-0" type="text" name="firstName" placeholder="First Name" required>
                                    </div>
                                    <div class="col-md-6 col-12 mb-20">
                                        <label>Last Name</label>
                                        <input class="mb-0" type="text" name="lastName" placeholder="Last Name" required>
                                    </div>
                                    <div class="col-md-12 mb-20">
                                        <label>Username</label>
                                        <input class="mb-0" type="username" name="username" placeholder="Username" required>
                                    </div>
                                    <div class="col-md-6 mb-20">
                                        <label>Password</label>
                                        <input class="mb-0" type="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="col-md-6 mb-20">
                                        <label>Confirm Password</label>
                                        <input class="mb-0" type="password" name="confirmPassword" placeholder="Confirm Password" required>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="register-button mt-0">Register</button>
                                    </div>
                                    <div class="col-md-6 mb-20">
                                        <label>Already have an Account? Click <a href="user_login.php">Login</a></label>
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