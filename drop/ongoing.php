<?php
include('../php/session_commuter.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Progress | Commuter</title>
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
    include('../php/navbar_commuter.php');
    ?>

    <div id="map" style="height: 400px;"></div>

    <br>
    <?php
    include('../db/dbconn.php');

    if (isset($_SESSION['commuterid'])) {
        $commuterid = $_SESSION['commuterid'];

        $sql = "SELECT pickuppoint, dropoffpoint, fare, conveniencefee, distance, ETA FROM booking WHERE commuterid = ? ORDER BY bookingdate DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $commuterid);
        $stmt->execute();
        $stmt->bind_result($pickuppoint, $dropoffpoint, $fare, $conveniencefee, $distance, $ETA);
        $stmt->fetch();
        $stmt->close();
        $pickupCoords = explode(',', $pickuppoint);
        $dropoffCoords = explode(',', $dropoffpoint);
    } else {
        echo '<h1>Commuter ID not set</h1>';
    }
    ?>

    <script>
        var map = L.map('map', {
            zoomControl: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var userMarker;
        var userPopup = L.popup().setContent("You are here");

        map.setView([0, 0], 10);

        function updateUserMarker(position) {
            const { coords } = position;

            if (!userMarker) {
                userMarker = L.marker([coords.latitude, coords.longitude]).addTo(map);
                userMarker.bindPopup(userPopup).openPopup();
            } else {
                userMarker.setLatLng([coords.latitude, coords.longitude]);
            }

            map.setView([coords.latitude, coords.longitude], 15);

            userPopup.setContent(`You are here<br>Fare: ₱<?= $fare ?><br>Convenience Fee: ₱<?= $conveniencefee ?><br>Distance: <?= $distance ?> km<br>ETA: <?= $ETA ?> mins`);
        }

        function handleLocationError(error) {
            console.error('Error getting user location:', error);
        }

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(updateUserMarker, handleLocationError);
        }

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

        <?php if (isset($_SESSION['commuterid'])) { ?>
            var pickupMarker = L.marker([<?= $pickupCoords[0] ?>, <?= $pickupCoords[1] ?>], { icon: greenMarkerIcon }).addTo(map);
            var dropoffMarker = L.marker([<?= $dropoffCoords[0] ?>, <?= $dropoffCoords[1] ?>], { icon: redMarkerIcon }).addTo(map);

            pickupMarker.bindPopup('Pickup Point').openPopup();
            dropoffMarker.bindPopup('Drop-off Point').openPopup();

            map.setView([<?= $pickupCoords[0] ?>, <?= $pickupCoords[1] ?>], 15);

            var routeUrl = `https://router.project-osrm.org/route/v1/driving/${<?= $pickupCoords[1] ?>},${<?= $pickupCoords[0] ?>};${<?= $dropoffCoords[1] ?>},${<?= $dropoffCoords[0] ?>}?overview=full&geometries=geojson`;
            fetch(routeUrl)
                .then(response => response.json())
                .then(data => {
                    L.geoJSON(data.routes[0].geometry, {}).addTo(map);
                })
                .catch(error => console.error('Error fetching route data:', error));
        <?php } ?>

        // Check if the marker is less than 10 meters from the drop-off point and redirect to receipt.php
        function checkDistance() {
            var userLatLng = userMarker.getLatLng();
            var dropoffLatLng = dropoffMarker.getLatLng();

            var distance = userLatLng.distanceTo(dropoffLatLng);

            if (distance < 10) {
                window.location.href = 'receipt.php';
            }
        }

        // Check the distance periodically
        setInterval(checkDistance, 5000);
    </script>
</body>

</html>
