<?php
session_start();
require 'connect.php';

// Ensure the candidate is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('You must log in as a candidate to view applications.'); window.location.href = 'login.php';</script>";
    exit;
}

$email = $_SESSION['email'];

// Fetch the UserID for the candidate
$candidateID = $_SESSION['UserID'];

// Retrieve the candidate's applications with statuses
$sql = "SELECT a.ApplicationID, j.Job_Title, abs.Status
        FROM application a
        JOIN job j ON a.JobID = j.JobID
        JOIN applicationbystatus abs ON a.ApplicationID = abs.ApplicationID
        WHERE a.UserID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $candidateID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <style>
        body{
            background-color: #132a13;
        }
        h3{
            color:rgb(134, 242, 98);
            text-align: center;
            padding: 20px;
        }
    </style>
    <div class="container mt-5">
        <h3 class="mb-4">My Applications</h3>
        <?php if ($result->num_rows > 0): ?>
            <div   class="row">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div  style="background-color:#ecf39e !important;" class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['Job_Title']); ?></h5>
                                <p class="card-text">
                                    <strong>Status:</strong> 
                                    <span class="badge bg-<?php 
                                        echo $row['Status'] === 'Accepted' ? 'success' : 
                                             ($row['Status'] === 'Rejected' ? 'danger' : 'warning'); ?>">
                                        <?php echo htmlspecialchars($row['Status']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No applications found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
