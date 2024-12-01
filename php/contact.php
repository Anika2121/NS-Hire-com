<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $adminID = 2; // Hardcoded Admin ID
    $submissionDate = date('Y-m-d H:i:s'); // Current timestamp

    // Insert query
    $query = "INSERT INTO contact (Name, Email, Message, Submission_date, ManagedBy_Admin)
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    // Bind and execute
    $stmt->bind_param("ssssi", $name, $email, $message, $submissionDate, $adminID);
    if ($stmt->execute()) {
        echo "<script>alert('Contact submitted successfully!'); window.location.href = '../index.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" type="image/png" href="/images/favicon.png">

<!-- Bootstrap-5 css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<!-- Font-awsome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Owl-coursel css links -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Own-style -->
 
    <link rel="stylesheet" href="css/style.css">
 <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <style>
        .contact{
            background-color:#03045e;
        }
        input,textarea{
            background-color: transparent !important;
            color: white !important;
            border: 2px solid yellow !important;
        }
      input placeholder{
            color: wheat !important;
        }
        h2{
            color: yellow;
        }
        button{
            background-color: lawngreen !important;
            width: 200px;
            padding: 9px !important;
            margin-top: 5px;
            color: #03045e !important;
        }
        button:hover{
            background-color: #03045e !important;
            color: lawngreen !important;
            border: 2px solid lawngreen !important;
        }
     </style>
    <section class="contact py-5">
        <div class="container">
            <h2 class="text-center mb-4">Contact Us</h2>
            <form action="contact.php" method="POST" class="border p-4 shadow">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="Name" style="color: white;padding-bottom: 3px;">Name :</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="Name" style="color: white;padding-bottom: 3px;">Email :</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label for="Name" style="color: white;padding-bottom: 3px;">Message :</label>
                        <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success">Send Message</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    
    <!-- Footer -->
     <style>
        body {
      background: #111;
    }
    
    div.container-footer.w-container {
      box-sizing: border-box;
      margin-left: auto;
      margin-right: auto;
      max-width: 940px;
      padding-bottom: 40px;
      padding-top: 70px;
    }
    
    div.container-footer.w-container:after {
      clear: both;
      content: " ";
      display: table;
      grid-column-end: 2;
      grid-column-start: 1;
      grid-row-end: 2;
      grid-row-start: 1;
    }
    
    div.container-footer.w-container:before {
      content: " ";
      display: table;
      grid-column-end: 2;
      grid-column-start: 1;
      grid-row-end: 2;
      grid-row-start: 1;
    }
    
    div.w-row {
      box-sizing: border-box;
      margin-left: -10px;
      margin-right: -10px;
    }
    
    div.w-row:after {
      clear: both;
      content: " ";
      display: table;
      grid-column-end: 2;
      grid-column-start: 1;
      grid-row-end: 2;
      grid-row-start: 1;
    }
    
    div.w-row:before {
      content: " ";
      display: table;
      grid-column-end: 2;
      grid-column-start: 1;
      grid-row-end: 2;
      grid-row-start: 1;
    }
    
    div.footer-column.w-clearfix.w-col.w-col-4 {
      box-sizing: border-box;
      float: left;
      min-height: 1px;
      padding-left: 10px;
      padding-right: 10px;
      position: relative;
      width: 33.3333%;
    }
    
    div.footer-column.w-clearfix.w-col.w-col-4:after {
      clear: both;
      content: " ";
      display: table;
      grid-column-end: 2;
      grid-column-start: 1;
      grid-row-end: 2;
      grid-row-start: 1;
    }
    
    div.footer-column.w-clearfix.w-col.w-col-4:before {
      content: " ";
      display: table;
      grid-column-end: 2;
      grid-column-start: 1;
      grid-row-end: 2;
      grid-row-start: 1;
    }
    
    img.failory-logo-image {
      border-width: 0;
      box-sizing: border-box;
      display: inline-block;
      /* float: left; */
      max-width: 100%;
      vertical-align: middle;
    }
    
    h3.footer-failory-name {
      box-sizing: border-box;
      color: #FFFFFF;
      display: block;
      font-family: Lato, sans-serif;
      font-size: 20px;
      font-weight: 900;
      line-height: 1.1em;
      margin-bottom: 10px;
      margin-left: 10px;
      margin-top: 24px;
    }
    
    p.footer-description-failory {
      box-sizing: border-box;
      color: rgba(255, 255, 255, 0.8);
      display: block;
      font-family: Lato, sans-serif;
      font-size: 17px;
      font-weight: 300;
      letter-spacing: .5px;
      line-height: 1.5em;
      margin-bottom: 16px;
      margin-top: 15px;
    }
    
    br {
      box-sizing: border-box;
    }
    
    div.footer-column.w-col.w-col-8 {
      box-sizing: border-box;
      float: left;
      min-height: 1px;
      padding-left: 10px;
      padding-right: 10px;
      position: relative;
      width: 66.6667%;
    }
    
    div.w-col.w-col-8 {
      box-sizing: border-box;
      float: left;
      min-height: 1px;
      padding-left: 0;
      padding-right: 0;
      position: relative;
      width: 66.6667%;
    }
    
    div.w-col.w-col-7.w-col-small-6.w-col-tiny-7 {
      box-sizing: border-box;
      float: left;
      min-height: 1px;
      padding-left: 0;
      padding-right: 0;
      position: relative;
      width: 58.3333%;
    }
    
    h3.footer-titles {
      box-sizing: border-box;
      color: #FFFFFF;
      display: block;
      font-family: Lato, sans-serif;
      font-size: 20px;
      font-weight: 900;
      line-height: 1.1em;
      margin-bottom: 0;
      margin-left: 0;
      margin-top: 24px;
    }
    
    p.footer-links {
      box-sizing: border-box;
      color: rgba(255, 255, 255, 0.8);
      display: block;
      font-family: Lato, sans-serif;
      font-size: 17px;
      font-weight: 300;
      letter-spacing: .5px;
      line-height: 1.8em;
      margin-bottom: 16px;
      margin-top: 2px;
    }
    
    a {
      background-color: transparent;
      box-sizing: border-box;
      color: #FFFFFF;
      font-family: Lato, sans-serif;
      font-size: 17px;
      font-weight: 400;
      line-height: 1.2em;
      text-decoration: none;
    }
    
    a:active {
      outline: 0;
    }
    
    a:hover {
      outline: 0;
    }
    
    span.footer-link {
      box-sizing: border-box;
      color: rgba(255, 255, 255, 0.8);
      font-weight: 300;
    }
    
    span.footer-link:hover {
      color: #FFFFFF;
      font-weight: 400;
    }
    
    span {
      box-sizing: border-box;
    }
    
    strong {
      box-sizing: border-box;
      font-weight: 700;
    }
    
    div.w-col.w-col-5.w-col-small-6.w-col-tiny-5 {
      box-sizing: border-box;
      float: left;
      min-height: 1px;
      padding-left: 0;
      padding-right: 0;
      position: relative;
      width: 41.6667%;
    }
    
    div.column-center-mobile.w-col.w-col-4 {
      box-sizing: border-box;
      float: left;
      min-height: 1px;
      padding-left: 0;
      padding-right: 0;
      position: relative;
      width: 33.3333%;
    }
    
    a.footer-social-network-icons.w-inline-block {
      background-color: transparent;
      box-sizing: border-box;
      color: #FFFFFF;
      display: inline-block;
      font-family: Lato, sans-serif;
      font-size: 17px;
      font-weight: 400;
      line-height: 1.2em;
      margin-right: 8px;
      margin-top: 10px;
      max-width: 100%;
      opacity: .8;
      text-decoration: none;
    }
    
    a.footer-social-network-icons.w-inline-block:active {
      outline: 0;
    }
    
    a.footer-social-network-icons.w-inline-block:hover {
      opacity: 1;
      outline: 0;
    }
    
    img {
      border-width: 0;
      box-sizing: border-box;
      display: inline-block;
      max-width: 100%;
      vertical-align: middle;
    }
    
    p.footer-description {
      box-sizing: border-box;
      color: rgba(255, 255, 255, 0.8);
      display: block;
      font-family: Lato, sans-serif;
      font-size: 17px;
      font-weight: 300;
      letter-spacing: .5px;
      line-height: 1.5em;
      margin-bottom: 16px;
      margin-top: 15px;
    }
    
    strong.link-email-footer {
      box-sizing: border-box;
      font-weight: 700;
    }
     </style>
    <footer>
        <div class="container-footer w-container">
            <div class="w-row">
              <div class="footer-column w-clearfix w-col w-col-4"><img src="images/logo-ai-brush-removebg-z7ewrgm.png" alt="" width="80" class="failory-logo-image">
                <h3 class="footer-failory-name">NS Hire.com</h3>
                <p class="footer-description-failory" style="color: rgb(224, 230, 66) !important;">Search your dream job.<br></p>
              </div>
              <div class="footer-column w-col w-col-8">
                <div class="w-row">
                  <div class="w-col w-col-8">
                    <div class="w-row">
                      <div class="w-col w-col-7 w-col-small-6 w-col-tiny-7">
                        <h3 class="footer-titles">Feature</h3>
                        <p class="footer-links"><a href="" target="_blank"><span class="footer-link">Post Job<br></span>
                        </a><a href=""><span class="footer-link">Apply job<br></span></a><a href="">
                            <span class="footer-link">Submit Application</span></a><span><br></span><a href="">
                                <span class="footer-link">Review application<br></span></a><a href="">
                                    <span class="footer-link">Hire Candidate<br></span></a><a href="">
                                       
                      </div>
                      <div class="w-col w-col-5 w-col-small-6 w-col-tiny-5">
                        <h3 class="footer-titles">Other</h3>
                        <p class="footer-links"><a href="">
                            <span class="footer-link">About Us<br></span>
                        </a><a href=""><span class="footer-link">Blog Post<br></span></a>
                        <a href=""><span class="footer-link"></span></a><a href="">
                            <span class="footer-link">Search Job<br></span></a><a href="">
                                <span class="footer-link">FAQ</span></a><strong><br></strong></p>
                      </div>
                    </div>
                  </div>
                  <div class="column-center-mobile w-col w-col-4">
                    <h3 class="footer-titles">Follow Us!</h3><a href="" target="_blank" class="footer-social-network-icons w-inline-block"><img src="https://uploads-ssl.webflow.com/5966ea9a9217ca534caf139f/5c8dbf0a2f2b67e3b3ba079c_Twitter%20Icon.svg" width="20" alt="Twitter icon"></a><a href="" target="_blank" class="footer-social-network-icons w-inline-block"><img src="https://uploads-ssl.webflow.com/5966ea9a9217ca534caf139f/5c8dbfe70fcf5a0514c5b1da_Instagram%20Icon.svg" width="20" alt="Instagram icon"></a><a href="" target="_blank" class="footer-social-network-icons w-inline-block"><img src="https://uploads-ssl.webflow.com/5966ea9a9217ca534caf139f/5c8dbe42e1e6034fdaba46f6_Facebook%20Icon.svg" width="20" alt="Facebook Icon"></a><a href="" target="_blank" class="footer-social-network-icons w-inline-block"><img src="https://uploads-ssl.webflow.com/5966ea9a9217ca534caf139f/5c8dc0002f2b676eb4ba0869_LinkedIn%20Icon.svg" width="20" alt="LinkedIn Icon"></a><a href="" target="_blank" class="footer-social-network-icons w-inline-block"><img src="https://uploads-ssl.webflow.com/5966ea9a9217ca534caf139f/5c8dc0112f2b6739c9ba0871_Pinterest%20Icon.svg" width="20" alt="Pinterest Icon" class=""></a>
                    <p class="footer-description" style="color: #b2ffa1 !important;">Email me at: <a href="" style="color: #e76f51 !important;"><strong class="link-email-footer">nshire@gmail.com</strong></a><br></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </footer>
</body>
</html>