<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "admin";

// Create a connection to the database
$connection = mysqli_connect($servername, $username, $password, $database);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start(); // Start a session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    if (isset($_POST['subject']) && isset($_POST['platenum']) && isset($_POST['concern']) && isset($_POST['name'])) {
        $subject = $_POST['subject'];
        $platenum = $_POST['platenum'];
        $concern = $_POST['concern'];
        $name = $_POST['name'];
        $commuterid = $_SESSION["commuterid"];

        // Perform the database update (insert or update, depending on your use case)
        $sql = "INSERT INTO report (user_id, name, subject, plate_number, report_concern, date_time) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sssss", $commuterid, $name, $subject, $platenum, $concern);

        if ($stmt->execute()) {
            // Report submitted successfully
            echo "<script>alert('Report submitted. Please wait for our response. Thank you.'); window.location.href = 'lost.php';</script>";
        } else {
            // Handle the case where the database update fails
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        // Handle the case where not all required fields are set in $_POST
        echo "<script>alert('All required fields are not set.');</script>";
    }
    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
