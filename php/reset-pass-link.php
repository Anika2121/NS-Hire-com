<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email from the form
    $email = trim($_POST['email']);

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.'); window.location.href='../forgot-password.php';</script>";
        exit;
    }

    // Step 1: Identify user type and table
    $userType = null;
    $tableName = null;

    // Check in admin table
    $stmt = $conn->prepare("SELECT ID FROM admin WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $adminResult = $stmt->get_result();
    if ($adminResult->num_rows > 0) {
        $userType = 'admin';
        $tableName = 'admin';
    }

    // Check in user_info table
    if (!$userType) {
        $stmt = $conn->prepare("SELECT UserID FROM user_info WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $userResult = $stmt->get_result();
        if ($userResult->num_rows > 0) {
            $userType = 'user';
            $tableName = 'user_info';
        }
    }

    // Check in company table
    if (!$userType) {
        $stmt = $conn->prepare("SELECT CompanyID FROM company WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $companyResult = $stmt->get_result();
        if ($companyResult->num_rows > 0) {
            $userType = 'company';
            $tableName = 'company';
        }
    }

    // Step 2: Handle cases where email is not found
    if (!$userType) {
        echo "<script>alert('Email not found in our records.'); window.location.href='../forgot-pass.html';</script>";
        exit;
    }

    // Step 3: Generate reset token and expiry
        $token = bin2hex(random_bytes(32));
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token valid for 1 hour

    // Update reset_token and reset_expiry in the identified table
    $stmt = $conn->prepare("UPDATE `$tableName` SET reset_token = ?, token_expiry = ? WHERE Email = ?");
    $stmt->bind_param("sss", $token, $expiry, $email);

    if ($stmt->execute()) {
        // Step 4: Send reset link to email
        $resetLink = "http://localhost/nshire/php/reset-password.php?token=" . $token;
            $subject = "Password Reset Request";
        $message = "Hello,<br><br>We received a request to reset your password. Please click the link below to reset your password:<br>
                    <a href='$resetLink'>$resetLink</a><br><br>
                    If you did not request this, please ignore this email.<br><br>
                    Regards,<br>Nshire Team";
        $headers = "From: noreply@nshire.com\r\nContent-Type: text/html";

            if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('Reset link sent to your email.'); window.location.href='../login.php';</script>";
        } else {
            echo "<script>alert('Failed to send reset link. Please try again later.'); window.location.href='../forgot-pass.html';</script>";
        }

        $updateStmt->close();
    } else {
        echo "<script>alert('Failed to store reset token. Please try again later.'); window.location.href='../forgot-pass.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
