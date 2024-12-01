<?php
// Display update or delete success messages if redirected from update or delete operations
if (isset($_GET['update_status']) && $_GET['update_status'] == 'success') {
    echo "<div style='color: green; text-align: center; margin-top: 20px;'>
            Company record updated successfully!
          </div>";
}
if (isset($_GET['delete_status']) && $_GET['delete_status'] == 'success') {
    echo "<div style='color: red; text-align: center; margin-top: 20px;'>
            Company record deleted successfully!
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
           
        }
        .table-container {
            margin: 20px auto;
            width: 80%;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
       
            background-color: rgb(11, 0, 40);
            position: relative;
            top:20px;
        }
        .table th {
            padding:0.75rem 1rem;
            background-color: rgb(255, 141, 144);
            color: rgb(221, 1, 1);
        }
        
       .table td{
padding:0.75rem 1rem;
color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #23212e;
           
        }
        .button-edit{
            margin:10px 0px;
            
        }
        .button-delete{
            margin-bottom:10px;
        }
        .table-container h1{
            border-radius: 20px;
            padding: 12px;
            background-color: #728df7;
            color: #020656;
        }
        .btn-add {
  width:200px;
  background-color:rgb(255, 99, 117);
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
 z-index: 100;
}
.btn-add a{
    text-decoration: none !important;
}
.btn-add:hover {
  background-color:rgb(71, 83, 255);
  color: white;
  text-decoration: none;
}
.btn-search{
    display: flex;
    align-items: center;
    justify-content: space-between;
   background-color: #021438;
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
            <a class="btn-add" href="add-company.html">Add Company</a>
            <!-- <input type="submit" class="btn" value="Add user" /> -->
            <!-- <input type="text" class="form-control w-25" placeholder="Search..."> -->
            <form method="GET" action="display-company.php" class="d-flex">
                <input type="text" name="search" class="form-control w-75" placeholder="Search company..." value="<?php echo isset($_GET['search']) ? ($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary ml-4 btn-search-sc">Search</button>
            </form>
            
        </div>
        <h1 class="text-center pb-3">Company Information</h1>
        
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>CompanyID</th>
                    <th>Company Name</th>
                    <th>Website</th>
                    <th>Email</th>
                    <th>Phone</th>
                   
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include the PHP script to fetch user data
                include 'php/display-info-company.php';


                if (!empty($users)) {
                    foreach ($users as $user) {
                        echo "<tr>";
                        echo "<td>{$user['CompanyID']}</td>";
                        echo "<td>{$user['CompanyName']}</td>"; // Display Company Name
                        echo "<td>{$user['Website']}</td>";     // Display Website
                        echo "<td>{$user['Email']}</td>";       // Display Email
                        echo "<td>{$user['Phone']}</td>";       // Display Phone
                        echo "<td>{$user['Location']}</td>";    // Display Location
                        echo "<td><a href='php/edit-comp.php?id={$user['CompanyID']}' class='btn btn-primary btn-sm'>Update</a>
                                  <a href='php/delete-comp.php?id={$user['CompanyID']}' class='btn btn-danger btn-sm'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No company records found.</td></tr>";
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
