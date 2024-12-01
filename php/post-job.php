<?php
session_start();
require 'connect.php';

// Check if a company is logged in
if (!isset($_SESSION['CompanyID'])) {
    echo "<script>alert('Only companies can post jobs. Please log in as a company.'); window.location.href = 'login.php';</script>";
    exit;
}

// Retrieve the CompanyID from the session
$companyID = $_SESSION['CompanyID'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle = $_POST['job_title'] ?? null;
    $jobDescription = $_POST['job_description'] ?? null;
    $jobLocation = $_POST['job_location'] ?? null;
    $jobSalary = $_POST['job_salary'] ?? null;
    $category = $_POST['category'] ?? null;

    // Validate form inputs
    if (!$jobTitle || !$jobDescription || !$jobLocation || !$jobSalary || !$category) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit;
    }

    // Insert into `job` table
    $jobInsertSQL = "INSERT INTO job (Job_Title, Description, Location, Salary,CompanyID) VALUES (?, ?, ?,?, ?)";
    $stmt = $conn->prepare($jobInsertSQL);

    if ($stmt) {
        $stmt->bind_param("ssssi", $jobTitle, $jobDescription, $jobLocation, $jobSalary,$companyID);

        if ($stmt->execute()) {
            $jobID = $stmt->insert_id; // Get the inserted JobID

            // Insert into `job_post_by` table
            $jobPostSQL = "INSERT INTO job_post_by (JobID, CompanyID) VALUES (?, ?)";
            $jobPostStmt = $conn->prepare($jobPostSQL);

            if ($jobPostStmt) {
                $jobPostStmt->bind_param("ii", $jobID, $companyID);

                if ($jobPostStmt->execute()) {
                    // Insert into `jobbycategory` table
                    $categoryQuery = "INSERT INTO jobbycategory (JobID, Category) VALUES (?, ?)";
                    $categoryStmt = $conn->prepare($categoryQuery);

                    if ($categoryStmt) {
                        $categoryStmt->bind_param("is", $jobID, $category);

                        if ($categoryStmt->execute()) {
                            echo "<script>alert('Job posted successfully!'); window.location.href = 'job-listing.php';</script>";
                        } else {
                            echo "<script>alert('Error saving category: " . $categoryStmt->error . "'); window.history.back();</script>";
                        }

                        $categoryStmt->close();
                    }
                } else {
                    echo "<script>alert('Error posting job in job_post_by: " . $jobPostStmt->error . "');</script>";
                }

                $jobPostStmt->close();
            }
        } else {
            echo "<script>alert('Error posting job: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        die("Job insertion query preparation failed: " . $conn->error);
    }
}

$conn->close();
?>
