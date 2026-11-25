<?php
include "./include/header.php";

if (isset($_POST['update_status'])) {
    $app_id = $_POST['app_id'];
    $new_status = $_POST['update_status'];
    $conn->query("UPDATE job_applications SET status='$new_status' WHERE id=$app_id");
}

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$job_filter = isset($_GET['job_filter']) ? $_GET['job_filter'] : '';
$status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

$sql = "SELECT ja.*, jf.title AS job_title FROM job_applications ja JOIN job_forms jf ON ja.form_id = jf.id WHERE ja.user_id= '{$_SESSION["userid"]}'";
if (!empty($search)) $sql .= " AND (ja.applicant_name LIKE '%$search%' OR ja.applicant_email LIKE '%$search%')";
if (!empty($job_filter)) $sql .= " AND ja.form_id = $job_filter";
if (!empty($status_filter)) $sql .= " AND ja.status = '$status_filter'";
if (!empty($date_from) && !empty($date_to)) $sql .= " AND ja.submitted_at BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59'";

$total_records = $conn->query($sql)->num_rows;
$total_pages = ceil($total_records / $limit);
$sql .= " ORDER BY ja.submitted_at DESC LIMIT $limit OFFSET $offset";
$applications = $conn->query($sql);
$jobs = $conn->query("SELECT * FROM job_forms where user_id='{$_SESSION["userid"]}'");
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Candidaturas</h2>
        <a href="dashboard.php" class="btn btn-secondary">Voltar ao painel</a>
    </div>

    <div class="card shadow p-4 mb-4">
        <form method="GET">
            <div class="row g-3">
                <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Pesquisar por nome ou e-mail" value="<?= $search ?>"></div>
                <div class="col-md-2"><select name="job_filter" class="form-select"><option value="">Filtrar por vaga</option><?php while ($job = $jobs->fetch_assoc()) { ?><option value="<?= $job['id'] ?>" <?= ($job_filter == $job['id']) ? 'selected' : '' ?>><?= $job['title'] ?></option><?php } ?></select></div>
                <div class="col-md-2"><select name="status_filter" class="form-select"><option value="">Filtrar por situação</option><option value="Pending" <?= ($status_filter == 'Pending') ? 'selected' : '' ?>>Pendente</option><option value="Reviewed" <?= ($status_filter == 'Reviewed') ? 'selected' : '' ?>>Avaliada</option><option value="Hired" <?= ($status_filter == 'Hired') ? 'selected' : '' ?>>Contratada</option></select></div>
                <div class="col-md-2"><input type="date" name="date_from" class="form-control" value="<?= $date_from ?>"></div>
                <div class="col-md-2"><input type="date" name="date_to" class="form-control" value="<?= $date_to ?>"></div>
                <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Filtrar</button></div>
            </div>
        </form>
    </div>

    <div class="card shadow p-4">
        <table class="table table-bordered">
            <thead class="table-dark"><tr><th>Vaga</th><th>Nome do candidato</th><th>E-mail</th><th>Telefone</th><th>Currículo</th><th>Situação</th><th>Enviada em</th></tr></thead>
            <tbody>
                <?php while ($app = $applications->fetch_assoc()) { ?>
                    <tr>
                        <td><a href="view_application_details.php?id=<?= $app['id'] ?>" class="btn btn-info btn-sm"><?= $app['job_title'] ?></a></td>
                        <td><?= $app['applicant_name'] ?></td><td><?= $app['applicant_email'] ?></td><td><?= $app['applicant_mobile'] ?></td>
                        <td><a href="<?= $app['resume'] ?>" target="_blank" class="btn btn-info btn-sm">Ver currículo</a></td>
                        <td><form method="POST" class="d-inline"><input type="hidden" name="app_id" value="<?= $app['id'] ?>"><select name="update_status" class="form-select form-select-sm" onchange="this.form.submit()"><option value="Pending" <?= ($app['status'] == 'Pending') ? 'selected' : '' ?>>Pendente</option><option value="Reviewed" <?= ($app['status'] == 'Reviewed') ? 'selected' : '' ?>>Avaliada</option><option value="Hired" <?= ($app['status'] == 'Hired') ? 'selected' : '' ?>>Contratada</option></select></form></td>
                        <td><?= date('d/m/Y H:i', strtotime($app['submitted_at'])) ?></td>
                    </tr>
                <?php } ?>
                <?php if ($applications->num_rows == 0) { ?><tr><td colspan="7" class="text-center">Nenhuma candidatura encontrada.</td></tr><?php } ?>
            </tbody>
        </table>
    </div>

    <nav class="mt-3"><ul class="pagination justify-content-center"><?php for ($i = 1; $i <= $total_pages; $i++) { ?><li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="view_applications.php?page=<?= $i ?>&search=<?= urlencode($search) ?>&job_filter=<?= urlencode($job_filter) ?>&status_filter=<?= urlencode($status_filter) ?>&date_from=<?= urlencode($date_from) ?>&date_to=<?= urlencode($date_to) ?>"><?= $i ?></a></li><?php } ?></ul></nav>
</div>

<?php include "./include/footer.php"; ?>