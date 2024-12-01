<?php
session_start();

if (!isset($_SESSION['UserID']) || !isset($_SESSION['email'])) {
    header("Location: user-login.php");
    exit;
}



require 'connect.php';
$userID = $_SESSION['UserID'];
// Fetch logged-in user's email from session
$email = $_SESSION['email'];

// $_SESSION['Fname'] = $row['Fname'];
// $_SESSION['Lname'] = $row['Lname'];
// User details

// Query to fetch user details, type, and company information (if available)
$sql="SELECT 
    u.Fname, 
    u.Lname, 
    u.Email AS UserEmail, 
    u.Phone, 
    u.District, 
    u.Division, 
    t.User_Type, 
    e.CompanyID, 
    c.CompanyName, 
    c.Website, 
    c.Email AS CompanyEmail
FROM user_info u
INNER JOIN user_by_type t ON u.UserID = t.UserID
LEFT JOIN employee e ON u.UserID = e.UserID
LEFT JOIN company c ON e.CompanyID = c.CompanyID
WHERE u.Email = ?";


$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user record exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $userName = $row['Fname'] . ' ' . $row['Lname'];
    $userEmail = $row['UserEmail'];
    $userPhone = $row['Phone'];
    $userAddress = $row['District'] . ', ' . $row['Division'];
    $userType = $row['User_Type']; // Can be "Candidate" or "Employee"

    // Company details
    $companyID = $row['CompanyID'] ?? 'Not Assigned';
    $companyName = $row['CompanyName'] ?? 'No Company';
    $companyLocation = $row['Location'] ?? 'N/A';
    $companyWebsite = $row['Website'] ?? 'N/A';
    $companyEmail = $row['Email'] ?? 'N/A';

    // Optional: Display user and company type for debugging purposes
    // echo "Welcome, $userType: $userName";
    // echo "Company: $companyName ($companyLocation)";
} else {
    echo "User not found.";
    exit;
}
// Employee Login
$userID = $_SESSION['UserID']; // From session
$userType = "Employee"; // From user_by_type

// Check if UserID is already in the employee table
$checkSQL = "SELECT EmpNo FROM employee WHERE UserID = ?";
$checkStmt = $conn->prepare($checkSQL);
$checkStmt->bind_param("i", $userID);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows == 0) {
    $insertSQL = "INSERT INTO employee (UserID, CompanyID) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertSQL);
    $companyID = null; // Set default CompanyID if not assigned
    $insertStmt->bind_param("ii", $userID, $companyID);
    $insertStmt->execute();
}

// Update CompanyID for the logged-in employee

// Close connections
$stmt->close();
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #c1e88e;
        }
        .px-4{
            padding: 10px;
            width: 400px;
        }
        .sidebar {
            width: 250px;
            background-color: #96dd5f;
            min-height: 100vh;
            position: fixed;
            padding-top: 20px;
            color: #ffffff;
        }
        .sidebar .profile {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #ffffff;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            width: 100%;
        }
        .sidebar ul li a {
            color: #319216;
            padding: 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: background 0.3s;
        }
        .sidebar ul li a:hover {
            background-color: #90ff50;
        }
        .sidebar ul li a i {
            margin-right: 10px;
        }
    


.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
}

section {
    padding: 120px 0;
    overflow: hidden;
    background: #fff;
}
.mb-2-3, .my-2-3 {
    margin-bottom: 2.3rem;
}

.section-title {
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 10px;
    position: relative;
    display: inline-block;
}
.text-primary {
    color: #ceaa4d !important;
}
.text-secondary {
    color: #15395A !important;
}
.font-weight-600 {
    font-weight: 600;
}
.display-26 {
    font-size: 1.3rem;
}

@media screen and (min-width: 992px){
    .p-lg-7 {
        padding: 4rem;
    }
}
@media screen and (min-width: 768px){
    .p-md-6 {
        padding: 3.5rem;
    }
}
@media screen and (min-width: 576px){
    .p-sm-2-3 {
        padding: 2.3rem;
    }
}
.p-1-9 {
    padding: 2.9rem;
}

