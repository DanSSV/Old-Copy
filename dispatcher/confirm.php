<?php
include('../db/dbconn.php');

// Check if the bookingId is present in the URL

    
    // Sanitize the input to prevent SQL injection
    $bookingId = '7';

    $status = 'ongoing';

    $query = "UPDATE booking SET status = '$status' WHERE bookingid = '$bookingId'";
    $result = $conn->query($query);

    if ($result) {
        header("Location: pending.php");
        exit;
        
    } else {
        echo "Update failed: " . $conn->error;
    }

    $conn->close();

?>
