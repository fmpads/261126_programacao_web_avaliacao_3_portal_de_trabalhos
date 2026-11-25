<?php
include "./conn.php";

if (!isset($_GET['id'])) die("O ID da candidatura não foi informado.");
$app_id = $_GET['id'];
$app_result = $conn->query("SELECT a.*, j.title AS job_title FROM job_applications a JOIN job_forms j ON a.form_id = j.id WHERE a.id = $app_id");
$application = $app_result->fetch_assoc();
if (!$application) die("Candidatura não encontrada.");

$answers_result = $conn->query("SELECT job_questions.question_text, job_questions.question_type, job_answers.answer_text FROM job_answers JOIN job_questions ON job_answers.question_id = job_questions.id WHERE response_id = $app_id");
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Vaga')->setCellValue('B1', $application['job_title']);
$sheet->setCellValue('A2', 'Nome')->setCellValue('B2', $application['applicant_name']);
$sheet->setCellValue('A3', 'E-mail')->setCellValue('B3', $application['applicant_email']);
$sheet->setCellValue('A4', 'Candidatou-se em')->setCellValue('B4', date('d/m/Y, H:i', strtotime($application['submitted_at'])));
$sheet->setCellValue('A5', 'Situação')->setCellValue('B5', $application['status']);
$sheet->setCellValue('A6', 'Currículo')->setCellValue('B6', $application['resume']);

$row = 8;
$sheet->setCellValue("A$row", "Pergunta")->setCellValue("B$row", "Resposta");
$row++;
while ($answer = $answers_result->fetch_assoc()) {
    $sheet->setCellValue("A$row", $answer['question_text']);
    $sheet->setCellValue("B$row", $answer['answer_text']);
    $row++;
}

$fileName = "Candidatura_{$app_id}.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=$fileName");
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
