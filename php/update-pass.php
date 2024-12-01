<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch token and new password from the form
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];

    // Input validation: Check if password meets security requirements
    if (strlen($newPassword) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.'); window.history.back();</script>";
        exit;
    }

    // Enforce password policy
    if (!preg_match('/[A-Z]/', $newPassword)) {
        echo "<script>alert('Password must contain at least one uppercase letter.'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/[0-9]/', $newPassword)) {
        echo "<script>alert('Password must contain at least one number.'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/[\W_]/', $newPassword)) {
        echo "<script>alert('Password must contain at least one special character.'); window.history.back();</script>";
        exit;
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Determine user type based on token
    $tableName = null;
    $stmt = $conn->prepare("SELECT 'admin' AS user_type FROM admin WHERE reset_token = ? AND reset_expiry > NOW()
                            UNION
                            SELECT 'user_info' AS user_type FROM user_info WHERE reset_token = ? AND reset_expiry > NOW()
                            UNION
                            SELECT 'company' AS user_type FROM company WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("sss", $token, $token, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tableName = $row['user_type'];
    } else {
        echo "<script>alert('Token is invalid or expired.'); window.location.href='../forgot-pass.html';</script>";
        exit;
    }

    $stmt->close();

    // Update the password for the identified user
    $query = "UPDATE $tableName SET Password = ?, reset_token = NULL, reset_expiry = NULL 
              WHERE reset_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $hashedPassword, $token);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "<script>alert('Password has been reset successfully.'); window.location.href='../login.php';</script>";
    } else {
        echo "<script>alert('Failed to reset password. Please try again later.'); window.location.href='../forgot-pass.html';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request method.'); window.location.href='../forgot-password.html';</script>";
}
?>
