<?php
require_once '../header.php';
require_once '../db.class.php';

$db = (new DB())->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : null;
$cliente = [
    'nome_completo' => '',
    'cpf' => '',
    'telefone_contato' => '',
    'email_contato' => ''
];

if ($id) {
    $stmt = $db->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = trim($_POST['nome_completo']);
    $cpf = trim($_POST['cpf']);
    $telefone_contato = trim($_POST['telefone_contato']);
    $email_contato = trim($_POST['email_contato']);

    if (empty($nome_completo) || empty($cpf) || empty($telefone_contato) || empty($email_contato)) {
        $erro = "Todos os campos são obrigatórios.";
    } else {
        if ($id) {
            $stmt = $db->prepare("UPDATE clientes SET nome_completo = ?, cpf = ?, telefone_contato = ?, email_contato = ? WHERE id = ?");
            $stmt->execute([$nome_completo, $cpf, $telefone_contato, $email_contato, $id]);
        } else {
            $stmt = $db->prepare("INSERT INTO clientes (nome_completo, cpf, telefone_contato, email_contato) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome_completo, $cpf, $telefone_contato, $email_contato]);
        }
        echo "<script>window.location.href = 'ClienteList.php';</script>";
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h2><i class="fa-solid fa-user"></i> <?= $id ? 'Editar' : 'Novo' ?> Cliente</h2>
    <a href="ClienteList.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
</div>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome_completo" class="form-label">Nome Completo *</label>
                    <input type="text" class="form-control" id="nome_completo" name="nome_completo" value="<?= htmlspecialchars($cliente['nome_completo']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cpf" class="form-label">CPF *</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" value="<?= htmlspecialchars($cliente['cpf']) ?>" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefone_contato" class="form-label">Telefone de Contato *</label>
                    <input type="text" class="form-control" id="telefone_contato" name="telefone_contato" value="<?= htmlspecialchars($cliente['telefone_contato']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email_contato" class="form-label">E-mail de Contato *</label>
                    <input type="email" class="form-control" id="email_contato" name="email_contato" value="<?= htmlspecialchars($cliente['email_contato']) ?>" required>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../footer.php'; ?>
