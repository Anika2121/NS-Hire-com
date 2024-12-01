<?php
session_start();
require 'connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if email exists in the session
if (!isset($_SESSION['email'])) {
    echo "<script>alert('No email found in session. Please log in again.'); window.location.href='../index.html';</script>";
    exit();
}

$email = $_SESSION['email']; // Get email from session
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']; // Admin login flag
    $enteredCode = $_POST['verification_code']; // OTP entered by user

// Determine query based on user type
if ($isAdmin) {
    $query = "SELECT verification_code, code_expiry FROM admin WHERE Email = ?";
} else {
    $query = "SELECT ui.verification_code, ui.code_expiry, ubt.User_Type 
              FROM user_info ui
              INNER JOIN user_by_type ubt ON ui.UserID = ubt.UserID 
              WHERE ui.Email = ?";
}

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo "Error in query preparation: " . $conn->error;
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Debugging: Check if the query returned any results
if ($result->num_rows > 0) {
    echo "Query returned results!<br>";
    $data = $result->fetch_assoc();

    // Check if the verification code matches and is not expired
    if ($enteredCode == $data['verification_code'] && strtotime($data['code_expiry']) > time()) {
        // Verification succeeded
        unset($_SESSION['email']); // Clear temporary session

        if ($isAdmin) {
            // Admin login
            $_SESSION['admin_email'] = $email; // Set session for admin
            header("Location: admin-dashboard.php");
        } else {
            // Regular user login
            $_SESSION['email'] = $email; // Set session for user
            $_SESSION['User_Type'] = $data['User_Type']; // Store User_Type in session

            if ($data['User_Type'] === 'Candidate') {
                header("Location: candidate-dashboard.php");
            } elseif ($data['User_Type'] === 'Employee') {
                header("Location: employee-dashboard.php");
            }
        }
        exit();
    } else {
        // Invalid or expired code
        echo "<script>alert('Invalid or expired verification code.'); window.location.href='verify-code.php';</script>";
    }
} else {
    // No user found with the provided email
        echo "<script>alert('Unexpected error: No records found.'); window.location.href='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        form {
            margin-top: 50px;
        }
        input {
            margin: 10px;
            padding: 10px;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            background-color: blue;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Enter Verification Code</h2>
    <form method="POST" action="verify-code.php">
        <input type="text" name="verification_code" placeholder="Enter Code" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
