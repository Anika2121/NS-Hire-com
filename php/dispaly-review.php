<?php
require 'connect.php';

$query = "SELECT r.Review_Comment, r.Review_Date, rb.Rating, j.Job_Title, c.CompanyName
          FROM review r
          JOIN reviewbyrating rb ON r.ReviewID = rb.ReviewID
          JOIN job j ON r.JobID = j.JobID
          JOIN company c ON r.CompanyID = c.CompanyID
          ORDER BY r.Review_Date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <style>
        body{
            background-color: #086788;
           
        }
        .card-body{
            background-color: #b5e2fa;
           
        }
        .card-title{
            color:#086788;
        }
        .card-subtitle{
color:#c9184a !important;
        }
        h2{
            color:white;
        }
    </style>
    <div class="container my-4">
        <h2 class="text-center mb-4">Reviews</h2>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['Job_Title']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($row['CompanyName']); ?></h6>
                            <p class="card-text"><?php echo htmlspecialchars($row['Review_Comment']); ?></p>
                            <p class="card-text">
                                <strong>Rating:</strong> 
                                <?php echo str_repeat("&#9733;", $row['Rating']) . str_repeat("&#9734;", 5 - $row['Rating']); ?>
                            </p>
                            <small class="text-muted">Reviewed on <?php echo htmlspecialchars($row['Review_Date']); ?></small>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
