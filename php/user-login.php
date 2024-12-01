<?php
session_start();
require 'connect.php';
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Ensure email and password are provided
    if (empty($email) || empty($password)) {
        echo "<script>alert('Please provide both email and password.'); window.history.back();</script>";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.history.back();</script>";
        exit;
    }

    // Query to get user info
    $stmt = $conn->prepare("SELECT * FROM user_info WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashedPassword = $user['Password'];

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['email'] = $user['Email'];
            $_SESSION['UserID'] = $user['UserID'];

            // Generate a 6-digit verification code
            $verificationCode = rand(100000, 999999);
            $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes")); // Code valid for 10 minutes

            // Update the database with the code and expiry
            $updateQuery = "UPDATE user_info SET verification_code = ?, code_expiry = ? WHERE Email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sss", $verificationCode, $expiry, $email);
            $updateStmt->execute();

            // Send the code via email
            $subject = "Your Verification Code";
            $message = "Your verification code is: $verificationCode. It is valid for 10 minutes.";
            $headers = "From: noreply@nshire.com";

            if (mail($email, $subject, $message, $headers)) {
                // Redirect to the verification page
                header("Location: verify-code.php");
                exit();
            } else {
                echo "<script>alert('Failed to send verification code.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Invalid password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email not found.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
