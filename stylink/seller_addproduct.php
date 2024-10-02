<?php
session_start();
include("include/config.php");
if (!isset($_SESSION["username"])) {
  // Redirect to the appropriate page for logged-in users
  header("Location: user_login.php");
  exit;
}
$username = $_SESSION["username"];

$sellerinfo2 = [];
$stmt2 = $conn->prepare("SELECT * FROM seller_register WHERE username = ?");
$stmt2->bind_param("s", $_SESSION["username"]);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
  $sellerinfo2 = $result2->fetch_assoc();
}
$stmt2->close(); // Close the first statement

// // 1. Fetch Existing Sizes and Colors Efficiently
$sizes = [];
$colors = [];


// // Fetch existing sizes for this product
// $stmt = $conn->prepare("SELECT * FROM product_allsizes WHERE username = ?");
// $stmt->bind_param("s", $username);
// $stmt->execute();
// $result = $stmt->get_result();

// while ($row = $result->fetch_assoc()) {
//   $sizes[] = $row;
// }
// 

// Fetch existing colors for this product
// $stmt = $conn->prepare("SELECT * FROM product_allcolors WHERE username = ?");
// $stmt->bind_param("s", $username);
// $stmt->execute();
// $result = $stmt->get_result();

// while ($row = $result->fetch_assoc()) {
//   $colors[] = $row;
// }
// $stmt->close();
// 1. Fetch Existing Sizes and Colors Efficiently (Optimized)
$sellerinfo2 = [];
$stmt2 = $conn->prepare("SELECT * FROM seller_register WHERE username = ?");
$stmt2->bind_param("s", $_SESSION["username"]);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2->num_rows > 0) {
  $sellerinfo2 = $result2->fetch_assoc();
}
$stmt2->close(); // Close the first statement



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get product data from the form
  $productId = $_POST["product_id"];
  $productName = $_POST["product_name"];
  $productPrice = $_POST["product_price"];
  $productBrand = $_POST["product_brand"];
  $productType = $_POST["product_type"];
  $productCategory = $_POST["product_category"];
  $productDescription = $_POST["product_description"];
  $productStyle = $_POST["product_style"];
  $productTypeclothes = $_POST["product_typeclothes"];
  $productStocks = $_POST["product_stocks"];
  $productArrival = $_POST["new_arrival"];
  $productFeatured = $_POST["featured_product"];
  $shopName = $_POST["shop_name"];

  // Image Upload Handling
  $targetDir = "uploads/cover/";
  $originalFilename = basename($_FILES["product_image"]["name"]);
  $imageFileType = strtolower(pathinfo($originalFilename, PATHINFO_EXTENSION));

  // Initialize increment counter
  $increment = 1;

  // Check for Unique Filename
  do {
    $newFilename = pathinfo($originalFilename, PATHINFO_FILENAME) . "_$increment." . $imageFileType;
    $targetFile = $targetDir . $newFilename;
    $increment++;
  } while (file_exists($targetFile));  // Increment until a unique filename is found

  $uploadOk = 1;

  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["product_image"]["tmp_name"]);
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
  $checkUniqueStmt = $conn->prepare("SELECT * FROM product_list WHERE product_id = ? ");
  $checkUniqueStmt->bind_param("s", $productId);
  $checkUniqueStmt->execute();
  $checkUniqueResult = $checkUniqueStmt->get_result();
  //Check if product_id already exists in database
  if ($checkUniqueStmt->num_rows > 0) {
    $error = "Error: Product ID already exists.";
    $uploadOk = 0; // Prevent upload if not unique
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    // Display error message and skip the insertion
    echo $error;
  } else {
    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
      // Insert into product_list table (use the newFilename)
      $stmt = $conn->prepare("INSERT INTO product_list (product_id, product_name, product_price, product_brand, product_type, product_category, username, product_image, product_description, product_style, product_typeclothes, product_stocks, product_status, new_arrival, featured_product, shop_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', ?, ?, ?)");
      $stmt->bind_param("ssdssssssssssss", $productId, $productName, $productPrice, $productBrand, $productType, $productCategory, $username, $targetFile, $productDescription, $productStyle, $productTypeclothes, $productStocks, $productArrival, $productFeatured, $shopName);

      if ($stmt->execute()) {
        // 2. Handle Checked Sizes
        if (isset($_POST['product_size'])) {
          $selectedSizes = $_POST['product_size'];
          $insertSizeStmt = $conn->prepare("INSERT INTO product_sizes (product_id, product_size) VALUES (?, ?)");
          foreach ($selectedSizes as $size) {
            $insertSizeStmt->bind_param("ss", $productId, $size);
            $insertSizeStmt->execute();
          }
        }

        // 3. Handle Checked Colors & Images
        if (isset($_POST['product_color']) && isset($_FILES['product_images_color'])) {
          // $selectedColors = $_POST['product_color'];
          // $selectedImages = $_FILES['product_images_color'];

          $productColors = $_POST["product_color"];
          $productImageFiles = $_FILES["product_images_color"];

          for ($i = 0; $i < count($productColors); $i++) {
            // File handling and incrementing the filename
            $targetDir = "uploads/colors/"; // Directory to store images
            $originalFilename = basename($productImageFiles["name"][$i]);
            $imageFileType = strtolower(pathinfo($originalFilename, PATHINFO_EXTENSION));

            // Security: check for valid image types
            $allowedTypes = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowedTypes)) {
              echo "Error: Only JPG, JPEG, PNG & GIF files are allowed.";
              continue; // Skip to the next iteration
            }

            // Generate a new filename with increment
            $newFilename = pathinfo($originalFilename, PATHINFO_FILENAME);
            $counter = 1;
            while (file_exists($targetDir . $newFilename . "_" . $counter . "." . $imageFileType)) {
              $counter++;
            }
            $newFilename = $newFilename . "_" . $counter . "." . $imageFileType;

            $targetFile = $targetDir . $newFilename;
            if (move_uploaded_file($productImageFiles["tmp_name"][$i], $targetFile)) {
              // Prepare data for database insertion
              $colorName = $productColors[$i];
              $imagePath = $newFilename; // Store relative path

              // Insert into database (adapt your table structure)
              $sql = "INSERT INTO product_colors (product_id, product_color, product_image, product_style) VALUES (?, ?, ?, ?)";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("ssss", $productId, $colorName, $targetFile, $productStyle);
              if (!$stmt->execute()) {
                echo "Error: " . $sql . "<br>" . $conn->error;
              }
              $stmt->close();
            } else {
              echo "Error uploading file.";
            }
          }
        }
        $success = "Product added successfully.";
      } else {
        $error = "Error: " . $stmt->error;
      }
    } else {
      $error = "Sorry, there was an error uploading your file.";
    }
  }
  $checkUniqueStmt->close();
}


