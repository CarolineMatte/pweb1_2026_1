<?php
require_once '../header.php';
require_once '../db.class.php';

$db = (new DB())->getConnection();

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    echo "<div class='alert alert-success'>Usuário excluído com sucesso!</div>";
}

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
if (!empty($busca)) {
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE nome LIKE ? OR email LIKE ?");
    $stmt->execute(["%$busca%", "%$busca%"]);
} else {
    $stmt = $db->query("SELECT * FROM usuarios");
}
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h2><i class="fa-solid fa-users"></i> Gerenciar Usuários</h2>
    <a href="UsuarioForm.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Novo Usuário</a>
</div>

<form method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou email..." value="<?= htmlspecialchars($busca) ?>">
        <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        <?php if (!empty($busca)): ?>
            <a href="UsuarioList.php" class="btn btn-outline-danger">Limpar</a>
        <?php endif; ?>
    </div>
</form>

<div class="table-responsive shadow-sm">
    <table class="table table-hover table-bordered bg-white mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Login</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($usuarios) > 0): ?>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td><?= htmlspecialchars($u['telefone']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['login']) ?></td>
                    <td class="text-center">
                        <a href="UsuarioForm.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fa-solid fa-pen"></i></a>
                        <a href="UsuarioList.php?excluir=<?= $u['id'] ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum usuário encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../footer.php'; ?>
