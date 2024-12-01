<?php
session_start();
require 'connect.php';

// Ensure the employee is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('You must log in as an employee to update applications.'); window.location.href = 'login.php';</script>";
    exit;
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $applicationID = $_POST['application_id'] ?? '';
    $status = $_POST['status'] ?? '';

    // Validate the form data
    if (empty($applicationID) || empty($status)) {
        echo "<script>alert('Invalid form data.'); window.history.back();</script>";
        exit;
    }

    // Prepare the SQL query to update the status
    $sql = "UPDATE applicationbystatus SET Status = ? WHERE ApplicationID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $applicationID); // "s" for string, "i" for integer

    if ($stmt->execute()) {
        // Successful update
        echo "<script>alert('Application status updated successfully.'); window.location.href = 'employee-dashboard.php';</script>";
    } else {
        // Error during update
        echo "<script>alert('Failed to update status.'); window.history.back();</script>";
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect if the request is not POST
    echo "<script>alert('Invalid request method.'); window.location.href = 'employee-dashboard.php';</script>";
}

// Close the database connection
$conn->close();
?>
