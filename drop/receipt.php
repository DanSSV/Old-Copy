<?php
include('../php/session_commuter.php');
?>
<?php


// Include the database connection
include('../db/dbconn.php');

// Get the commuterid from the session
$commuterid = $_SESSION['commuterid'];

// Define the SQL query to update the "booking" table
$sql = "UPDATE booking 
        SET status = 'completed' 
        WHERE commuterid = ? 
        ORDER BY bookingdate DESC 
        LIMIT 1";

// Create a prepared statement
$stmt = $conn->prepare($sql);

// Bind the commuterid to the prepared statement
$stmt->bind_param("i", $commuterid);

// Execute the prepared statement
if ($stmt->execute()) {
} else {
    echo "Error updating booking status: " . $conn->error;
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>

<?php
include '../db/dbconn.php'; // Include your database connection script

$commuterid = $_SESSION['commuterid'];

// Your SQL query with the defined $commuterid
$query = "SELECT platenumber, Toda, PickupPoint, DropoffPoint, Fare, ConvenienceFee, Distance,driverETA FROM booking WHERE commuterid = $commuterid ORDER BY bookingdate DESC  LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch the data from the result set
$row = mysqli_fetch_assoc($result);

// Store the retrieved data in variables
$platenumber = $row['platenumber'];
$Toda = $row['Toda'];
$PickupPoint = $row['PickupPoint'];
$DropoffPoint = $row['DropoffPoint'];
$Fare = $row['Fare'];
$ConvenienceFee = $row['ConvenienceFee'];
$Distance = $row['Distance'];
$driverETA = $row['driverETA'];

?>

<!DOCTYPE html>
<html>

<head>
    <?php include '../dependencies/dependencies.php'; ?>
    <link rel="stylesheet" href="../css/receipt.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .navbar {
            background-color: #033957 !important;
        }

        .container {
            width: 500px;
            margin: 20px auto;
            border: 1px solid #FFFF;
            padding: 10px;
            margin-top: 5%;
            background: #e6ebda;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 75vh;
            /* Center vertically in the viewport */
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: black;
        }

        tr:nth-child(even) {
            background-color: #0000;
        }

        td,
        p {
            color: black;
            /* Change to the color of your choice */
        }

        title {
            color: black;
        }

        h1 {
            color: black;
        }

        p {
            font-weight: bold;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <?php
    include('../php/navbar_commuter.php');
    ?>
    <div class="container">

        <h1>Your Receipt</h1>

        <!-- Add an image with the <img> tag -->

        <table>
            <tr>
                <td>Body Number</td>
                <td>
                    <?php echo $platenumber; ?>
                </td>
            </tr>
            <tr>
                <td>Toda</td>
                <td>
                    <?php echo $Toda; ?>
                </td>
            </tr>

            <tr>
                <td>Fare</td>
                <td>
                    <?php echo $Fare; ?>
                </td>
            </tr>
            <tr>
                <td>Convenience Fee</td>
                <td>
                    <?php echo $ConvenienceFee; ?>
                </td>
            </tr>
            <tr>
                <td>Distance</td>
                <td>
                    <?php echo $Distance; ?>
                </td>
            </tr>
        </table>

        <?php
        // Calculate the total amount by adding Fare and ConvenienceFee
        $totalAmount = $Fare + $ConvenienceFee;
        ?>

        <p>Total Amount: ₱
            <?php echo $totalAmount; ?>
        </p>
    </div>
</body>

</html>