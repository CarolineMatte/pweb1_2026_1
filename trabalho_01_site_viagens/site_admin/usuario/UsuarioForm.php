<?php
require_once '../header.php';
require_once '../db.class.php';

$db = (new DB())->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : null;
$usuario = [
    'nome' => '',
    'telefone' => '',
    'email' => '',
    'login' => '',
    'senha' => ''
];

if ($id) {
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);

    if (empty($nome) || empty($telefone) || empty($email) || empty($login) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios.";
    } else {
        if ($id) {
            $stmt = $db->prepare("UPDATE usuarios SET nome = ?, telefone = ?, email = ?, login = ?, senha = ? WHERE id = ?");
            $stmt->execute([$nome, $telefone, $email, $login, $senha, $id]);
        } else {
            $stmt = $db->prepare("INSERT INTO usuarios (nome, telefone, email, login, senha) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $telefone, $email, $login, $senha]);
        }
        echo "<script>window.location.href = 'UsuarioList.php';</script>";
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h2><i class="fa-solid fa-user-pen"></i> <?= $id ? 'Editar' : 'Novo' ?> Usuário</h2>
    <a href="UsuarioList.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
</div>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome Completo *</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone *</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">E-mail *</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="login" class="form-label">Login *</label>
                    <input type="text" class="form-control" id="login" name="login" value="<?= htmlspecialchars($usuario['login']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="senha" class="form-label">Senha *</label>
                    <input type="password" class="form-control" id="senha" name="senha" value="<?= htmlspecialchars($usuario['senha']) ?>" required>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../footer.php'; ?>
