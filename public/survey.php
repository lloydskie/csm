<?php
require_once '../database/config.php';
// Restrict direct access unless a valid token is present
if (!isset($_GET['token']) || empty($_GET['token'])) {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Access Denied</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><div class="container mt-5"><div class="alert alert-danger"><h4>Access Denied</h4><p>This survey page is restricted. Please use the link provided by the admin.</p></div></div></body></html>';
    exit();
}

$token = $_GET['token'];
$conn = get_db_connection();
$stmt = $conn->prepare("SELECT used FROM survey_links WHERE token = ?");
$stmt->bind_param('s', $token);
$stmt->execute();
$stmt->bind_result($used);
$stmt->fetch();
$stmt->close();
$conn->close();
if ($used == 1 && !isset($_GET['success'])) {
    // If already used and not just submitted, show used message directly
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Survey Link Used</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><div class="container mt-5"><div class="alert alert-danger"><h4>This survey link has already been used.</h4><p>Thank you for your participation! If you have already submitted your response, we appreciate your feedback.</p></div></div></body></html>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Satisfaction Survey</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Client Satisfaction Survey</h2>
    <!-- Success or used message -->
    <div id="survey-message">
            <!-- PHP logic for message -->
            <?php
            if (isset($_GET['success'])) {
                echo '<div class="alert alert-success"><h4>Thank you for accomplishing the survey!</h4><p>Your feedback is valuable and helps us improve our services. Have a great day!</p></div>';
            } elseif (isset($_GET['used'])) {
                echo '<div class="alert alert-danger"><h4>This survey link has already been used.</h4><p>Thank you for your participation! If you have already submitted your response, we appreciate your feedback.</p></div>';
            }
            ?>
    </div>
    <?php if (!isset($_GET['success']) && !isset($_GET['used'])): ?>
    <form id="surveyForm" action="../src/submit_survey.php<?php echo isset($_GET['token']) ? '?token=' . htmlspecialchars($_GET['token']) : ''; ?>" method="POST">
        <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
        <div class="mb-3">
            <label for="client_name" class="form-label">Name (optional)</label>
            <input type="text" class="form-control" id="client_name" name="client_name">
        </div>
        <div class="mb-3">
            <label for="branch" class="form-label">Branch</label>
            <input type="text" class="form-control" id="branch" name="branch" required>
        </div>
        <div class="mb-3">
            <label for="service_type" class="form-label">Service Type</label>
            <input type="text" class="form-control" id="service_type" name="service_type" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Service Rating</label>
            <select class="form-select" name="service_rating" required title="Service Rating">
                <option value="">Select</option>
                <option value="1">1 - Poor</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Staff Rating</label>
            <select class="form-select" name="staff_rating" required title="Staff Rating">
                <option value="">Select</option>
                <option value="1">1 - Poor</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Response Time Rating</label>
            <select class="form-select" name="response_time_rating" required title="Response Time Rating">
                <option value="">Select</option>
                <option value="1">1 - Poor</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Survey</button>
    </form>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
