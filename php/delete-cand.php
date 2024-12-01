<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ns_hire');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if UserID is set in the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Delete query
    $deleteQuery = "DELETE FROM user_info WHERE UserID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Redirect back to display page with a success message
        // Redirect back to display-employee.php with a success message
header("Location: ../display-candidate.php?delete_status=success");
exit;

    } else {
        echo "Error deleting record: " . $conn->error ;
    }

    $stmt->close();
} else {
    echo "No UserID provided!";
    exit;
}

$conn->close();
?>
