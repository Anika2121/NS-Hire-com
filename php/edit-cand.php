<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ns_hire');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the ID is set and get the current employee information
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the existing employee data
    $sql = "SELECT * FROM user_info WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if form is submitted to update data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $division = $_POST['division'];
        $district = $_POST['district'];
        
        // Update the employee data
        $updateSql = "UPDATE user_info SET Fname = ?, Lname = ?, Email = ?, Phone = ?, Division = ?, District = ? WHERE UserID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssssssi", $firstName, $lastName, $email, $phone, $division, $district, $id);
        
   

        if ($updateStmt->execute()) {
            // Redirect back to display page with a success message
            // sleep(5);
           // Redirect back to display-employee.php with a success message
header("Location: ../display-candidate.php?update_status=success");
exit;

        } else {
            echo "<p>Error updating record: " . $conn->error . "</p>";
        }
    }

    $stmt->close();
} else {
    echo "No employee ID specified!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <style>
        body {
            position: relative;
            padding: 50px;
            margin: 0 auto;
            background-color: #a2d2ff;
        }
        .box {
            position: absolute;
            top: 100px;
            margin: 50px;
        }
        .i-box input {
            padding: 15px;
            background-color: #caf0f8;
            border: none;
            margin-left: 10px;
        }
        .btn-add {
            position: relative;
            margin-left: 70px;
            padding: 12px;
            width: 100px;
        }
        h2 {
            text-align: center;
        }
        .btn {
  width:200px;
  background-color:rgb(26, 175, 220);
  border: none;
  outline: none;
  height: 49px;
  border-radius: 18px;
  color: #fff;
  text-transform: uppercase;
  font-weight: 600;
  margin: 50px 0;
  margin-left: 70px;
  cursor: pointer;
  transition: 0.5s;
  font-size: 16px;
  position: absolute;
  bottom: 0;
}

.btn:hover {
  background-color: #11fbff;
}
.form-check .form-check-input {
    float:none ;
    margin-left: 0;
    padding-right: 15px;
    margin-right: 5px;
}
    </style>
    <h2>Candidate Information</h2>
    <form  method="POST" enctype="multipart/form-data">
        <div class="row g-3 box">
            <div class="col-6 i-box">
                <input type="text" class="form-control" placeholder="First Name" name="first_name" value="<?php echo $user['Fname']; ?>" required />
            </div>
            <div class="col-6 i-box">
                <input type="text" class="form-control" placeholder="last Name" name="last_name"  value="<?php echo $user['Lname']; ?>" required />
            </div>
            <div class="col-6 i-box">
                <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $user['Email']; ?>" required>
            </div>
            <div class="col-6 i-box">
                <input type="tel" class="form-control" placeholder="Phone" name="phone" value="<?php echo $user['Phone']; ?>" required/>
            </div>
            
            <div class="col-6 i-box">
                <input type="text" class="form-control" placeholder="District" name="district" value="<?php echo $user['District']; ?>" required />
             </div>
            <div class="col-6 i-box">
                <input type="text" class="form-control" placeholder="Division" name="division" value="<?php echo $user['Division']; ?>" required />
            </div>
           
            <!-- <div class="col-6 i-box">
                <input type="file" class="form-control" placeholder="Upload photo" aria-label="Upload photo">
            </div> -->
            
        </div>
        <!-- <button type="submit" class="btn btn-primary btn-add">Add</button> -->
        <input type="submit" class="btn" value="Update candidate" />
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
