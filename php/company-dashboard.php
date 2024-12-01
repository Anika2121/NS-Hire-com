<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if email is not set in the session
    header("Location: company-login.php");
    exit;
}

// Include database connection file
require 'connect.php';

// Fetch the logged-in user's email from the session
$email = $_SESSION['email'];

try {
    // Query to fetch company details based on the email
    $sql = "
        SELECT c.CompanyName, c.Website, c.Email, c.Phone, c.Location
        FROM company c
        WHERE c.Email = ?
    ";

    $stmt = $conn->prepare($sql);

    // Check if the prepared statement was successful
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameters and execute the query
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the user record exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Extract company details
        $compName = $row['CompanyName'] ?? "N/A";
        $compEmail = $row['Email'] ?? "N/A";
        $compWebsite = $row['Website'] ?? "N/A";
        $compPhone = $row['Phone'] ?? "N/A";
        $compAddress = $row['Location'] ?? "N/A";
    } else {
        // If no records found, display an error and exit
        echo "Company not found.";
        exit;
    }
} catch (Exception $e) {
    // Handle errors gracefully
    echo "Error: " . $e->getMessage();
    exit;
} finally {
    // Close connections
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}

// You can now use the variables $compName, $compEmail, $compWebsite, $compPhone, and $compAddress
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0d002d;
        
     
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .main-content h2{
            color: white;
            text-align: center;
        }

        .img-fluid {
    max-width: 100%;
    height: auto;
}

.card {
    margin-bottom: 30px;
    background-color: #ffdac4;
}

.overflow-hidden {
    overflow: hidden!important;
}

.p-0 {
    padding: 0!important;
}

.mt-n5 {
    margin-top: -3rem!important;
}

