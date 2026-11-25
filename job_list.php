<?php
session_start();
include "conn.php";

$search = trim($_GET['search'] ?? '');
$filter = $_GET['filter'] ?? 'recentes';
$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$where = "WHERE jf.title LIKE '%" . $conn->real_escape_string($search) . "%'";
if ($filter == 'sem_candidaturas') $where .= " AND (SELECT COUNT(*) FROM job_applications ja WHERE ja.form_id = jf.id) = 0";
if ($filter == 'com_candidaturas') $where .= " AND (SELECT COUNT(*) FROM job_applications ja WHERE ja.form_id = jf.id) > 0";

$total = $conn->query("SELECT COUNT(*) AS total FROM job_forms jf $where")->fetch_assoc()['total'];
$total_pages = max(1, ceil($total / $limit));
$page = min($page, $total_pages);
$offset = ($page - 1) * $limit;
$forms = $conn->query("SELECT jf.*, u.email AS author_email, (SELECT COUNT(*) FROM job_applications ja WHERE ja.form_id = jf.id) AS total_candidaturas FROM job_forms jf JOIN users u ON u.id = jf.user_id $where ORDER BY jf.created_at DESC LIMIT $limit OFFSET $offset");

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $html = '';
    if ($forms->num_rows == 0) {
        $html .= '<div class="col-12"><div class="alert alert-secondary">Nenhum trabalho encontrado.</div></div>';
    }
    while ($form = $forms->fetch_assoc()) {
        $title = htmlspecialchars($form['title']);
        $author = htmlspecialchars($form['author_email']);
        $date = date('d/m/Y', strtotime($form['created_at']));
        $count = intval($form['total_candidaturas']);
        $plural = $count == 1 ? '' : 's';
        $formId = base64_encode($form['id']);
        $html .= '<div class="col-md-6"><div class="card shadow-sm p-3 mb-3"><h5>' . $title . '</h5><p class="mb-1"><b>Publicado em:</b> ' . $date . '</p><p class="mb-1"><b>E-mail do responsável:</b> ' . $author . '</p><p class="mb-3 text-muted">' . $count . ' candidatura' . $plural . '</p><a href="apply.php?form_id=' . $formId . '" class="btn btn-primary">Candidatar-se</a></div></div>';
    }

    $pagination = '';
    if ($total_pages > 1) {
        $pagination .= '<nav class="mt-3"><ul class="pagination justify-content-center">';
        if ($page > 1) $pagination .= '<li class="page-item"><button type="button" class="page-link" data-page="' . ($page - 1) . '">Anterior</button></li>';
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = $i == $page ? ' active' : '';
            $pagination .= '<li class="page-item' . $active . '"><button type="button" class="page-link" data-page="' . $i . '">' . $i . '</button></li>';
        }
        if ($page < $total_pages) $pagination .= '<li class="page-item"><button type="button" class="page-link" data-page="' . ($page + 1) . '">Próxima</button></li>';
        $pagination .= '</ul></nav>';
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['html' => $html, 'pagination' => $pagination]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Trabalhos</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Portal de Trabalhos</a>
            <div>
                <?php if (!empty($_SESSION['logged_in'])) { ?>
                    <a href="dashboard.php" class="btn btn-outline-primary btn-sm">Painel</a>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
                <?php } else { ?>
                    <a href="index.php?login=1#login" class="btn btn-outline-primary btn-sm">Entrar</a>
                    <a href="user_register.php" class="btn btn-primary btn-sm">Cadastrar</a>
                <?php } ?>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <h2 class="h4 mb-3">Trabalhos disponíveis</h2>

        <form id="busca-trabalhos" method="GET" class="row g-2 mb-4">
            <div class="col-md-7"><input id="campo-busca" type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Pesquisar trabalho"></div>
            <div class="col-md-5">
                <select id="filtro-trabalhos" name="filter" class="form-select">
                    <option value="recentes" <?= $filter == 'recentes' ? 'selected' : '' ?>>Mais recentes</option>
                    <option value="sem_candidaturas" <?= $filter == 'sem_candidaturas' ? 'selected' : '' ?>>Nenhuma candidatura</option>
                    <option value="com_candidaturas" <?= $filter == 'com_candidaturas' ? 'selected' : '' ?>>Já possui candidatura</option>
                </select>
            </div>
        </form>

        <div class="row" id="resultado-busca">
            <?php if ($forms->num_rows == 0) { ?><div class="col-12"><div class="alert alert-secondary">Nenhum trabalho encontrado.</div></div><?php } ?>
            <?php while ($form = $forms->fetch_assoc()) { ?>
                <div class="col-md-6">
                    <div class="card shadow-sm p-3 mb-3">
                        <h5><?= htmlspecialchars($form['title']) ?></h5>
                        <p class="mb-1"><b>Publicado em:</b> <?= date('d/m/Y', strtotime($form['created_at'])) ?></p>
                        <p class="mb-1"><b>E-mail do responsável:</b> <?= htmlspecialchars($form['author_email']) ?></p>
                        <p class="mb-3 text-muted"><?= intval($form['total_candidaturas']) ?> candidatura<?= intval($form['total_candidaturas']) == 1 ? '' : 's' ?></p>
                        <a href="apply.php?form_id=<?= base64_encode($form['id']) ?>" class="btn btn-primary">Candidatar-se</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if ($total_pages > 1) { ?>
            <nav class="mt-3" id="navegacao-paginas"><ul class="pagination justify-content-center"><?php if ($page > 1) { ?><li class="page-item"><a class="page-link" href="job_list.php?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&filter=<?= urlencode($filter) ?>">Anterior</a></li><?php } ?><?php for ($i = 1; $i <= $total_pages; $i++) { ?><li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link" href="job_list.php?page=<?= $i ?>&search=<?= urlencode($search) ?>&filter=<?= urlencode($filter) ?>"><?= $i ?></a></li><?php } ?><?php if ($page < $total_pages) { ?><li class="page-item"><a class="page-link" href="job_list.php?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&filter=<?= urlencode($filter) ?>">Próxima</a></li><?php } ?></ul></nav>
        <?php } ?>
    </main>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        const formularioBusca = document.getElementById('busca-trabalhos');
        const campoBusca = document.getElementById('campo-busca');
        const filtroBusca = document.getElementById('filtro-trabalhos');
        const resultadoBusca = document.getElementById('resultado-busca');
        let buscaTempo;

        function aplicarBusca(pagina = 1) {
            const parametros = new URLSearchParams();
            parametros.set('search', campoBusca.value);
            parametros.set('filter', filtroBusca.value);
            parametros.set('page', pagina);
            parametros.set('ajax', '1');

            fetch('job_list.php?' + parametros.toString(), {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                resultadoBusca.innerHTML = data.html;

                let navegacao = document.getElementById('navegacao-paginas');
                if (!navegacao) {
                    navegacao = document.createElement('div');
                    navegacao.id = 'navegacao-paginas';
                    document.querySelector('main').appendChild(navegacao);
                }
                navegacao.innerHTML = data.pagination;

                campoBusca.focus();
                const tamanho = campoBusca.value.length;
                campoBusca.setSelectionRange(tamanho, tamanho);
            })
            .catch(error => console.error('Erro ao realizar a busca:', error));
        }

        campoBusca.addEventListener('input', function() {
            clearTimeout(buscaTempo);
            buscaTempo = setTimeout(() => aplicarBusca(), 300);
        });

        filtroBusca.addEventListener('change', () => aplicarBusca());

        document.addEventListener('click', function(e) {
            const botaoPagina = e.target.closest('.page-link[data-page]');
            if (botaoPagina) {
                e.preventDefault();
                aplicarBusca(botaoPagina.dataset.page);
            }
        });
    </script>
</body>
</html>