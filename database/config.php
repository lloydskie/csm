<?php
// database/config.php
function get_db_connection() {
    $host = 'localhost';
    $user = 'root'; // Change if needed
    $pass = '';
    $db = 'csm_db'; // Change to your DB name
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die('Database connection failed: ' . $conn->connect_error);
    }
    return $conn;
}
?>