.linear-gradient {
    background-image: linear-gradient(#50b2fc,#f44c66);
}

.rounded-circle {
    border-radius: 50%!important;
}

.align-items-center {
    align-items: center!important;
}

.justify-content-center {
    justify-content: center!important;
}

.d-flex {
    display: flex!important;
}

.rounded-2 {
    border-radius: 7px !important;
}

.bg-light-info {
    --bs-bg-opacity: 1;
    background-color: rgba(235,243,254,1)!important;
}

.card {
    margin-bottom: 30px;
}

.position-relative {
    position: relative!important;
}

.shadow-none {
    box-shadow: none!important;
}

.overflow-hidden {
    overflow: hidden!important;
}

.border {
    border: 1px solid #ebf1f6 !important;
}

.fs-6 {
    font-size: 1.25rem!important;
}

.mb-2 {
    margin-bottom: 0.5rem!important;
}

.d-block {
    display: block!important;
}

a {
    text-decoration: none;
}

.user-profile-tab .nav-item .nav-link.active {
    color: #5d87ff;
    border-bottom: 2px solid #5d87ff;
}

.mb-9 {
    margin-bottom: 20px!important;
}

.fw-semibold {
    font-weight: 600!important;
}
.fs-4 {
    font-size: 1rem!important;
}

.card, .bg-light {
    box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
}

.fs-2 {
    font-size: .75rem!important;
}

.rounded-4 {
    border-radius: 4px !important;
}

.ms-7 {
    margin-left: 30px!important;
}
    </style>
    <script type="text/javascript">
    // Prevent caching of the page in the browser
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</head>
<body>
    <!-- Sidebar -->
    <!-- <div class="sidebar">
        <div class="profile">
            <img src="https://via.placeholder.com/80" alt="Admin Profile Picture">
            
            <h5>Company Name</h5>
            <h6>ID###</h6>
        </div>
        <ul>
            <li><a href="#"><i class="fas fa-cog" style="color: #ffffff;"></i> Settings</a></li>
            <li><a href="#"><i class="fas fa-users" style="color: #ffffff;"></i> Employee Management</a></li>
            <li><a href="#"><i class="fa-solid fa-suitcase" style="color: #ffffff;"></i>Job Posting</a></li>
          
            <li><a href="#"><i class="fa-regular fa-star-half-stroke" style="color: #ffffff;"></i> Review</a></li>
           
        </ul>
    </div> -->

    <!-- Main Content -->
    <div class="container">
      <div class="card overflow-hidden">
        <div class="card-body p-0">
          <img src="https://th.bing.com/th/id/R.d784958573b2fd79f37c2eccd8072edd?rik=6XgoB3NFsm7qNw&pid=ImgRaw&r=0" alt="" class="img-fluid">

          <div class="row align-items-center">
            <div class="col-lg-4 order-lg-1 order-2">
              <div class="d-flex align-items-center justify-content-around m-4">
                <div class="text-center mr-4">
                  <i class="fa fa-file fs-4 d-block mb-3 "></i>
                  <h5 style="color:blue;" class="mb-0">10000</h5>
                  <p  class="mb-0 fs-4">CV-Drop</p>
                </div>
                <div class="text-center mr-4">
                  <i class="fa fa-user fs-4 d-block mb-3 "></i>
                  <h5 style="color:blue;" class="mb-0  ">10096</h5>
                  <p class="mb-0 fs-4">Manage Candidate</p>
                </div>
                <div class="text-center mr-4">
                  <i class="fa fa-check fs-4 d-block mb-3 "></i>
                  <h5 style="color:blue;" class="mb-0">5000+</h5>
                  <p class="mb-0 fs-4">Employee</p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mt-n3 order-lg-2 order-1">
              <div class="mt-n5">
                <div class="d-flex align-items-center justify-content-center mb-2">
                  <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle" style="width: 110px; height: 110px;" ;="">
                    <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width: 100px; height: 100px;" ;="">
                      <img src="https://yt3.ggpht.com/a/AGF-l78eHILwrF51bF8ErzWKj7t3pTjOP_Z023-3Fg=s900-mo-c-c0xffffffff-rj-k-no" alt="" class="w-100 h-100">
                    </div>
                  </div>
                </div>
                <div class="text-center">
                  <h5 class="fs-5 mb-0 fw-semibold"><?php echo "$compName"; ?></h5>
                  <p class="mb-0 fs-4"><?php echo "$compWebsite"; ?></p>
                </div>
              </div>
            </div>
         
          <div class="col-lg-4 order-last">
          <ul class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-start my-3 gap-3">
                <li> <a style="text-decoration:none;" href="../post-job.html" ><button class="btn btn-primary">Post job</button></a></li>

              </ul>
          </div>
          </div>


     
          <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2" id="pills-tab" role="tablist">
         
      
            
            <!-- <li class="nav-item" role="presentation">
              <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-friends-tab" data-bs-toggle="pill" data-bs-target="#pills-friends" type="button" role="tab" aria-controls="pills-friends" aria-selected="false" tabindex="-1">
                <i class="fas fa-users me-2 fs-6 mr-2"></i>
                <span class="d-none d-md-block">Manage candiadte</span> 
              </button>
            </li> -->
            <!-- <li class="nav-item" role="presentation">
            <a href="display-review.php">
              <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-gallery-tab" data-bs-toggle="pill" data-bs-target="#pills-gallery" type="button" role="tab" aria-controls="pills-gallery" aria-selected="false" tabindex="-1">
                <i class="fa-regular fa-star-half-stroke me-2 fs-6 mr-2"></i>
                <span class="d-none d-md-block">Review</span> 
              </button>
              </a>
            </li> -->
            <li class="nav-item" role="presentation">
              <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center " style="color: #604cf4; background-color: #aedbfd; border: 1px solid skyblue;">
             
                <span class="d-none d-md-block"><?php echo"$compAddress"; ?></span> 
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center" style="color: #f44c66;background-color: #f7b3fd;border:1px solid crimson;">
                
                <span class="d-none d-md-block" > <?php echo"$compPhone"; ?></span> 
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center" style="color: #f44c66;background-color: #f7b3fd;border:1px solid crimson;">
              <li><a href="logout.php" style="text-decoration: none; color: white; background-color: red; padding: 12px 18px; border-radius: 5px;">Logout</a>
              </li>
             
              </button>
            </li>
          </ul>
        </div>
      </div>
      
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
