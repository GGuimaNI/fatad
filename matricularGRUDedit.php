<?php
session_start();
require './conexao.php';
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$nomeAluno="";

if (isset($_SESSION['usuario_autenticado'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opFatad"){
      include('./index.html');
  }elseif($privilegio=="admFatad"){

  }elseif($privilegio=="opNuc"){
      include('./barOpNuc.html');
  }else{
      //visitante
      include('./barVisitante.html');    
  }
} else { 
  echo 'Sessão não iniciada ou privilégio não definido.'; 
  // Redirecionar para a página de login ou mostrar uma mensagem de erro 
  header('Location: login.php'); exit(); 
} 
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
                        <h4>Edição
                        <a href="matricularGRUD.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
            <div class="card-header">
              <h4>Matrícula
            <?php
//            var_dump($_GET);
            if (isset($_GET['idAluno'])) {
                $aluno_id = mysqli_real_escape_string($conn, $_GET['idAluno']);
                $turma_id = mysqli_real_escape_string($conn, $_GET['idTurma']);
                $curso_id = mysqli_real_escape_string($conn, $_GET['idCurso']);
                $sql = "SELECT a.idAluno,a.nomeAluno,m.nrMatricula,m.dtMatricula,m.opcao,m.valorMaterial,m.valorEncadernacao,m.frete,t.nomeSala, n.descNucleo, 
                        t.idTurma,t.idNucleo  
                        FROM tb_aluno as a, tb_matricula as m,tb_turma as t,tb_nucleofatad as n 
                        WHERE a.idAluno=m.idAluno and m.idTurma=t.idTurma 
                        and t.idNucleo=n.idNucleo and a.idAluno=$aluno_id and m.idTurma=$turma_id";
                $query = mysqli_query($conn, $sql);
                if (mysqli_num_rows($query) > 0) {
                  $aluno = mysqli_fetch_array($query);
            ?>
              
            <div class="card-body">
                <form action="matricularGRUDacoes.php" method="POST">
                                       
                    <div class="mb-3">
                        <label>Nome</label>
                        <p class="form-control">
                            <?=$aluno['nomeAluno'];?>
                        </p> 
                    </div>
                    
                         
<!--                    Desabilitado até  encontrar uma solução para o histórico do aluno.
                        Isto porque se trocaar o curso tem implicação no histórico
                        <div class="mb-3">
                        <label>Cursos:</label>
                        <select   required="" name="idCurso" id="idCurso" class="form-control">
                            <option value="">Escolha Curso</option>
                            <?php $rsCursos = $fg->findCursosNivel();
                            foreach ($rsCursos as $row) {
                                ?>
                                <option value="<?= $row->idCurso ?>"><?= $row->cursoNivel ?></option>                                                        
                            <?php 
                            } ?>
                        </select>
                    </div>-->
                     
                    <div class="mb-3">
                        <label>Data Matrícula:</label>
                        <input type="date" required="Escreva ou selecione" placeholder="dd/mm/AAAA" name="dtMatricula" id="dtMatricula" value="<?=$aluno['dtMatricula']?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Número Matrícula:</label>
                        <input type="text" required="Informe número matrícula" placeholder="Escreva o número da matrícula" name="nrMatricula" id="nrMatricula" value="<?=$aluno['nrMatricula']?>" class="form-control">
                    </div>

                    <?php
                    $opcaoSelecionada = isset($aluno['opcao']) ? (intval($aluno['opcao']) === 1 ? 'sim' : 'nao') : '';
                    ?>
                    <div class="mb-3">
                      <label>Recebe Material Correios:&nbsp;&nbsp;&nbsp;</label>
                      <label>
                          <input type="radio" name="opcao" value="sim"
                              <?= $opcaoSelecionada === 'sim' ? 'checked' : '' ?>
                              onclick="toggleCampos(true)" required> Sim&nbsp;&nbsp;
                      </label>
                      <label>
                          <input type="radio" name="opcao" value="nao"
                              <?= $opcaoSelecionada === 'nao' ? 'checked' : '' ?>
                              onclick="toggleCampos(false)" required> Não
                      </label>
                  </div>
                  <div class="mb-3">
                          <label for="valorMaterial">Valor do Material:</label>
                          <input type="text" name="valorMaterial" class="form-control" value="<?= $aluno['valorMaterial'] ?>">
                      </div>

                  <div id="camposAdicionais" style="display: none;">
                      <div class="mb-3">
                          <label for="encadernacao">Encadernação:</label>
                          <input type="text" name="encadernacao" class="form-control" value="<?= $aluno['valorEncadernacao'] ?>">
                      </div>
                      <div class="mb-3">
                          <label for="frete">Valor do Frete:</label>
                          <input type="text" name="frete" class="form-control" value="<?= $aluno['frete'] ?>">
                      </div>
                  </div>

 <!--                   Pela mesma razão do curso.  Implica no histórico do aluno 
                        <div class="mb-3">
                        <label>Locais Disponíveis:</label>
                        <select multiple size="8" name="idTurma" id="idTurma" required class="form-control">   
                            <option value="">Escolha um local</option>
                        </select>
                    </div>-->
                <input type="hidden" id="idAluno" name="idAluno" value="<?=$aluno_id?>">
                <input type="hidden" id="pidTurma" name="pidTurma" value="<?=$turma_id?>">
                <input type="hidden" id="pidcurso" name="pidCurso" value="<?=$curso_id?>">
                <div class="mb-3">
                  <button type="submit" name="update_aluno" class="btn btn-primary">Salvar</button>
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

        <script>
          function toggleCampos(mostrar) {
              const container = document.getElementById("camposAdicionais");
              container.style.display = mostrar ? "block" : "none";

              container.querySelectorAll("input").forEach(input => {
                  if (mostrar) {
                      input.setAttribute("required", "required");
                  } else {
                      input.removeAttribute("required");
                  }
              });
          }

          window.addEventListener('DOMContentLoaded', () => {
              const selecionada = '<?= $opcaoSelecionada ?>';
              toggleCampos(selecionada === 'sim');
          });
        </script>
  </body>
</html>