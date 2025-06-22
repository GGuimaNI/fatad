<?php
session_start();
require './conexao.php';
include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;
$argumento="";
$nomeNucleo="";

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

//Esta função é utilizada porque o idNucleo pode vir por GET ou por POST
$nucleo_id = $fg->getNucleoId();
if ($nucleo_id === null || $nucleo_id === false) {
    echo "ID do núcleo inválido";
    exit;
}
// var_dump($_POST);
// var_dump($_GET);
// exit();

$nucleo=$fg->findDescNucleoEspecifico($nucleo_id);
foreach($nucleo as $row){
  $nomeNucleo=$row['descNucleo'];
}
// var_dump($_POST);
// var_dump($_GET);
// exit();

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
      <?php
      if (isset($_SESSION['message'])) {
          echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
          unset($_SESSION['message']); // Limpa a mensagem após exibição
      }
      ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <tr>
                    <td><h5 style="color: blue">Documentos <?= $nomeNucleo; ?>
                      <!--<a href="nucleosGRUDpesqDoc.php?idNucleo=<?=$nucleo_id?>" class="btn btn-link"><span class="bi bi-search"></span>&nbsp;Pesquisar</a>-->
                      <a href="nucleosGRUDcadDoc.php?idNucleo=<?=$nucleo_id?>" class="btn btn-primary btn-sm float-end">Novo Documento&nbsp;</a>
                      <!-- <a href="nucleosGRUD.php" class="btn btn-danger float-end">Voltar</a>                    -->
                    </td></h5>
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

                      $sql = "SELECT idArquivo, nomeArq 
                            FROM tb_docnucleos Where idNucleo=$nucleo_id "; 
                  
                  $nucleos = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($nucleos) > 0) {
                    foreach($nucleos as $nucleo) {
                  ?>
                  <tr>
                    <td><?=$nucleo['idArquivo']?></td>
                    <td><?=$nucleo['nomeArq']?></td>
                    <td>
                      <a href="nucleosGRUDdocView.php?idArquivo=<?=$nucleo['idArquivo']?>" target='_blank' class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>  
                      <a href="nucleosGRUDexcluirDoc.php?idNucleo=<?=$nucleo_id?>&idArquivo=<?=$nucleo['idArquivo']?>&nomeArq=<?=$nucleo['nomeArq']?>"  class="btn btn-warning btn-sm"><span class="bi bi-trash"></span>&nbsp;Excluir</a>
                   </tr>
                  <?php
                  }
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
