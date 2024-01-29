<?php

if (isset($_POST['scan_result'])) {
    // Get the scanned result from the form
    $scanResult = $_POST['scan_result'];

    // Include the database connection file
    include('../db/dbconn.php');

    // Get the booking ID from the session
    $bookingid = $_SESSION['bookingid'];

    // Perform the database update
    $sql = "UPDATE booking SET platenumber = :scanResult WHERE bookingid = :bookingid";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':scanResult', $scanResult);
    $stmt->bindParam(':bookingid', $bookingid);

    if ($stmt->execute()) {
        echo "Database update successful";
        // You can do further actions or redirection here
    } else {
        echo "Database update failed";
        // Handle the error as needed
    }
}
?>
