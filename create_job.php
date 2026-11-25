<?php
include "./include/header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_title = $_POST['form_title'];
    $conn->query("INSERT INTO job_forms (title,user_id) VALUES ('$form_title', '{$_SESSION["userid"]}')");
    $form_id = $conn->insert_id;

    foreach ($_POST['questions'] as $index => $question_text) {
        $question_type = $_POST['question_types'][$index];
        $options = isset($_POST['options'][$index]) ? $_POST['options'][$index] : "";
        $conn->query("INSERT INTO job_questions (form_id, question_text, question_type, options) VALUES ($form_id, '$question_text', '$question_type', '$options')");
    }

    echo "<script>alert('Formulário criado com sucesso!'); window.location='job_forms.php';</script>";
}
?>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4 text-center">Criar formulário de vaga</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Título do formulário</label>
                <input type="text" name="form_title" class="form-control" required>
            </div>

            <h4>Perguntas</h4>
            <div id="questions-container">
                <div class="question-row mb-3">
                    <input type="text" name="questions[]" class="form-control mb-2" placeholder="Digite a pergunta" required>
                    <select name="question_types[]" class="form-select mb-2" onchange="toggleOptions(this)">
                        <option value="text">Resposta curta</option>
                        <option value="textarea">Parágrafo</option>
                        <option value="number">Número</option>
                        <option value="select">Lista suspensa</option>
                        <option value="file">Envio de arquivo</option>
                    </select>
                    <input type="text" name="options[]" class="form-control mb-2 d-none" placeholder="Opções separadas por vírgulas">
                    <button type="button" class="btn btn-danger btn-sm remove-question">Remover</button>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="add-question">+ Adicionar pergunta</button>
            <button type="submit" class="btn btn-primary w-100 mt-3">Criar formulário</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('add-question').addEventListener('click', function() {
        let container = document.getElementById('questions-container');
        let newQuestion = document.createElement('div');
        newQuestion.classList.add('question-row', 'mb-3');
        newQuestion.innerHTML = `
        <input type="text" name="questions[]" class="form-control mb-2" placeholder="Digite a pergunta" required>
        <select name="question_types[]" class="form-select mb-2" onchange="toggleOptions(this)">
            <option value="text">Resposta curta</option><option value="textarea">Parágrafo</option>
            <option value="number">Número</option><option value="select">Lista suspensa</option>
            <option value="file">Envio de arquivo</option>
        </select>
        <input type="text" name="options[]" class="form-control mb-2 d-none" placeholder="Opções separadas por vírgulas">
        <button type="button" class="btn btn-danger btn-sm remove-question">Remover</button>`;
        container.appendChild(newQuestion);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-question')) e.target.parentElement.remove();
    });

    function toggleOptions(select) {
        let optionsInput = select.nextElementSibling;
        optionsInput.classList.toggle('d-none', select.value !== 'select');
    }
</script>

<?php include "./include/footer.php"; ?>