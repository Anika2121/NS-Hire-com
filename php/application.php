<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'Candidate') {
  header("Location: user-login.php");
  exit;
}

$jobID = isset($_GET['job_id']) && is_numeric($_GET['job_id']) ? intval($_GET['job_id']) : null;

if (!$jobID) {
    echo "<script>alert('Invalid or missing Job ID.'); window.location.href = 'job-listing.php';</script>";
    exit;
}


if (!isset($_SESSION['UserID'])) {
    echo "<script>alert('Session variables are not properly set.'); window.location.href = 'candidate-dashboard.php';</script>";
    exit;
}

$email = $_SESSION['email'];
$userID = $_SESSION['UserID'];

// Assuming CompanyID is fetched dynamically for the given JobID
$stmt = $conn->prepare("SELECT CompanyID FROM job WHERE JobID = ?");
$stmt->bind_param("i", $jobID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $companyID = $result->fetch_assoc()['CompanyID'];
} else {
    echo "<script>alert('Company not found for the given job.'); window.location.href = 'candidate-dashboard.php';</script>";
    exit;
}
if (!$companyID) {
  echo "<script>alert('Invalid comapny ID.'); window.location.href = 'job-listing.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'] ?? '';
    $resumeFile = $_FILES['resume']['name'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($resumeFile);

    $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $fileType = mime_content_type($_FILES['resume']['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        echo "<script>alert('Invalid file type. Only PDF and DOC files are allowed.'); window.history.back();</script>";
        exit;
    }

    if (move_uploaded_file($_FILES['resume']['tmp_name'], $targetFile)) {
        $applicationSQL = "
            INSERT INTO application (Resume_File, UserID, JobID, CompanyID, Description)
            VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($applicationSQL);
        $stmt->bind_param("siiis", $resumeFile, $userID, $jobID, $companyID, $description);

        if ($stmt->execute()) {
            $applicationID = $stmt->insert_id;

            $statusSQL = "INSERT INTO applicationbystatus (ApplicationID, Status) VALUES (?, 'Pending')";
            $statusStmt = $conn->prepare($statusSQL);
            $statusStmt->bind_param("i", $applicationID);
            $statusStmt->execute();

            echo "<script>alert('Application submitted successfully!'); window.location.href = 'candidate-dashboard.php';</script>";
        } else {
            echo "Error submitting application: " . $conn->error;
        }
    } else {
        echo "Failed to upload resume.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   
<!-- Bootstrap-5 css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <section class="vh-100" style="background-color: #2779e2;">
        <div class="container h-100">
        <form action="application.php?job_id=<?php echo htmlspecialchars($jobID); ?>" method="POST" enctype="multipart/form-data">

          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-9">
      
              <h1 class="text-white mb-3 p-2">Apply for a job</h1>
      
              <div class="card" style="border-radius: 15px;">
                <div class="card-body">
      
                  <div class="row align-items-center pt-4 pb-3">
                    <div class="col-md-3 ps-5">
      
                      <h6 class="mb-0">Full name</h6>
      
                    </div>
                    <div class="col-md-9 pe-5">
      
                      <input type="text" name="fname" class="form-control form-control-lg" />
      
                    </div>
                  </div>
      
                  <hr class="mx-n3">
      
                  <div class="row align-items-center py-3">
                    <div class="col-md-3 ps-5">
      
                      <h6 class="mb-0">Email address</h6>
      
                    </div>
                    <div class="col-md-9 pe-5">
      
                      <input type="email" name="email" class="form-control form-control-lg" placeholder="example@example.com" />
      
                    </div>
                  </div>
      
                  <hr class="mx-n3">
      
                  <div class="row align-items-center py-3">
                    <div class="col-md-3 ps-5">
      
                      <h6 class="mb-0">Description</h6>
      
                    </div>
                    <div class="col-md-9 pe-5">
      
                      <textarea class="form-control" rows="3" placeholder="Message sent to the employer" name="description"></textarea>
      
                    </div>
                  </div>
      
                  <hr class="mx-n3">
      
                  <div class="row align-items-center py-3">
                    <div class="col-md-3 ps-5">
      
                      <h6 class="mb-0">Upload CV</h6>
      
                    </div>
                    <div class="col-md-9 pe-5">
      
                      <input class="form-control form-control-lg" name="resume" id="formFileLg" type="file" />
                      <div class="small text-muted mt-2">Upload your CV/Resume or any other relevant file. Max file
                        size 50 MB</div>
      
                    </div>
                  </div>
      
                  <hr class="mx-n3">
      
                  <div class="px-5 py-4">
                   
                    <button type="submit" class="btn btn-primary btn-lg">Send application</button>

                    
                  </div>
      
                </div>
              </div>
      
            </div>
          </div>
          </form>
        </div>
      </section>
</body>
</html>