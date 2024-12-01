<?php
// Include Composer's autoload file
// require __DIR__ . '/../vendor/autoload.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // Get the email from the POST request
//     $email = isset($_POST['email']) ? $_POST['email'] : null;

//     // Check if email is provided
//     if (!$email) {
//         die('Email is required to send the reset link.');
//     }

//     // Database connection
//     $servername = "localhost";
//     $username = "root";
//     $password = "";
//     $dbname = "ns_hire";

//     $conn = new mysqli($servername, $username, $password, $dbname);

//     // Check connection
//     if ($conn->connect_error) {
//         die("Connection failed: " . $conn->connect_error);
//     }

//     // Generate a reset token
//     $resetToken = bin2hex(random_bytes(16));

//     // Check if email exists and update the token
//     $stmt = $conn->prepare("UPDATE admin SET reset_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE Email = ?");
//     $stmt->bind_param('ss', $resetToken, $email);

//     if ($stmt->execute() && $stmt->affected_rows > 0) {
//         $stmt->close();

//         // Send email
//         $mail = new PHPMailer(true);

//         try {
//             // Debug settings
//             $mail->SMTPDebug = 0; // Set to 0 for no debug output
//             $mail->Debugoutput = 'html'; // Debug output format

//             // Server settings
//             $mail->isSMTP();
//             $mail->Host = 'smtp.gmail.com';
//             $mail->SMTPAuth = true;
//             $mail->Username = 'anikahossain544@gmail.com'; // Replace with your email
//             $mail->Password = 'pkce fqsi dqoq dfof'; // Replace with your email password or app-specific password
//             $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//             $mail->Port = 587;

//             // Sender and recipient settings
//             $mail->setFrom('noreply@nshire.com', 'Nshire');
//             $mail->addAddress($email);

//             // Email content
//             $resetLink = "http://localhost/nshire/php/reset-pass.php?token=" . $resetToken;
//             $mail->isHTML(true);
//             $mail->Subject = 'Password Reset Request';
//             $mail->Body = "Hello,<br><br>
//                            We received a request to reset your password. Please click the link below to reset your password:<br>
//                            <a href='$resetLink'>$resetLink</a><br><br>
//                            If you did not request this, please ignore this email.<br><br>
//                            Regards,<br>
//                            Nshire Team";

//             // SMTP Options
//             $mail->SMTPOptions = array(
//                 'ssl' => array(
//                     'verify_peer' => false,
//                     'verify_peer_name' => false,
//                     'allow_self_signed' => true,
//                 ),
//             );

//             // Send email
//             $mail->send();
//             echo 'Email sent successfully!';
//         } catch (Exception $e) {
//             echo "Failed to send email: {$mail->ErrorInfo}";
//         }
//     } else {
//         echo 'Failed to send reset link. Please check the email address.';
//     }

//     $conn->close();
// }



// Database connection
// $conn = new mysqli('localhost', 'root', '', 'ns_hire');
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // Check if form is submitted
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $email = $_POST['email'];

//     // Step 1: Identify user type (admin, user, or company)
//     $userType = null;
//     $tableName = null;

//     // Check in admin table
//     $stmt = $conn->prepare("SELECT ID FROM admin WHERE Email = ?");
//     $stmt->bind_param("s", $email);
//     $stmt->execute();
//     $adminResult = $stmt->get_result();
//     if ($adminResult->num_rows > 0) {
//         $userType = 'admin';
//         $tableName = 'admin';
//     }

//     // Check in user_info table
//     if (!$userType) {
//         $stmt = $conn->prepare("SELECT UserID FROM user_info WHERE Email = ?");
//         $stmt->bind_param("s", $email);
//         $stmt->execute();
//         $userResult = $stmt->get_result();
//         if ($userResult->num_rows > 0) {
//             $userType = 'user';
//             $tableName = 'user_info';
//         }
//     }