// $stmt->close();
// $stmt->close();
$conn->close();

?>


  <link rel="stylesheet" href="css/style1.css">
 



<?php
  include("include/admin_head.php");
    ?>


<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php
      include("include/admin_nav.php");
      ?>


    <div class="container-fluid page-body-wrapper">
      <?php
      include("include/admin_sidebar.php");
      ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
              Add Product
            </h3>
          </div>
          <form class="forms-sample" method="POST" id="commentForm1" action="seller_addproduct.php" enctype="multipart/form-data">
            <div class="card">
              <div class="card-body">
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
                <div class="form-group">
                  <div class="row">
                    <!-- <div class="col-md-4">
                      <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Cover Product Image<code>*</code></h4>
                          <input type="file" name="product_image" class="dropify" required />
                        </div>
                      </div>
                    </div> -->
                    <div class="col-md-12">
                      <h4 for="exampleSelectGender">Basic Information</h3>
                        <div class="row">
                          <div class="col-md-6">
                            <label for="exampleInputName1">Product ID#</label>
                            <input type="text" name="product_id" id="product_id" class="form-control" readonly>
                            <input type="hidden" name="shop_name" value="<?php echo isset($sellerinfo2['shop_name']) ? $sellerinfo2['shop_name'] : ''; ?>">
                          </div>
                          <div class="col-md-6">
                            <br>
                            <button type="button" class="btn btn-primary mr-2" onclick="generateProductId()">Generate Product ID</button>
                            <br>
                            <br>
                            <p class="card-description" style="margin-bottom: 0; font-size: 1rem;">
                              (Note<code>*</code> Need to Generate Product ID)
                            </p>
                          </div>
                        </div>
                        <br>

                        <!-- <div class="card"> -->
                        <!-- <div class="card-body"> -->
                        <div class="col-md-4">
                          <label for="exampleInputName1">Cover Product Image<code>*</code></label>
                          <!-- <h4 class="card-title">Cover Product Image<code>*</code></h4> -->
                          <input type="file" name="product_image" class="dropify" required />
                        </div>
                        <!-- </div> -->
                        <!-- </div> -->
                        <br>
                        <br>
                        <!-- <label for="exampleSelectGender">Type of Clothes<code>*</code></label>
                        <select class="form-control" name="product_typeclothes" id="exampleSelectGender" required>
                          <option value="" disabled selected hidden>Choose...</option>
                          <option value="Rental">Rental</option>
                          <option value="Non-Rental">Non-Rental</option>
                        </select> -->

                        <input type="hidden" name="product_typeclothes"  value="<?php echo isset($sellerinfo2['store_type']) ? $sellerinfo2['store_type'] : ''; ?>">



                        <br>
                        <label for="exampleInputName1">Product Name<code>*</code></label>
                        <input type="text" name="product_name" class="form-control" id="exampleInputName1" placeholder="Product Name" required>
                        <br>

                        <div class="row">
                          <div class="col-md-12">

                            <label for="exampleSelectGender">Type of Product<code>*</code></label>
                            <select class="form-control" name="product_type" id="Men-Select-Category" required>
                              <option value="" disabled selected hidden>Select Category...</option>
                              <option value="Men Fashion">Men Fashion</option>
                              <option value="Women Fashion">Women Fashion</option>
                              <option value="All Fashion">All Fashion</option>
                            </select>

                            <script>
                              document.getElementById('Men-Select-Category').addEventListener('change', function() {
                                const selectedStyleM = this.value;
                                const categoryContainersM = document.querySelectorAll('.Men-Category-Container');
                                const categoryContainersM1 = document.querySelectorAll('.sub-Category-Container');

                                categoryContainersM.forEach(container => {
                                  container.style.display = 'none'; // Hide all containers initially
                                });
                                categoryContainersM1.forEach(container => {
                                  container.style.display = 'none'; // Hide all containers initially
                                });

                                if (selectedStyleM === 'Men Fashion') {
                                  document.getElementById('Men-Containers').style.display = 'block';
                                  document.getElementById('WomenContainers').style.display = 'none';
                                  document.getElementById('Women-Tops').style.display = 'none';
                                  document.getElementById('Women-Bottoms').style.display = 'none';
                                  document.getElementById('Women-Sets').style.display = 'none';

                                } else if (selectedStyleM === 'Women Fashion') {
                                  document.getElementById('WomenContainers').style.display = 'block';
                                  document.getElementById('Men-Containers').style.display = 'none';
                                  document.getElementById('Men-Tops').style.display = 'none';
                                  document.getElementById('Men-Bottoms').style.display = 'none';
                                  document.getElementById('Men-Sets').style.display = 'none';

                                } else {
                                  // document.getElementById('SetsContainers').style.display = 'block';
                                }
                              });
                            </script>
                            <br>

                          </div>
                          <!-- <div id=" col-md-4 MenContainer"> -->
                          <div class="col-md-12 Men-Category-Container" id="Men-Containers" style="display: none;">
                            <label for="exampleSelectGender">Men Category Style<code>*</code></label>
                            <select class="form-control" name="product_style" id="Men-Product-Style">
                              <option value="" disabled selected>Select Category...</option>
                              <option value="Men Tops">Men Tops</option>
                              <option value="Men Bottoms">Men Bottoms</option>
                              <option value="Men Sets">Men Sets</option>
                            </select>
                            <br>
                          </div>
                          <!-- <div class="col-md-4" id="categoryContainers"> -->
                          <div class="col-md-4 sub-category-container" id="Men-Tops" style="display: none;">

                          <!-- <label>Category<code>*</code></label> -->
                          <!-- <select class="js-example-basic-single w-100" name="product_category" required> -->

                          <style>
                            .select2-selection{
                              width: 100%;
                            }
                          </style>

                            <label for="exampleSelectGender">Men Tops<code>*</code></label>
                            <!-- <select class="js-example-basic-single-1 w-100 col-md-12" name="product_category" id="tops_category"> -->
                            <select class="form-control" name="product_category" id="tops_category">
                              <option value="" disabled selected>Choose...</option>
                              <option value="Men shirt">Men shirt</option>
                              <option value="Men T-shirt">Men T-shirt</option>
                              <option value="Men Polo">Men Polo</option>
                              <option value="Jackets, Coats & Vest">Jackets, Coats & Vest</option>
                              <option value="Barongs">Barongs</option>
                              <option value="Semi-formal">Semi-formal</option>
                              <option value="Formal">Formal</option>
                              <option value="Casual">Casual</option>
                              <option value="Hoodies">Hoodies</option>
                              <option value="Others">Others</option>
                            </select>
                            <br>
                          </div>
                          <div class="col-md-4 sub-category-container" id="Men-Bottoms" style="display: none;">
                            <label for="exampleSelectGender">Men Bottoms<code>*</code></label>
                            <select class="form-control" name="product_category" id="bottom_category">
                              <option value="" disabled selected>Choose...</option>
                              <option value="Men Shorts">Men Shorts</option>
                              <option value="Men Pants">Men Pants</option>
                              <option value="Men Sweatpants">Men Sweatpants</option>
                            </select>
                            <br>
                          </div>
                          <div class="col-md-4 sub-category-container" id="Men-Sets" style="display: none;">
                            <label for="exampleSelectGender">Men Sets<code>*</code></label>
                            <select class="form-control" name="product_category" id="sets_category">
                              <option value="" disabled selected>Choose...</option>
                              <option value="Barongs">Barongs</option>
                              <option value="Semi-formal">Semi-formal</option>
                              <option value="Formal">Formal</option>
                              <option value="Costumes">Costumes</option>
                              <option value="Others">Others</option>
                            </select>
                            <br>
                          </div>
                          <!-- </div> -->

                          <script>
                            document.getElementById('Men-Product-Style').addEventListener('change', function() {
                              // <SELECT> </SELECT>
                              const selectedStyle = this.value;
                              const categoryContainers = document.querySelectorAll('.sub-category-container');
                              // <DIV CONTAINER> </DIV>

                              categoryContainers.forEach(container => {
                                container.style.display = 'none'; // Hide all containers initially
                              });

                              if (selectedStyle === 'Men Tops') {
                                document.getElementById('Men-Tops').style.display = 'block';
                                document.getElementById('Men-Bottoms').style.display = 'none';
                                document.getElementById('Men-Sets').style.display = 'none';
                              } else if (selectedStyle === 'Men Bottoms') {
                                document.getElementById('Men-Tops').style.display = 'none';
                                document.getElementById('Men-Bottoms').style.display = 'block';
                                document.getElementById('Men-Sets').style.display = 'none';
                              } else {
                                document.getElementById('Men-Tops').style.display = 'none';
                                document.getElementById('Men-Bottoms').style.display = 'none';
                                document.getElementById('Men-Sets').style.display = 'block';
                              }
                            });
                          </script>



                          <!-- </div> -->

                          <div class="col-md-4 category-container1" id="WomenContainers" style="display: none;">
                            <label for="exampleSelectGender">Women Category Style<code>*</code></label>
                            <select class="form-control" name="product_style" id="Women-Select-Category">
                              <option value="" disabled selected hidden>Choose...</option>
                              <option value="Women Tops">Women Tops</option>
                              <option value="Women Bottoms">Women Bottoms</option>
                              <option value="Women Sets/Dresses">Women Sets/Dresses </option>
                              <!-- <option value="Gown">Gown</option>
                              <option value="Semi-formal-top">Semi-formal-Top</option>
                              <option value="Formal">Formal</option>
                              <option value="Dress">Dress</option> -->
                            </select>
                            <br>
                          </div>

                          <div class="col-md-4 womensub-category-container" id="Women-Tops" style="display: none;">
                            <label for="exampleSelectGender">Women Tops<code>*</code></label>
                            <select class="form-control" name="product_category" id="tops_category">
                              <option value="" disabled selected hidden>Choose...</option>
                              <option value="Women shirt">Women shirt</option>
                              <option value="Women T-shirt">Women T-shirt</option>
                              <option value="Women Polo">Women Polo</option>
                              <option value="Jackets, Coats & Vest">Jackets, Coats & Vest</option>
                              <option value="Blouses">Blouses</option>
                              <option value="Semi-formal">Semi-formal</option>
                              <option value="Formal">Formal</option>
                              <option value="Casual">Casual</option>
                              <option value="Hoodies">Hoodies</option>
                              <option value="Denims">Denims</option>
                              <option value="Bikini Tops">Bikini Tops</option>
                              <option value="Others">Others</option>
                            </select>
                            <br>
                          </div>
                          <div class="col-md-4 womensub-category-container" id="Women-Bottoms" style="display: none;">
                            <label for="exampleSelectGender">Women Bottoms<code>*</code></label>
                            <select class="form-control" name="product_category" id="bottom_category">
                              <option value="" disabled selected hidden>Choose...</option>
                              <option value="Women Shorts">Women Shorts</option>
                              <option value="Women Pants">Women Pants</option>
                              <option value="Women Sweatpants">Women Sweatpants</option>
                              <option value="Skirt">Skirt</option>
                              <option value="Semi-formal">Semi-formal</option>
                              <option value="Formal">Formal</option>
                              <option value="Casual">Casual</option>
                              <option value="Hoodies">Hoodies</option>
                              <option value="Denims">Denims</option>
                              <option value="Bikini Bottom">Bikini Bottom</option>
                              <option value="Others">Others</option>
                            </select>
                            <br>
                          </div>
                          <div class="col-md-4 womensub-category-container" id="Women-Sets" style="display: none;">
                            <label for="exampleSelectGender">Women Sets/Dresses<code>*</code></label>
                            <style>
                              /* Basic styling (customize as needed) */
                              .searchable-dropdown {
                                position: relative;
                              }

                              .search-input {
                                width: 100%;
                                padding: 8px;
                              }
                            </style>
                            <div class="searchable-dropdown">
                              <input type="text" class="search-input" placeholder="Search...">
                              <select class="form-control" name="product_category" id="sets_category">
                                <option value="" disabled selected>Choose...</option>
                                <option value="Short Dresses">Short Dresses</option>
                                <option value="Long Dresses">Long Dresses</option>
                                <option value="Casual Dresses">Casual Dresses</option>
                                <option value="Elegant Dresses">Elegant Dresses</option>
                                <option value="Boho Dresses">Boho Dresses</option>
                                <option value="Sexy Dresses">Sexy Dresses</option>
                                <option value="Cocktail Dresses">Cocktail Dresses</option>
                                <option value="Denims Dresses">Denims Dresses</option>
                                <option value="Costumes">Costumes</option>
                                <option value="Others">Others</option>
                              </select>
                            </div>
                            <script>
                              const searchInput = document.querySelector('.search-input');
                              const selectDropdown = document.getElementById('sets_category');

                              searchInput.addEventListener('input', () => {
                                const filter = searchInput.value.toLowerCase();
                                const options = selectDropdown.querySelectorAll('option');

                                options.forEach(option => {
                                  const text = option.textContent.toLowerCase();
                                  if (text.includes(filter)) {
                                    option.style.display = '';
                                  } else {
                                    option.style.display = 'none';
                                  }
                                });
                              });
                            </script>


                            <br>
                          </div>


                          


                          <script>
                            document.getElementById('Women-Select-Category').addEventListener('change', function() {
                              // <SELECT> </SELECT>
                              const selectedStyle = this.value;
                              const categoryContainers = document.querySelectorAll('.womensub-category-container');
                              // <DIV CONTAINER> </DIV>

                              categoryContainers.forEach(container => {
                                container.style.display = 'none'; // Hide all containers initially
                              });

                              if (selectedStyle === 'Women Tops') {
                                document.getElementById('Women-Tops').style.display = 'block';
                                document.getElementById('Women-Bottoms').style.display = 'none';
                                document.getElementById('Women-Sets').style.display = 'none';
                              } else if (selectedStyle === 'Women Bottoms') {
                                document.getElementById('Women-Tops').style.display = 'none';
                                document.getElementById('Women-Bottoms').style.display = 'block';
                                document.getElementById('Women-Sets').style.display = 'none';
                              } else {
                                document.getElementById('Women-Tops').style.display = 'none';
                                document.getElementById('Women-Bottoms').style.display = 'none';
                                document.getElementById('Women-Sets').style.display = 'block';
                              }
                            });
                          </script>



                        </div>



                        <div class="row">
                          <div class="col-md-6">
                            <label for="exampleInputName1">Product Price<code>*</code></label>
                            <input type="number" name="product_price" class="form-control" id="exampleInputName1" placeholder="Price" required>
                          </div>
                          <div class="col-md-6">
                            <label for="exampleInputName1">Product Stocks<code>*</code></label>
                            <input type="number" name="product_stocks" class="form-control" id="exampleInputName1" placeholder="Stocks" required>
                          </div>
                        </div>
                        <br>


                        <label for="exampleInputName1">Product Description</label>
                        <div class="row">
                          <div class="col-md-12">
                            <br>
                            <textarea class="form-control" name="product_description" id="exampleTextarea1" rows="10"></textarea>
                            <br>
                          </div>
                        </div>
                        <br>






                        <label for="exampleInputName1">Product Brand(Optional)</label>
                        <input type="text" name="product_brand" class="form-control" id="exampleInputName1" placeholder="Brand Name">
