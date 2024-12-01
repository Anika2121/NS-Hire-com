<?php
// Database connection details
session_start();
require'connect.php';
// Fetch admin data based on ID (assuming you have the admin ID)
$adminEmail = $_SESSION['admin_email'];

// Fetch admin data
$sql = "SELECT * FROM admin WHERE Email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $adminEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $adminName = $row['Fname'] . " " . $row['Lname'];
    $adminEmail = $row['Email'];
    $adminPhone = $row['Phone'];

    // Display the information on the page
    echo "<h2>Admin Information</h2>";
    echo "<p>Name: " . $adminName . "</p>";
    echo "<p>Email: " . $adminEmail . "</p>";
    echo "<p>Phone: " . $adminPhone . "</p>";
} else {
    echo "Admin not found.";
}

$stmt->close();
$conn->close();
?>