<?php
session_start();
require 'connect.php';

// Ensure the employee is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('You must log in as an employee to view applications.'); window.location.href = 'login.php';</script>";
    exit;
}

$email = $_SESSION['email'];

// Get employee ID based on the logged-in email
$employeeID = $_SESSION['UserID']; // Assuming this is already stored in the session

// Fetch the CompanyID associated with the employee
$companySQL = "SELECT CompanyID FROM employee WHERE UserID = ?";
$companyStmt = $conn->prepare($companySQL);
$companyStmt->bind_param("i", $employeeID);
$companyStmt->execute();
$companyResult = $companyStmt->get_result();

// If no company found for the employee, exit
if ($companyResult->num_rows === 0) {
    echo "<script>alert('No company found for this employee.'); window.location.href = 'employee-dashboard.php';</script>";
    exit;
}

$company = $companyResult->fetch_assoc();
$companyID = $company['CompanyID'];

// Fetch applications for jobs posted by the employee's company
$sql = "SELECT a.ApplicationID, j.Job_Title, a.Resume_File, abs.Status
        FROM application a
        JOIN job j ON a.JobID = j.JobID
        JOIN applicationbystatus abs ON a.ApplicationID = abs.ApplicationID
        JOIN candidate c ON a.UserID = c.UserID
        WHERE j.CompanyID = ?"; // Using ? to bind CompanyID dynamically

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $companyID); // Bind the company ID for the query
$stmt->execute();
$result = $stmt->get_result();



?>


  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <style>
        h5{
            color: blue !important;
        }
        h6{
            color:gray;
        }
    </style>
    <div class="container my-4">
        <h3 class="text-center mb-4">Applications for Your Company</h3>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['Job_Title']); ?></h5>
                                <h6 style="padding:8px 2px;" class="card-subtitle mb-2 text-muted">Application ID :<?php echo htmlspecialchars($row['ApplicationID']); ?></h6>
                                <p class="card-text">Status: <strong><?php echo htmlspecialchars($row['Status']); ?></strong></p>
                                <p class="card-text">
                                    <a href="uploads/<?php echo htmlspecialchars($row['Resume_File']); ?>" target="_blank" class="btn btn-primary btn-sm">View Resume</a>
                                </p>

                                <form action="update-status.php" method="POST">
                                    <input type="hidden" name="application_id" value="<?php echo $row['ApplicationID']; ?>">
                                    <select name="status" class="form-select mb-2">
                                        <option  value="Pending" <?php echo $row['Status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Accepted" <?php echo $row['Status'] === 'Accepted' ? 'selected' : ''; ?>>Accepted</option>
                                        <option value="Rejected" <?php echo $row['Status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                    <button type="submit" class="btn btn-success btn-sm">Update Status</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No applications found for this company.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the statement and connection
$stmt->close();
$companyStmt->close();
$conn->close();
?>
