<?php
session_start();
require 'connect.php';

if ($_SESSION['user_type'] !== 'Employee') {
    echo "<script>alert('Unauthorized access!'); window.location.href = 'login.php';</script>";
    exit;
}

$userID = $_SESSION['UserID'];

// Fetch employee-specific info
$sql = "SELECT * FROM employee WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyID = $_POST['company_id'];
   

    $updateSQL = "UPDATE employee SET CompanyID = ? WHERE UserID = ?";
    $updateStmt = $conn->prepare($updateSQL);
    $updateStmt->bind_param("ii",$companyID,$userID);

    if ($updateStmt->execute()) {
        echo "<script>alert('Employee-specific information updated successfully!'); window.location.href = 'employee-dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to update employee information!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Update Company ID :</h3>
    <form method="POST">
        <div class="mb-3">
        <label for="company_id">Company ID:</label>
        <input type="number" id="company_id" name="company_id" value="<?php echo htmlspecialchars($employee['CompanyID']); ?>" class="form-control" required>

        </div>
     
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
</body>
</html>