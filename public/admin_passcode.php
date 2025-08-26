<?php
session_start();
$locked_until = $_SESSION['passcode_locked_until'] ?? 0;
$now = time();
$is_locked = $locked_until > $now;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Passcode Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Enter Admin Passcode</h2>
    <div class="mb-3">
        <label class="form-label">6-digit Passcode</label>
        <div id="passcode-boxes" style="display: flex; gap: 10px;">
            <input type="text" maxlength="1" class="passcode-input form-control text-center" style="width:40px; font-size:2rem;" <?php if($is_locked) echo 'disabled'; ?> />
            <input type="text" maxlength="1" class="passcode-input form-control text-center" style="width:40px; font-size:2rem;" <?php if($is_locked) echo 'disabled'; ?> />
            <input type="text" maxlength="1" class="passcode-input form-control text-center" style="width:40px; font-size:2rem;" <?php if($is_locked) echo 'disabled'; ?> />
            <input type="text" maxlength="1" class="passcode-input form-control text-center" style="width:40px; font-size:2rem;" <?php if($is_locked) echo 'disabled'; ?> />
            <input type="text" maxlength="1" class="passcode-input form-control text-center" style="width:40px; font-size:2rem;" <?php if($is_locked) echo 'disabled'; ?> />
            <input type="text" maxlength="1" class="passcode-input form-control text-center" style="width:40px; font-size:2rem;" <?php if($is_locked) echo 'disabled'; ?> />
        </div>
    </div>
    <div id="passcode-message" class="mt-3 text-danger"></div>
</div>
<script>
const correctPasscode = '123456';
const inputs = document.querySelectorAll('.passcode-input');
const message = document.getElementById('passcode-message');
let retryCount = <?php echo isset($_SESSION['passcode_retry']) ? $_SESSION['passcode_retry'] : 0; ?>;
let isLocked = <?php echo $is_locked ? 'true' : 'false'; ?>;
function lockout() {
    inputs.forEach(input => input.disabled = true);
    let unlockTime = <?php echo $locked_until; ?>;
    let now = Math.floor(Date.now() / 1000);
    let seconds = unlockTime - now;
    message.textContent = 'Too many failed attempts. Please try again in ' + seconds + ' seconds.';
    if (seconds > 0) {
        setTimeout(() => { location.reload(); }, seconds * 1000);
    }
}
function checkPasscode() {
    if (isLocked) {
        lockout();
        return;
    }
    let code = '';
    let allFilled = true;
    inputs.forEach(input => {
        if (input.value === '') {
            allFilled = false;
        }
        code += input.value;
    });
    if (!allFilled) {
        inputs.forEach(input => input.style.borderColor = 'red');
        message.textContent = '';
        return;
    }
    if (code === correctPasscode) {
        inputs.forEach(input => input.style.borderColor = 'green');
        message.textContent = '';
        // Reset retry count in session via AJAX
        fetch('../src/reset_passcode_retry.php');
        setTimeout(() => {
            window.location.href = 'admin_signup.html';
        }, 500);
    } else {
        inputs.forEach(input => input.style.borderColor = 'red');
        retryCount++;
        message.textContent = 'Invalid passcode. Please try again.';
        // Update retry count in session via AJAX
        fetch('../src/update_passcode_retry.php?retry=' + retryCount)
            .then(() => {
                if (retryCount >= 2) {
                    message.textContent = 'Too many failed attempts. Please try again in 180 seconds.';
                    inputs.forEach(input => input.disabled = true);
                    fetch('../src/lock_passcode.php');
                    setTimeout(() => { location.reload(); }, 180000);
                }
            });
    }
}
if (!isLocked) {
    inputs.forEach((input, idx) => {
        input.addEventListener('input', function(e) {
            if (e.inputType === 'insertText' && input.value.length === 1 && idx < inputs.length - 1) {
                inputs[idx + 1].focus();
            }
            checkPasscode();
        });
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && input.value === '' && idx > 0) {
                inputs[idx - 1].focus();
            }
        });
    });
    inputs[0].focus();
} else {
    lockout();
}
</script>
</body>
</html>
