<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];

// Fetch favorite outfit IDs
$stmt = $conn->prepare("SELECT * FROM favorite_outfits WHERE username = ? ORDER BY id DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$top_id = [];
$bottom_id = [];
while ($row = $result->fetch_assoc()) {
    $top_id[] = $row['top_id'];
    $bottom_id[] = $row['bottom_id'];
}
$stmt->close();


ob_end_flush();

?>
<!-- blog-2-column31:55-->

<head>
   
   
    <!-- Modernizr js -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>

    <link rel="shortcut icon" href="images/favicon.png" />
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/iconfonts/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.addons.css">



</head>

<style>
    .lg-image img,
    .sm-image img {

        width: 100%;
        aspect-ratio: 1 / 1;
        /* Sets a 1:1 aspect ratio directly */
        object-fit: cover;
    }

    .lg-image {
        padding: 10px;
    }

    .lg-image-cloth,
    .show_image img,
    .li-blog-content img {
        width: 70%;
        aspect-ratio: 1 / 1;
        /* Sets a 1:1 aspect ratio directly */
        object-fit: cover;
        /* padding: 10px; */
    }

    .show_image_top,
    .show_image_bottom {
        display: flex;
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
                        <li class="active">Personal Wardrobe</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-wraper">
            <div class="container">
                <h4 class="card-title">Wardrobe Management</h4>
                <p class="card-description">sample short descript about sa wardrobe management nyo(short lang)</p>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link " id="home-tab1" href="customer_wardrobe.php" aria-controls="home-1" aria-selected="true">Product Wardrobe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="profile-tab1" href="customer_customizewardrobe.php" aria-controls="profile-1" aria-selected="false">Customize Styling</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active " id="profile-tab1" href="customer_favoritewardrobe.php" aria-controls="profile-1" aria-selected="false">Favorite Outfits</a>
                    </li>


                </ul>
                <br>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home-1" role="tab" aria-controls="home-1" aria-selected="true">Favorite Product Outfits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#top-1" role="tab" aria-controls="top-1" aria-selected="false">Favorite Custmize Tops</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="bottom-tab" data-toggle="tab" href="#bottom-1" role="tab" aria-controls="bottom-1" aria-selected="false">Favorite Custmize Bottom</a>
                    </li>

                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="home-1" role="tabpanel" aria-labelledby="home-tab">
                        <div class="col-lg-12">
                            <div class="row li-main-content">
                                <?php if (isset($error)) : ?>
                                    <div class="error-message">
                                        <p><?php echo $error; ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($success)) : ?>
                                    <div class="success-message alert">
                                        <p><?php echo $success; ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php

                                $stmt = $conn->prepare("SELECT * FROM favorite_outfits WHERE username = ? AND archive_status = 'Active' ORDER BY id DESC");
                                $stmt->bind_param("s", $username);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Display favorite outfits
                                if ($result->num_rows > 0) {
                                    while ($outfit = $result->fetch_assoc()) {
                                        $topId = $outfit['top_id'];
                                        $bottomId = $outfit['bottom_id'];
                                        $ID = $outfit['id'];
                                        // Fetch top image data
                                        $stmtTop = $conn->prepare("SELECT clothes_image FROM wardrobe_top WHERE id = ?");
                                        $stmtTop->bind_param("i", $topId);
                                        $stmtTop->execute();
                                        $resultTop = $stmtTop->get_result();

                                        if ($resultTop->num_rows > 0) { // Check if a row was found
                                            $topData = $resultTop->fetch_assoc();
                                            $topImage = $topData['clothes_image'];
                                        } else {
                                            $topImage = null; // Set to null if no image found
                                        }
                                        $stmtTop->close();

                                        // Fetch bottom image data
                                        $stmtBottom = $conn->prepare("SELECT clothes_image FROM wardrobe_bottom WHERE id = ?");
                                        $stmtBottom->bind_param("i", $bottomId);
                                        $stmtBottom->execute();
                                        $resultBottom = $stmtBottom->get_result();

                                        if ($resultBottom->num_rows > 0) { // Check if a row was found
                                            $bottomData = $resultBottom->fetch_assoc();
                                            $bottomImage = $bottomData['clothes_image'];
                                        } else {
                                            $bottomImage = null; // Set to null if no image found
                                        }
                                        $stmtBottom->close();
                                ?>
                                        <div class="col-lg-4 col-md-6" style="margin-bottom: 30px;">
                                            <div class="li-blog-single-item pb-25">
                                                <div class="li-blog-content">
                                                    <a href="remove_to_favorite.php?id=<?php echo $ID; ?>">Trash</a>
                                                </div>
                                                <div class="li-blog-content">
                                                    <?php if ($topImage) : ?>
                                                        <img class="img-full" src="<?php echo $topImage; ?>" alt="Favorite Top">
                                                    <?php else : ?>
                                                        <span>Deleted Top</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="li-blog-content">
                                                    <?php if ($bottomImage) : ?>
                                                        <img class="img-full" src="<?php echo $bottomImage; ?>" alt="Favorite Bottom">
                                                    <?php else : ?>
                                                        <span>Deleted Bottom</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo "No products found.";
                                }
                                $stmt->close();
                                ?>


                                <!-- Begin Li's Pagination Area -->
                                <div class="col-lg-12">
                                    <div class="li-paginatoin-area text-center pt-25">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="li-pagination-box">
                                                    <li><a class="Previous" href="#">Previous</a></li>
                                                    <li class="active"><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a class="Next" href="#">Next</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Li's Pagination End Here Area -->
                            </div>
                        </div>
                    </div>
                   
                    <div class="tab-pane fade" id="top-1" role="tabpanel" aria-labelledby="top-tab">
                        <div class="col-lg-12">
                            <div class="row li-main-content">
                                <?php if (isset($error)) : ?>
                                    <div class="error-message">
                                        <p><?php echo $error; ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($success)) : ?>
                                    <div class="success-message alert">
                                        <p><?php echo $success; ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php

                                $stmt = $conn->prepare("SELECT * FROM favorite_tops WHERE username = ? AND archive_status = 'Active' ORDER BY id DESC");
                                $stmt->bind_param("s", $username);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Display favorite outfits
                                if ($result->num_rows > 0) {
                                    while ($outfit = $result->fetch_assoc()) {
                                        $topId = $outfit['top_id'];
                                        $ID = $outfit['id'];
                                      
                                        // Fetch bottom image data
                                        $stmtBottom = $conn->prepare("SELECT clothes_image FROM wardrobe_top WHERE id = ?");
                                        $stmtBottom->bind_param("i", $topId);
                                        $stmtBottom->execute();
                                        $resultBottom = $stmtBottom->get_result();

                                        if ($resultBottom->num_rows > 0) { // Check if a row was found
                                            $bottomData = $resultBottom->fetch_assoc();
                                            $topImage = $bottomData['clothes_image'];
                                        } else {
                                            $topImage = null; // Set to null if no image found
                                        }
                                        $stmtBottom->close();
                                ?>
                                        <div class="col-lg-4 col-md-6" style="margin-bottom: 30px;">
                                            <div class="li-blog-single-item pb-25">
                                                <div class="li-blog-content">
                                                    <a href="remove_to_favorite_top.php?id=<?php echo $ID; ?>">Trash</a>
                                                </div>
                                                <div class="li-blog-content">
                                                    <?php if ($topImage) : ?>
                                                        <img class="img-full" src="<?php echo $topImage; ?>" alt="Favorite Top">
                                                    <?php else : ?>
                                                        <span>Deleted Top</span>
                                                    <?php endif; ?>
                                                </div>
                                                <!-- <div class="li-blog-content">
                                                    <?php if ($bottomImage) : ?>
                                                        <img class="img-full" src="<?php echo $bottomImage; ?>" alt="Favorite Bottom">
                                                    <?php else : ?>
                                                        <span>Deleted Bottom</span>
                                                    <?php endif; ?>
                                                </div> -->
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo "No products found.";
                                }
                                $stmt->close();
                                ?>


                                <!-- Begin Li's Pagination Area -->
                                <div class="col-lg-12">
                                    <div class="li-paginatoin-area text-center pt-25">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="li-pagination-box">
                                                    <li><a class="Previous" href="#">Previous</a></li>
                                                    <li class="active"><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a class="Next" href="#">Next</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Li's Pagination End Here Area -->
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="bottom-1" role="tabpanel" aria-labelledby="bottom-tab">
                        <div class="col-lg-12">
                            <div class="row li-main-content">
                                <?php if (isset($error)) : ?>
                                    <div class="error-message">
                                        <p><?php echo $error; ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($success)) : ?>
                                    <div class="success-message alert">
                                        <p><?php echo $success; ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php

                                $stmt = $conn->prepare("SELECT * FROM favorite_bottoms WHERE username = ? AND archive_status = 'Active' ORDER BY id DESC");
                                $stmt->bind_param("s", $username);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Display favorite outfits
                                if ($result->num_rows > 0) {
                                    while ($outfit = $result->fetch_assoc()) {
                                        // $topId = $outfit['top_id'];
                                        $bottomId = $outfit['bottom_id'];
                                        $ID = $outfit['id'];
                                        // Fetch top image data
                                        // $stmtTop = $conn->prepare("SELECT clothes_image FROM wardrobe_top WHERE id = ?");
                                        // $stmtTop->bind_param("i", $topId);
                                        // $stmtTop->execute();
                                        // $resultTop = $stmtTop->get_result();

                                        // if ($resultTop->num_rows > 0) { // Check if a row was found
                                        //     $topData = $resultTop->fetch_assoc();
                                        //     $topImage = $topData['clothes_image'];
                                        // } else {
                                        //     $topImage = null; // Set to null if no image found
                                        // }
                                        // $stmtTop->close();

                                        // Fetch bottom image data
                                        $stmtBottom = $conn->prepare("SELECT clothes_image FROM wardrobe_bottom WHERE id = ?");
                                        $stmtBottom->bind_param("i", $bottomId);
                                        $stmtBottom->execute();
                                        $resultBottom = $stmtBottom->get_result();

                                        if ($resultBottom->num_rows > 0) { // Check if a row was found
                                            $bottomData = $resultBottom->fetch_assoc();
                                            $bottomImage = $bottomData['clothes_image'];
                                        } else {
                                            $bottomImage = null; // Set to null if no image found
                                        }
                                        $stmtBottom->close();
                                ?>
                                        <div class="col-lg-4 col-md-6" style="margin-bottom: 30px;">
                                            <div class="li-blog-single-item pb-25">
                                                <div class="li-blog-content">
                                                    <a href="remove_to_favorite_bottom.php?id=<?php echo $ID; ?>">Trash</a>
                                                </div>
                                                <!-- <div class="li-blog-content">
                                                    <?php if ($topImage) : ?>
                                                        <img class="img-full" src="<?php echo $topImage; ?>" alt="Favorite Top">
                                                    <?php else : ?>
                                                        <span>Deleted Top</span>
                                                    <?php endif; ?>
                                                </div> -->
                                                <div class="li-blog-content">
                                                    <?php if ($bottomImage) : ?>
                                                        <img class="img-full" src="<?php echo $bottomImage; ?>" alt="Favorite Bottom">
                                                    <?php else : ?>
                                                        <span>Deleted Bottom</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo "No products found.";
                                }
                                $stmt->close();
                                ?>


                                <!-- Begin Li's Pagination Area -->
                                <div class="col-lg-12">
                                    <div class="li-paginatoin-area text-center pt-25">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="li-pagination-box">
                                                    <li><a class="Previous" href="#">Previous</a></li>
                                                    <li class="active"><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a class="Next" href="#">Next</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Li's Pagination End Here Area -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
    <script src="js/dragula.js"></script>
    <script src="js/dropify.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/jquery-file-upload.js"></script>
    <script src="js/data-table.js"></script>

</body>

<!-- blog-2-column31:55-->

</html>