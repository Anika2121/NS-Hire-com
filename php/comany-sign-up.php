
<?php

require 'connect.php';
// print_r($_POST);
// print_r($_FILES);

// Collect form data
$cname = $_POST['cname'];
$website = $_POST['website'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$location = $_POST['location']; 
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$compType = $_POST['comp_type']; 



$defaultAdminID = 2;

// Insert query with placeholders for security
$query = "INSERT INTO company (CompanyName, Website, Email, Phone,Password, Location, ManagedBy_Admin) 
          VALUES (?, ?, ?, ?, ?, ?,?)";

$stmt = $conn->prepare($query);

// Bind the parameters
$stmt->bind_param("ssssssi", $cname, $website, $email, $phone,$password,$location,  $defaultAdminID);

// Execute the query and check for success
if ($stmt->execute()) {
    // echo "New user registered successfully!";
    
    // Get the last inserted UserID
    $compId = $stmt->insert_id;

    // Insert data into user_type table with UserType
    $sql_comp_type = "INSERT INTO company_by_type (CompanyID, comp_type) VALUES ('$compId', '$compType')";
    
    if (mysqli_query($conn, $sql_comp_type)) {
        // echo "Signup successful!";
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

