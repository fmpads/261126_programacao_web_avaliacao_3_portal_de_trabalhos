<?php
include "./include/header.php";

if (isset($_GET['delete'])) {
    $form_id = $_GET['delete'];
    $conn->query("DELETE FROM job_forms WHERE id = $form_id");
    echo "<script>window.location='job_forms.php';</script>";
}

$forms = $conn->query("SELECT * FROM job_forms where user_id='{$_SESSION["userid"]}' ORDER BY created_at DESC");
?>


<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Formulários de vagas</h2>
        <a href="create_job.php" class="btn btn-primary">+ Criar novo formulário</a>
    </div>

    <div class="card shadow p-4">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Título do formulário</th>
                    <th>Data de criação</th>
                    <th>Link do formulário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($form = $forms->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $form['title'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($form['created_at'])) ?></td>
                        <td>
                            <a href="apply.php?form_id=<?= base64_encode($form['id']) ?>" target="_blank" class="btn btn-success btn-sm">Abrir formulário</a>
                        </td>
                        <td>
                            <a href="edit_job.php?id=<?= $form['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?delete=<?= $form['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza de que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php } ?>

                <?php if ($forms->num_rows == 0) { ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhum formulário encontrado.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "./include/footer.php" ?>