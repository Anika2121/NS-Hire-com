<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch token and new password from form
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];

    // Step 1: Validate input
    if (empty($token) || empty($newPassword)) {
        echo "<script>alert('Invalid request.'); window.location.href = '../forgot-pass.html';</script>";
        exit;
    }

    // Enforce password policy (e.g., at least 8 characters)
    if (strlen($newPassword) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.'); window.history.back();</script>";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Step 2: Identify the table containing the token
    $userType = null;
    $tableName = null;

    // Check in admin table
    $stmt = $conn->prepare("SELECT Email FROM admin WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $adminResult = $stmt->get_result();
    if ($adminResult->num_rows > 0) {
        $userType = 'admin';
        $tableName = 'admin';
    }

    // Check in user_info table
    if (!$userType) {
        $stmt = $conn->prepare("SELECT Email FROM user_info WHERE reset_token = ? AND token_expiry > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $userResult = $stmt->get_result();
        if ($userResult->num_rows > 0) {
            $userType = 'user';
            $tableName = 'user_info';
        }
    }

    // Check in company table
    if (!$userType) {
        $stmt = $conn->prepare("SELECT Email FROM company WHERE reset_token = ? AND token_expiry > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $companyResult = $stmt->get_result();
        if ($companyResult->num_rows > 0) {
            $userType = 'company';
            $tableName = 'company';
        }
    }

    // Step 3: Handle invalid or expired token
    if (!$userType) {
        echo "<script>alert('Invalid or expired token.'); window.location.href = '../forgot-pass.html';</script>";
        exit;
    }

    // Step 4: Update the password in the corresponding table
    $stmt = $conn->prepare("UPDATE `$tableName` SET Password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $hashedPassword, $token);
    $stmt->execute();

    if ($stmt->execute()) {
        echo "<script>alert('Password reset successful!'); window.location.href = '../index.html';</script>";
    } else {
        echo "<script>alert('Failed to reset password. Please try again.'); window.location.href = '../forgot-pass.html';</script>";
    }

    $stmt->close();

    $conn->close();
    exit;
}

// Display password reset form
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Invalid request. No token provided.");
}
$token = $_GET['token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            background-color: black;
            font-family: Arial, sans-serif;
        }
        .forgot-password-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 150px;
        }
        input, button {
            margin: 10px;
            padding: 10px;
            width: 250px;
            border-radius: 4px;
        }
        h2 {
            font-size: 24px;
            color: white;
        }
        button {
            background-color: rgb(239, 254, 29);
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: rgb(255, 73, 1);
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Reset Password</h2>
        <form action="reset-pass.php" method="POST">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required>
    <button type="submit" name="reset_password">Reset Password</button>
        </form>

    </div>
    <img src="../images/work-2-removebg-preview-transformed.png" width="350px" alt="Password Reset Image">
</body>
</html>
