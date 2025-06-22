<?php 
session_start();
require './conexao.php';

$idAluno = filter_input(INPUT_GET, 'idAluno', FILTER_SANITIZE_NUMBER_INT);
$idArquivo = filter_input(INPUT_GET, 'idArquivo', FILTER_SANITIZE_NUMBER_INT);
$nomeArq = filter_input(INPUT_GET, 'nomeArq', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cpf = filter_input(INPUT_GET, 'cpf', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// var_dump($_POST);
// var_dump($idAluno);
// var_dump($idArquivo);
// var_dump($cpf);
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

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doc Excluir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
        <br><br>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Excluir
                        <a href="cadastroListDocAluno.php?cpf=<?=$cpf?>" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <form action="cadExcluirDocAlunoPro.php" method="POST">
            
                            <div class="mb-3">
                                 <label>Excluir definitivamente o arquivo?</label>
                                <p class="form-control">
                                  <?=$idArquivo;?> - <?=$nomeArq;?>
                                </p>
                            </div> 
                            <input type="hidden" id="idAluno" name="idAluno" value="<?=$idAluno?>">
                            <input type="hidden" id="idArquivo" name="idArquivo" value="<?=$idArquivo?>">
                            <input type="hidden" id="cpf" name="cpf" value="<?=$cpf?>">                    
                            <div class="card-header">

                            <div class="mb-3">
                              <button type="submit" name="excluirDocAluno" value="excluirDocAluno" class="btn btn-primary">Excluir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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