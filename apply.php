<?php
session_start();
include "conn.php";

if (!isset($_GET['form_id'])) die("Link do formulário inválido!");

$form_id = base64_decode($_GET['form_id']);
$form = $conn->query("SELECT * FROM job_forms WHERE id = $form_id")->fetch_assoc();

if (!$form) die("Vaga não encontrada.");

if (empty($_SESSION['logged_in'])) {
    header("Location: index.php?form_id=" . urlencode($_GET['form_id']) . "#login");
    exit();
}

$questions = $conn->query("SELECT * FROM job_questions WHERE form_id = $form_id");
$user = $conn->query("SELECT * FROM useers WHERE id=" . intval($_SESSION['userid']))->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);

    $resume = NULL;
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        if ($_FILES['resume']['size'] <= 2 * 1024 * 1024) {
            $resume = "uploads/" . time() . "_" . basename($_FILES['resume']['name']);
            move_uploaded_file($_FILES['resume']['tmp_name'], $resume);
        } else {
            die("Tipo de arquivo inválido ou arquivo maior que o limite de 2 MB.");
        }
    }

    $safe_name = $conn->real_escape_string($name);
    $safe_email = $conn->real_escape_string($email);
    $safe_mobile = $conn->real_escape_string($mobile);

    $conn->query("INSERT INTO job_responses (user_id, form_id, applicant_name, applicant_email, applicant_mobile, resume) VALUES ('{$form['user_id']}', $form_id, '$safe_name', '$safe_email', '$safe_mobile', '$resume')");
    $conn->query("INSERT INTO job_applications (user_id, form_id, applicant_name, applicant_email, applicant_mobile, resume) VALUES ('{$form['user_id']}', $form_id, '$safe_name', '$safe_email', '$safe_mobile', '$resume')");
    $response_id = $conn->insert_id;

    foreach ($_POST['answers'] ?? [] as $question_id => $user_answer) {
        $safe_answer = $conn->real_escape_string($user_answer);
        $conn->query("INSERT INTO job_answers (response_id, question_id, answer_text) VALUES ($response_id, $question_id, '$safe_answer')");
    }

    echo "<script>alert('Candidatura enviada com sucesso!');window.location='thankyou.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatar-se a <?= htmlspecialchars($form['title']) ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-light bg-white border-bottom">
        <div class="container"><a class="navbar-brand fw-bold" href="index.php">Portal de Trabalhos</a><a href="index.php" class="btn btn-outline-secondary btn-sm">Voltar às vagas</a></div>
    </nav>

    <div class="container py-4">
        <div class="card shadow-sm p-4 mx-auto" style="max-width: 760px">
            <h2 class="h4 mb-4">Candidatar-se à vaga: <?= htmlspecialchars($form['title']) ?></h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3"><label class="form-label">Nome completo</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required></div>
                <div class="mb-3"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required></div>
                <div class="mb-3"><label class="form-label">Telefone celular</label><input type="tel" name="mobile" class="form-control" value="<?= htmlspecialchars($user['mobile'] ?? '') ?>" required></div>

                <?php while ($question = $questions->fetch_assoc()) { ?>
                    <div class="mb-3">
                        <label class="form-label"><?= htmlspecialchars($question['question_text']) ?></label>
                        <?php if ($question['question_type'] == 'text') { ?><input type="text" name="answers[<?= $question['id'] ?>]" class="form-control">
                        <?php } elseif ($question['question_type'] == 'textarea') { ?><textarea name="answers[<?= $question['id'] ?>]" class="form-control"></textarea>
                        <?php } elseif ($question['question_type'] == 'file') { ?><input type="file" name="answers[<?= $question['id'] ?>]" class="form-control" required>
                        <?php } elseif ($question['question_type'] == 'number') { ?><input type="number" name="answers[<?= $question['id'] ?>]" class="form-control">
                        <?php } elseif ($question['question_type'] == 'select') { ?><select name="answers[<?= $question['id'] ?>]" class="form-select"><?php foreach (explode(",", $question['options']) as $option) { ?><option value="<?= htmlspecialchars(trim($option)) ?>"><?= htmlspecialchars(trim($option)) ?></option><?php } ?></select><?php } ?>
                    </div>
                <?php } ?>

                <div class="mb-3"><label class="form-label">Enviar currículo</label><input type="file" name="resume" class="form-control" required></div>
                <button type="submit" class="btn btn-primary w-100">Enviar candidatura</button>
            </form>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>