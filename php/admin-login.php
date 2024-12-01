<?php
require 'connect.php';
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user details
    $query = "SELECT * FROM admin WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['Password'])) {
            // Generate a 6-digit verification code
            $verificationCode = rand(100000, 999999);
            $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes")); // Code valid for 10 minutes

            // Update the database with the code and expiry
            $updateQuery = "UPDATE admin SET verification_code = ?, code_expiry = ? WHERE Email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sss", $verificationCode, $expiry, $email);
            $updateStmt->execute();

            // Send the code via email
            $subject = "Your Verification Code";
            $message = "Your verification code is: $verificationCode. It is valid for 10 minutes.";
            $headers = "From: noreply@nshire.com";

            if (mail($email, $subject, $message, $headers)) {
                $_SESSION['email'] = $email; // Store email temporarily in session
                $_SESSION['isAdmin'] = true; // Set session flag for admin
                header("Location: verify-code.php"); // Redirect to verification page
                exit();
            } else {
                echo "<script>alert('Failed to send verification code.'); window.location.href='admin-login.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='admin-login.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found.'); window.location.href='admin-login.php';</script>";
    }
}
?>
