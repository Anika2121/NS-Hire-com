<?php
require 'connect.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_email'])) {
    echo "<script>alert('Please log in as an admin.'); window.location.href='admin-login.php';</script>";
    exit;
}

$adminEmail = $_SESSION['admin_email'];

// Fetch AdminID based on the email
$query = "SELECT ID FROM admin WHERE Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $adminEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    $adminID = $admin['ID']; // Retrieve AdminID dynamically
} else {
    echo "<script>alert('Admin not found.'); window.location.href='admin-login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    // Handle file upload for the blog image
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES['blog_image']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    if (isset($_POST['submit'])) {
        $check = getimagesize($_FILES['blog_image']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "<script>alert('File is not an image.');</script>";
            $uploadOk = 0;
        }
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.');</script>";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES['blog_image']['size'] > 2000000) {
        echo "<script>alert('File is too large.');</script>";
        $uploadOk = 0;
    }

    // If file upload is successful
    if ($uploadOk && move_uploaded_file($_FILES['blog_image']['tmp_name'], $targetFile)) {
        // Insert blog post details into the database
        $blogInsert = "INSERT INTO blog (Cont_img, Title, AdminID) VALUES (?, ?, ?)";
        $blogStmt = $conn->prepare($blogInsert);
        $blogStmt->bind_param("ssi", $targetFile, $title, $adminID);
        $blogStmt->execute();

        $postID = $conn->insert_id; // Get the PostID for the inserted blog post

        // Insert the content into BlogByContent table
        $contentInsert = "INSERT INTO blogby_content (PostID, Content) VALUES (?, ?)";
        $contentStmt = $conn->prepare($contentInsert);
        $contentStmt->bind_param("is", $postID, $content);
        $contentStmt->execute();

        echo "<script>alert('Blog post uploaded successfully!'); window.location.href='admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog-post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <style>
        body{
            padding: 30px;
        }
        h2{
            text-align: center;
            color: blue;
        }
    </style>
    <h2>Blog Post</h2>
<form action="blog-post.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title" class="form-label">Blog Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Blog Content</label>
        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
    </div>
    <div class="mb-3">
        <label for="blog_image" class="form-label">Upload Image</label>
        <input type="file" class="form-control" id="blog_image" name="blog_image" required>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Upload Blog</button>
</form>

</body>
</html>