<?php
session_start();

// Incluir a conexão com o banco de dados
include_once './config.php';
$pdo = new Config();
include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;

$cpfAluno="";
// var_dump($_POST);
// var_dump($_GET);
// exit();
if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opFatad"){
        include('./index.html');
    }elseif($privilegio=="admFatad"){
        include('./index.html');
    }elseif($privilegio=="opNuc"){
        include('./barOpNuc.php');
    }elseif($privilegio=="opAluno"){
        include('./barOpAluno.php');
    }elseif($privilegio=="Visitante"){
        include('./barVisitante.html');    
    } else { 
    echo 'Sessão não iniciada ou privilégio não definido.'; 
    // Redirecionar para a página de login ou mostrar uma mensagem de erro 
    header('Location: login.php'); exit(); 
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
<body>

    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    $aluno_id = $_GET['idAluno'];
    $nmAluno=$fg->findAlunoEspecifico($aluno_id);
    foreach($nmAluno as $row){
        $nomeAluno=$row->nomeAluno;
        $cpfAluno=$row->cpfAluno;
        break;
    }


    ?>

    <!-- Formulário para cadastrar múltiplos arquivos blob no banco de dados -->
    <br> 
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <h5 style="color: blue;"><?php echo "Para enviar documentos de " . htmlspecialchars($row->nomeAluno, ENT_QUOTES, 'UTF-8') . "!"; ?>
                <a href="alunoGRUDlistDoc.php?idAluno=<?=$aluno_id?>" class="btn btn-danger float-end">Voltar</a>
              </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="alunosGRUDacoes.php" enctype="multipart/form-data">

                    <input type="hidden" name="idAluno" value="<?=$aluno_id?>" ><br>
                    <input type="hidden" name="cpfAluno" value="<?=$cpfAluno?>" >

                    <div class="mb-3">
                    <label for="files">Selecione os arquivos:</label>
                    <input type="file" name="files[]" id="files" multiple accept=".pdf,.jpg,.jpeg,.bmp">
                    </div>
                    <input type="submit" name="CadArquivo" value="Enviar"><br><br>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
      <script>

    </script>
    <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="js/cep.js"></script>
</body>

</html>
            

