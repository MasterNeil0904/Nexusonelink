<?php
// Start PHP part

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp_billing";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$showGcashButton = false;
$formSubmitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $account_number = $_POST['account_number'];
    $formSubmitted = true;

    $sql = "SELECT * FROM clients WHERE name = ? AND account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $account_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $showGcashButton = true;
    } else {
        $error = "Wrong Information. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Nexus OneLink</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            background: linear-gradient(to right, #fff200, #ffd700);
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            text-align: center;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        .logo {
            width: 90px;
            margin-bottom: 15px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border: 2px solid #ffd700;
            border-radius: 10px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="text"]:focus {
            border-color: #ffcc00;
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 20px;
            background-color: #ffcc00;
            border: none;
            color: #333;
            font-size: 18px;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #ffb700;
        }

        .gcash-btn {
            background-color: #00b9f1;
            color: white;
        }

        .gcash-btn:hover {
            background-color: #0095c9;
        }

        .error {
            margin-top: 15px;
            color: red;
            font-weight: bold;
        }

        .success-message {
            font-size: 18px;
            color: green;
            margin-bottom: 20px;
            font-weight: bold;
        }

        #loadingSpinner {
            display: none;
            margin: 10px auto;
        }

        #qrPreview {
            margin-top: 20px;
        }

        img.qr-img {
            width: 180px;
            height: auto;
        }

        @media(max-width: 480px) {
            .form-container {
                padding: 30px 20px;
            }

            input[type="text"], button {
                font-size: 16px;
            }
        }
    </style>

    <script>
        function showLoading() {
            document.getElementById('loadingSpinner').style.display = 'block';
        }
    </script>

</head>
<body>

<div class="form-container">
    <!-- LOGO Part -->
    <img src="NexusOneLinkBG (1).png" alt="Nexus Onelink Logo" class="logo">


    <h2> Welcome to Nexus OneLink</h2>

    <?php if (!$showGcashButton): ?>
        <form method="POST" onsubmit="showLoading()">
            <input type="text" name="name" placeholder="Enter your Name" required>
            <input type="text" name="account_number" placeholder="Enter your Account Number" required>
            <button type="submit">Proceed</button>
            <div id="loadingSpinner">
                <img src="spinner.gif" alt="Loading..." width="50">
            </div>
        </form>
    <?php endif; ?>

    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

    <?php if ($showGcashButton): ?>
        <p class="success-message">Successfully Verified!</p>

        <!-- GCash QR Code Preview -->
        <div id="qrPreview">
            <p>Scan to Pay:</p>
            <img src="gcashsample.png" alt="GCash QR" class="qr-img">
        </div>
        <form action="gcash.php" method="POST">
            <button type="submit" class="gcash-btn">Pay with GCash</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
