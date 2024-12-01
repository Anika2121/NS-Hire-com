<?php
// Database connection
session_start();
// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

$conn = new mysqli('localhost', 'root', '', 'ns_hire');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Step 1: Validate Email and Company Name
    $stmt = $conn->prepare("SELECT CompanyID, Password FROM company WHERE  Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch company details
        $company = $result->fetch_assoc();
        $hashedPassword = $company['Password'];

        // Step 2: Verify Password
        if (password_verify($password, $hashedPassword)) {
            // Password matches, login successful
         
            $_SESSION['CompanyID'] = $company['CompanyID'];
            $_SESSION['email'] = $email;
            echo "<script>alert('Login successful!'); window.location.href = 'company-dashboard.php';</script>";
        } else {
            // Invalid password
            echo "<script>alert('Invalid password. Please try again.'); window.location.href = '../company-login.html';</script>";
        }
    } else {
        // Email or CompanyName not found
        echo "<script>alert('Invalid email or company name. Please try again.'); window.location.href = '../company-login.html';</script>";
    }
} else {
    echo "Invalid request method.";
}
?>
