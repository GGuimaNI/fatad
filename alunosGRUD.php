<?php
session_start();
require './conexao.php';
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
// var_dump($_POST);
// var_dump($_SESSION);

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
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alunos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  
  <body>
    <div class="container mt-12">
      <br><br><br>
      <?php
      if (isset($_SESSION['message'])) {
          echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
          unset($_SESSION['message']); // Limpa a mensagem após exibição
      }
      ?>      
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 style="color: blue;">Listar Alunos</h5>
              <input type="text" id="filtroTabela" class="form-control w-25" placeholder="Filtrar...">
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Data Nascimento</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody id="conteudoTabela">
                  <?php
                  // Carrega os registros ao abrir a página
                                    
                  if ($privilegio == "opNuc") {
                    $nucleo = $fg->findNucleoCpf($usuario);
                    foreach ($nucleo as $row) {
                        $idNucleo = $row->idNucleo;
                        break;
                    }
                    $sql = "SELECT * FROM tb_aluno WHERE idCadastro=$idNucleo ";
                  } else {
                    $sql = "SELECT * FROM tb_aluno ";
                  }
                  $alunos = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($alunos) > 0) {
                    foreach($alunos as $aluno) {
                  ?>
                  <tr>
                    <td><?=$aluno['idAluno']?></td>
                    <td><?=$aluno['nomeAluno']?></td>
                    <td><?=$aluno['cpfAluno']?></td>
                    <td><?=date('d/m/Y', strtotime($aluno['dtNascAluno']))?></td>
                    <td>
                      <a href="alunoGRUDview.php?idAluno=<?=$aluno['idAluno']?>" class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>Visualizar</a>
                      <a href="alunoGRUDedit.php?idAluno=<?=$aluno['idAluno']?>" class="btn btn-success btn-sm"><span class="bi-pencil-fill"></span>Editar</a>
                      <a href="alunoGRUDlistDoc.php?idAluno=<?=$aluno['idAluno']?>" class="btn btn-warning btn-sm"><span class="bi bi-archive"></span>Documentos</a>
                    </td>
                  </tr>
                  <?php
                    }
                  } else {
                    echo '<tr><td colspan="5">Nenhum aluno encontrado.</td></tr>';
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js" integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#filtroTabela').on('input', function() {
                var filtro = $(this).val();
                console.log("Filtro digitado:", filtro);
                $.post('buscar_alunos.php', { filtro: filtro }, function(data) {
                    try {
                        var result = JSON.parse(data);
                        $('#conteudoTabela').html(result.tabela);
                    } catch (error) {
                        console.error("Erro ao analisar JSON:", error);
                        console.log("Conteúdo recebido: " + data);
                    }
                });
            });
        });
    </script>
  </body>
</html>
