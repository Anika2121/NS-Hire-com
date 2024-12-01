<?php
// Include your database connection file
require 'connect.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $cname = $_POST['cname'];
    $website = $_POST['website'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
    $location = $_POST['location'];
    $compType = $_POST['comp_type'];

    
    // File upload handling (if applicable)
 

    // Insert data into user_info table
    $query = "INSERT INTO company (CompanyName, Website, Email, Phone, Password, Location,  ManagedBy_Admin) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $defaultAdminID = 2; // Or adjust according to your system
    $stmt->bind_param("ssssssi", $cname, $website, $email, $phone, $password, $location,  $defaultAdminID);

    if ($stmt->execute()) {
        // Get the last inserted UserID
        $userId = $stmt->insert_id;

        // Insert data into user_type table with UserType as "Employee"
        $sql_user_type = "INSERT INTO company_by_type (CompanyID, comp_type) VALUES (?, ?)";
        $stmt_user_type = $conn->prepare($sql_user_type);
        $stmt_user_type->bind_param("is", $userId, $compType);

        if ($stmt_user_type->execute()) {
            echo "Employee added successfully!";
          
          
        } else {
            echo "Error adding user type: " . $stmt_user_type->error;
        }
        $stmt_user_type->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
