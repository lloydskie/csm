<?php
// Generates a unique survey link and QR code
require_once '../database/config.php';
require_once '../src/phpqrcode/qrlib.php'; // You need to download phpqrcode library

function generateToken($length = 16) {
    return bin2hex(random_bytes($length));
}

$token = generateToken();
$link = 'http://localhost/csm/public/survey.php?token=' . $token;

// Store token in DB for tracking
$conn = get_db_connection();
$stmt = $conn->prepare("INSERT INTO survey_links (token, created_at) VALUES (?, NOW())");
$stmt->bind_param('s', $token);
$stmt->execute();
$stmt->close();
$conn->close();

// Generate QR code
$qrPath = '../exports/qr_' . $token . '.png';
QRcode::png($link, $qrPath, QR_ECLEVEL_L, 4);

// Show QR code to admin
header('Content-Type: text/html');
echo '<h3>Share this QR code with your client:</h3>';
echo '<img src="' . $qrPath . '" alt="Survey QR Code">';
echo '<p>Or share this link: <a href="' . $link . '" target="_blank">' . $link . '</a></p>';
?>
