<?php
include('../php/session_dispatcher.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan | Dispatcher</title>
    <script src="https://unpkg.com/@zxing/library@0.19.1"></script>
    <?php
    include '../dependencies/dependencies.php';
    ?>
    <link rel="stylesheet" href="../css/newscan.css">
</head>
<body>
<?php
    include('../php/navbar_dispatcher.php');
?>
<video id="qr-video" width="400" height="300"></video>
<div id="result"></div>

<script>
    // Function to handle the QR code scanning
    const videoElement = document.getElementById('qr-video');
    const resultElement = document.getElementById('result');

    const codeReader = new ZXing.BrowserQRCodeReader();

    codeReader
        .decodeFromVideoDevice(undefined, 'qr-video', (result, err) => {
            if (result) {
                resultElement.innerHTML = `QR Code Result: ${result.text}`;

                // Send the scanned result to the server for processing
                fetch('scan_handler.php', {
                    method: 'POST',
                    body: JSON.stringify({ scan_result: result.text }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    // Handle the response from the server if needed
                })
                .catch(error => {
                    console.error('Error processing scan result:', error);
                });
            }
            if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error('QR Code scan error:', err);
                resultElement.innerHTML = 'Error scanning QR code.';
            }
        })
        .catch(error => {
            console.error('Error accessing the camera:', error);
            resultElement.innerHTML = 'Error accessing the camera.';
        });
</script>
</body>
</html>
