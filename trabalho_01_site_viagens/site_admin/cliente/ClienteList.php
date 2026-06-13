<?php
require_once '../header.php';
require_once '../db.class.php';

$db = (new DB())->getConnection();

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    try {
        $stmt = $db->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        echo "<div class='alert alert-success'>Cliente excluído com sucesso!</div>";
    } catch(PDOException $e) {
        echo "<div class='alert alert-danger'>Erro ao excluir: este cliente possui reservas vinculadas.</div>";
    }
}

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
if (!empty($busca)) {
    $stmt = $db->prepare("SELECT * FROM clientes WHERE nome_completo LIKE ? OR cpf LIKE ?");
    $stmt->execute(["%$busca%", "%$busca%"]);
} else {
    $stmt = $db->query("SELECT * FROM clientes");
}
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h2><i class="fa-solid fa-person-walking-luggage"></i> Gerenciar Clientes</h2>
    <a href="ClienteForm.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Novo Cliente</a>
</div>

<form method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou CPF..." value="<?= htmlspecialchars($busca) ?>">
        <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        <?php if (!empty($busca)): ?>
            <a href="ClienteList.php" class="btn btn-outline-danger">Limpar</a>
        <?php endif; ?>
    </div>
</form>

<div class="table-responsive shadow-sm">
    <table class="table table-hover table-bordered bg-white mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome Completo</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clientes) > 0): ?>
                <?php foreach ($clientes as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['nome_completo']) ?></td>
                    <td><?= htmlspecialchars($c['cpf']) ?></td>
                    <td><?= htmlspecialchars($c['telefone_contato']) ?></td>
                    <td><?= htmlspecialchars($c['email_contato']) ?></td>
                    <td class="text-center">
                        <a href="ClienteForm.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fa-solid fa-pen"></i></a>
                        <a href="ClienteList.php?excluir=<?= $c['id'] ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este cliente?');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum cliente encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../footer.php'; ?>
