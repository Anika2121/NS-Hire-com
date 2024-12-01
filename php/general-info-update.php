<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['UserID']) || !isset($_SESSION['user_type'])) {
    echo "<script>alert('Please log in first!'); window.location.href = 'login.php';</script>";
    exit;
}

$userID = $_SESSION['UserID'];
$userType = $_SESSION['user_type'];

// Fetch general user info
$sql = "SELECT * FROM user_info WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone'];
$district= $_POST['district'];
$division= $_POST['division'];
    $updateSQL = "UPDATE user_info SET FName = ?, LName = ?, Phone = ?, District=? ,Division=? WHERE UserID = ?";
    $updateStmt = $conn->prepare($updateSQL);
    $updateStmt->bind_param("sssssi", $firstName, $lastName, $phone,$district,$division, $userID);

    if ($updateStmt->execute()) {
        // echo "<script>alert('General information updated successfully!'); window.location.href = 'candidate-dashboard.php';</script>";
     if ($userType === 'Candidate') {
        // Redirect to the candidate dashboard if user type is Candidate
        header("Location: candidate-dashboard.php");
       
    }
} else if ($userType === "Employee") {
    header("Location:employee-dashboard.php");
    exit;
    }
}
    else {
        echo "<script>alert('Failed to update general information!');</script>";
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
    <h3>Update General Information</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['Fname']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['Lname']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['Phone']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="district" class="form-label">District</label>
            <input type="text" name="district" id="district" value="<?php echo htmlspecialchars($user['District']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="division" class="form-label">Division</label>
            <input type="text" name="division" id="division" value="<?php echo htmlspecialchars($user['Division']); ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
</body>
</html>
