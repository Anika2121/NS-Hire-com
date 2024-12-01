<?php
// Display update or delete success messages if redirected from update or delete operations
if (isset($_GET['update_status']) && $_GET['update_status'] == 'success') {
    echo "<div style='color: green; text-align: center; margin-top: 20px;'>
            Candidate record updated successfully!
          </div>";
}
if (isset($_GET['delete_status']) && $_GET['delete_status'] == 'success') {
    echo "<div style='color: red; text-align: center; margin-top: 20px;'>
            Candidate record deleted successfully!
          </div>";
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS styling */
        .container{
            max-width: 100%;
           background-color: #363635;
          
        }
        .table-container {
            margin: 20px auto;
            width: 80%;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        
            background-color: #363635;
            position: relative;
            top:20px;
            z-index: 100;
        }
        .table-container h1{
            border-radius: 20px;
            padding: 12px;
            background-color: #f8c89e;
            color: #ff5506;
        }
        .table th {
            background-color: #b091ff;
            color: rgb(0, 0, 0);
            padding:0.75rem 1rem;
        }
       .table td{
padding:0.75rem 1rem;
color: rgb(255, 249, 249);
        }
        .table-hover tbody tr:hover {
            background-color: #50504b;
            
        }
        .button-edit{
            margin:10px 0px ;
            
        }
        .button-delete{
            margin-bottom:10px;
        }
    
        .btn-add {
  width:200px;
  background-color:#ff66b3;
  border: none;
  outline: none;
  height: 49px;
  border-radius: 18px;
  color: #fff;
  text-transform: uppercase;
  font-weight: 600;
  margin: 50px 0;
  margin-left: 30px;
  cursor: pointer;
  transition: 0.5s;
  font-size: 16px;
  /* position: absolute; */
  bottom: 0;
  text-align: center;
  padding-top: 10px;
 
}
.btn-add a{
    text-decoration: none !important;
}
.btn-add:hover {
  background-color: #ff8c4f;
  color: white;
  text-decoration: none;
}
.btn-search{
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: none;
 background-color: #cea1ff;
}
.btn-search-sc{
    margin-right: 15px;
   
}
 
    </style>
</head>
<body>
<div class="container-lg-12 ">

    <div class="container table-container">

        <div class="d-flex justify-content-between mb-3 btn-search">
            <a class="btn-add" href="add-candidate.html">Add Candidate</a>
            <!-- <input type="submit" class="btn" value="Add user" /> -->
            <form method="GET" action="display-candidate.php" class="d-flex">
                <input type="text" name="search" class="form-control w-75" placeholder="Search candidate..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary ml-4 btn-search-sc">Search</button>
            </form>
        </div>
        <h1 class="text-center pb-3">Candidate Information</h1>
        
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Nmae</th>
                    <th>Last Nmae</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Division</th>
                    <th>District</th>
                    <th>Register_Date</th>
                    <th>Birthdate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include the PHP script to fetch user data
                include 'php/display-info-candidate.php';

                // Display data if $users array is not empty
                if (!empty($users)) {
                    foreach ($users as $user) {
                        echo "<tr>";
                        echo "<td>{$user['UserID']}</td>";
                        echo "<td>{$user['Fname']}</td>";
                        echo "<td>{$user['Lname']}</td>";
                      
                        echo "<td>{$user['Email']}</td>";
                        echo "<td>{$user['Phone']}</td>";
                        echo "<td>{$user['Division']}</td>";
                        echo "<td>{$user['District']}</td>";
                        echo "<td>{$user['Register_Date']}</td>";
                        echo "<td>{$user['Birth_Date']}</td>";

                        echo "<td><a href='php/edit-cand.php?id={$user['UserID']}' class='btn btn-primary btn-sm button-edit'>Update</a> 
                                  <a href='php/delete-cand.php?id={$user['UserID']}' class='btn btn-danger btn-sm button-delete'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
            </div>
    <!-- Optional Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
