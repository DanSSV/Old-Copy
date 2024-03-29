<?php
session_start();
if (isset($_SESSION['Email_Session'])) {
  header("Location: signupcommuter.php");
  die();
}


include('config2.php');
$msg = "";
$Error_Pass = "";
if (isset($_GET['Verification'])) {
  $raquet = mysqli_query($conn, "SELECT * FROM commuter WHERE CodeV='{$_GET['Verification']}'");
  if (mysqli_num_rows($raquet) > 0) {
    $query = mysqli_query($conn, "UPDATE commuter SET verification='1' WHERE CodeV='{$_GET['Verification']}'");
    if ($query) {
      $rowv = mysqli_fetch_assoc($raquet);
      header("Location:welcome.php?CommuterID='{$rowv['CommuterID']}'");
    }else{
      header("Location: signupcommuter.php");
    }
  } else {
    header("Location: ewan.php");
  }
}
if (isset($_POST['submit'])) {
  $email = mysqli_real_escape_string($conn, $_POST['Email']);
  $Pass = mysqli_real_escape_string($conn, md5($_POST['Password']));
  $sql = "SELECT * FROM commuter WHERE email='{$email}' and Password='{$Pass}'";
  $resulte = mysqli_query($conn, $sql);
  if (mysqli_num_rows($resulte) === 1) {
    $row = mysqli_fetch_assoc($resulte);
    if ($row['verification'] === '1') {
      $_SESSION['Email_Session']=$email;
      header("Location: signupcommuter.php");
    }else{$msg = "<div class='alert alert-info'>First Verify Your Account</div>";}
  }else{
    $msg = "<div class='alert alert-danger'>Email or Password is not match</div>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | TriSakay</title>
  <?php
  $imagePath = "img/Logo_Nobg.png";
  ?>
  <link rel="icon" href="<?php echo $imagePath; ?>" type="image/png" />
  
  <link rel="stylesheet" href="css/login.css" />
</head>

<body>
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
  <img src="img/nyenyeenye.png" alt="Baliwag City Logo" />
  <h1>Welcome</h1>
  <h2 style="color: white">Hi there! Sign in to continue.</h2>

  <div class="error-container">
    <?php
    if (isset($_GET['error'])) {
      echo '<p class="error-message">Invalid username or password. Please try again.</p>';
    }
    ?>
  </div>
  <div class="container2">
    <form action="" method="POST">
      <div class="container d-flex justify-content-center">
        <div class="input-box">
          <div class="input-field">
            <input type="text" class="input" id="Email" name="Email" required autocomplete="off" />
            <label for="email"><i class="fa-solid fa-envelope fa-lg" style="color: #ffffff;"></i> Email</label>
          </div>
          <div class="input-field">
            <input type="password" class="input" id="Password" name="Password" required />
            <label for="password"><i class="fa-solid fa-key fa-lg" style="color: #ffffff;"></i> Password</label>
          </div>

          <div class="container1 d-flex justify-content-center mt-3">
            <p style="color: #f5f5f5">
              Forgot your
              <a href="forgot.php" style="color: #f5f5f5" onmouseover="this.style.color='#9ACD32'"
                onmouseout="this.style.color='#F5F5F5'">password?</a>
            </p>
          </div>

          <div class="container d-flex justify-content-center mt-3">
            <button type="submit" name="submit" class="btn btn-default custom-btn">Sign in</button>
          </div>
        </div>
      </div>
    </form>

    <hr>

    <div class="container1 d-flex justify-content-center mt-3">
      <p style="color: #f5f5f5">
        Don’t have an account?
        <a href="signup.php" style="color: #f5f5f5" onmouseover="this.style.color='#9ACD32'"
          onmouseout="this.style.color='#F5F5F5'">Sign up</a>
      </p>
    </div>

  </div>

</body>

</html>