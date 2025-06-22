<?php
session_start();

require './conexao.php';
include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;
$argumento="";
$nomeAluno="";
$cpf="";
$idAluno=0;


// var_dump($_POST);
// var_dump($_GET);
// exit();
//para se o retorno da pesquisa contem algum texto
$formVals = array_count_values($_POST);
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
 <?php
    $pdo = new Config();
    $cpf=$fg->getCpfId();

    $query = "SELECT idAluno, nomeAluno, cpfAluno FROM tb_aluno WHERE cpfAluno = :cpf";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $aluno = $stmt->fetch();

       
        if ($aluno) {
            $nomeAluno = $aluno['nomeAluno'];
        } else {
           echo "<script>
                alert('Cpf: " . htmlspecialchars($cpf, ENT_QUOTES, 'UTF-8'). " Erro ao buscar Aluno.');
                window.location.href = 'iniciar.php';
              </script>";     
        }
    } else {
        echo "<script>
                alert('Cpf: " . htmlspecialchars($cpf, ENT_QUOTES, 'UTF-8'). " não existe como Aluno.');
                window.location.href = 'iniciar.php';
              </script>";     
    }
        ?>


      <form action="cadastroExcluirDoc.php" method="POST">
  
      <div class="container mt-4">
      <br><br>
      <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']); // Limpa a mensagem após exibição
        }
        ?>      <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                  <tr>
                      <td>
                        <input type='hidden' name='cpf' value='" . $cpfAluno . "'>
                        <h5 style="color: blue;"><?php echo "Docs de " . htmlspecialchars($nomeAluno, ENT_QUOTES, 'UTF-8') . "!"; ?>
                        <a href="iniciar.php" class="btn btn-danger float-end">Voltar</a>  
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
                      
                  $sql = "SELECT idArquivo, idOp, nomeArq FROM tb_recibos WHERE cpf = :cpf ";

                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':cpf', $cpf, PDO::PARAM_INT);
                //   $stmt->bindParam(':argumento', $paramArgumento, PDO::PARAM_STR);

                  $stmt->execute();

                  $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  if (count($usuarios) > 0) {
                      foreach($usuarios as $usuario) {
                    ?>
                    <tr>
                      <td><?=$usuario['idArquivo']?></td>
                      <td><?=$usuario['nomeArq']?></td>
                      <td>
                        <input type='hidden' name='idAluno' value="<?= $idAluno ?>">
                        <input type='hidden' name='cpf' value="<?= $cpf ?>">
                        <input type='hidden' name='idArquivo' value="<?= isset($usuario['idArquivo']) ? $usuario['idArquivo'] : '' ?>">                        
                        <a href="cadastroAlunoDocView.php?idArquivo=<?=$usuario['idArquivo']?>" target='_blank' class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>  
                        <a href="cadastroExcluirDocAluno.php?idAluno=<?=$idAluno?>&cpf=<?=$cpf?>&idArquivo=<?=$usuario['idArquivo']?>&nomeArq=<?=$usuario['nomeArq']?>"  class="btn btn-warning btn-sm"><span class="bi bi-trash"></span>&nbsp;Excluir</a>
                    </tr>
                    <?php
                    }
                  }else{
                     echo "<script>
                        alert('Cpf: " . htmlspecialchars($cpf, ENT_QUOTES, 'UTF-8'). " não possui documentos para serem exibidos.');
                        window.location.href = 'iniciar.php';
                      </script>";
                  }
                  ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
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
