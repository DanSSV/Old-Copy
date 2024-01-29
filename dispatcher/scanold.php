<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://cdn.rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>
<body>
    <h1>QR Code Scanner</h1>
    <p>Scan a QR code to see the result:</p>
    <video id="qr-video" width="400" height="300"></video>
    <div id="result"></div>

    <script>
        const videoElement = document.getElementById('qr-video');
        const resultElement = document.getElementById('result');

        const scanner = new Instascan.Scanner({ video: videoElement });

        scanner.addListener('scan', function (content) {
            // Check if the scanned QR code contains the specific content
            if (content.includes("scan.php?bookingid=")) {
                // Extract the bookingId from the URL using URLSearchParams
                const urlParams = new URLSearchParams(content);
                const bookingId = urlParams.get("bookingid");

                // Send an AJAX request to your PHP script
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'confirm.php?bookingId=' + bookingId, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        resultElement.innerHTML = 'QR Code scanned and data sent to server.';
                    }
                };
                xhr.send();
            } else {
                resultElement.innerHTML = 'Invalid QR code content.';
            }
        });

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]); // You can choose a different camera if available
            } else {
                console.error('No cameras found.');
                resultElement.innerHTML = 'No cameras found.';
            }
        }).catch(function (error) {
            console.error('Error accessing the camera:', error);
            resultElement.innerHTML = 'Error accessing the camera.';
        });
    </script>
</body>
</html>
