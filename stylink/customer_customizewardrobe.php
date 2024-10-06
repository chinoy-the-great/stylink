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

$stmt = $conn->prepare("SELECT clothes_image, id FROM wardrobe_top WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$clothesImages = [];
$clothesImagesid = [];
while ($row = $result->fetch_assoc()) {
    $clothesImages[] = $row['clothes_image'];
    $clothesImagesid[] = $row['id'];
}
$stmt->close();


$stmt1 = $conn->prepare("SELECT clothes_image, id FROM wardrobe_bottom WHERE username = ?");
$stmt1->bind_param("s", $username);
$stmt1->execute();
$result1 = $stmt1->get_result();

$clothesImagesid1 = [];
$clothesImages1 = [];
while ($row = $result1->fetch_assoc()) {
    $clothesImages1[] = $row['clothes_image'];
    $clothesImagesid1[] = $row['id'];
}
$stmt1->close();


// Check wardrobe_bottom Table (Filter by Username)
$sql_bottom = "SELECT COUNT(*) FROM wardrobe_bottom WHERE username = ?";
$stmt_bottom = $conn->prepare($sql_bottom);
$stmt_bottom->bind_param("s", $username);
$stmt_bottom->execute();
$result_bottom = $stmt_bottom->get_result();
$row_bottom = $result_bottom->fetch_row();
$has_bottom = ($row_bottom[0] > 0);

// Check wardrobe_top Table (Filter by Username)
$sql_top = "SELECT COUNT(*) FROM wardrobe_top WHERE username = ?";
$stmt_top = $conn->prepare($sql_top);
$stmt_top->bind_param("s", $username);
$stmt_top->execute();
$result_top = $stmt_top->get_result();
$row_top = $result_top->fetch_row();
$has_top = ($row_top[0] > 0);



ob_end_flush();
include("include/head.php");

?>
<!-- blog-2-column31:55-->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<head>

    <!-- Main Style CSS -->
    <!-- <link rel="stylesheet" href="style1.css"> -->
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <!-- <link rel="stylesheet" href="/css/style.css"> -->

    <link rel="shortcut icon" href="http://www.urbanui.com/" />
    <!-- Responsive CSS -->
    <!-- <link rel="stylesheet" href="css/responsive.css"> -->
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
    .show_image img {
        width: 50%;
        aspect-ratio: 1 / 1;
        /* Sets a 1:1 aspect ratio directly */
        object-fit: cover;
    }

    .show_image_top,
    .show_image_bottom {
        display: flex;
        justify-content: center;
    }
    input[type=checkbox] {
        height: 30px;
        width: 30px;
        /* width: fit-content; */
    }

    .add-actions-link {
        display: inline-block;
        margin-top: 0px;
        padding-top: 20px;
        -webkit-transition: all 300ms ease-in 0s;
        transition: all 300ms ease-in 0s;
        width: 100%;
    }

    /* Basic Styles */
    .btn-container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background-color: #f5f5f5;
        padding: 10px;
        border-radius: 8px;
    }

    .btn-container-link {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
    }

    .links-details {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        margin: 0 5px;
        background-color: #fff;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .links-details:hover {
        background-color: #e0e0e0;
    }

    .links-details i {
        margin-right: 5px;
        font-size: 18px;
    }


    input[type=checkbox] {
        width: 18px;
        height: 18px;
    }

    /* Checkbox Round Styling */
    .top_id_send,
    .bottom_id_send {
        /* Customize checkbox appearance */
        accent-color: #457b75;
        /* Blue */
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: solid 1px #007bff;
        /* Make the checkbox round */
        appearance: none;
        -webkit-appearance: none;

    }

    /* Optional: Styling for Checked State */
    .top_id_send:checked,
    .bottom_id_send:checked {
        background-color: #007bff;
        /* Fill with blue when checked */
    }

    .top_id_send:checked::before,
    .bottom_id_send::before {
        content: '✓';
        /* You can use a checkmark icon from a font library instead */
        display: block;
        text-align: center;
        color: white;
        font-size: 14px;
    }

    /* Label Styling */
    .top_id_send+label,
    .bottom_id_send+label {
        /* Targets the label immediately after the checkbox */
        cursor: pointer;
        /* Indicate interactivity */
        margin-left: 8px;
        /* Add some space between the checkbox and label */
    }

    /* Optional: Styling for Checked State */
    .top_id_send:checked+label,
    .bottom_id_send:checked+label {
        font-weight: bold;
        /* Add other styles if desired */
    }

    /* Optional: Styling for Hover State */
    .top_id_send:hover+label .bottom_id_send:hover+label {
        text-decoration: underline;
    }

    /* Mobile-Specific Styles (Media Query) */
    @media (max-width: 768px) {

        /* Adjust the breakpoint as needed */
        .btn-container {
            flex-direction: column;
            /* Stack elements vertically */
            align-items: flex-start;
            /* Align to the left */
        }

        .links-details {
            margin: 5px 0;
            /* Add vertical spacing */
            width: 100%;
            /* Make links full width */
            justify-content: center;
            /* Center content horizontally */
        }
    }

    .save-button:disabled {
        cursor: not-allowed;
        /* Change to the "not-allowed" symbol */
        /* Optionally add other styles like opacity or background color */
    }
