<?php
session_start();
unset($_SESSION['passcode_retry']);
unset($_SESSION['passcode_locked_until']);