.bg-secondary {
    background: #15395A !important;
}
@media screen and (min-width: 576px){
    .pe-sm-6, .px-sm-6 {
        padding-right: 3.5rem;
    }
}
@media screen and (min-width: 576px){
    .ps-sm-6, .px-sm-6 {
        padding-left: 3.5rem;
    }
}
.pe-1-9, .px-1-9 {
    padding-right: 1.9rem;
}
.ps-1-9, .px-1-9 {
    padding-left: 1.9rem;
}
.pb-1-9, .py-1-9 {
    padding-bottom: 1.9rem;
}
.pt-1-9, .py-1-9 {
    padding-top: 1.9rem;
}
.mb-1-9, .my-1-9 {
    margin-bottom: 1.9rem;
}
@media (min-width: 992px){
    .d-lg-inline-block {
        display: inline-block!important;
    }
}
.rounded {
    border-radius: 0.25rem!important;
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
    <div class="sidebar">
        <div class="profile">
            <img src="https://via.placeholder.com/80" alt="Admin Profile Picture">
            
            <h5>Employee Name</h5>
            <h6>ID###</h6>
        </div>
        <ul>
            <li><a href="general-info-update.php"><i class="fas fa-cog" style="color: #ffffff;"></i>General Settings</a></li>
            <li><a href="job-listing.php"><i class="fa-solid fa-suitcase" style="color: #ffffff;"></i>Jobs list</a></li>
            <li><a href="view-application.php"><i class="fas fa-user-tie" style="color: #ffffff;"></i>Application Management</a></li>
           
            <li><a href="update-emp.php"><i class="fa-solid fa-file" style="color: #ffffff;"></i>Update info</a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket fa-lg" style="color: #267a15;"></i> Logout</a>
            </li>
           
        </ul>
    </div>

    <!-- Main Content -->
    <section class="bg-light">
    <div class="container">
        <div class="row ml-5">
            <div class="col-lg-12 mb-4 mb-sm-5">
                <div class="card card-style1 border-0">
                    <div class="card-body p-1-9 p-sm-2-3 p-md-6 p-lg-7 ml-4">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-4 mb-lg-0 ">
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="...">
                            </div>
                            <div class="col-lg-6 px-xl-10">
                                <div style="background-color:#b452ff" class=" d-lg-inline-block py-1-9 px-1-9 px-sm-6 mb-1-9 rounded">
                                    <h3 class="h2 text-white mb-0"><?php echo htmlspecialchars($userName); ?></h3>
                                 
                                </div>
                                <ul class="list-unstyled mb-1-9">
                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">Position:</span> <?php echo"$userType"; ?></li>
                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">UserID:</span><?php echo "#" . htmlspecialchars($userID); ?></li>
                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">Email:</span> <?php echo htmlspecialchars($row['UserEmail']); ?></li>

                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">Phone:</span> <?php echo"$userPhone"; ?></li>
                                    <li class="display-28"><span class="display-26 text-secondary me-2 font-weight-600">Address:</span> <?php echo"$userAddress"; ?></li>
                                </ul>
                                <ul class="list-unstyled mb-1-9">
                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">CompanyID:</span> <?php echo htmlspecialchars($companyID); ?></li>
                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">Company Name:</span><?php echo htmlspecialchars($companyName); ?></li>
                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">Email:</span> <?php echo htmlspecialchars($row['CompanyEmail']); ?></li>
                                    <li class="mb-2 mb-xl-3 display-28"><span class="display-26 text-secondary me-2 font-weight-600">Website:</span> <?php echo htmlspecialchars($companyWebsite); ?></li>
   
                                </ul>
                                <ul class="social-icon-style1 list-unstyled mb-0 ps-0">
                                    <li><a href="#!"><i class="ti-twitter-alt"></i></a></li>
                                    <li><a href="#!"><i class="ti-facebook"></i></a></li>
                                    <li><a href="#!"><i class="ti-pinterest"></i></a></li>
                                    <li><a href="#!"><i class="ti-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-4 mb-sm-5">
                <div>
                    <span class="section-title text-primary mb-3 mb-sm-4">About Me</span>
                    <p>Edith is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    <p class="mb-0">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed.</p>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12 mb-4 mb-sm-5">
                        <div class="mb-4 mb-sm-5">
                            <span class="section-title text-primary mb-3 mb-sm-4">Skill</span>
                            <div class="progress-text">
                                <div class="row">
                                    <div class="col-6">Driving range</div>
                                    <div class="col-6 text-end">80%</div>
                                </div>
                            </div>
                            <div class="custom-progress progress progress-medium mb-3" style="height: 4px;">
                                <div class="animated custom-bar progress-bar slideInLeft bg-secondary" style="width:80%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="10" role="progressbar"></div>
                            </div>
                            <div class="progress-text">
 

                            <div class="custom-progress progress progress-medium mb-3" style="height: 4px;">
                                <div class="animated custom-bar progress-bar slideInLeft bg-secondary" style="width:90%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" role="progressbar"></div>
                            </div>
                            <div class="progress-text">
                                <div class="row">
                                    <div class="col-6">Side Bets</div>
                                    <div class="col-6 text-end">50%</div>
                                </div>
                            </div>
                            <div class="custom-progress progress progress-medium mb-3" style="height: 4px;">
                                <div class="animated custom-bar progress-bar slideInLeft bg-secondary" style="width:50%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" role="progressbar"></div>
                            </div>
                            <div class="progress-text">
                                <div class="row">
                                    <div class="col-6">Putting</div>
                                    <div class="col-6 text-end">60%</div>
                                </div>
                            </div>
                            <div class="custom-progress progress progress-medium" style="height: 4px;">
                                <div class="animated custom-bar progress-bar slideInLeft bg-secondary" style="width:60%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="70" role="progressbar"></div>
                            </div>
                        </div>
                        <div>
                            <span class="section-title text-primary mb-3 mb-sm-4">Education</span>
                            <p>Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.</p>
                            <p class="mb-1-9">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
              
                        </div>
                        <div class="row mb-4 p-3">
                                    <div class="col-sm-3 "></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="button" class="btn btn-primary px-4 ml-5"  value="Edit">
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
