 <?php
    include '../header.php';
    include_once "../database/db.class.php";

    $db = new db('usuario'); 
    $success = '';
    $actionError ='';
    $errors = [];

    if(!empty($_POST)){
       // var_dump($_POST);
        //exit;
        try{
            if(empty($_POST['nome'])){
                $errors[] = "<li>Nome Obrigatório</li>";
            }
            if(empty($_POST['email'])){
                $errors[] = "<li>Email Obrigatório</li>";
            }
            if (empty($errors)){
            $db->store($_POST);
            $success = "Registro salvo com sucesso!";

            redirect('./UsuarioList.php');
    }
    }catch(PDOException $e){
        $error = $e->getMessage();
    }catch(Exception $e){
        $error = $e->getMessage();
    }
    }
    ?>

    <div class="row">
        <?php actionMessage($success, $actionError) ?>
        <?php showValidationError($errors) ?>
        <form action="UsuarioForm.php" method="post">
            <h3>Formulário Usuario</h3>
            <div class="col-6">
                <label for="nome">Nome</label>
                <input type="text" name="nome" class="form-control" value="<?php echo  getFormValue('nome'); ?>"> 
            </div>
            <div class="col-6">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control"<?php echo  getFormValue('email'); ?>">
            </div>
            <div class="col-6">
                <label for="nome">Telefone</label>
                <input type="text" name="telefone" class="form-control"<?php echo  getFormValue('telefone'); ?>">
            </div>

            <div class="mt-2">
                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="./UsuarioList.php" class="btn btn-primary">Voltar</a>
            </div>

           
        </form>

    </div>
  

        
   

    <?php
    include '../footer.php';
    ?>