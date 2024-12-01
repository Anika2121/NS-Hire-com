<?php
require 'connect.php'; // Include your database connection

// Assuming form inputs are validated before this point
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$phone = $_POST['phone'];
$email = $_POST['email'];

// File upload handling
$profilePic = null; // Default to null if no file is uploaded
if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
    $profilePic = file_get_contents($_FILES['profile']['tmp_name']); // Read image as binary
}

// Insert query with placeholders for security
$query = "INSERT INTO admin (Fname, Lname, Password, Phone, Email, Image) 
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);

// Bind the parameters, including the binary image
$stmt->bind_param("sssssb", $fname, $lname, $password, $phone, $email, $profilePic);

// Execute the query and check for success
if ($stmt->execute()) {
    echo "Admin registered successfully!";
    header("Location: ../sign-up-in-admin.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
