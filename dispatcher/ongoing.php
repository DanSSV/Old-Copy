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
</head>

<body>
    <?php
    include('../php/navbar_dispatcher.php');
    ?>
    <div id="map" style="height: 400px;"></div>
    <h1>
        <?php
        if (isset($_SESSION['bookingid'])) {
            echo $_SESSION['bookingid'];
        } else {
            echo 'Booking ID not set';
        }
        ?>
    </h1>

    <script>
        var map = L.map('map', {
            zoomControl: false
        }).setView([0, 0], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker([0, 0]).addTo(map).bindPopup('You are here').openPopup(); // Open the popup on load

        function updateLocation(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            marker.setLatLng([latitude, longitude]).update();
            map.setView([latitude, longitude], 15);

        }

        function handleError(error) {
            // console.error('Error getting user location: ' + error.message);
        }

        // Watch user's position
        var watchId = navigator.geolocation.watchPosition(updateLocation, handleError);

        // To stop watching the position, you can call navigator.geolocation.clearWatch(watchId);

    </script>
</body>

</html>