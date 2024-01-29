<?php
include('../php/session_dispatcher.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    include '../dependencies/dependencies.php';
    ?>
    <link rel="stylesheet" href="../css/pending_2.css">

</head>

<body>
    <?php
    include('../php/navbar_dispatcher.php');
    ?>
    <div class="loading">
        <div id="booking-data">
            <?php include('bookingdata.php'); ?>
        </div>
    </div>

</body>

</html>