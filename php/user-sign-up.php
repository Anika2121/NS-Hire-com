
<?php

require 'connect.php';
// print_r($_POST);
// print_r($_FILES);

// Collect form data
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
$district = $_POST['district'];
$division = $_POST['division'];
$birthDate = $_POST['bdate'];
$userType = $_POST['user_type']; // Capture the UserType (Employee or Candidate)

// File upload handling
$profilePic = null;
if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
    $filename = $_FILES['profile']['name'];
    $tempname = $_FILES['profile']['tmp_name'];
    $folder = 'uploads/' . $filename;

    // Move file to the uploads directory and read it as binary data (BLOB)
    if (move_uploaded_file($tempname, $folder)) {
        // echo "File uploaded successfully.";
        $profilePic = file_get_contents($folder); // For storing as BLOB
    } else {
        echo "File upload failed.";
    }
} else {
    echo "No file uploaded or there was an error.";
}

// Default AdminID value
$defaultAdminID = 1;

// Insert query with placeholders for security
$query = "INSERT INTO user_info (Fname, Lname, Email, Phone, Password, District, Division, Birth_Date, Profile_Pic, ManagedBy_Admin) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);

// Bind the parameters
$stmt->bind_param("sssssssssi", $fname, $lname, $email, $phone, $password, $district, $division, $birthDate, $profilePic, $defaultAdminID);

// Execute the query and check for success
if ($stmt->execute()) {
    // echo "New user registered successfully!";
    
    // Get the last inserted UserID
    $userId = $stmt->insert_id;

    // Insert data into user_type table with UserType
    $sql_user_type = "INSERT INTO user_by_type (UserID, User_Type) VALUES ('$userId', '$userType')";
    
    if (mysqli_query($conn, $sql_user_type)) {
      
        header("Location: ../sign-up-in-user.html");
    } else {
        echo "Error inserting into user_type: " . mysqli_error($conn);
    }
} else {
    echo "Error inserting into user_info: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

