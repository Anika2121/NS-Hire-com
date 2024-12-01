<?php
// Database connection details
session_start();
require 'connect.php';

// Fetch admin data based on ID (assuming you have the admin ID)
$adminEmail = $_SESSION['admin_email'];

$sql = "SELECT * FROM admin WHERE Email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $adminEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
  $adminName = $row['Fname'] . ' ' . $row['Lname'];
  $adminEmail = $row['Email'];
  $adminPhone = $row['Phone'];
} else {
  echo "Admin not found.";
    exit;
}

// Close connections
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #012a3c;
          
        }

        .sidebar {
            width: 250px;
            background-color: #022028;
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
            color: #92f6f3;
            padding: 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #47c9fd;
            color: #1a253a;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .main-content h2 {
            color: white;
            text-align: center;
        }

        /* template */
        .section {
            padding: 100px 0;
            position: relative;
        }

        .gray-bg {
            background-color: #f5f5f5;
        }

        img {
            max-width: 100%;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }


        .about-text h3 {
            font-size: 45px;
            font-weight: 700;
            margin: 0 0 6px;
        }

        @media (max-width: 767px) {
            .about-text h3 {
                font-size: 35px;
            }
        }

        .about-text h6 {
            font-weight: 600;
            margin-bottom: 15px;
        }

        @media (max-width: 767px) {
            .about-text h6 {
                font-size: 18px;
            }
        }

        .about-text p {
            font-size: 18px;
            max-width: 450px;
        }

        .about-text p mark {
            font-weight: 600;
            color: #20247b;
        }

        .about-list {
            padding-top: 10px;
        }

        .about-list .media {
            padding: 5px 0;
        }

        .about-list label {
            color: #20247b;
            font-weight: 600;
            width: 88px;
            margin: 0;
            position: relative;
        }

        .about-list label:after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            right: 11px;
            width: 1px;
            height: 12px;
            background: #20247b;
            -moz-transform: rotate(15deg);
            -o-transform: rotate(15deg);
            -ms-transform: rotate(15deg);
            -webkit-transform: rotate(15deg);
            transform: rotate(15deg);
            margin: auto;
            opacity: 0.5;
        }

        .about-list p {
            margin: 0;
            font-size: 15px;
        }

        @media (max-width: 991px) {
            .about-avatar {
                margin-top: 30px;
            }
        }

        .about-section .counter {
            padding: 22px 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(31, 45, 61, 0.125);
        }

        .about-section .counter .count-data {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .about-section .counter .count {
            font-weight: 700;
            color: #20247b;
            margin: 0 0 5px;
        }

        .about-section .counter p {
            font-weight: 600;
            margin: 0;
        }

        mark {
            background-image: linear-gradient(rgba(252, 83, 86, 0.6), rgba(252, 83, 86, 0.6));
            background-size: 100% 3px;
            background-repeat: no-repeat;
            background-position: 0 bottom;
            background-color: transparent;
            padding: 0;
            color: currentColor;
        }

        .theme-color {
            color: #fc5356;
        }

        .dark-color {
            color: #20247b;
        }
    </style>
    </style>
    <script type="text/javascript">
    // Prevent caching of the page in the browser
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</head>

<body>

    <div class="sidebar">
        <div class="profile">
            <h2>Admin</h2>
            <h3>Dashboard</h3>
        </div>

        <ul>
            <!-- <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li> -->
            <li><a href="../display-employee.php"><i class="fas fa-users"></i> Employee Management</a></li>
            <li><a href="../display-candidate.php"><i class="fas fa-user-tie"></i> Candidate Management</a></li>
            <li><a href="../display-company.php"><i class="fas fa-building"></i> Company Management</a></li>
            <li><a href="manage-contact.php"><i class="fa-solid fa-address-book"></i> Contact</a></li>
            <li><a href="blog-post.php"><i class="fa-solid fa-blog"></i> Blog</a></li>
            <li><a href="logout.php" style="text-decoration: none; color: white; background-color: red; padding: 10px; border-radius: 5px;">Logout</a>
            </li>
        </ul>
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <section class="section about-section gray-bg" id="about">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-lg-6">
                        <div class="about-text go-to">
                            <!-- Dynamic Admin Name -->
                            <h3 class="dark-color">
                            <?php echo $adminName; ?>
                            </h3>
                            <!-- Static Description -->
                            <h6 class="theme-color lead">
                                A Top Developer &amp; UI designer based in USA
                            </h6>
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima 
                                ad non molestias corporis ea. Maiores eius vero animi quasi ratione
                                 velit architecto voluptates consectetur commodi itaque
                                 ad cumque id minus, ullam molestiae esse veniam aperiam corporis dolorem repellendus, sequi sint?
                            </p>
                            <div class="row about-list">
                                <div class="col-md-6">
                                    <div class="media">
                                        <label>E-mail</label>
                                        <p> <?php echo $adminEmail; ?></p>
                                    </div>
                                    <div class="media">
                                        <label>Phone</label>
                                        <p> <?php echo $adminPhone; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Dynamic Admin Image -->
                    <div class="col-lg-6">
                        <div class="about-avatar">
                        <img src="https://img.lovepik.com/free-png/20211216/lovepik-boy-avatar-png-image_401704859_wh1200.png" alt="">
     <!-- alt="Profile Image" 
     class="rounded-circle img-fluid" 
     style="width: 100px; height: 100px;"> -->


                        </div>
                    </div>
                </div>
                <div class="counter">
                    <div class="row">
                        <div class="col-6 col-lg-3">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="500" data-speed="500">10</h6>
                                <p class="m-0px font-w-600">Worked Company</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="150" data-speed="150">150</h6>
                                <p class="m-0px font-w-600">Handle Candidate</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="850" data-speed="850">250</h6>
                                <p class="m-0px font-w-600">Managed Client</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="190" data-speed="190">190</h6>
                                <p class="m-0px font-w-600">Project</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
