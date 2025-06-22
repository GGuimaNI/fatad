<?php 
session_start();
require './conexao.php';

$idDowndocs= filter_input(INPUT_GET, 'idDowndocs', FILTER_SANITIZE_NUMBER_INT);
$idDisciplina = filter_input(INPUT_GET, 'idDisciplina', FILTER_SANITIZE_NUMBER_INT);
$nomeDocumento="";
$nomeDisciplina="";
$codigoDisciplina="";
$nivelCurso="";
$idCurso=0;


include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;

if (isset($_SESSION['privilegio'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opFatad"){
        include('./index.html');
    }elseif($privilegio=="admFatad"){
  
    }elseif($privilegio=="opNuc"){
        include('./barOpNuc.php');
    }else{
        //visitante
        include('./barVisitante.html');    
    }
  } else { 
    echo 'Sessão não iniciada ou privilégio não definido.'; 
    // Redirecionar para a página de login ou mostrar uma mensagem de erro 
    header('Location: login.php'); exit(); 
  } 

 $disciplina=$fg->findDisciplinaEspecifica($idDisciplina);
 foreach($disciplina as $row){
    $nomeDisciplina=$row['nomeDisciplina'];
    $codigoDisciplina=$row['codigoDisciplina'];
    $nivelCurso=$row['nivelCurso'];
    $idCurso=$row['idCurso'];

 }

 $documentos=$fg->findCaminhoNomeDisciplinaEspecifica($idDowndocs);
 foreach($documentos as $row){
    $nomeDocumento=$row['nomeArq'];
    break;
 }

//      var_dump($_POST);
//     var_dump($_GET);
//   exit();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documento Excluir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
        <br><br>
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        
                        <h5 style="color: blue;">Doc Vinculado Disciplina:  <?= $nomeDisciplina; ?> - Código:  <?= $codigoDisciplina; ?> - Nível:  <?= $nivelCurso; ?>
                        <a href='mntDocumentosListar.php
                            ?idCurso=<?=$idCurso?>
                            &idDisciplina=<?=$idDisciplina?>
                            &nivelCurso=<?=$nivelCurso?>
                            &nomeDisciplina=<?=$nomeDisciplina?>' 
                         class="btn btn-danger btn-sm float-end">Voltar</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        
                        <form action="mntDoc_acoes.php" method="POST">
            
                            <div class="mb-3">
                                <p style="color: red;">O documento físico não será excluido. Apenas deixará de constar desta lista, até ser reincluido novamente.</p>
                                 <label>Excluir este documento?</label>
                                <p class="form-control">
                                  <?=$idDowndocs;?> - <?=$nomeDocumento;?>
                                </p>
                            </div> 
                            <input type="hidden" id="idDisciplina" name="idDisciplina" value="<?=$idDisciplina?>">
                            <input type="hidden" id="idDowndocs" name="idDowndocs" value="<?=$idDowndocs?>">
                            <div class="mb-3">
                              <button type="submit" name="excluir_documento" class="btn btn-primary btn-sm">Excluir</button>
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