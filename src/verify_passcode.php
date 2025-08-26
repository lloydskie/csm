<?php
// Set your secret passcode here
$correct_passcode = '123456'; // Change this to your desired passcode

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $passcode = $_POST['passcode'] ?? '';
    if ($passcode === $correct_passcode) {
        $_SESSION['passcode_verified'] = true;
        unset($_SESSION['passcode_failed']);
    header('Location: ../public/admin_signup.html');
        exit();
    } else {
        $_SESSION['passcode_failed'] = true;
    header('Location: ../public/admin_passcode.php?error=1');
        exit();
    }
}
?>
