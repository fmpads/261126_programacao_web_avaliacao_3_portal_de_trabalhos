<?php
include "./include/header.php";
$id = $_GET['id'];
$user = $conn->query("SELECT * FROM useers WHERE id=$id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE useers SET name='$name', email='$email', mobile='$mobile', password='$hashed_password' WHERE id=$id";
    } else {
        $update_query = "UPDATE useers SET name='$name', email='$email', mobile='$mobile' WHERE id=$id";
    }

    if ($conn->query($update_query)) {
        echo "<script>window.location='users_list.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o usuário!');</script>";
    }
}
?>


<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4 text-center">Editar usuário</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" value="<?= $user['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Telefone celular</label>
                <input type="text" name="mobile" class="form-control" value="<?= $user['mobile'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nova senha (deixe em branco para manter a senha atual)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success w-100">Atualizar</button>
        </form>
    </div>
</div>


<?php include "./include/footer.php"; ?>