<?php
include "./include/header.php";

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM useers WHERE id = $id");
    echo "<script>window.location='users_list.php';</script>";
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$where = "WHERE role='user'";
if (!empty($search)) {
    $where .= " AND name LIKE '%$search%'";
}

$total_query = "SELECT COUNT(*) AS total FROM useers $where";
$total_result = $conn->query($total_query);
$total_users = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_users / $limit);

$query = "SELECT * FROM useers $where ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lista de usuários</h2>
        <a href="user_register.php" class="btn btn-primary">+ Cadastrar usuário</a>
    </div>

    <form method="GET" action="users_list.php" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Pesquisar pelo nome..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-secondary me-2">Pesquisar</button>
        <a href="users_list.php" class="btn btn-danger">Limpar</a>
    </form>

    <div class="card shadow p-4">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Ações</th></tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['mobile'] ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?delete=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza de que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <nav class="mt-3">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="users_list.php?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a></li>
            <?php } ?>
        </ul>
    </nav>
</div>

<?php include "./include/footer.php"; ?>
