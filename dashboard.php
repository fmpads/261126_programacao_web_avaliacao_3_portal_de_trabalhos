<?php
include "./include/header.php";

$totalJobs = $conn->query("SELECT COUNT(*) AS total FROM job_forms where user_id='{$_SESSION["userid"]}'")->fetch_assoc()['total'];
$totalApplications = $conn->query("SELECT COUNT(*) AS total FROM job_applications where user_id='{$_SESSION["userid"]}'")->fetch_assoc()['total'];
$pendingReviews = $conn->query("SELECT COUNT(*) AS total FROM job_applications WHERE user_id='{$_SESSION["userid"]}' and status = 'Pending'")->fetch_assoc()['total'];
?>

<h3>Painel</h3>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card card1 bg-primary text-white p-3">
            <h5>Total de trabalhos publicados</h5>
            <h3><?= $totalJobs ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card1 bg-success text-white p-3">
            <h5>Candidaturas recebidas</h5>
            <h3><?= $totalApplications ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card1 bg-warning text-white p-3">
            <h5>Avaliações pendentes</h5>
            <h3><?= $pendingReviews ?></h3>
        </div>
    </div>
</div>

<?php include "./include/footer.php" ?>