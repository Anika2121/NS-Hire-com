
<?php
session_start();

if (!isset($_SESSION['email'])) {
    // Redirect to login if email is not set
    header("Location: user-login.php");
    exit;
}

require 'connect.php';

// Fetch logged-in user's email from session
$email = $_SESSION['email'];

// Query to fetch user and associated company details
$sql = "
    SELECT u.Fname, u.Lname, u.Email, u.Phone, u.District, u.Division, t.User_Type, e.CompanyID, c.CompanyName
    FROM user_info u
    JOIN user_by_type t ON u.UserID = t.UserID
    LEFT JOIN employee e ON u.UserID = e.UserID
    LEFT JOIN company c ON e.CompanyID = c.CompanyID
    WHERE u.Email = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>alert('User not found. Please contact support.'); window.location.href = 'user-login.php';</script>";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="dashboard-header">
        <h1>Welcome, <?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?>!</h1>
        <p>Email: <?php echo htmlspecialchars($user['Email']); ?></p>
        <p>Phone: <?php echo htmlspecialchars($user['Phone']); ?></p>
        <p>District: <?php echo htmlspecialchars($user['District']); ?></p>
        <p>Division: <?php echo htmlspecialchars($user['Division']); ?></p>
        <?php if (!empty($user['CompanyName'])): ?>
            <p>Company: <?php echo htmlspecialchars($user['CompanyName']); ?></p>
        <?php endif; ?>
    </header>

    <!-- Dashboard Content -->
    <main class="dashboard-main">
        <!-- Existing dashboard content remains here -->
    </main>
</body>
</html>
