<?php
    session_start();
    if (!isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: index.php");
        die();
    }

    include 'config2.php';

    $query = mysqli_query($conn, "SELECT * FROM commuter WHERE email='{$_SESSION['SESSION_EMAIL']}'");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        echo "You are Verified, close this window and proceed to Log in page " . $row['FirstName'] . " <a href='Index.php'>Back</a>";
    }
?>