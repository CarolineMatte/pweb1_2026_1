<?php
define('BASE_PATH', '..');
require_once '../header.php';
require_once '../db.class.php';

$database = new Database();
$db = $database->getConnection();

$busca = $_GET['busca'] ?? '';

// Exclusão
if (isset($_GET['excluir_id'])) {
    $id_excluir = $_GET['excluir_id'];
    $stmt = $db->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $id_excluir);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Usuário excluído com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao excluir usuário.</div>";
    }
}

// Listagem com busca
$query = "SELECT * FROM usuarios";
if (!empty($busca)) {
    $query .= " WHERE nome LIKE :busca OR email LIKE :busca OR login LIKE :busca";
}
$stmt = $db->prepare($query);
if (!empty($busca)) {
    $termo = "%{$busca}%";
    $stmt->bindParam(':busca', $termo);
}
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Usuários</h2>
    <a href="UsuarioForm.php" class="btn btn-success"><i class="fas fa-plus"></i> Novo Usuário</a>
</div>

<form method="GET" action="UsuarioList.php" class="mb-4">
    <div class="input-group">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome, email ou login..." value="<?php echo htmlspecialchars($busca); ?>">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
        <a href="UsuarioList.php" class="btn btn-secondary">Limpar</a>
    </div>
</form>

<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cargo</th>
                <th>Contratação</th>
                <th>E-mail</th>
                <th>Login</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($usuarios) > 0): ?>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['nome']); ?></td>
                        <td><?php echo htmlspecialchars($u['cargo'] ?? ''); ?></td>
                        <td><?php echo $u['data_contratacao'] ? date('d/m/Y', strtotime($u['data_contratacao'])) : ''; ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['login']); ?></td>
                        <td class="text-center">
                            <a href="UsuarioForm.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Editar</a>
                            <a href="UsuarioList.php?excluir_id=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?');"><i class="fas fa-trash"></i> Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center py-4">Nenhum usuário encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../footer.php'; ?>