</style>

<body style="color: #292825;">
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
                        <a class="nav-link active" id="profile-tab1" href="customer_customizewardrobe.php" aria-controls="profile-1" aria-selected="false">Customize Styling</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="profile-tab1" href="customer_favoritewardrobe.php" aria-controls="profile-1" aria-selected="false">Favorite Outfits</a>
                    </li>


                </ul>

                <div class="row single-product-area">
                    <div class="col-lg-6 col-md-6">
                        <div class="product-details-left sp-tab-style-left-page">

                            <div class="product-details-images slider-navigation-1">
                                <?php foreach ($clothesImages as $index => $image) : ?>
                                    <div class="lg-image">
                                        <div class="single-product-wrap">
                                            <div class="product-image">
                                                <img src="<?php echo $image; ?>" alt="product image">
                                            </div>
                                            <div class="product_desc">
                                                <div class="product_desc_info">
                                                    <div class="btn-container">
                                                        <ul class="btn-container-link">
                                                            <li><a class="btn btn-outline-dark remove-btn" data-image-id="<?php echo $clothesImagesid[$index]; ?>" class="links-details" href="" style="color: #343a40;"><i class="fa-solid fa-trash"></i></a></li>
                                                            <li>
                                                                <form action="add_favorite_top.php" method="post">
                                                                    <input type="hidden" id="show_top_id1" name="top_id" value="<?php echo $clothesImagesid[$index]; ?>" readonly>

                                                                    <button type="submit" class=" btn btn-outline-danger links-details"><i class="fa fa-heart-o"></i></button>
                                                                </form>
                                                            </li>
                                                            <li style="justify-content:center; align-content: center;">
                                                                <input type="checkbox" class="btn top_id_send" id="<?php echo $clothesImagesid[$index]; ?>">
                                                                <label for="<?php echo $clothesImagesid[$index]; ?>">Select Top Clothes</label>
                                                            </li>
                                                        </ul>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="tab-style-left">
                                <?php foreach ($clothesImages as $image) : ?>
                                    <div class="sm-image"> <img src="<?php echo $image; ?>" alt="product image">
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="product-details-left sp-tab-style-left-page">
                            <div class="product-details-images slider-navigation-1">
                                <?php foreach ($clothesImages1 as $index => $image) : ?>
                                    <div class="lg-image">
                                        <div class="single-product-wrap">
                                            <div class="product-image">
                                                <img src="<?php echo $image; ?>" alt="product image">
                                            </div>
                                            <div class="product_desc">
                                                <div class="product_desc_info">
                                                    <div class="btn-container">
                                                        <ul class="btn-container-link">
                                                            <li><a class="btn btn-outline-dark remove-btn1" data-image-id="<?php echo $clothesImagesid1[$index]; ?>" class="links-details" href="" style="color: #343a40;"><i class="fa-solid fa-trash"></i></a></li>
                                                            <li>
                                                                <form action="add_favorite_bottom.php" method="post">
                                                                    <input type="hidden" id="show_top_id1" name="bottom_id" value="<?php echo $clothesImagesid1[$index]; ?>" readonly>

                                                                    <button type="submit" class=" btn btn-outline-danger links-details"><i class="fa fa-heart-o"></i></button>
                                                                </form>

                                                            </li>


                                                            <li style="justify-content:center; align-content: center;">
                                                                <input type="checkbox" class="bottom_id_send" id="<?php echo $clothesImagesid1[$index]; ?>">
                                                                <label for="<?php echo $clothesImagesid1[$index]; ?>">Select Bottom Clothes</label>
                                                            </li>
                                                        </ul>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="tab-style-left">
                                <!-- Product Small Picture Icon -->
                                <?php foreach ($clothesImages1 as $image) : ?>
                                    <div class="sm-image"> <img src="<?php echo $image; ?>" alt="product image">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Add event listeners to all "Remove" buttons
                                const removeButtons = document.querySelectorAll('.remove-btn');
                                removeButtons.forEach(button => {
                                    button.addEventListener('click', function() {
                                        const imageId = this.getAttribute('data-image-id');
                                        if (confirm('Are you sure you want to delete this image?')) {
                                            // Send an AJAX request to delete_top.php
                                            fetch(`delete_top.php?id=${imageId}`)
                                                .then(response => {
                                                    if (!response.ok) {
                                                        throw new Error('Network response was not ok.');
                                                    }
                                                    return response.text(); // Get the response text
                                                })
                                                .then(data => {
                                                    // Handle the response from delete_top.php
                                                    console.log(data); // Log the response (you might want to display it to the user)
                                                    location.reload(); // Reload the page to update the image list
                                                })
                                                .catch(error => {
                                                    console.error('There has been a problem with your fetch operation:', error);
                                                });
                                        }
                                    });
                                });
                            });

                            document.addEventListener('DOMContentLoaded', function() {
                                // Add event listeners to all "Remove" buttons
                                const removeButtons = document.querySelectorAll('.remove-btn1');
                                removeButtons.forEach(button => {
                                    button.addEventListener('click', function() {
                                        const imageId = this.getAttribute('data-image-id');
                                        if (confirm('Are you sure you want to delete this image?')) {
                                            // Send an AJAX request to delete_top.php
                                            fetch(`delete_bottom.php?id=${imageId}`)
                                                .then(response => {
                                                    if (!response.ok) {
                                                        throw new Error('Network response was not ok.');
                                                    }
                                                    return response.text(); // Get the response text
                                                })
                                                .then(data => {
                                                    // Handle the response from delete_top.php
                                                    console.log(data); // Log the response (you might want to display it to the user)
                                                    location.reload(); // Reload the page to update the image list
                                                })
                                                .catch(error => {
                                                    console.error('There has been a problem with your fetch operation:', error);
                                                });
                                        }
                                    });
                                });
                            });
                        </script>

                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const checkboxes = document.querySelectorAll('.top_id_send');
                            const showTopIdInput = document.getElementById('show_top_id');

                            checkboxes.forEach(checkbox => {
                                checkbox.addEventListener('change', function() {
                                    if (this.checked) {
                                        showTopIdInput.value = this.id;

                                        // Uncheck all other checkboxes
                                        checkboxes.forEach(otherCheckbox => {
                                            if (otherCheckbox !== this) {
                                                otherCheckbox.checked = false;
                                            }
                                        });
                                    } else {
                                        showTopIdInput.value = ''; // Clear input if unchecked
                                    }
                                });
                            });
                        });


                        document.addEventListener('DOMContentLoaded', function() {
                            const checkboxes1 = document.querySelectorAll('.bottom_id_send');
                            const showBottomIdInput = document.getElementById('show_bottom_id');

                            checkboxes1.forEach(checkbox => {
                                checkbox.addEventListener('change', function() {
                                    if (this.checked) {
                                        showBottomIdInput.value = this.id;
                                        // Uncheck all other checkboxes
                                        checkboxes1.forEach(otherCheckbox1 => {
                                            if (otherCheckbox1 !== this) {
                                                otherCheckbox1.checked = false;
                                            }
                                        });


                                    } else {
                                        // Clear the input if the checkbox is unchecked
                                        showBottomIdInput.value = '';
                                    }
                                });
                            });
                        });
                    </script>
                </div>
                <?php if ($has_bottom && $has_top) : ?>
                    <form action="add_favorite_outfitcustom.php" method="post">
                        <input type="hidden" id="show_top_id" name="top_id1" readonly>
                        <input type="hidden" id="show_bottom_id" name="bottom_id1" readonly>
                        <span id="validationMessage" style="color: red;"></span>
                        <!-- <input type="submit" value="Save to Favorite"> -->
                        <input class="btn btn-outline-dark" type="submit" class="save-button" value="Save to Favorite" disabled onclick="validateSelection()">

                    </form>
                    <br>
                    <br>

                <?php elseif ($has_bottom) : ?>
                    <div class="alert-message">
                        <span>Upload Your Top Outfit for Randomizer Outfit</span>
                    </div>
                <?php elseif ($has_top) : ?>

                    <!-- <span>Upload Your Bottom Outfit for Randomizer Outfit</span> -->
                    <div class="alert-message">
                        <!-- <p>Upload Your Bottom Outfit for Randomizer Outfit</p> -->
                        <span>Upload Your Bottom Outfit for Randomizer Outfit</span>
                    </div>

                <?php else : ?>
                    <div class="alert-message">
                        <span>Add items to your Personal Wardrobe!</span>
                    </div>

                <?php endif; ?>


                <!-- <p><?php echo $_GET['success']; ?></p> -->

                <!-- <?php if (isset($_GET['success'])) : ?>
                    <div class="success-message">
                        <span>Image uploaded successfully.</span>

                    </div>
                <?php elseif (isset($_GET['error'])) : ?>
                    <div class="error-message">
                        <span>Please select at least one image to upload!</span>
                    </div>
                <?php endif; ?> -->
                <style>
                    .message-popup {
                        position: fixed;
                        /* Position it relative to the viewport */
                        bottom: 20px;
                        /* Adjust distance from the top */
                        right: 20px;
                        /* Adjust distance from the right */
                        padding: 15px 25px;
                        border-radius: 5px;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                        z-index: 99999;
                        /* Ensure it's above other content */
                        font-weight: normal;
                        opacity: 0;
                        /* Start hidden */
                        transition: opacity 0.5s ease;
                        /* Smooth fade-in effect */
                    }

                    .message-popup.success {
                        background-color: #4CAF50;
                        /* Green */
                        color: white;
                    }

                    .message-popup.error {
                        background-color: #f44336;
                        /* Red */
                        color: white;
                    }

                    /* Show the popup after a slight delay */
                    .message-popup.show {
                        opacity: 1;
                    }
                </style>

                <?php if (isset($_GET['success'])) : ?>
                    <div class="message-popup success success-message">Image uploaded successfully.</div>
                <?php elseif (isset($_GET['error'])) : ?>
                    <div class="message-popup error error-message">Please select at least one image to upload!</div>
                <?php endif; ?>

                <?php if (isset($_GET['success-top'])) : ?>
                    <!-- <div class="message-popup success success-message">Added to your favorite tops.</div> -->
                    <div class="message-popup success "><?php echo $_GET['success-top']; ?></div>

                <?php elseif (isset($_GET['error-top'])) : ?>
                    <!-- <div class="message-popup error ">Already Added in your favorite!</div> -->
                    <div class="message-popup error "><?php echo $_GET['error-top']; ?></div>
                <?php endif; ?>

                <?php if (isset($_GET['success-save'])) : ?>
                    <!-- <div class="message-popup success success-message">Added to your favorite tops.</div> -->
                    <div class="message-popup success "><?php echo $_GET['success-save']; ?></div>

                <?php elseif (isset($_GET['error-save'])) : ?>
                    <!-- <div class="message-popup error ">Already Added in your favorite!</div> -->
                    <div class="message-popup error "><?php echo $_GET['error-save']; ?></div>
                <?php endif; ?>



                <script>
                    // Wait for the DOM to load
                    document.addEventListener('DOMContentLoaded', (event) => {
                        const popups = document.querySelectorAll('.message-popup');

                        popups.forEach(popup => {
                            popup.classList.add('show'); // Show the popup
                            setTimeout(() => {
                                popup.classList.remove('show'); // Hide after a few seconds
                            }, 5000); // 3000 milliseconds (3 seconds)
                        });
                    });
                </script>



                <div class="row">
                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <form class="forms-sample" method="POST" action="upload_topwardrobe.php" enctype="multipart/form-data">
                                    <h4 class="card-title d-flex">Top Clothes
                                        <small class="ml-auto align-self-end">
                                            <p>Upload Your Personal Top Clothes</p>
                                        </small>
                                    </h4>
                                    <input type="file" name="top_clothes[]" class="dropify" multiple />

                                    <button type="submit" class="add-to-cart-outline col-md-12">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">

                                <form class="forms-sample" method="POST" action="upload_bottomwardrobe.php" enctype="multipart/form-data">
                                    <h4 class="card-title d-flex">Bottom Clothes
                                        <small class="ml-auto align-self-end">
                                            <p>Upload Your Personal Bottom Clothes</p>
                                        </small>
                                    </h4>
                                    <input type="file" name="bottom_clothes[]" class="dropify" multiple />

                                    <button type="submit" class="add-to-cart-outline col-md-12">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-md-6">
                        <center>
                            <?php if ($has_bottom && $has_top) : ?>

                                <button class="add-to-cart-outline" id="random_style_generator">Randomizer Outfit</button>

                                <!-- <php elseif ($has_bottom) : ?>
                                <div class="error-message">
                                    <span>Upload Your Top Outfit for Randomizer Outfit</span>
                                </div>
                            <php elseif ($has_top) : ?>

                             >
                                <div class="error-message">
                                    <span>Upload Your Bottom Outfit for Randomizer Outfit</span>
                                </div>

                            <php else : ?>
                                <div class="error-message">
                                    <span>Add items to your wardrobe!</span>
                                </div> -->

                            <?php endif; ?>

                            <div class="show_generate_outfit">

                                <form action="add_favorite_outfit.php" method="post">

                                    <input type="hidden" name="top_id" id="top_id_input">
                                    <input type="hidden" name="bottom_id" id="bottom_id_input">
                                    <br>
                                    <br>

                                    <div class="row">
                                        <div class="col-md-12 show_image_top"></div>
                                        <div class="col-md-12 show_image_bottom"></div>
                                    </div><br>
                                    <button type="submit" class="btn btn-outline-dark save-to-favorite" id="save-to-favorite">Save to Favorite</button>


                                </form>
                            </div>
                        </center>
                    </div>
                    <div class="col-md-6">

                        <div class="contact-page-side-content about-text-wrap">
                            <p><i>
                                    Note:
                                    For more Random Outfit style you need to upload more outfit Tops and Bottom Clothes.
                                </i>
                            </p>
                        </div>
                    </div>
                </div>


                <script>
                    function validateSelection() {
                        const topChecked = document.querySelector('.top_id_send:checked');
                        const bottomChecked = document.querySelector('.bottom_id_send:checked');
                        const messageSpan = document.getElementById('validationMessage');
                        const submitButton = document.querySelector('input[type="submit"]'); // Get the submit button

                        if (!topChecked && !bottomChecked) {
                            messageSpan.textContent = "Select one of the Top clothes and Bottom Clothes";
                            submitButton.disabled = true; // Disable the button
                        } else if (!topChecked) {
                            messageSpan.textContent = "Select Top Clothes to save";
                            submitButton.disabled = true; // Disable the button
                        } else if (!bottomChecked) {
                            messageSpan.textContent = "Select Bottom Clothes to save";
                            submitButton.disabled = true; // Disable the button
                        } else {
                            messageSpan.textContent = ""; // Clear the message if both are checked
                            submitButton.disabled = false; // Enable the button
                            // Submit the form or perform other actions here
                        }
                    }

                    // Initial check when the page loads
                    validateSelection();

                    // Add event listeners to checkboxes
                    const checkboxes = document.querySelectorAll('.top_id_send, .bottom_id_send');
                    checkboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', validateSelection);
                    });



                    document.addEventListener('DOMContentLoaded', function() {
                        const generateButton = document.getElementById('random_style_generator');
                        const saveButton = document.getElementById('save-to-favorite');
                        const topImageContainer = document.querySelector('.show_image_top');
                        const bottomImageContainer = document.querySelector('.show_image_bottom');
                        const topIdInput = document.getElementById('top_id_input');
                        const bottomIdInput = document.getElementById('bottom_id_input');

                        saveButton.style.display = 'none';

                        generateButton.addEventListener('click', function() {
                            // Fetch random images (same as before)
                            const randomTopIndex = Math.floor(Math.random() * <?php echo count($clothesImages); ?>);
                            const randomTopImage = <?php echo json_encode($clothesImages); ?>[randomTopIndex];
                            const randomTopId = <?php echo json_encode($clothesImagesid); ?>[randomTopIndex];

                            const randomBottomIndex = Math.floor(Math.random() * <?php echo count($clothesImages1); ?>);
                            const randomBottomImage = <?php echo json_encode($clothesImages1); ?>[randomBottomIndex];
                            const randomBottomId = <?php echo json_encode($clothesImagesid1); ?>[randomBottomIndex];

                            // Create image elements (same as before)
                            const topImg = document.createElement('img');
                            topImg.src = randomTopImage;
                            topImg.alt = 'Random Top';
                            topImg.classList.add('lg-image-cloth');

                            const bottomImg = document.createElement('img');
                            bottomImg.src = randomBottomImage;
                            bottomImg.alt = 'Random Bottom';
                            bottomImg.classList.add('lg-image-cloth');

                            // Assign IDs as attributes (same as before)
                            topImg.setAttribute('data-top-id', randomTopId);
                            bottomImg.setAttribute('data-bottom-id', randomBottomId);

                            // Clear previous content
                            topImageContainer.innerHTML = '';
                            bottomImageContainer.innerHTML = '';

                            // Append to the correct containers
                            topImageContainer.appendChild(topImg);
                            bottomImageContainer.appendChild(bottomImg);
                            topIdInput.value = randomTopId;
                            bottomIdInput.value = randomBottomId;

                            saveButton.style.display = 'block';
                        });
                    });
                </script>

                <!-- <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const generateButton = document.getElementById('random_style_generator');
                        const topImageContainer = document.querySelector('.show_image_top');
                        const bottomImageContainer = document.querySelector('.show_image_bottom');
                        const topIdInput = document.getElementById('top_id_input');
                        const bottomIdInput = document.getElementById('bottom_id_input');

                        // Function to display a random image in a container
                        function displayRandomImage(images, containerId, thumbsContainerId, idInput) {
                            var randomIndex = Math.floor(Math.random() * images.length);
                            var imageData = images[randomIndex];
                            var productId = imageData.product_id;
                            var imagePath = imageData.image_path;
                            var id = imageData.id;

                            $(containerId).html(`
            <div class="lg-image">
                <img src="${imagePath}" alt="product image">
                <br>
                <a href="single-product.php?product_id=${productId}">
                    <button class="add-to-cart-outline col-md-12" id="addToCartBtn" type="button">View Product</button>
                </a>
            </div>
        `);
                            $(thumbsContainerId).html(`
            <div class="sm-image"> <img src="${imagePath}" alt="product image"></div>
        `);

                            // Update the corresponding hidden input
                            idInput.value = id;
                        }

                        // Initial display (optional)
                        displayRandomImage(<php echo json_encode($clothesImages); ?>, '#topClothesContainer', '#topClothesThumbs', topIdInput);
                        displayRandomImage(<php echo json_encode($clothesImages1); ?>, '#bottomClothesContainer', '#bottomClothesThumbs', bottomIdInput);

                        // On button click, randomize images
                        $('#random_style_generator').click(function() {
                            displayRandomImage(<php echo json_encode($clothesImages); ?>, '#topClothesContainer', '#topClothesThumbs', topIdInput);
                            displayRandomImage(<php echo json_encode($clothesImages1); ?>, '#bottomClothesContainer', '#bottomClothesThumbs', bottomIdInput);
                        });

                    });
                </script> -->

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