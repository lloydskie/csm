<?php
session_start();
if (isset($_GET['retry'])) {
    $_SESSION['passcode_retry'] = intval($_GET['retry']);
}