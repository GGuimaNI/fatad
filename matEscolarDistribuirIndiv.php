<?php
session_start();
require './conexao.php';
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$argumento="";

///Para evitar que a página regarrege do cache. 
//Esta página recarrega a cada 5 min (linha 60)
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");

if (isset($_SESSION['usuario_autenticado'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opFatad" || $privilegio=="admFatad"){
      include('./index.html');
  }elseif($privilegio=="opNuc"){
      include('./barOpNuc.php');
  }elseif($privilegio=="opAluno"){
        include('./barOpAluno.php');

} else { 
  echo 'Sessão não iniciada ou privilégio não definido.'; 
  // Redirecionar para a página de login ou mostrar uma mensagem de erro 
  header('Location: logout.php'); exit(); 
} }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Material Sede</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<!--Inclui modal-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js">
    </script> <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 

  </head>
  
  <body>
    <?php 
        // $idAluno = mysqli_real_escape_string($conn,$_GET['idAluno']); 
        $perfil="FATAD";
    ?>
    <div class="container mt-4">
    <br><br>
    
      <?php include('mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <tr>
              <?php 
                  $cpf=preg_replace( '/[^0-9]/is', '', $usuario );
                  $nomeAluno=$fg->findAlunoCpf($cpf);
                  foreach ($nomeAluno as $row) {
                        $nmAluno=$row->nomeAluno;
                    }
              ?>

              <td>
                  <h5 style="color: blue">Aluno: <?php print $nmAluno; ?></h5>
                  <h6 style="color: blueviolet">Procure seguir a sequência proposta ao fazer solicitação de material.</h6>
              </td>
              </tr>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Código</th>
                    <th>Disciplina</th>
                    <th><center>Ações</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    try {
                        $pdo = new Config;
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch (PDOException $e) {
                        die("Erro na conexão: " . $e->getMessage());
                    }

                    // Obtendo o CPF do aluno
                    $cpf = preg_replace('/[^0-9]/', '', $usuario); // Removendo caracteres não numéricos
                    $sql = "SELECT * FROM view_matEscolarDistribuirIndiv WHERE cpfAluno = :cpf ORDER BY idDisciplina";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
                    $stmt->execute();
//                  var_dump($disciplinas);

                    // Iterando sobre os resultados
                    $disciplinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($disciplinas as $disciplina) {

                       $mensagem="";  

                        ?>
                        <tr>
                          <td><?=$disciplina['codigoDisciplina']?></td>
                          <td><?=$disciplina['nomeDisciplina']?></td>
                          

                          <td>
                          <?php
                          if($disciplina['idOp']){
                              $dtAtual = new DateTime(); // Obtém a data atual
                              $dtIniEstudo = $fg->criarDateTimeOuNull($disciplina['dtIniEstudo'] ?? null);
                              $dtDispo     = $fg->criarDateTimeOuNull($disciplina['dtIniEstudo'] ?? null);
                              $dtDisponibilidade = null;
                              if ($dtDispo instanceof DateTime) {
                                  $dtDisponibilidade = (clone $dtDispo)->modify('+6 months');
                                  $dtEntrega=(clone $dtDispo)->modify('+10 days');
                              }                              
                              $mensagem = "";
                              $idCurso=$disciplina['idCurso'];
                              $idDisciplina=$disciplina['idDisciplina'];
                              $nota=$fg->findNotaAluno($cpf,$idCurso,$idDisciplina)['nota'];
                              $mensagem= mensagem($nota,$disciplina['tipoOp'],$disciplina['situacao']);
                              
                              if($disciplina['opcao']==1){
                                //Aluno recebe material pelos correios
                                 if($disciplina['tipoOp']=="P" || $disciplina['tipoOp']=="E"){
                                      $mensagem = $mensagem . PHP_EOL . "  O pedido está em processamento, e o material será enviado em até 24 horas.";
                                      ?>
                                      <a href="" class="btn btn-secondary btn-sm disabled" disabled>
                                          <span class="bi-check-circle-fill"></span>Processando </a>

                                      <button type="button" class="btn btn-sm btn-info botaoModal" data-bs-toggle="modal" data-bs-target="#meuModal" data-mensagem="<?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>">
                                          <span class="bi-info-circle"></span> Observações
                                      </button>
                                      <?php
                                    }else{
                                      $mensagem .= PHP_EOL . "O material foi enviado e deverá chegar em até " . $dtEntrega->format('d/m/Y') . PHP_EOL . "Bons Estudos.";                                      ?>
                                      <a href=""  
                                      class="btn btn-secondary btn-sm disabled" disabled
                                      <span class="bi-check-circle-fill"></span>&nbsp;&nbsp;&nbsp;&nbsp;Enviado&nbsp;&nbsp;&nbsp;&nbsp;
                                      </a>
                                      <button type="button" class="btn btn-sm btn-info botaoModal" data-bs-toggle="modal" data-bs-target="#meuModal" data-mensagem="<?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>">
                                          <span class="bi-info-circle"></span> Observações
                                      </button>
                                      <?php

                                    }

                              }else{
                                //Início de quem vai baixar material
                                if ($dtAtual >= $dtIniEstudo && $dtAtual < $dtDisponibilidade) {
                                    if($disciplina['tipoOp']=="P"){
                                      $mensagem = $mensagem . PHP_EOL . "  O pedido está em processamento, e o material ficará disponível em até 24 horas.";
                                      ?>
                                      <a href="" class="btn btn-secondary btn-sm disabled" disabled>
                                          <span class="bi-check-circle-fill"></span>&nbsp;Aguardando </a>

                                      <button type="button" class="btn btn-sm btn-info botaoModal" data-bs-toggle="modal" data-bs-target="#meuModal" data-mensagem="<?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>">
                                          <span class="bi-info-circle"></span> Observações
                                      </button>
                                      <?php
                                    }else{
                                      $mensagem = $mensagem . PHP_EOL . "  A disponibilidade deste material de estudo é até " . $dtDisponibilidade->format('d/m/Y'). ".  Depois desta data ficará indisponível.";
                                      ?>
                                      <a href="importarListDocAluno.php?
                                      idNucleo=<?=$disciplina['idNucleo']?> 
                                      &idTurma=<?=$disciplina['idTurma']?> 
                                      &idCurso=<?=$disciplina['idCurso']?> 
                                      &idDisciplina=<?=$disciplina['idDisciplina']?>"  
                                      class="btn btn-success btn-sm">
                                      <span class="bi-check-circle-fill"></span>&nbsp;&nbsp;Disponível&nbsp;&nbsp;
                                      </a>
                                      <button type="button" class="btn btn-sm btn-info botaoModal" data-bs-toggle="modal" data-bs-target="#meuModal" data-mensagem="<?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>">
                                          <span class="bi-info-circle"></span> Observações
                                      </button>
                                      <?php

                                    }

                                  
                                } else {
                                  // $dtDisponibilidade = (clone $dtDispo)->modify('+6 months');
                                  $mensagem .= PHP_EOL . "  O material de estudo esteve disponível até " . 
                                      ($dtDisponibilidade instanceof DateTime ? $dtDisponibilidade->format('d/m/Y') : '---') . ".";                                ?>
                              
                                <button type="button" class="btn btn-sm btn-info botaoModal" data-bs-toggle="modal" data-bs-target="#meuModal" data-mensagem="<?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>">
                                    <span class="bi-info-circle"></span> Observações
                                </button>                                  
                                <?php
                                }}
                                ?>
                                <!-- Fim de quem baixa material  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

                              <!-- Ícone para abrir o modal -->
                            
                              </td>
                            </tr>
                            <!-- Modal -->
                            <div class="modal" id="meuModal" tabindex="-1">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" style="color: blue;" >Observações</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                      </div>
                                      <div class="modal-body">
                                          <p id="modalTexto"></p>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-info" data-bs-dismiss="modal">Fechar</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      <?php 
                      
                      //end if de idOp
                      }else{
                        ?>
                        <a href="matEscolarDistAlunoIndiv.php?
                                      idAluno=<?=$disciplina['idAluno']?>
                                      &cpf=<?=$cpf?> 
                                      &idTurma=<?=$disciplina['idTurma']?> 
                                      &idCurso=<?=$disciplina['idCurso']?> 
                                      &idDisciplina=<?=$disciplina['idDisciplina']?>"  
                                      class="btn btn-secondary">
                                      <span class="bi-check-circle-fill"></span>&nbsp;Solicitar
                                  </a>
                <?php      
                }  
                      
              }
                ?>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        document.querySelectorAll(".botaoModal").forEach(function(botao) {
                            botao.addEventListener("click", function() {
                                let mensagem = this.getAttribute("data-mensagem");
                                document.getElementById("modalTexto").innerText = mensagem;
                            });
                        });
                    });
                </script>                
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
  
    <script type="text/javascript">
            $('#historicoModal').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget) // Button that triggered the modal
              var recipient = button.data('whatever') // Extract info from data-* attributes
              var recipientnome = button.data('whatevernome')
              var recipientini = button.data('whateverini')
              
              var modal = $(this)
              modal.find('.modal-title').text('ID ' + recipient)
              modal.find('#id-curso').val(recipient)
              modal.find('#recipient-name').val(recipientnome)
              modal.find('#recipient-ini').val(recipientini)
            })
             
            $(document).on('click'),'.editar', function(){
                var id=$(this).data('$idNucleo');
            }
    </script>
    <?php
    function mensagem($nota, $tipoOp, $situacao) {
    $mensagem = ""; // Inicializa antes de qualquer uso

    if (is_null($nota)) {
        if ($tipoOp == "P") {
            $mensagem = ""; // Nesse caso, mantém a mensagem vazia
        } else {
            $mensagem = "Prova não realizada." . PHP_EOL;
        }
    } elseif ($nota < 5) {
        $mensagem = "Você obteve nota $nota. Portanto, abaixo da média necessária e foi considerado Reprovado nesta disciplina." . PHP_EOL;
    } elseif ($situacao == "Aprovado(*)") {
        $mensagem = "A nota obtida nesta matéria, $nota - {$situacao}, está abaixo da Média Final necessária para ser considerado aprovado no curso." . PHP_EOL;
    } else {
        $mensagem = "Parabéns pelo bom desempenho nesta matéria ($nota - {$situacao})." . PHP_EOL;
    }

    return $mensagem;
}
?>
  
  </body>
</html>