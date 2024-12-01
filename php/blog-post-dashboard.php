<?php
require 'connect.php';

$query = "SELECT b.PostID, b.Cont_img, b.Title, bc.Content 
          FROM Blog b 
          JOIN blogby_content bc ON b.PostID = bc.PostID 
          ORDER BY b.PostID DESC";
$result = $conn->query($query);

$blogs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Blog</title>
</head>
<body>
    <style>
        body{
            background-color:#a3a380;
        }
    h2{
        text-align: center;
        color: white;
        padding: 13px;
    }
        

        
    </style>
<div class="container my-5">
    <h2 class="mb-4">Latest Blog Posts</h2>
    <div class="row">
        <?php foreach ($blogs as $blog): ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="<?php echo $blog['Cont_img']; ?>" class="card-img-top" alt="Blog Image">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($blog['Title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($blog['Content'], 0, 100)) . '...'; ?></p>
                        <a href="blog-detail.php?id=<?php echo $blog['PostID']; ?>" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>