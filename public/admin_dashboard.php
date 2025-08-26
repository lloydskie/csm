<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta http-equiv="refresh" content="3;url=index.html"><title>Access Denied</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body><div class="container mt-5"><div class="alert alert-danger"><h4>Access Denied</h4><p>This page is restricted. Please log in as admin to access the dashboard.</p><p>You will be redirected to the home page.</p></div></div></body></html>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <a href="admin_logout.php" class="btn btn-danger mb-3">Logout</a>
    <div class="mb-4">
        <h4>Generate Survey QR Code</h4>
        <form action="../src/generate_qr.php" method="POST">
            <button type="submit" class="btn btn-success">Generate New QR Code Link</button>
        </form>
    </div>
    <div id="survey-results">
        <h4 class="mt-4">Summary of Responses</h4>
        <div class="row">
            <div class="col-md-6">
                <canvas id="serviceChart"></canvas>
                <canvas id="staffChart" class="mt-4"></canvas>
                <canvas id="responseChart" class="mt-4"></canvas>
            </div>
            <div class="col-md-6">
                <h5>Text Responses (Remarks)</h5>
                <div style="max-height:300px;overflow-y:auto;border:1px solid #ccc;padding:10px;">
                    <?php
                    require_once '../database/config.php';
                    $conn = get_db_connection();
                    $remarks = [];
                    $service_ratings = [0,0,0,0,0];
                    $staff_ratings = [0,0,0,0,0];
                    $response_ratings = [0,0,0,0,0];
                    $responses = [];
                    $result = $conn->query("SELECT * FROM survey_responses ORDER BY submitted_at DESC");
                    while ($row = $result->fetch_assoc()) {
                        $remarks[] = $row['remarks'];
                        $service_ratings[$row['service_rating']-1]++;
                        $staff_ratings[$row['staff_rating']-1]++;
                        $response_ratings[$row['response_time_rating']-1]++;
                        $responses[] = $row;
                    }
                    $conn->close();
                    foreach ($remarks as $r) {
                        if (trim($r) !== '') echo '<div class="mb-2">'.htmlspecialchars($r).'</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <h4 class="mt-5">Individual Responses</h4>
        <div style="max-height:300px;overflow-y:auto;border:1px solid #ccc;padding:10px;">
            <?php
            foreach ($responses as $resp) {
                echo '<div class="mb-3 p-2 border rounded">';
                echo '<strong>Name:</strong> '.htmlspecialchars($resp['client_name']).'<br>';
                echo '<strong>Branch:</strong> '.htmlspecialchars($resp['branch']).'<br>';
                echo '<strong>Service Type:</strong> '.htmlspecialchars($resp['service_type']).'<br>';
                echo '<strong>Service Rating:</strong> '.$resp['service_rating'].'<br>';
                echo '<strong>Staff Rating:</strong> '.$resp['staff_rating'].'<br>';
                echo '<strong>Response Time Rating:</strong> '.$resp['response_time_rating'].'<br>';
                echo '<strong>Remarks:</strong> '.htmlspecialchars($resp['remarks']).'<br>';
                echo '<strong>Submitted At:</strong> '.$resp['submitted_at'].'<br>';
                echo '</div>';
            }
            ?>
        </div>
        <h4 class="mt-5">Spreadsheet View</h4>
        <form method="POST" action="../src/export_excel.php">
            <button type="submit" class="btn btn-primary mb-2">Export to Excel</button>
        </form>
        <div style="max-height:300px;overflow-x:auto;border:1px solid #ccc;padding:10px;">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Name</th><th>Branch</th><th>Service Type</th><th>Service Rating</th><th>Staff Rating</th><th>Response Time Rating</th><th>Remarks</th><th>IP</th><th>Location</th><th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($responses as $resp) {
                        echo '<tr>';
                        echo '<td>'.htmlspecialchars($resp['client_name']).'</td>';
                        echo '<td>'.htmlspecialchars($resp['branch']).'</td>';
                        echo '<td>'.htmlspecialchars($resp['service_type']).'</td>';
                        echo '<td>'.$resp['service_rating'].'</td>';
                        echo '<td>'.$resp['staff_rating'].'</td>';
                        echo '<td>'.$resp['response_time_rating'].'</td>';
                        echo '<td>'.htmlspecialchars($resp['remarks']).'</td>';
                        echo '<td>'.htmlspecialchars($resp['ip_address']).'</td>';
                        echo '<td>'.htmlspecialchars($resp['geo_location']).'</td>';
                        echo '<td>'.$resp['submitted_at'].'</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        const serviceData = <?php echo json_encode($service_ratings); ?>;
        const staffData = <?php echo json_encode($staff_ratings); ?>;
        const responseData = <?php echo json_encode($response_ratings); ?>;
        new Chart(document.getElementById('serviceChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['1','2','3','4','5'],
                datasets: [{ label: 'Service Rating', data: serviceData, backgroundColor: 'rgba(54, 162, 235, 0.7)' }]
            }
        });
        new Chart(document.getElementById('staffChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['1','2','3','4','5'],
                datasets: [{ label: 'Staff Rating', data: staffData, backgroundColor: 'rgba(255, 99, 132, 0.7)' }]
            }
        });
        new Chart(document.getElementById('responseChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['1','2','3','4','5'],
                datasets: [{ label: 'Response Time Rating', data: responseData, backgroundColor: 'rgba(75, 192, 192, 0.7)' }]
            }
        });
        </script>
    </div>
</div>
</body>
</html>
