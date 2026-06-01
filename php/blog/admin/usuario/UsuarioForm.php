<?php
include '../header.php';
include_once "../database/db.class.php";

$db = new db('usuario');
$success = '';
$actionError = '';
$errors = [];

if (!empty($_GET['id'])) {
    $data = $db->find($_GET['id']);
}

if (!empty($_POST)) {

    $data = (object) $_POST;
    // var_dump($_POST);
    //exit;
    try {

        if (empty($_POST['nome'])) {
            $errors[] = "<li>O nome é obrigatório</li>";
        }

        if (empty($_POST['email'])) {
            $errors[] = "<li>O email é obrigatório</li>";
        }

        if (empty($errors)) {
            if (empty($_POST['id'])) {
                $db->store($_POST);
                $success = "Registro Salvo com sucesso!";
            } else {
                //$db->update($_POST);
                $success = "Registro Atualizado com sucesso!";
            }

            redirect('./UsuarioList.php');
        }
    } catch (PDOException $e) {
        $actionError = $e->getMessage();
    } catch (Exception $e) {
        $actionError = $e->getMessage();
    }
}

?>

<div class="row">
    <?php actionMessage($success, $actionError) ?>
    <?php showValidationError($errors) ?>

    <form action="UsuarioForm.php" method="post">
        <h3>Formulário Usuário</h3>
        <input type="hidden" name="id" value="<?php echo getFormValue($data, 'nome'); ?>">
        <div class="col-6">
            <label for="nome">Nome</label>
            <input type="text" name="nome" class="form-control" value="<?php echo getFormValue($data, 'nome'); ?>">
        </div>
        <div class="col-6">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo getFormValue($data, 'email'); ?>">
        </div>
        <div class="col-6">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" class="form-control" value="<?php echo getFormValue($data, 'telefone'); ?>">
        </div>
        <div class="mt-2">
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="./UsuarioList.php" class="btn btn-primary"> Voltar</a>
        </div>


    </form>

</div>

<?php
include '../footer.php';
?>