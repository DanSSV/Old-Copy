<?php
include('../php/session_dispatcher.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map with Current Location</title>
    <?php
    $imagePath = "../img/Logo_Nobg.png";
    ?>
    <link rel="icon" href="<?php echo $imagePath; ?>" type="image/png" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <?php
    include '../dependencies/dependencies.php';
    ?>
    <link rel="stylesheet" href="../css/leaflet_map.css">
    <style>
        .custom-btn {
            width: 172px;
            height: 50px;
            border-radius: 50px;
            background-color: #407c87;
            color: white;
            margin-top: 30px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .custom-btn:hover {
            background-color: #3b8875;
            color: white;
            transition-delay: 0.2s;
        }
    </style>
</head>

<body>
    <?php
    include('../php/navbar_dispatcher.php');
    ?>

    <div id="map" style="height: 300px;"></div>


    <div id="map"></div>

    <form action="process_booking.php" method="POST">
    <div class="input-field">
        <input type="text" class="input" id="platenumber" name="platenumber" required autocomplete="off" />
        <label for="platenumber">platenumber</label>
    </div>
    <button  type="submit" class="btn btn-default custom-btn">
        Confirm Booking
    </button>
    </form>

    <!-- <script>
        document.getElementById('confirmButton').addEventListener('click', function () {
            window.location.href = "newscan.php";
        });
    </script> -->

    <br>
    <?php
    include('../db/dbconn.php');

    if (isset($_SESSION['bookingid'])) {
        $bookingid = $_SESSION['bookingid'];
        $sql = "SELECT pickuppoint, dropoffpoint FROM booking WHERE bookingid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookingid);
        $stmt->execute();
        $stmt->bind_result($pickuppoint, $dropoffpoint);
        $stmt->fetch();
        $stmt->close();
        $pickupCoords = explode(',', $pickuppoint);
        $dropoffCoords = explode(',', $dropoffpoint);
    } else {
        echo '<h1>Booking ID not set</h1>';
    }
    ?>

    <script>
        var map = L.map('map', {
            zoomControl: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const greenMarkerIcon = L.icon({
            iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png",
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
        });

        const redMarkerIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34]
        });

        <?php if (isset($_SESSION['bookingid'])) { ?>
            var pickupMarker = L.marker([<?= $pickupCoords[0] ?>, <?= $pickupCoords[1] ?>], { icon: greenMarkerIcon }).addTo(map);
            var dropoffMarker = L.marker([<?= $dropoffCoords[0] ?>, <?= $dropoffCoords[1] ?>], { icon: redMarkerIcon }).addTo(map);

            pickupMarker.bindPopup('Pickup Point').openPopup();
            dropoffMarker.bindPopup('Drop-off Point').openPopup();

            map.setView([<?= $pickupCoords[0] ?>, <?= $pickupCoords[1] ?>], 15);

            // Calculate and display the shortest route
            var routeUrl = `https://router.project-osrm.org/route/v1/driving/${<?= $pickupCoords[1] ?>},${<?= $pickupCoords[0] ?>};${<?= $dropoffCoords[1] ?>},${<?= $dropoffCoords[0] ?>}?overview=full&geometries=geojson`;
            fetch(routeUrl)
                .then(response => response.json())
                .then(data => {
                    L.geoJSON(data.routes[0].geometry, {

                    }).addTo(map);
                })
                .catch(error => console.error('Error fetching route data:', error));
        <?php } ?>

    </script>
</body>

</html>