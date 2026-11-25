<div class="sidebar">
    <a href="dashboard.php">
        <h4 class="text-center py-3">Portal de Trabalhos</h4>
    </a>
    <a href="dashboard.php"><i class="bi bi-house-door"></i> <span>Painel</span></a>
    <?php if ($_SESSION['userrole'] == "admin") { ?>
        <a href="user_register.php"><i class="bi bi-person-plus-fill"></i> <span>Cadastro de usuário</span></a>
        <a href="users_list.php"><i class="bi bi-file-earmark-person"></i> <span>Lista de usuários</span></a>
    <?php } ?>
    <a href="create_job.php"><i class="bi bi-file-earmark-plus"></i> <span>Criar vaga</span></a>
    <a href="job_list.php"><i class="bi bi-card-list"></i> <span>Lista de vagas</span></a>
    <a href="job_forms.php"><i class="bi bi-card-checklist"></i> <span>Formulários de vagas</span></a>
    <a href="view_applications.php"><i class="bi bi-list-check"></i> <span>Ver candidaturas</span></a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> <span>Sair</span></a>
</div>

<div class="content">
    <nav class="navbar navbar-expand-lg navbar-light shadow mb-4 sticky-top">
        <div class="container-fluid">
            <span class="navbar-brand">Bem-vindo(a), <?= $_SESSION['username'] ?></span>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </nav>