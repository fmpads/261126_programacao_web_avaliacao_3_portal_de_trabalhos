<?php
include "include/header.php";

if (!isset($_GET['id'])) {
    die("O ID da candidatura não foi informado.");
}

$app_id = $_GET['id'];
$app_query = "SELECT a.*, j.title AS job_title FROM job_applications a JOIN job_forms j ON a.form_id = j.id WHERE a.id = $app_id";
$app_result = $conn->query($app_query);
$application = $app_result->fetch_assoc();

if (!$application) {
    die("Candidatura não encontrada.");
}
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalhes da candidatura</h2>
        <span>
            <a href="view_applications.php" class="btn btn-secondary">Voltar</a>
            <a href="export_application.php?id=<?= $app_id ?>" class="btn btn-success">Exportar para Excel</a>
        </span>
    </div>

    <?php if (isset($_GET['status_updated'])): ?><div class="alert alert-success">Situação da candidatura atualizada com sucesso.</div><?php endif; ?>
    <?php if (isset($_GET['email_sent'])): ?><div class="alert alert-success">E-mail enviado ao candidato.</div><?php endif; ?>

    <div class="card p-4 mb-4">
        <h4><?= $application['job_title'] ?> - Candidatura</h4>
        <p><b>Nome:</b> <?= $application['applicant_name'] ?></p>
        <p><b>E-mail:</b> <?= $application['applicant_email'] ?></p>
        <p><b>Candidatou-se em:</b> <?= date('d/m/Y, H:i', strtotime($application['submitted_at'])) ?></p>
        <p><b>Currículo:</b> <a href="<?= $application['resume'] ?>" target="_blank" class="btn btn-primary btn-sm">Baixar currículo</a></p>
        <p>
            <b>Situação:</b>
            <span class="badge bg-<?= ($application['status'] == 'Pending') ? 'warning' : (($application['status'] == 'Reviewed') ? 'info' : 'success') ?>">
                <?php if ($application['status'] == 'Pending') echo 'Pendente'; elseif ($application['status'] == 'Reviewed') echo 'Avaliada'; else echo 'Contratada'; ?>
            </span>
        </p>
    </div>

    <div class="card p-3">
        <h4 class="mb-3">Respostas</h4>
        <div class="border p-3 bg-white">
            <?php
            $answers = $conn->query("SELECT job_questions.question_text, job_questions.question_type, job_answers.answer_text FROM job_answers JOIN job_questions ON job_answers.question_id = job_questions.id WHERE response_id = {$application['id']}");
            while ($answer = $answers->fetch_assoc()) {
                if ($answer['question_type'] == "file") {
                    echo "<div class='mb-3 p-2 border rounded'><b>{$answer['question_text']}</b><br><a href='{$answer['answer_text']}' target='_blank' class='btn btn-primary btn-sm'>Baixar arquivo</a></div>";
                } else {
                    echo "<div class='mb-3 p-2 border rounded'><b>{$answer['question_text']}</b><br> {$answer['answer_text']}</div>";
                }
            }
            ?>
        </div>
    </div>
</div>

<?php include "include/footer.php"; ?>