<?php
session_start();

if (isset($_SESSION['usuario_autenticado'])) { 
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

require './conexao.php';
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$nomeAluno="";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aluno - Editar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <br>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                        <h4>Visualização
                        <a href="matricularGRUD.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
            <div class="card-header">
              
            <?php
//            var_dump($_GET);
            if (isset($_GET['idAluno'])) {
                $aluno_id = mysqli_real_escape_string($conn, $_GET['idAluno']);
                $turma_id = mysqli_real_escape_string($conn, $_GET['idTurma']);
                $curso_id = mysqli_real_escape_string($conn, $_GET['idCurso']);
                $sql = "SELECT a.idAluno,a.nomeAluno,m.nrMatricula,m.dtMatricula,m.opcao,t.nomeSala, n.descNucleo, "
                        ."t.idTurma,t.idNucleo  "
                        ."FROM tb_aluno as a, tb_matricula as m,tb_turma as t,tb_nucleofatad as n "
                        ."WHERE a.idAluno=m.idAluno and m.idTurma=t.idTurma "
                        ."and t.idNucleo=n.idNucleo and a.idAluno=$aluno_id and m.idTurma=$turma_id";
                $query = mysqli_query($conn, $sql);
                if (mysqli_num_rows($query) > 0) {
                  $aluno = mysqli_fetch_array($query);
                  
                 $nomeCurso=$fg->findCursoEspecifico($curso_id);
                 foreach ($nomeCurso as $nmCurso) {
                     $nome=$nmCurso->nomeCurso;
                 }
            ?>
              
            <div class="card-body">
                <form action="matricularGRUDacoes.php" method="POST">
                                       
                    <div class="mb-3">
                        <label>Nome</label>
                        <p class="form-control">
                            <?=$aluno['nomeAluno'];?>
                        </p> 
                    </div>
                    
                    <div class="mb-3">
                        <label>Curso</label>
                        <p class="form-control">
                            <?=$nome;?>
                        </p> 
                    </div>
                         
                    <div class="mb-3">
                        <label>Data da Matrícula</label>
                        <p class="form-control">
                            <?=$aluno['dtMatricula'];?>
                        </p> 
                    </div>
                    
                    <div class="mb-3">
                        <label>Número da Matrícula</label>
                        <p class="form-control">
                            <?=$aluno['nrMatricula'];?>
                        </p> 
                    </div>
                    
                    <div class="mb-3">
                        <label>Local do Curso</label>
                        <p class="form-control">
                            <?=$aluno['descNucleo']." (".$aluno['nomeSala'].")"  ;?>
                        </p> 
                    </div>
                    
                    <div class="mb-3">
                        <label>Recebe Mateirial Correios:</label>
                        <p class="form-control">
                            <?=$opcaotxt = isset($aluno['opcao']) && $aluno['opcao'] == 1 ? "Sim" : "Não";?>
                        </p> 
                    </div>                
              </form>
                <?php
                } else {
                    echo "<h5>Usuário não encontrado</h5>";
                  }
                }
                ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="js/alunoMatricula.js"></script>
    <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="js/cep.js"></script>
  </body>
</html>