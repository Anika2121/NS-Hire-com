<?php
require 'connect.php';

// Retrieve the admin's image based on their ID or email
if (isset($_GET['id'])) {
    $adminID = $_GET['id'];

    $query = "SELECT Image FROM admin WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $adminID);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($profilePic);

    if ($stmt->fetch()) {
        // Output the image with appropriate headers
        header("Content-Type: image/jpeg"); // Adjust MIME type as needed
        echo $profilePic;
    } else {
        echo "Image not found.";
    }

    $stmt->close();
}
$conn->close();
?>
