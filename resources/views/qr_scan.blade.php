<!-- resources/views/qr_scan.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
</head>
<body>
    <h1>Scan QR Code</h1>
    <video id="preview" width="100%" height="auto"></video>

    <script>
        const videoElement = document.getElementById("preview");

        // Start video stream to scan QR code
        navigator.mediaDevices
            .getUserMedia({ video: { facingMode: "environment" } })
            .then((stream) => {
                videoElement.srcObject = stream;
                videoElement.setAttribute("playsinline", true); // iOS compatibility
                videoElement.play();
                requestAnimationFrame(scanQRCode); // Start scanning QR code
            });

        // Function to scan QR Code
        function scanQRCode() {
            const canvas = document.createElement("canvas");
            const context = canvas.getContext("2d");

            // Set canvas size based on video element size
            canvas.height = videoElement.videoHeight;
            canvas.width = videoElement.videoWidth;

            // Draw the current video frame onto the canvas
            context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

            console.log("Canvas dimensions: ", canvas.width, canvas.height);
console.log("Image Data: ", imageData);

            const code = jsQR(imageData.data, canvas.width, canvas.height);

            if (code) {
                // The QR code contains the hotel_id (or entire data string)
                const hotelId = code.data;
// console.log(hotelId, "hotelIdhotelIdhotelIdhotelIdhotelId");

                // Redirect to Laravel route with the hotel_id in the URL
                window.location.href = `/hotel/${hotel_id}`;
            } else {
                // Continue scanning if no QR code detected
                requestAnimationFrame(scanQRCode);
            }
        }
    </script>
</body>
</html>