//     // Check in company table
//     if (!$userType) {
//         $stmt = $conn->prepare("SELECT CompanyID FROM company WHERE Email = ?");
//         $stmt->bind_param("s", $email);
//         $stmt->execute();
//         $companyResult = $stmt->get_result();
//         if ($companyResult->num_rows > 0) {
//             $userType = 'company';
//             $tableName = 'company';
//         }
//     }

//     // Step 2: Handle cases where the email is not found
//     if (!$userType) {
//         echo "<script>alert('Email not found in our records.'); window.location.href = '../forgot-password.html';</script>";
//         exit;
//     }

//     // Step 3: Generate reset token and expiry time
//     $resetToken = bin2hex(random_bytes(16));
//     $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valid for 1 hour

//     // Update the corresponding table with the reset token and expiry
//     $stmt = $conn->prepare("UPDATE $tableName SET reset_token = ?, token_expiry = ? WHERE Email = ?");
//     $stmt->bind_param("sss", $resetToken, $expiryTime, $email);
//     if ($stmt->execute()) {
//         // Step 4: Send reset password email
//         $resetLink = "http://localhost/nshire/php/reset-pass.php?token=$resetToken";

//         // Configure PHPMailer or mail() function to send the reset email
//         $subject = "Password Reset Request";
//         $message = "Click the following link to reset your password: $resetLink";
//         $headers = "From: no-reply@nshire.com";

//         if (mail($email, $subject, $message, $headers)) {
//             echo "<script>alert('Password reset link has been sent to your email.'); window.location.href = '../forgot-password.html';</script>";
//         } else {
//             echo "<script>alert('Failed to send email. Please try again later.'); window.location.href = '../forgot-password.html';</script>";
//         }
//     } else {
//         echo "<script>alert('Failed to process password reset. Please try again.'); window.location.href = '../forgot-password.html';</script>";
//     }
// }








 require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', '', 'ns_hire');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
echo "Step 1: Connected to database.<br>";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Step 2: Form submitted.<br>";
    $email = $_POST['email'];
    echo "Step 3: Email received: $email<br>";

    // Identify user type
    $userType = null;
    $tableName = null;

    $stmt = $conn->prepare("SELECT ID FROM admin WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $adminResult = $stmt->get_result();
    if ($adminResult->num_rows > 0) {
        $userType = 'admin';
        $tableName = 'admin';
    }

    if (!$userType) {
        $stmt = $conn->prepare("SELECT UserID FROM user_info WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $userResult = $stmt->get_result();
        if ($userResult->num_rows > 0) {
            $userType = 'user';
            $tableName = 'user_info';
        }
    }

    if (!$userType) {
        echo "<script>alert('Email not found in our records.'); window.location.href = '../forgot-password.html';</script>";
        exit;
    }

    echo "Step 4: User type identified: $userType.<br>";

    // Generate reset token
    $resetToken = bin2hex(random_bytes(16));
    $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt = $conn->prepare("UPDATE $tableName SET reset_token = ?, reset_expiry = ? WHERE Email = ?");
    $stmt->bind_param("sss", $resetToken, $expiryTime, $email);
    if ($stmt->execute()) {
        echo "Step 5: Token stored in database.<br>";

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 2; // Enable debug output
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'anikahossain544@gmail.com';
            $mail->Password = 'pkce fqsi dqoq dfof';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
     
            $mail->Port = 587;

            $mail->setFrom('noreply@nshire.com', 'Nshire');
            $mail->addAddress($email);

            $resetLink = "http://localhost/nshire/php/reset-pass.php?token=$resetToken";
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the link to reset your password: <a href='$resetLink'>$resetLink</a>";
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ),
            );
            $mail->send();
            echo "<script>alert('Password reset link has been sent to your email.'); window.location.href = '../forgot-pass.html';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Failed to send email: {$mail->ErrorInfo}'); window.location.href = '../forgot-pass.html';</script>";
        }
    } else {
        echo "Step 6: Failed to update database.<br>";
    }
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ),
    );
    
}
?>






