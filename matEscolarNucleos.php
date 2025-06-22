<?php
session_start();
require './conexao.php';
$argumento="";

if (isset($_SESSION['privilegio'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  $idSessao=$_SESSION['idSessao'];

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
// var_dump($_SESSION);
// exit();

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Núcleos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  </head>
  
  <body>
 
  <br><br>      

    <div class="container mt-4">
      <?php include('mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <tr>
                    <td><h5 style="color: blue">Distribuição de Material por Núcleo
                               <a href="matEscolarGRUDpesq.php" class="btn btn-link"><span class="bi bi-search"></span>&nbsp;Pesquisar</a>
                    </h5></td>           
                </tr>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Nome Núcleo / Nome Curso</th>
                    <th>Nome Responsável</th>
                    <th>Telefone</th>
                    <th>Nr Alunos</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if(isset($_POST['argumento'])){
                    $argumento=$_POST['argumento'];
                  }
                  if($privilegio=="opNuc"){
                    $sql = "SELECT n.*, t.*, c.nomeCurso, 
                                    (SELECT COUNT(m.idAluno) FROM tb_matricula m WHERE m.idTurma = t.idTurma) AS Total 
                            FROM tb_nucleofatad n 
                            JOIN tb_turma t ON n.idNucleo = t.idNucleo 
                            JOIN tb_cursos c ON t.idCursoCurriculo = c.idCurso 
                            WHERE t.ativo = 0 
                              AND n.cpfResp = '$usuario' 
                              AND n.descNucleo LIKE '%$argumento%'";
                  }else{
                    $sql = "SELECT n.perfil, n.*, t.*, c.nomeCurso, 
                              (SELECT COUNT(m.idAluno) FROM tb_matricula m WHERE m.idTurma = t.idTurma) AS Total 
                            FROM tb_nucleofatad n 
                            JOIN tb_turma t ON n.idNucleo = t.idNucleo 
                            JOIN tb_cursos c ON t.idCursoCurriculo = c.idCurso 
                            WHERE t.ativo = 0 and n.perfil='Núcleo' 
                              AND n.descNucleo LIKE '%$argumento%'";
                  }

                 
   

                  $nucleos = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($nucleos) > 0) {
                    foreach($nucleos as $nucleo) {
                  ?>
                  <tr>
                    <td><?=$nucleo['descNucleo']?> / <?=$nucleo['nomeCurso']?></td>
                    <td><?=$nucleo['nomeRespNucleo']?></td>
                    <td><?=$nucleo['telZap']?></td>
                    <td><center><?=$nucleo['Total']?></center></td>
                             
                    <td>
                     <?php
                     if($nucleo['Total']>0){ ?>
                        <a href="matEscolarDistribuir.php?
                        idNucleo=<?=$nucleo['idNucleo']?>
                        &idTurma=<?=$nucleo['idTurma']?>
                        &idCursoCurriculo=<?=$nucleo['idCursoCurriculo']?>
                        &qtdAlunos=<?=$nucleo['Total']?>" 
                        class="btn btn-primary"><i class="bi bi-backpack-fill"></i></span>&nbsp;Material</a>
                     <?php } ?>
                   
                    </td>
                  </tr>
                  <?php
                  }
                 } else {
                   echo '<h5>Nenhum usuário encontrado</h5>';
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