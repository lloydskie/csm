<?php
session_start();
$_SESSION['passcode_locked_until'] = time() + 180;
$_SESSION['passcode_retry'] = 0;