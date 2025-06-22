<?php
session_start();
require './conexao.php';
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$argumento="";
if (isset($_SESSION['usuario_autenticado'])) {
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario'];
  // Adicione lógica baseada no privilégio do usuário
  if($privilegio=="opFatad"){
      include('./index.html');
  }elseif($privilegio=="admFatad"){

  }elseif($privilegio=="opNuc"){
      include('./barOpNuc.php');
  }elseif($privilegio=="Visitante"){
      include('./barVisitante.html');
  }else {
  echo 'Sessão não iniciada ou privilégio não definido.';
  // Redirecionar para a página de login ou mostrar uma mensagem de erro
  header('Location: logout.php'); exit();
}
}
// var_dump($_GET); exit();
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

        $idAluno = mysqli_real_escape_string($conn,$_GET['idAluno']);
        $cpf = mysqli_real_escape_string($conn,$_GET['cpf']);
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
                    <?php $nomeAluno=$fg->findAlunoEspecifico($idAluno);
                         foreach ($nomeAluno as $row) {
                             $nmAluno=$row->nomeAluno;
                         }
                    ?>

                    <td>
                        <h5 style="color: blue">Aluno: <?php print $nmAluno; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        <button type="button" class="btn btn-xs btn-warning btn-sm "  data-toggle="modal"
                                data-whatever=" - Impressão de Histórico"
                                data-target="#historicoModal" >Endereçamento</button>
                        
                      <a href="matEscolarAlunosFatad.php" class="btn btn-danger float-end btn-sm">Voltar</a></h5>
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
//                  var_dump($_GET);

                      $idCurso = mysqli_real_escape_string($conn,$_GET['idCurso']);
                      $idTurma = mysqli_real_escape_string($conn, $_GET['idTurma']);
                      $cpf = mysqli_real_escape_string($conn,$_GET['cpf']);
                      $sql = "SELECT * FROM qry_mat_escolar_aluno_distribuir "
                             ."WHERE cpfAluno=$cpf and idTurma=$idTurma and idCurso=$idCurso "
                             . "ORDER BY idDisciplina";

                  $disciplinas = mysqli_query($conn, $sql);
//                  var_dump($disciplinas);
                if (mysqli_num_rows($disciplinas) > 0) {
                    foreach($disciplinas as $disciplina) {

                        $valor= $disciplina['valorMatDisciplina'];
                        $valor= number_format($valor,2,',','.');
                  ?>
                  <tr>
                    <td><?=$disciplina['codigoDisciplina']?></td>
                    <td><?=$disciplina['nomeDisciplina']?></td>



                    <td>
                       <?php
                        if($disciplina['idOp']>0){

                            if (is_null($disciplina['dtPagamento'])
                               || $disciplina['dtPagamento']=='0000-00-00'
                               || $disciplina['dtPagamento']=='' ){

                            ?>
                             <a href="opFinanceiraAlunoReceber.php?
                             idAluno=<?=$disciplina['idAluno'];?>
                             &cpf=<?=$disciplina['cpfAluno'];?>
                             &idTurma=<?=$disciplina['idTurma'];?>
                             &idCurso=<?=$disciplina['idCurso'];?>
                             &idDisciplina=<?=$disciplina['idDisciplina']?>
                             " class="btn btn-outline-danger" "btn-sm"><i class="bi bi-calculator""></i></span>&nbsp;Receber</a>

                            <?php
                            } else {
                            ?>
                            <a href="rptOpFinanceirasReciboNucleo.php?
                             perfil=<?=$disciplina['perfil'];?>
                             &idOp=<?=$disciplina['idOp'];?>""
                             class="btn btn-outline-primary" btn-sm"><i class="bi bi-printer"></i></span>&nbsp;&nbsp;Recibo&nbsp;</a>
                             <?php
                            }
                        }else{
                            ?>

                        <a href="matEscolarAlunoDistMat.php?
                             idAluno=<?=$disciplina['idAluno'];?>
                             &idTurma=<?=$disciplina['idTurma'];?>
                             &cpf=<?=$disciplina['cpfAluno'];?>
                             &idCurso=<?=$disciplina['idCurso'];?>
                             &idDisciplina=<?=$disciplina['idDisciplina'];?>
                             " class="btn btn-success btn-sm"><i class="bi bi-backpack-fill"></i></span>&nbsp;&nbsp;Distribuir&nbsp;&nbsp;</a>

                        <?php
                        }
                    }
                }
                ?>
                </tbody>
              </table>

                             <!--Início de Modal para endereçamento-->
                <div class="modal fade" id="historicoModal"  tabindex="-1" role="dialog" aria-labelledby="historicoModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="historicoModalLabel">Endereçamento </h4>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="rptOpFinanceiraEnderecamento.php" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="recipient-ini" class="control-label">A página com o endereçamento será gravada na pasta padrão de 'download', em pdf, com nome do destinatário.</label>

                                    </div>

                                    <input name="idHistorico" type="hidden" class="form-control" id="id-curso" value="">
                                    <input type="hidden" id="idNucleo" name="idNucleo" value="<?php echo $idAluno; ?>">
                                    <input type="hidden" id="perfil" name="perfil" value="<?php echo $perfil; ?>">
                                    <button type="button" class="btn btn-success" data-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-danger" target='_blank' name="botao" id="botao" value="historico">Gerar o Endereçamento</button>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <!--Fim de modal-->

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


  </body>
</html>