<!-- 
                        <label>Category<code>*</code></label>
                        <select class="js-example-basic-single w-100" name="product_category" required>
                          <option value="" disabled selected hidden>Choose...</option>
                          <php
                          $productQuery = "SELECT * FROM product_category";
                          $productResult = $conn->query($productQuery);
                          if ($productResult->num_rows > 0) {
                            while ($product = $productResult->fetch_assoc()) {
                          ?>
                              <option value="<php echo $product['category_name']; ?>">
                                <php echo $product['category_name']; ?>
                              </option>
                          <php
                            }
                          } else {
                            echo "No products found.";
                          }
                          ?>
                        </select> -->
                        <br>
                        <br>

                        <div class="row">
                          <div class="col-md-12">
                            <h5 for="exampleSelectGender">Other Information</h5>
                            <br>

                            <label for="exampleSelectGender">New Arrival<code>*</code></label>
                            <select class="form-control" name="new_arrival" id="exampleSelectGender" required>
                              <option value="" disabled selected hidden>Choose...</option>
                              <option value="Yes">Yes</option>
                              <option value="No">No</option>
                              <br>
                            </select>
                            <br>
                            <label for="exampleSelectGender">Featured Products<code>*</code></label>
                            <select class="form-control" name="featured_product" id="exampleSelectGender" required>
                              <option value="" disabled selected hidden>Choose...</option>
                              <option value="Yes">Yes</option>
                              <option value="No">No</option>
                              <br>
                            </select> <br>
                          </div>
                        </div>

                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <!-- <h4 class="card-title">Variations<code>*</code></h4> -->
                      <h5 for="exampleSelectGender">Variations</h5>
                      <br>
                      <h6 for="exampleSelectGender">Add Colors</h6>
                      <div class="row">
                        <div id="input_fields">
                        </div>
                      </div>
                      <br>
                      <span class="btn btn-primary mr-2" id="add_more">Add More Color</span>
                      <style>
                        /* Image Preview Container */
                        .imagePreview {
                          width: 50;
                          /* Adjust size as needed */
                          height: 50;
                          border: 2px dashed #ccc;
                          /* Modern dashed border */
                          cursor: pointer;
                          /* Indicate it's clickable */
                          display: flex;
                          justify-content: center;
                          align-items: center;
                          position: relative;
                          /* For overlay positioning */
                          overflow: hidden;
                          /* Hide image overflow */
                          z-index: 1;
                        }

                        /* Overlay (appears on hover) */
                        .imagePreview:hover::before {
                          content: '';
                          position: absolute;
                          top: 0;
                          left: 0;
                          width: 100%;
                          height: 100%;
                          background-color: rgba(0, 0, 0, 0.5);
                          /* Semi-transparent overlay */
                          display: flex;
                          justify-content: center;
                          align-items: center;
                        }

                        /* Overlay Text */
                        .imagePreview:hover::after {
                          content: 'Click to Upload';
                          position: absolute;
                          color: white;
                          font-size: 18px;
                          font-weight: bold;
                        }

                        /* Preview Image Styling */
                        .imagePreview img {
                          max-width: 100%;
                          max-height: 100%;
                          object-fit: cover;
                          /* Nicely scales the image */
                        }

                        /* Input Field (hidden by default) */
                        .imagePreview input[type="file"] {
                          position: absolute;
                          display: none;
                          top: 0;
                          left: 0;
                          width: 100%;
                          height: 100%;
                          opacity: 0;
                          /* Make it invisible */
                          cursor: pointer;
                          /* Still clickable */
                          z-index: 2;
                        }

                        .product_color {
                          display: flex;
                          justify-content: center;
                          align-items: center;
                        }
                      </style>

                      <script>
                        const inputFieldsContainer = document.getElementById("input_fields");
                        const addMoreButton = document.getElementById("add_more");
                        let counter = 0; // Counter for unique IDs

                        function addInputPair() {
                          counter++; // Increment the counter
                          const newPair = document.createElement("div");

                          newPair.innerHTML = `
                            <div class="row input-pair product_color">
                              <div class="col-md-4product_color_container">
                                <input hidden type="file" name="product_images_color[]" id="imageInput_${counter}" accept="image/*">
                                <div id="imagePreview_${counter}" class="imagePreview"></div>
                              </div>
                              <div class="col-md-4 product_color">
                                <input type="text" class="form-control" name="product_color[]" id="product_color_${counter}">
                              </div>
                           
                              <div class="col-md-4 product_color ">
                               <span class="delete-button fas fa-trash"></span>
                              </div>
                          

                            </div>
                            `;

                          inputFieldsContainer.appendChild(newPair);

                          // Add event listener to the new image input
                          const newImageInput = document.getElementById(`imageInput_${counter}`);
                          const newImagePreview = document.getElementById(`imagePreview_${counter}`);

                          newImageInput.addEventListener('change', handleImageUpload.bind(null, newImageInput, newImagePreview));
                        }

                        function handleImageUpload(imageInput, imagePreview) {
                          imagePreview.innerHTML = ''; // Clear previous previews

                          const files = imageInput.files; // Get the FileList object
                          const fileListArray = Array.from(files); // Convert FileList to Array

                          fileListArray.forEach(file => {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                              const img = document.createElement('img');
                              img.src = e.target.result;
                              img.classList.add('preview-image'); // Add a class for styling
                              imagePreview.appendChild(img);
                            };
                            reader.readAsDataURL(file);
                          });
                        }

                        // Add initial pair
                        addInputPair();

                        addMoreButton.addEventListener("click", addInputPair);

                        // Click handler for delete buttons and image previews
                        inputFieldsContainer.addEventListener("click", function(event) {
                          if (event.target.classList.contains("delete-button")) {
                            // Remove the entire row (input-pair)
                            event.target.closest(".input-pair").remove();
                          }

                          if (event.target.classList.contains("imagePreview")) {
                            const fileInputId = event.target.id.replace("imagePreview_", "imageInput_");
                            const fileInput = document.getElementById(fileInputId);
                            fileInput.click();
                          }
                        });
                      </script>
                    </div>
                    <div class="col-md-6">
                      <!-- <h4 class="card-title">Variations<code>*</code></h4> -->
                      <!-- <h4 for="exampleSelectGender">Variations</h3> -->
                      <br>
                      <br>
                      <h6 for="exampleSelectGender">Add Sizes</h6>
                      <div class="row">
                        <div class="col-md-6">
                          <div id="input_fields_Size">
                          </div>

                          <span class="btn btn-primary mr-2" id="add_more_size">Add More Sizes</span>
                        </div>

                        <div class="col-md-6">
                          <div class="table-responsive">
                            <h5 style="color: #ababab;" for="exampleSelectGender">General Apparel Size Chart</h5>
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>
                                    <label>Size</label>
                                  </th>
                                  <th><label>Height (ft/in)</label></th>
                                  <th><label>Weight (kg)</label></th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>2XS</td>
                                  <td>4'10" - 5'1"</td>
                                  <td>39-45</td>
                                </tr>
                                <tr>
                                  <td>XS</td>
                                  <td>5'1" - 5'3"</td>
                                  <td>45-52</td>
                                </tr>
                                <tr>
                                  <td>S</td>
                                  <td>5'3" - 5'5"</td>
                                  <td>52-59</td>
                                </tr>
                                <tr>
                                  <td>M</td>
                                  <td>5'5" - 5'7"</td>
                                  <td>52-59</td>
                                </tr>
                                <tr>
                                  <td>L</td>
                                  <td>5'7" - 5'9"</td>
                                  <td>59-66</td>
                                </tr>
                                <tr>
                                  <td>XL</td>
                                  <td>5'9" - 5'11"</td>
                                  <td>66-73</td>
                                </tr>
                                <tr>
                                  <td>XL</td>
                                  <td>5'3" - 5'5"</td>
                                  <td>73-80</td>
                                </tr>
                                <tr>
                                  <td>2XL</td>
                                  <td>5'11" - 6'1"</td>
                                  <td>80-86</td>
                                </tr>
                                <tr>
                                  <td>3XL</td>
                                  <td>6'1" - 6'3"</td>
                                  <td>86-93</td>
                                </tr>

                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>

                      <script>
                        const inputFieldsContainer1 = document.getElementById("input_fields_Size");
                        const addMoreButton1 = document.getElementById("add_more_size");
                        let counter1 = 0; // Counter for unique IDs

                        function addInputPair1() {
                          counter++; // Increment the counter
                          const newPair = document.createElement("div");

                          newPair.innerHTML = `
                              <div class="product_size">
                                <input type="text" class="form-control" name="product_size[]" id="product_size_${counter1}">
                              </div>
                           
                              <br>
                            `;

                          inputFieldsContainer1.appendChild(newPair);
                        }
                        // Add initial pair
                        addInputPair1();

                        addMoreButton1.addEventListener("click", addInputPair1);
                      </script>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <br><button type="submit" class="btn btn-primary mr-2 col-md-12">Add Product</button>

                </div>
              </div>
            </div>
            <br>
          </form>

        </div>
        <!-- content-wrapper ends -->
        <!-- partial"partials/_footer.html -->
       
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
  <script src="js/form-validation.js"></script>
  <!-- endinject -->

  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>

  <script src="js/file-upload.js"></script>
  <!-- <script src="js/typeahead.js"></script> -->
  <script src="js/select2.js"></script>

  <!-- End custom js for this page-->


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
  <script src="js/dashboard.js"></script>




</body>


</html>