<?php
require_once '../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['passcode_verified']) || $_SESSION['passcode_verified'] !== true) {
        echo 'error';
        exit();
    }
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $conn = get_db_connection();
    // Check if username exists
    $check_stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
    $check_stmt->bind_param('s', $username);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        $check_stmt->close();
        $conn->close();
        echo 'exists';
        exit();
    }
    $check_stmt->close();
    $stmt = $conn->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param('ss', $username, $password_hash);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            echo 'success';
            exit();
        } else {
            $stmt->close();
            $conn->close();
            echo 'error';
            exit();
        }
    } else {
        $conn->close();
        echo 'error';
        exit();
    }
}
?>
