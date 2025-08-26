<?php
// src/submit_survey.php
require_once '../database/config.php';

function get_client_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function get_geolocation($ip) {
    $url = "http://ip-api.com/json/" . $ip;
    $response = @file_get_contents($url);
    if ($response) {
        $data = json_decode($response, true);
        if ($data && $data['status'] === 'success') {
            return $data['country'] . ', ' . $data['regionName'] . ', ' . $data['city'];
        }
    }
    return 'Unknown';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = $_POST['client_name'] ?? '';
    $branch = $_POST['branch'] ?? '';
    $service_type = $_POST['service_type'] ?? '';
    $service_rating = intval($_POST['service_rating'] ?? 0);
    $staff_rating = intval($_POST['staff_rating'] ?? 0);
    $response_time_rating = intval($_POST['response_time_rating'] ?? 0);
    $remarks = $_POST['remarks'] ?? '';
    $token = $_GET['token'] ?? $_POST['token'] ?? '';

    $ip_address = get_client_ip();
    $geo_location = get_geolocation($ip_address);

    $conn = get_db_connection();
    // Check if token is valid and unused
    $stmt = $conn->prepare("SELECT used FROM survey_links WHERE token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->bind_result($used);
    if ($stmt->fetch() && $used == 0) {
        $stmt->close();
        // Insert survey response
        $stmt2 = $conn->prepare("INSERT INTO survey_responses (client_name, branch, service_type, service_rating, staff_rating, response_time_rating, remarks, ip_address, geo_location, token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param('sssiiissss', $client_name, $branch, $service_type, $service_rating, $staff_rating, $response_time_rating, $remarks, $ip_address, $geo_location, $token);
        $stmt2->execute();
        $stmt2->close();
        // Mark token as used
        $stmt3 = $conn->prepare("UPDATE survey_links SET used = 1 WHERE token = ?");
        $stmt3->bind_param('s', $token);
        $stmt3->execute();
    $stmt3->close();
    $conn->close();
    header('Location: ../public/survey.php?success=1&token=' . urlencode($token));
    exit();
    } else {
    $stmt->close();
    $conn->close();
    header('Location: ../public/survey.php?used=1&token=' . urlencode($token));
    exit();
    }
} else {
    header('Location: ../public/survey.php');
    exit();
}
?>
