<?php
session_start();

require './conexao.php';
$argumento="";
$sql="";
//para saber se o $_POST está vazio ou não
$formVals = array_count_values($_POST);

if (isset($_SESSION['privilegio'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opFatad"){
      include('./index.html');
  }elseif($privilegio=="admFatad"){

  }elseif($privilegio=="opNuc"){
      include('./barOpNuc.php');   
  } else { 
  echo 'Sessão não iniciada ou privilégio não definido.'; 
  // Redirecionar para a página de login ou mostrar uma mensagem de erro 
  header('Location: login.php'); exit(); 
} 
}

//$formsVal contém as linhas do $_GET. Se igual a 1 o array está vazio
if(count($formVals) >1){
  $argumento=$_POST['argumento'];
}else{
  $argumento="";
}
if($privilegio=="opNuc"){
  $sql = "SELECT a.idAluno, a.nomeAluno, m.nrMatricula, t.idCursoCurriculo AS idCurso, n.descNucleo, t.idTurma "
."FROM tb_aluno AS a JOIN tb_matricula AS m ON a.idAluno = m.idAluno "
."JOIN tb_turma AS t ON m.idTurma = t.idTurma "
."JOIN tb_nucleofatad AS n ON t.idNucleo = n.idNucleo "
."WHERE n.cpfResp = '$usuario' "
  ."AND a.nomeAluno LIKE '%$argumento%' "
."ORDER BY a.nomeAluno ASC";
 

}else{
  $sql = "SELECT a.idAluno, a.nomeAluno, m.nrMatricula, t.idCursoCurriculo AS idCurso, n.descNucleo, t.idTurma "
."FROM tb_aluno AS a JOIN tb_matricula AS m ON a.idAluno = m.idAluno "
."JOIN tb_turma AS t ON m.idTurma = t.idTurma "
."JOIN tb_nucleofatad AS n ON t.idNucleo = n.idNucleo "
."WHERE a.nomeAluno LIKE '%$argumento%' "
."ORDER BY a.nomeAluno ASC";
}
// var_dump($sql);
// exit();

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Matriculados</title>
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
                    <td>
                    
                    <h5 style="color: blue">Alunos Matriculados
                      <a href="matricularPesquisarGRUD.php" class="btn btn-link"><span class="bi bi-search"></span>&nbsp;Filtrar Aluno</a>
                      <?php if($privilegio=="opNuc"){ ?>
                          <a href="matricularGRUDcreateNuc.php" class="btn btn-primary float-end btn-sm">Matricular&nbsp;</a></h5>
                      <?php }else{?>
                          <a href="matricularGRUDfiltrarNuc.php" class="btn btn-link"><span class="bi bi-search"></span>&nbsp;Filtrar Núcleo&nbsp;</a>&nbsp
                          <a href="matricularGRUDcreate.php" class="btn btn-primary float-end btn-sm">Matricular&nbsp;</a></h5>
                    <?php } ?>
                  </td>
                  </tr>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Matrícula</th>
                    <th>Nome</th>
                    <th>Núcleo/(Id Curso)</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  
                  $alunos = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($alunos) > 0) {
                    foreach($alunos as $aluno) {
                  ?>
                  <tr>
                      
                    <td><?=$aluno['nrMatricula']?></td>
                    <td><?=$aluno['nomeAluno']?></td>
                    <td><?=$aluno['descNucleo']?> (Id curso <?=$aluno['idCurso']?>)</td>
                    <td>
                      <?php if($privilegio=="opNuc"){ ?>
                        <a href="matricularGRUDview.php?idAluno=<?=$aluno['idAluno']?>&idTurma=<?=$aluno['idTurma']?>&idCurso=<?=$aluno['idCurso']?>" class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>
                        <?php }else{ ?>
                          <a href="matricularGRUDview.php?idAluno=<?=$aluno['idAluno']?>&idTurma=<?=$aluno['idTurma']?>&idCurso=<?=$aluno['idCurso']?>" class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>
                          <a href="matricularGRUDedit.php?idAluno=<?=$aluno['idAluno']?>&idTurma=<?=$aluno['idTurma']?>&idCurso=<?=$aluno['idCurso']?>" class="btn btn-success btn-sm"><span class="bi-pencil-fill"></span>&nbsp;Editar</a>
                      <?php } ?>
                      <!--<form action="alunosGRUDacoes.php" method="POST" class="d-inline">
                        <button onclick="return confirm('Confirma exclusão do aluno escolhido? ')" type="submit" name="delete_aluno" value="<?=$aluno['idAluno']?>" class="btn btn-danger btn-sm">
                          <span class="bi-trash3-fill"></span>&nbsp;Excluir
                        </button>
                      </form>-->
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