<?php
define('BASE_PATH', '..');
require_once '../header.php';
require_once '../db.class.php';

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'] ?? null;
$nome_completo = '';
$cpf = '';
$telefone_contato = '';
$email_contato = '';
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome_completo = trim($_POST['nome_completo']);
    $cpf = trim($_POST['cpf']);
    $telefone_contato = trim($_POST['telefone_contato']);
    $email_contato = trim($_POST['email_contato']);

    if (!empty($nome_completo) && !empty($cpf) && !empty($telefone_contato) && !empty($email_contato)) {
        
        // Verifica se CPF já existe (se não for o próprio)
        $checkQuery = "SELECT id FROM clientes WHERE cpf = :cpf AND id != :id";
        $checkStmt = $db->prepare($checkQuery);
        $checkId = $id ? $id : 0;
        $checkStmt->bindParam(':cpf', $cpf);
        $checkStmt->bindParam(':id', $checkId);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            $mensagem = "<div class='alert alert-danger'>Este CPF já está cadastrado.</div>";
        } else {
            if ($id) {
                $query = "UPDATE clientes SET nome_completo = :nome_completo, cpf = :cpf, telefone_contato = :telefone_contato, email_contato = :email_contato WHERE id = :id";
            } else {
                $query = "INSERT INTO clientes (nome_completo, cpf, telefone_contato, email_contato) VALUES (:nome_completo, :cpf, :telefone_contato, :email_contato)";
            }

            $stmt = $db->prepare($query);
            $stmt->bindParam(':nome_completo', $nome_completo);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':telefone_contato', $telefone_contato);
            $stmt->bindParam(':email_contato', $email_contato);
            if ($id) {
                $stmt->bindParam(':id', $id);
            }

            try {
                if ($stmt->execute()) {
                    $mensagem = "<div class='alert alert-success'>Cliente salvo com sucesso! <a href='ClienteList.php'>Voltar à listagem</a></div>";
                } else {
                    $mensagem = "<div class='alert alert-danger'>Erro ao salvar cliente.</div>";
                }
            } catch(PDOException $e) {
                 $mensagem = "<div class='alert alert-danger'>Erro no banco de dados: " . $e->getMessage() . "</div>";
            }
        }
    } else {
        $mensagem = "<div class='alert alert-warning'>Preencha todos os campos obrigatórios.</div>";
    }
} else if ($id) {
    $stmt = $db->prepare("SELECT * FROM clientes WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($cliente) {
        $nome_completo = $cliente['nome_completo'];
        $cpf = $cliente['cpf'];
        $telefone_contato = $cliente['telefone_contato'];
        $email_contato = $cliente['email_contato'];
    } else {
        $mensagem = "<div class='alert alert-danger'>Cliente não encontrado.</div>";
        $id = null;
    }
}
?>

<div class="mb-3">
    <h2><?php echo $id ? 'Editar Cliente' : 'Novo Cliente'; ?></h2>
</div>

<?php echo $mensagem; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="ClienteForm.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">
            
            <div class="mb-3">
                <label for="nome_completo" class="form-label">Nome Completo *</label>
                <input type="text" class="form-control" id="nome_completo" name="nome_completo" value="<?php echo htmlspecialchars($nome_completo); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF *</label>
                <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>" required>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telefone_contato" class="form-label">Telefone de Contato *</label>
                    <input type="text" class="form-control" id="telefone_contato" name="telefone_contato" value="<?php echo htmlspecialchars($telefone_contato); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="email_contato" class="form-label">E-mail de Contato *</label>
                    <input type="email" class="form-control" id="email_contato" name="email_contato" value="<?php echo htmlspecialchars($email_contato); ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
            <a href="ClienteList.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../footer.php'; ?>
