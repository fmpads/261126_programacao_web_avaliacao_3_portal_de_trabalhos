<?php
include "./include/header.php";

if (!isset($_GET['id'])) {
    die("O ID do formulário de vaga não foi informado.");
}

$form_id = $_GET['id'];
$form_query = "SELECT * FROM job_forms WHERE id = $form_id";
$form_result = $conn->query($form_query);
$job_form = $form_result->fetch_assoc();

if (!$job_form) {
    die("Formulário de vaga não encontrado.");
}

$questions_result = $conn->query("SELECT * FROM job_questions WHERE form_id = $form_id");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_title = $_POST['form_title'];
    $conn->query("UPDATE job_forms SET title = '$form_title' WHERE id = $form_id");

    if (!empty($_POST['deleted_questions'])) {
        $deleted_ids = implode(",", array_map('intval', $_POST['deleted_questions']));
        $conn->query("DELETE FROM job_questions WHERE id IN ($deleted_ids)");
    }

    foreach ($_POST['questions'] as $index => $question_text) {
        $question_id = $_POST['question_ids'][$index] ?? null;
        $question_type = $_POST['question_types'][$index];
        $options = isset($_POST['options'][$index]) ? $_POST['options'][$index] : "";

        if ($question_id) {
            $conn->query("UPDATE job_questions SET question_text = '$question_text', question_type = '$question_type', options = '$options' WHERE id = $question_id");
        } else {
            $conn->query("INSERT INTO job_questions (form_id, question_text, question_type, options) VALUES ($form_id, '$question_text', '$question_type', '$options')");
        }
    }

    echo "<script>alert('Formulário de vaga atualizado com sucesso!'); window.location='job_forms.php';</script>";
}
?>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4 text-center">Editar formulário de vaga</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Título do formulário</label>
                <input type="text" name="form_title" class="form-control" value="<?= $job_form['title'] ?>" required>
            </div>

            <h4>Perguntas</h4>
            <input type="hidden" name="deleted_questions[]" id="deleted-questions">

            <div id="questions-container">
                <?php while ($question = $questions_result->fetch_assoc()): ?>
                    <div class="question-row mb-3" data-id="<?= $question['id'] ?>">
                        <input type="hidden" name="question_ids[]" value="<?= $question['id'] ?>">
                        <input type="text" name="questions[]" class="form-control mb-2" placeholder="Digite a pergunta" value="<?= $question['question_text'] ?>" required>
                        <select name="question_types[]" class="form-select mb-2" onchange="toggleOptions(this)">
                            <option value="text" <?= $question['question_type'] == 'text' ? 'selected' : '' ?>>Resposta curta</option>
                            <option value="textarea" <?= $question['question_type'] == 'textarea' ? 'selected' : '' ?>>Parágrafo</option>
                            <option value="number" <?= $question['question_type'] == 'number' ? 'selected' : '' ?>>Número</option>
                            <option value="select" <?= $question['question_type'] == 'select' ? 'selected' : '' ?>>Lista suspensa</option>
                            <option value="file" <?= $question['question_type'] == 'file' ? 'selected' : '' ?>>Envio de arquivo</option>
                        </select>
                        <input type="text" name="options[]" class="form-control mb-2 <?= $question['question_type'] == 'select' ? '' : 'd-none' ?>" placeholder="Opções separadas por vírgulas" value="<?= $question['options'] ?>">
                        <button type="button" class="btn btn-danger btn-sm remove-question">Remover</button>
                    </div>
                <?php endwhile; ?>
            </div>

            <button type="button" class="btn btn-secondary" id="add-question">+ Adicionar pergunta</button>
            <button type="submit" class="btn btn-primary w-100 mt-3">Atualizar formulário</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('add-question').addEventListener('click', function() {
        let container = document.getElementById('questions-container');
        let newQuestion = document.createElement('div');
        newQuestion.classList.add('question-row', 'mb-3');
        newQuestion.innerHTML = `<input type="hidden" name="question_ids[]" value=""><input type="text" name="questions[]" class="form-control mb-2" placeholder="Digite a pergunta" required><select name="question_types[]" class="form-select mb-2" onchange="toggleOptions(this)"><option value="text">Resposta curta</option><option value="textarea">Parágrafo</option><option value="number">Número</option><option value="select">Lista suspensa</option><option value="file">Envio de arquivo</option></select><input type="text" name="options[]" class="form-control mb-2 d-none" placeholder="Opções separadas por vírgulas"><button type="button" class="btn btn-danger btn-sm remove-question">Remover</button>`;
        container.appendChild(newQuestion);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-question')) {
            let questionRow = e.target.closest('.question-row');
            let questionId = questionRow.getAttribute('data-id');
            if (questionId) {
                let deletedQuestions = document.getElementById('deleted-questions');
                deletedQuestions.value += (deletedQuestions.value ? ',' : '') + questionId;
            }
            questionRow.remove();
        }
    });

    function toggleOptions(select) {
        let optionsInput = select.nextElementSibling;
        optionsInput.classList.toggle('d-none', select.value !== 'select');
    }
</script>

<?php include "./include/footer.php" ?>