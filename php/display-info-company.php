<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ns_hire');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search query variable
$searchQuery = "";

// Search Query Handling
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $conn->real_escape_string($_GET['search']);
    $searchQuery = " AND (CompanyName LIKE '%$searchTerm%' OR Website LIKE '%$searchTerm%' OR Email LIKE '%$searchTerm%' OR Phone LIKE '%$searchTerm%' OR Location LIKE '%$searchTerm%')";
}

// SQL query to retrieve company data with search filter if any
$queryCompany = "SELECT CompanyID, CompanyName, Website, Email, Phone, Location FROM company WHERE 1 $searchQuery";
$resultCompany = $conn->query($queryCompany);

// Initialize an empty array to store company data
$users = [];

// Check and display results
if ($resultCompany->num_rows > 0) {
    // Fetch all data and store it in $users array
    while ($row = $resultCompany->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    $users = null;  // No results found
}

// Check if users array is populated

// Close the connection
$conn->close();
?>
