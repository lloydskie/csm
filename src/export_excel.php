<?php
require_once '../database/config.php';
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = get_db_connection();
$result = $conn->query("SELECT * FROM survey_responses ORDER BY submitted_at DESC");
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Name');
$sheet->setCellValue('B1', 'Branch');
$sheet->setCellValue('C1', 'Service Type');
$sheet->setCellValue('D1', 'Service Rating');
$sheet->setCellValue('E1', 'Staff Rating');
$sheet->setCellValue('F1', 'Response Time Rating');
$sheet->setCellValue('G1', 'Remarks');
$sheet->setCellValue('H1', 'IP');
$sheet->setCellValue('I1', 'Location');
$sheet->setCellValue('J1', 'Submitted At');
$row = 2;
while ($resp = $result->fetch_assoc()) {
    $sheet->setCellValue('A'.$row, $resp['client_name']);
    $sheet->setCellValue('B'.$row, $resp['branch']);
    $sheet->setCellValue('C'.$row, $resp['service_type']);
    $sheet->setCellValue('D'.$row, $resp['service_rating']);
    $sheet->setCellValue('E'.$row, $resp['staff_rating']);
    $sheet->setCellValue('F'.$row, $resp['response_time_rating']);
    $sheet->setCellValue('G'.$row, $resp['remarks']);
    $sheet->setCellValue('H'.$row, $resp['ip_address']);
    $sheet->setCellValue('I'.$row, $resp['geo_location']);
    $sheet->setCellValue('J'.$row, $resp['submitted_at']);
    $row++;
}
$conn->close();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="survey_responses.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;