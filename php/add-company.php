<?php
session_start();
require 'connect.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['UserID'])) {
    echo "<script>alert('You need to log in first!'); window.location.href = 'login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyID = $_POST['company_id'];
    $companyName = $_POST['company_name'];
    $userID = $_SESSION['UserID'];

    // Validate company ID and company name
    $companySQL = "SELECT * FROM company WHERE CompanyID = ? AND CompanyName = ?";
    $stmt = $conn->prepare($companySQL);
    $stmt->bind_param("is", $companyID, $companyName);
    $stmt->execute();
    $companyResult = $stmt->get_result();

    if ($companyResult->num_rows === 0) {
        echo "<script>alert('Invalid CompanyID or CompanyName. Please try again.'); window.history.back();</script>";
        exit;
    }

    // If valid, update the employee record with CompanyID
    $updateSQL = "UPDATE employee SET CompanyID = ? WHERE UserID = ?";
    $updateStmt = $conn->prepare($updateSQL);
    $updateStmt->bind_param("ii", $companyID, $userID);

    if ($updateStmt->execute()) {
        echo "<script>alert('Company information updated successfully!'); window.location.href = 'employee-dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to update company information.'); window.history.back();</script>";
    }

    $updateStmt->close();
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Information</title>
</head>
<body>
    <style>
.form{
  padding-top: 30px;
   position: relative;
   margin: 50px;

   padding-bottom: 30px;
   padding-right: 50px;
   padding-leftt: 50px;
   border-radius: 13px;
}
.form input{
  
   padding: 7px 15px;
   margin-top: 30px;
  padding-right: 30px;
  margin-left: 20px;
}
h1{
    text-align:left;
    color: blue;
    margin-left: 50px;
}
.form input label{
    padding: 5px;
}
button{
    background-color: blue;
    border-radius: 5px;
    padding: 10px 20px;
    border: none;
    color: white;
    margin-left: 20px;
}
    </style>
    <div class="form">
    <h1>Enter Your Company Information</h1>
    <form action="add-company.php" method="POST">
        <label for="company_id">Company ID:</label>
        <input type="number" id="company_id" name="company_id" required>

        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" required>

        <button  type="submit">Submit</button>
    </form>
    </div>
   
</body>
</html>
