<?php
ob_start(); // Start output buffering
session_start();
include("include/config.php");
include("include/head.php");


ob_end_flush();

?>
<!-- about-us32:04-->

<body>
    <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <!-- Begin Body Wrapper -->
    <div class="body-wrapper">
        <?php include("include/header.php"); ?>
        <!-- Header Area End Here -->
        <!-- Begin Li's Breadcrumb Area -->
        <div class="breadcrumb-area">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li class="active">About Us</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Li's Breadcrumb Area End Here -->
        <!-- about wrapper start -->
        <div class="about-us-wrapper pt-60 pb-40">
            <div class="container">
                <div class="row">
                    <!-- About Text Start -->
                    <div class="col-lg-6 order-last order-lg-first">
                        <div class="about-text-wrap">
                            <h2><span>We Help You Discover</span>The Best Version Of You</h2>
                            <p>Stylink is your personal fashion stylist in the modern digital world. We offer curated collections tailored to your unique taste, expert styling advice to elevate your look, and a seamless shopping experience from the comfort of your home. </p>
                                <p>Whether you're looking for everyday essentials or statement pieces for special occasions, Stylink has you covered! Discover your unique style and confidence with Stylink.
                            </p>
                            <p>We provide the best service possible to ensure our customers an excellent and long-term partnership. Join us!</p>
                        </div>
                    </div>
                    <!-- About Text End -->
                    <!-- About Image Start -->
                    <div class="col-lg-5 col-md-10">
                        <div class="about-image-wrap">
                            <!-- <img class="img-full" src="images/product/large-size/13.jpg" alt="About Us" /> -->
                            <img class="img-full" src="images/menu/logo/5.png" width="200px" alt="">
                        </div>
                    </div>
                    <!-- About Image End -->
                </div>
            </div>
        </div>
        <!-- about wrapper end -->
        <!-- Begin Counterup Area -->
 
        <!-- Counterup Area End Here -->
        <!-- team area wrapper start -->
        <div class="team-area pt-60 pt-sm-44">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="li-section-title capitalize mb-25">
                            <h2><span>our team</span></h2>
                        </div>
                    </div>
                </div> <!-- section title end -->
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="team-member mb-60 mb-sm-30 mb-xs-30">
                            <div class="team-thumb">
                                <img src="images/team/1.png" alt="Our Team Member">
                            </div>
                            <div class="team-content text-center">
                                <h3>Althea Arrobang</h3>
                                <p>Documents</p>
                                <a href="#">facebook@example.com</a>
                                <div class="team-social">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-linkedin"></i></a>
                                    <a href="#"><i class="fa fa-google-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end single team member -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="team-member mb-60 mb-sm-30 mb-xs-30">
                            <div class="team-thumb">
                                <img src="images/team/2.png" alt="Our Team Member">
                            </div>
                            <div class="team-content text-center">
                                <h3>Albely Fulitado</h3>
                                <p>Web Designer</p>
                                <a href="#">facebook@example.com</a>
                                <div class="team-social">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-linkedin"></i></a>
                                    <a href="#"><i class="fa fa-google-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end single team member -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="team-member mb-30 mb-sm-60">
                            <div class="team-thumb">
                                <img src="images/team/3.png" alt="Our Team Member">
                            </div>
                            <div class="team-content text-center">
                                <h3>Arravilla Saludes</h3>
                                <p>Web Developer</p>
                                <a href="#">facebook@example.com</a>
                                <div class="team-social">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-linkedin"></i></a>
                                    <a href="#"><i class="fa fa-google-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end single team member -->
                 
                </div>
            </div>
        </div>
        <!-- team area wrapper end -->

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
</body>

<!-- about-us32:14-->

</html>