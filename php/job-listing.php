<?php
// Start session for user authentication
session_start();
require 'connect.php';

// Initialize filter variables
$location = isset($_GET['location']) ? $_GET['location'] : '';
$salary = isset($_GET['salary']) ? (int)$_GET['salary'] : 0;
$title = isset($_GET['title']) ? $_GET['title'] : '';

// Build the base SQL query
$sql = "SELECT * FROM job WHERE 1=1";

// Add filters to the query
if (!empty($location)) {
    $sql .= " AND Location LIKE ?";
}
if ($salary > 0) {
    $sql .= " AND Salary >= ?";
}
if (!empty($title)) {
    $sql .= " AND Job_Title LIKE ?";
}

// Prepare the statement
$stmt = $conn->prepare($sql);
$params = [];

// Bind parameters based on filters
if (!empty($location)) {
    $params[] = "%$location%";
}
if ($salary > 0) {
    $params[] = $salary;
}
if (!empty($title)) {
    $params[] = "%$title%";
}

// Bind parameters dynamically
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}


// Execute the query
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link rel="stylesheet" href="../css/post-job.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *{
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}


body{
    background:#eee;
}

.card {
    box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: 1rem;
}
h3{
    padding: 20px;
}
.card-body {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.5rem 1.5rem;
}
.avatar-text {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    background: rgb(251, 63, 245);
    background: radial-gradient(circle, rgba(63,94,251,1) 0%, rgba(252,70,107,1) 100%);
    color: #fafcff;
    font-weight: 700;
}

.avatar {
    width: 3rem;
    height: 3rem;
}
.rounded-3 {
    border-radius: 0.5rem!important;
}
.mb-2 {
    margin-bottom: 0.5rem!important;
}
.me-4 {
    margin-right: 1.5rem!important;
}
        .filter-container {
            position: relative;
        background-color: #f8f9fa;
        padding: 40px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .filter-container form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .filter-container h3 {
      position: absolute;
        margin-bottom: 30px;
        font-size: 1.5rem;
      right:20px;
        color: #0056b3;
        font-size: 40px;
       
    }
        .filter-container form input,
        .filter-container form button {
            padding: 10px;
        border: 1px solid #b3b2f2;
        border-radius: 5px;
        font-size: 1rem;
        }
        .filter-container form button {
        background-color: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
        width: 150px;
    }
    .filter-container form button:hover {
        background-color: #0056b3;
    }

    </style>
</head>
<body>
    <div class="container">
        <div class="filter-container">
            <h3>Filter Jobs</h3>
            <form action="job-listing.php" method="GET">
                <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($location); ?>">
                <input type="number" name="salary" placeholder="Minimum Salary" value="<?php echo htmlspecialchars($salary); ?>">
                <input type="text" name="title" placeholder="Job Title" value="<?php echo htmlspecialchars($title); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="text-center mb-5">
            <h3>Job Openings</h3>
            <p class="lead">Find your dream job with us</p>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-lg-row">
                            <span class="avatar avatar-text rounded-3 me-4 mb-2">NS</span>
                            <div class="row flex-fill">
                                <div class="col-sm-5">
                                    <h4 class="h5"><?php echo htmlspecialchars($row['Job_Title']); ?></h4>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($row['Location']); ?></span>
                                    <span class="badge bg-success">$<?php echo number_format($row['Salary'], 2); ?></span>
                                </div>
                                <div class="col-sm-4 py-2">
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($row['Category'] ?? 'N/A'); ?></span>
                                    <p><?php echo htmlspecialchars($row['Description']); ?></p>
                                </div>
                                <div class="col-sm-3 text-lg-end">
                                <a href="application.php?job_id=<?php echo htmlspecialchars($row['JobID']); ?>" class="btn btn-primary">Apply</a>




                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No jobs found. Please check back later.</p>
        <?php endif; ?>

    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
