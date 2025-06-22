<?php 
session_start(); 
require './barOpNuc.php';
$usuario="";
$sql="";

// Verificar se o valor de $_SESSION['usuario'] está correto no início 
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
    header('Location: logout.php'); exit(); 
  }
}

require './conexao.php';
$argumento="";
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  </head>
  
  <body>

  <div class="container mt-4">
  <br><br>
      <?php include('mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              
                <tr>
                <?php if($privilegio=="opNuc"){ ?>
                  <td><h5 style='color: blue'>Lista de Turmas
                      <a href="criarTurmasGRUDcreate.php" class="btn btn-primary float-end">Cadastrar&nbsp;</a></h5>
                    </td>
                  <?php } else{?>
                    <td><h5 style='color: blue'>Lista de Turmas
                      <a href="criarTurmasGRUDpesq.php" class="btn btn-link"><span class="bi bi-search"></span>&nbsp;Filtrar Núcleo</a>
                      <a href="criarTurmasGRUDcreate.php" class="btn btn-primary float-end">Cadastrar&nbsp;</a></h5>
                    </td>
                <?php } ?>
                </tr>
              
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Curso</th>
                    <th>Núcleo</th>
                    <th>Nome Resp</th>
                    <th>Contato</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if(isset($_POST['argumento'])){
                    $argumento=$_POST['argumento'];
                  }
                  
                  if($privilegio=="opNuc"){
                     $sql = $sqlp;
                    //  var_dump($sql);
                    //  exit();
                  }else{
                  $sql = "SELECT * "
                    ."FROM fatadgestao.tb_turma as t, tb_nucleofatad as n, tb_cursos as c "
                    ."where t.idNucleo=n.idNucleo and t.idCursoCurriculo=c.idCurso "
                    ."and n.descNucleo LIKE '%$argumento%'";
                  }
                  $cursos = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($cursos) > 0) {
                    foreach($cursos as $curso) {
                  ?>
                  <tr>
                    <td><?=$curso['nomeCurso']?></td>
                    <td><?=$curso['descNucleo']?></td>
                    <td><?=$curso['nomeRespNucleo']?></td>
                    <td><?=$curso['telZap']?></td>
                    <td>
                      <?php if($privilegio=="opNuc"){ ?>
                        <a href="criarTurmasGRUDview.php?idTurma=<?=$curso['idTurma']?>&idCurso=<?=$curso['idCurso']?>" class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>
                      <?php }else{ ?>
                        <a href="criarTurmasGRUDview.php?idTurma=<?=$curso['idTurma']?>&idCurso=<?=$curso['idCurso']?>" class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>
                        <a href="criarTurmasGRUDedit.php?idTurma=<?=$curso['idTurma']?>&idCurso=<?=$curso['idCurso']?>" class="btn btn-success btn-sm"><span class="bi-pencil-fill"></span>&nbsp;Editar</a>
                      <?php } ?>
                    
                      </td>
                  </tr>
                  <?php
                  }
                 } else {
                   echo '<h5>Nenhum usuário encontrado</h5>';
                 }
                 // Fechar a conexão 
                 mysqli_close($conn);
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

