<?php
session_start();
require './config.php';
include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;
$caminhoNome="";
$nomeNucleo="";
$nomeDisciplina="";

if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opAluno"){
        include('./barOpAluno.php');
    } else { 
        echo 'Sessão não iniciada ou privilégio não definido.'; 
        // Redirecionar para a página de login ou mostrar uma mensagem de erro 
        header('Location: login.php'); exit(); 
    }
}

//recebe idNucleo por GET ou POST
$nucleo_id=$fg->getAlunoId();

// Preparar e executar a inserção no banco de dados
$nucleo_id= filter_input(INPUT_GET, 'idNucleo', FILTER_SANITIZE_NUMBER_INT);
$idCurso = filter_input(INPUT_GET, 'idCurso', FILTER_SANITIZE_NUMBER_INT);
$idDisciplina = filter_input(INPUT_GET, 'idDisciplina', FILTER_SANITIZE_NUMBER_INT);
$idTurma = filter_input(INPUT_GET, 'idTurma', FILTER_SANITIZE_NUMBER_INT);
$qtdAlunos = filter_input(INPUT_GET, 'qtdAlunos', FILTER_SANITIZE_NUMBER_INT);

$nucleo=$fg->findDescNucleoEspecifico($nucleo_id);
foreach($nucleo as $row){
  $nomeNucleo=$row['descNucleo'];
}

$disciplinas = $fg->findDisciplinaEspecifica($idDisciplina);
foreach($disciplinas as $row){
    $nomeDisciplina=$row['nomeDisciplina'];
  }
//   var_dump($idDisciplina);
//  var_dump($nomeDisciplina);
//  var_dump($_GET);
//  exit();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  </head>
  
  <body>
      
    <br> <br> <br>
    <div class="container mt-4">
      <?php include('mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <tr>
                    <td>
     
                    <h5 style="color: blue">Documentos da Disciplina <?= $nomeDisciplina; ?> 
                  <a href='matEscolarDistribuirIndiv.php' 
                         class="btn btn-danger btn-sm float-end">Voltar</a>
                  </h5>
               
                </td>
                </tr>
             </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome Arquivo</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                    
                
                    $pdo=new config;
                      $stmt = $pdo->prepare("SELECT * FROM tb_downdocs WHERE idCurso=:idCurso and idDisciplina=:idDisciplina");
                      $stmt->bindParam(':idCurso', $idCurso); 
                      $stmt->bindParam(':idDisciplina', $idDisciplina);
                      $stmt->execute();
                  // Obter todos os registros 
                  $nucleos = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                  // Percorrer os registros 
                  foreach ($nucleos as $nucleo) {
                  ?>
                  <tr>
                    <td><?=$nucleo['idDowndocs']?></td>
                    <td><?=$nucleo['nomeArq']?></td>
                    <td>
                      <a href="downloadLivro.php?
                      idDowndocs=<?=$nucleo['idDowndocs']
                      ?>" target='_blank' class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>  
                      <!-- <a href="nucleosGRUDexcluirDoc.php?
                        idNucleo=<?=$nucleo_id?>
                        &idTurma=<?=$idTurma?>
                        &idCurso=<?=$idCurso?>
                        &idDisciplina=<?=$idDisciplina?>
                        &idDowndocs=<?=$nucleo['idDowndocs']?>
                        &nomeArq=<?=$nucleo['nomeArq']?>"  
                        class="btn btn-warning btn-sm"><span class="bi bi-trash"></span>&nbsp;Excluir</a> -->
                    </td>
                      </tr>
                  <?php
                  }
                 
                 ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </body>
</html>
