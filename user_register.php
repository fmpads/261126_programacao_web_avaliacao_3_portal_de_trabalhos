<?php
session_start();
include "conn.php";

$return_form = $_GET['form_id'] ?? '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $safe_name = $conn->real_escape_string($name);
    $safe_email = $conn->real_escape_string($email);
    $safe_mobile = $conn->real_escape_string($mobile);

    $check = $conn->query("SELECT id FROM users WHERE email='$safe_email'");
    if ($check->num_rows > 0) {
        $error = "Este e-mail já está cadastrado.";
    } else {
        $query = "INSERT INTO users (name, username, email, mobile, password, role) VALUES ('$safe_name', '$safe_email', '$safe_email', '$safe_mobile', '$password', 'user')";
        if ($conn->query($query)) {
            $_SESSION['logged_in'] = true;
            $_SESSION['userid'] = $conn->insert_id;
            $_SESSION['username'] = $name;
            $_SESSION['userrole'] = 'user';

            $form_id = $_POST['form_id'] ?? '';
            if (!empty($form_id)) {
                echo "<script>window.location='apply.php?form_id=" . htmlspecialchars($form_id, ENT_QUOTES) . "';</script>";
            } else {
                echo "<script>window.location='index.php';</script>";
            }
            exit();
        } else {
            $error = "Não foi possível concluir o cadastro.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-light bg-white border-bottom">
        <div class="container"><a class="navbar-brand fw-bold" href="index.php">Portal de Trabalhos</a><a href="index.php" class="btn btn-outline-secondary btn-sm">Voltar</a></div>
    </nav>

    <main class="container py-5">
        <div class="card shadow-sm p-4 mx-auto" style="max-width: 520px">
            <h2 class="h4 mb-4 text-center">Cadastro</h2>
            <?php if (!empty($error)) { ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php } ?>
            <form method="POST">
                <?php if (!empty($return_form)) { ?><input type="hidden" name="form_id" value="<?= htmlspecialchars($return_form) ?>"><?php } ?>
                <div class="mb-3"><label class="form-label">Nome</label><input type="text" name="name" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Telefone celular</label><input type="text" name="mobile" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Senha</label><input type="password" name="password" class="form-control" required></div>
                <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
            </form>
        </div>
    </main>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>