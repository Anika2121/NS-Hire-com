


<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ns_hire');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve user data
//Dispaly data========

$queryEmployee = "SELECT u.UserID, u.Fname, u.Lname, u.Email, u.Phone, u.District, u.Division,u.Register_Date, u.Birth_Date 
                  FROM user_info u 
                  INNER JOIN user_by_type t ON u.UserID = t.UserID
                  WHERE t.User_Type = 'Employee'";
$resultEmployee = $conn->query( $queryEmployee);

// Initialize an empty array to store user data
$users = [];

if ($resultEmployee->num_rows > 0) {
    // Fetch all data and store it in $users array
    while ($row = $resultEmployee->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    $users = null;
}

// Search Query------------

$searchQuery = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $conn->real_escape_string($_GET['search']);
    $searchQuery = " AND (u.Fname LIKE '%$searchTerm%' OR u.Lname LIKE '%$searchTerm%' OR u.Email LIKE '%$searchTerm%' OR u.Phone LIKE '%$searchTerm%' OR u.District LIKE '%$searchTerm%' OR u.Division LIKE '%$searchTerm%')";
}

// SQL query to retrieve employee data with search filtering
$queryEmployee = "SELECT u.UserID, u.Fname, u.Lname, u.Email, u.Phone, u.District, u.Division, u.Register_Date, u.Birth_Date 
                  FROM user_info u 
                  INNER JOIN user_by_type t ON u.UserID = t.UserID
                  WHERE t.User_Type = 'Employee' $searchQuery";
$resultEmployee = $conn->query($queryEmployee);

// Check and display results
$users = [];
if ($resultEmployee->num_rows > 0) {
    while ($row = $resultEmployee->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    $users = null;
}

// Close the connection
$conn->close();
?>
