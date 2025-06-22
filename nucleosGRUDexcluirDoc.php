<?php 
session_start();
require './conexao.php';

$nomeArq=filter_input(INPUT_GET, 'nomeArq', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idArquivo= filter_input(INPUT_GET, 'idArquivo', FILTER_SANITIZE_NUMBER_INT);
$idNucleo = filter_input(INPUT_GET, 'idNucleo', FILTER_SANITIZE_NUMBER_INT);

include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;

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
 $nucleo=$fg->findDescNucleoEspecifico($idNucleo);
 foreach($nucleo as $row){
     $descNucleo=$row['descNucleo'];
    break;
  }

//     var_dump($_POST);
//    var_dump($_GET);
//  exit();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documento Núcleo - Excluir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
        <br><br>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        
                        <h5 style="color: blue;">Doc Vinculado Núcleo:  <?= $descNucleo; ?>
                        <a href="./nucleosGRUDlistDoc.php?idNucleo=<?=$idNucleo ?>" class="btn btn-danger btn-sm float-end">Voltar</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        
                        <form action="nucleosGRUDacoes.php" method="POST">
            
                            <div class="mb-3">
                                 <label>Excluir definitivamente o arquivo?</label>
                                <p class="form-control">
                                  <?=$idArquivo;?> - <?=$nomeArq;?>
                                </p>
                            </div> 
                            <input type="hidden" id="idArquivo" name="idArquivo" value="<?=$idArquivo?>">
                            <input type="hidden" id="idNucleo" name="idNucleo" value="<?=$idNucleo?>">

                            <div class="mb-3">
                              <button type="submit" name="excluirdocumento" class="btn btn-primary btn-sm">Excluir</button>
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

  </body>
</html>