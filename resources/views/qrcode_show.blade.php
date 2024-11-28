<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }

        .container {
            text-align: center;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .container p {
            margin-top: 10px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Scan QR Code untuk Absensi</h1>
        <div id="qrCodeContainer">
            {!! $qrCode !!}
        </div>
        <p>QR Code ini akan diperbarui setiap 3 menit.</p>
    </div>

    <script>
        function refreshQRCode() {
            fetch('/qr-code') // Ganti dengan route atau endpoint yang menghasilkan QR Code
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text(); // Mendapatkan HTML QR Code
                })
                .then(html => {
                    document.getElementById('qrCodeContainer').innerHTML = html; // Memperbarui kontainer QR Code
                })
                .catch(error => {
                    console.error('Error fetching QR Code:', error);
                });
        }

        // Refresh halaman setiap 3 menit (180000 ms)
        setInterval(function() {
            location.reload();
        }, 180000); // 180000 ms = 3 menit
    </script>
</body>

</html>
