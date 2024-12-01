<?php
// Include your database connection file
require 'connect.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
    $district = $_POST['district'];
    $division = $_POST['division'];
    $birthDate = $_POST['bdate'];
    $userType = "Candidate"; // Set user type to "Employee"
    
    // File upload handling (if applicable)
    $profilePic = null;
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['profile']['name'];
        $tempname = $_FILES['profile']['tmp_name'];
        $folder = 'uploads/' . $filename;
        if (move_uploaded_file($tempname, $folder)) {
            $profilePic = file_get_contents($folder); // For storing as BLOB
        } else {
            echo "File upload failed.";
        }
    }

    // Insert data into user_info table
    $query = "INSERT INTO user_info (Fname, Lname, Email, Phone, Password, District, Division, Birth_Date,  ManagedBy_Admin) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $defaultAdminID = 1; // Or adjust according to your system
    $stmt->bind_param("ssssssssi", $fname, $lname, $email, $phone, $password, $district, $division, $birthDate,  $defaultAdminID);

    if ($stmt->execute()) {
        // Get the last inserted UserID
        $userId = $stmt->insert_id;

        // Insert data into user_type table with UserType as "Employee"
        $sql_user_type = "INSERT INTO user_by_type (UserID, User_Type) VALUES (?, ?)";
        $stmt_user_type = $conn->prepare($sql_user_type);
        $stmt_user_type->bind_param("is", $userId, $userType);

        if ($stmt_user_type->execute()) {
            // echo "Employee added successfully!";
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
