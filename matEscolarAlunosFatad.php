<?php
session_start();
require './conexao.php';
$argumento="";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alunos SEDE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  </head>
  
  <body>
      
  <?php include('./index.html'); ?> 
  <br><br>
    <div class="container mt-4">
      <?php include('mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <tr>
                    <td><h5 style="color: blue">Distribuição Direta - SEDE
                      <a href="matEscolarAlunosFatadPesq.php" class="btn btn-link"><span class="bi bi-search"></span>&nbsp;Pesquisar</a></h5>
                    </td>
                </tr>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Nome Aluno</th>
                    <th>Curso</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if(isset($_POST['argumento'])){
                    $argumento=$_POST['argumento'];
                  }
                      $sql = "Select * from qry_mat_escolar_alunos_fatad "
                              ."where nomeAluno like '%$argumento%'"
                            ."ORDER BY nomeAluno" ;

                  $alunos = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($alunos) > 0) {
                    foreach($alunos as $aluno) {
                  ?>
                  <tr>
                    <td><?=$aluno['nomeAluno']?> </td>
                    <td><?=$aluno['nomeCurso']?></td>
                    <td><?=$aluno['telZapAluno']?></td>
                    <td><?=$aluno['cpfAluno']?></td>
                             
                    <td>
                      <? //$idAluno=$aluno['idAluno'];?>
                      <a href="matEscolarAlunoDistribuir.php?idAluno=<?=$aluno['idAluno'];?>&cpf=<?=$aluno['cpfAluno'];?>&idTurma=<?=$aluno['idTurma'];?>&idCurso=<?=$aluno['idCurso'];?>" class="btn btn-primary"><i class="bi bi-backpack-fill"></i></span>&nbsp;Material</a>
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