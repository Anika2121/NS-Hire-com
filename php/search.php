<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['email'])) {
    echo "<script>alert('You must log in as an employee.'); window.location.href = 'login.php';</script>";
    exit;
}

$employeeID = $_SESSION['UserID'];

// Fetch company details for the logged-in employee
$sql = "SELECT c.CompanyID, c.CompanyName, c.Location,c.Website,c.Phone,c.Email
        FROM company c
        JOIN employee e ON c.CompanyID = e.CompanyID
        WHERE e.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-4">
        <h3>Welcome, Employee</h3>
        <h5>Company Details</h5>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($company['CompanyName']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($company['Location']); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($company['Description']); ?></p>
        <a href="manage-jobs.php" class="btn btn-primary">Manage Jobs</a>
        <a href="manage-applications.php" class="btn btn-secondary">View Applications</a>
    </div>
</body>
</html>
