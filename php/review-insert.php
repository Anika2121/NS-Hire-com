<?php
require 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['UserID']; // Assuming user is logged in
    $jobID = $_POST['jobID'];
    $companyID = $_POST['companyID'];
    $reviewComment = $_POST['reviewComment'];
    $rating = $_POST['rating'];
    $reviewDate = date('Y-m-d H:i:s'); // Current timestamp

    // Insert review into `review` table
    $reviewSQL = "INSERT INTO review (UserID, JobID, CompanyID, Review_Comment, Review_Date) 
                  VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($reviewSQL);
    $stmt->bind_param("iiiss", $userID, $jobID, $companyID, $reviewComment, $reviewDate);

    if ($stmt->execute()) {
        $reviewID = $stmt->insert_id; // Get the inserted ReviewID

        // Insert rating into `reviewbyrating` table
        $ratingSQL = "INSERT INTO reviewbyrating (ReviewID, Rating) VALUES (?, ?)";
        $ratingStmt = $conn->prepare($ratingSQL);
        $ratingStmt->bind_param("ii", $reviewID, $rating);

        if ($ratingStmt->execute()) {
            echo "<script>alert('Review submitted successfully!'); window.location.href = 'review-insert.php';</script>";
        } else {
            echo "Error inserting rating: " . $ratingStmt->error;
        }
        $ratingStmt->close();
    } else {
        echo "Error inserting review: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Reviews</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
        }

        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <h2 class="text-center mb-4">Leave a Review</h2>
        <form action="review-insert.php" method="POST" class="border p-4 shadow">
            <div class="mb-3">
                <label for="jobID" class="form-label">Job</label>
                <select name="jobID" id="jobID" class="form-select" required>
                    <option value="">Select Job</option>
                    <!-- Populate dynamically with PHP -->
                    <?php
                    require 'connect.php';
                    $result = $conn->query("SELECT JobID, Job_Title FROM job");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['JobID']}'>{$row['Job_Title']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="companyID" class="form-label">Company</label>
                <select name="companyID" id="companyID" class="form-select" required>
                    <option value="">Select Company</option>
                    <!-- Populate dynamically with PHP -->
                    <?php
                    $result = $conn->query("SELECT CompanyID, CompanyName FROM company");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['CompanyID']}'>{$row['CompanyName']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="reviewComment" class="form-label">Review Comment</label>
                <textarea name="reviewComment" id="reviewComment" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3 star-rating">
                <label>Rating:</label><br>
                <input type="radio" name="rating" id="star5" value="5"><label for="star5">&#9733;</label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4">&#9733;</label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3">&#9733;</label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2">&#9733;</label>
                <input type="radio" name="rating" id="star1" value="1"><label for="star1">&#9733;</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
</body>
</html>
