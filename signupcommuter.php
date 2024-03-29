<?php
session_start();
if (isset($_SESSION['Email_Session'])) {
  header("Location: signupcommuter.php");
  die();
}
include('config2.php');
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
$msg = "";
$Error_Pass = "";
if (isset($_POST['submit'])) {
  $Email = mysqli_real_escape_string($conn, $_POST['Email']);
  $MobileNumber = mysqli_real_escape_string($conn, $_POST['MobileNumber']);
  $Password = mysqli_real_escape_string($conn, hash('sha512', $_POST['Password']));
$Confirm_Password = mysqli_real_escape_string($conn, hash('sha512', $_POST['Conf-Password']));
  $FirstName = mysqli_real_escape_string($conn, $_POST['FirstName']);
  $LastName = mysqli_real_escape_string($conn, $_POST['LastName']);
  $Code = mysqli_real_escape_string($conn, md5(rand()));
  $query = "SELECT * FROM commuter where Email='{$Email}'";
  $queryResult = mysqli_query($conn, $query);
  
  if ($queryResult !== false) {
      if (mysqli_num_rows($queryResult) > 0) {
          $msg = "<div class='alert alert-danger'>This Email: '{$email}' has already been used.</div>";
      } else {
          if ($Password === $Confirm_Password) {
            $query = "INSERT INTO commuter(`Email`, `MobileNumber`, `Password`, `FirstName`, `LastName`, `CodeV`) 
            VALUES('$Email', '$MobileNumber', '$Password', '$FirstName', '$LastName', '$Code')";
  
              $result = mysqli_query($conn, $query);
              if ($result) {
                  // Create an instance; passing `true` enables exceptions
                  $mail = new PHPMailer(true);
  
                  try {
                      // Server settings
                      $mail->SMTPDebug = 0; // Enable verbose debug output
                      $mail->isSMTP(); // Send using SMTP
                      $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                      $mail->SMTPAuth = true; // Enable SMTP authentication
                      $mail->Username = 'trisakay977@gmail.com'; // SMTP username
                      $mail->Password = 'grintluwuzhcpelt'; // SMTP password
                      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable implicit TLS encryption
                      $mail->Port = 587; // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
                      // Recipients
                      $mail->setFrom('trisakay977@gmail.com', 'Trisakay');
                      $mail->addAddress($Email, $FirstName); // Changed '$name' to '$Fullname'
                      // Content
                      $mail->isHTML(true); // Set email format to HTML
                      $mail->Subject = 'Trisakay Commuter Sign Up';
                      $mail->Body = '<p>Copy This Verification Link and paste it into your Browser: <b><a href="http://localhost/TriSakay_2-Full-Copy-main/?Verification=' . $Code . '">"http://localhost/TriSakay_2-Full-Copy-main/?Verification=' . $Code . '"</a></b></p>';
                      $mail->send();
  
                      $msg = "<div class='alert alert-info'>We've sent a Verification Link to your Email Address</div>";
                  } catch (Exception $e) {
                      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                  }
              } else {
                  $msg = "<div class='alert alert-danger'>Something went wrong with the database.</div>";
              }
          } else {
              $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match</div>";
          }
      }
  } else {
      // Handle the query error here, e.g., display an error message or log it.
      $msg = "<div class='alert alert-danger'>Database query error: " . mysqli_error($conn) . "</div>";
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>TriSakay Commuter Sign up </title>
  <meta name="viewport" content="width=device-width,
      initial-scale=1.0" />
  <link rel="stylesheet" href="css/signup.css" />
</head>

<style>
  body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    /* Specify your desired font */
  }

  .background-section {
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    position: relative;
    width: 100%;
    height: 110vh;
    /* Adjust the height as needed */
  }

  .container {
    background-color: transparent;

    padding: 20px;
    margin: 20px;
    max-width: 400px;

    position: relative;
    z-index: 1;
    /* Place the form on top of the background */
  }

  img.trisakay {
    margin-left: 1px;
    margin-bottom: 25px;
    display: block;
  }

  .alert-danger {
    background-color: #6089D5;
    color: #fff;
  }

  .alert-info {
    background-color: #6089D5;
    color: #fff;
  }

  p {
    color: white;
    margin-bottom: 10px;
  }

  a {
    color: white;
  }

  .form-submit-btn {
    margin-right: 35px;
  }

  .form-title {
    margin-right: 35px;
  }
</style>

<body>
  <div class="background-svg">
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
      width="100%" height="100%" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMax slice">
      <defs>
        <linearGradient id="bg">
          <stop offset="0%" style="stop-color:rgba(130, 158, 249, 0.06)"></stop>
          <stop offset="50%" style="stop-color:rgba(76, 190, 255, 0.6)"></stop>
          <stop offset="100%" style="stop-color:rgba(115, 209, 72, 0.2)"></stop>
        </linearGradient>
        <path id="wave" fill="url(#bg)" d="M-363.852,502.589c0,0,236.988-41.997,505.475,0
  s371.981,38.998,575.971,0s293.985-39.278,505.474,5.859s493.475,48.368,716.963-4.995v560.106H-363.852V502.589z" />
      </defs>
      <g>
        <use xlink:href='#wave' opacity=".3">
          <animateTransform attributeName="transform" attributeType="XML" type="translate" dur="10s" calcMode="spline"
            values="270 230; -334 180; 270 230" keyTimes="0; .5; 1" keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0"
            repeatCount="indefinite" />
        </use>
        <use xlink:href='#wave' opacity=".6">
          <animateTransform attributeName="transform" attributeType="XML" type="translate" dur="8s" calcMode="spline"
            values="-270 230;243 220;-270 230" keyTimes="0; .6; 1" keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0"
            repeatCount="indefinite" />
        </use>
        <use xlink:href='#wave' opacty=".9">
          <animateTransform attributeName="transform" attributeType="XML" type="translate" dur="6s" calcMode="spline"
            values="0 230;-140 200;0 230" keyTimes="0; .4; 1" keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0"
            repeatCount="indefinite" />
        </use>
      </g>
    </svg>
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
      width="100%" height="100%" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMax slice">
      <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        width="100%" height="100%" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMax slice">
        <defs>
          <linearGradient id="bg">
            <stop offset="0%" style="stop-color:rgba(130, 158, 249, 0.06)"></stop>
            <stop offset="50%" style="stop-color:rgba(76, 190, 255, 0.6)"></stop>
            <stop offset="100%" style="stop-color:rgba(115, 209, 72, 0.2)"></stop>
          </linearGradient>
          <path id="wave" fill="url(#bg)" d="M-363.852,502.589c0,0,236.988-41.997,505.475,0
  s371.981,38.998,575.971,0s293.985-39.278,505.474,5.859s493.475,48.368,716.963-4.995v560.106H-363.852V502.589z" />
        </defs>
        <g>
          <use xlink:href='#wave' opacity=".3">
            <animateTransform attributeName="transform" attributeType="XML" type="translate" dur="10s" calcMode="spline"
              values="270 230; -334 180; 270 230" keyTimes="0; .5; 1" keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0"
              repeatCount="indefinite" />
          </use>
          <use xlink:href='#wave' opacity=".6">
            <animateTransform attributeName="transform" attributeType="XML" type="translate" dur="8s" calcMode="spline"
              values="-270 230;243 220;-270 230" keyTimes="0; .6; 1" keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0"
              repeatCount="indefinite" />
          </use>
          <use xlink:href='#wave' opacty=".9">
            <animateTransform attributeName="transform" attributeType="XML" type="translate" dur="6s" calcMode="spline"
              values="0 230;-140 200;0 230" keyTimes="0; .4; 1" keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0"
              repeatCount="indefinite" />
          </use>
        </g>
      </svg>
    </svg>

    <div class="container">
      <img class="trisakay" src="img/nyenyeenye.png" height="100" width="320" class="responsive">

      <h1 class="form-title">Commuter Sign Up</h1>
      <form action="" method="POST" class="sign-up-form">
        <?php echo $msg ?>
        <div class="main-user-info">

          <div class="user-input-box">
            <label for="username">First Name</label>
            <input type="text" name="FirstName" placeholder="First Name" value="<?php if (isset($_POST['FirstName'])) {
              echo $FirstName;
            } ?>" required />
          </div>
          <div class="user-input-box">
            <label for="username">Last Name</label>
            <input type="text" name="LastName" placeholder="Last Name" required />
          </div>
          <div class="user-input-box">
            <label for="">Mobile Number</label>
            <input type="text" name="MobileNumber" placeholder="Mobile Number" value="<?php if (isset($_POST['MobileNumber'])) {
              echo $MobileNumber;
            } ?>" required />
          </div>
          <div class="user-input-box">
            <label for="">Email</label>
            <input type="email" name="Email" placeholder="Email" value="<?php if (isset($_POST['Email'])) {
              echo $Email;
            } ?>" required />
          </div>
          <div class="user-input-box" <?php echo $Error_Pass ?>>


            <label for="">Password</label>
            <input type="password" name="Password" placeholder="Password" required />
          </div>
          <div class="user-input-box" <?php echo $Error_Pass ?>>
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" name="Conf-Password" placeholder="Confirm Password" required />
          </div>
          <p> Already have an account? <a href="index.php">Login</a></p>
        </div>
        <div class="form-submit-btn">
          <input type="submit" value="Sign up" class="btn" name="submit">
        </div>

      </form>
    </div>

</body>

</html>