<?php
session_start();
require './config.php';
$pdo=new config;
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$argumento="";

if (isset($_SESSION['privilegio'])) { 
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
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Material Escolar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">-->
    <!--Inclui modal-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js">
    </script> <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 

 
  </head>
  
  <body>
    <?php 

    $perfil="Núcleo"
//       // var_dump($_GET);
    ?>
    <div class="container mt-4">
    <br><br>
      <?php include('mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <table>
                <tr>

                    <?php 
                    $idNucleo= filter_input(INPUT_GET, 'idNucleo', FILTER_SANITIZE_NUMBER_INT); 
                    $descNucleo=$fg->findDescNucleoEspecifico($idNucleo);
                         foreach ($descNucleo as $row) {
                             $nmNucleo=$row['descNucleo'];
                         }
                    ?>
                    <td>
                      <h5 style="color: blue" >Lista de Disciplinas - <?php print $nmNucleo; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    
                        <button type="button" class="btn btn-xs btn-warning  btn-sm "  data-toggle="modal" 
                                data-whatever=" - Impressão de Histórico"
                                data-target="#historicoModal" >Endereçamento</button>
                      </h5>

                   </td>
                    
                </tr>
                </table> 
            
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Código</th>
                    <th>Disciplina</th>
                    <th><center>Nr Alunos</center></th>
                    <th><right><center>Custo</center></right></th>
                    <th><center>Ações</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
//                  var_dump($_GET);
                    $idTurma = $_GET['idTurma'];
                    
//                    $idNucleo = $_GET['idNucleo'];
                      $idCurso = filter_input(INPUT_GET, 'idCursoCurriculo', FILTER_SANITIZE_NUMBER_INT);
                      $idTurma= filter_input(INPUT_GET, 'idTurma', FILTER_SANITIZE_NUMBER_INT);
                      $qtdAlunos= filter_input(INPUT_GET, 'qtdAlunos', FILTER_SANITIZE_NUMBER_INT);


                      // // Verifique os valores capturados 
                      // echo "idTurma: $idTurma, idCurso: $idCurso, idNucleo: $idNucleo<br>";
                      $sql = "SELECT * FROM qry_matescolardistribuir 
                      WHERE idTurma = :idTurma 
                      AND idCursoCurriculo = :idCurso 
                      AND idNucleo = :idNucleo";
          
                      $stmt = $pdo->prepare($sql);
                      $stmt->bindParam(':idTurma', $idTurma, PDO::PARAM_INT);
                      $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
                      $stmt->bindParam(':idNucleo', $idNucleo, PDO::PARAM_INT);
                      
                      if (!$stmt->execute()) {
                        echo "Erro na execução da consulta: " . json_encode($stmt->errorInfo());
                        exit();
                    }
                
                     $disciplinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                     if ($disciplinas) {
                      foreach($disciplinas as $disciplina) {

                        $valor= $disciplina['valorMatDisciplina']*$qtdAlunos;   
                        $valor= number_format($valor,2,',','.');
                  ?>
                  <tr>
                    <td><?=$disciplina['codigoDisciplina']?></td>
                    <td><?=$disciplina['nomeDisciplina']?></td>
                    <td><center><?=$qtdAlunos?></center></td>
                    <td><center><?=$valor?></center></td>                   
                    <td>
                       <?php 
//                        $dtPagOk=date('d/m/Y', strtotime($disciplina['dtPagamento']));
                       if($disciplina['idOp']){
                          $rsMedia=$fg->findMediaDisciplina(
                            $disciplina['idTurma'],
                            $disciplina['idCursoCurriculo'],
                            $disciplina['idNucleo'],
                            $disciplina['idDisciplinaCurriculo']);
                           foreach($rsMedia as $row){
                             $media=$row['media'];
                           }
                           if($media>4.99){
                            ?>                  
                              <a href="rptBoletimNucleo.php?
                              idNucleo=<?=$disciplina['idNucleo']?>
                              &idTurma=<?=$disciplina['idTurma']?>
                              &idCurso=<?=$disciplina['idCursoCurriculo']?>
                              &idDisciplina=<?=$disciplina['idDisciplinaCurriculo']?>" 
                              class="btn btn-outline-primary btn-sm"><i class="bi bi-backpack-fill"></i></span>&nbsp; Boletim&nbsp; </a>
                           
                           <?php }else{
                            ?>
                                <a href="gerarExcel.php?
                                idNucleo=<?=$disciplina['idNucleo']?>
                                &idTurma=<?=$disciplina['idTurma']?>
                                &idCurso=<?=$disciplina['idCursoCurriculo']?>
                                &idDisciplina=<?=$disciplina['idDisciplinaCurriculo']?>" 
                                class="btn btn-outline-primary btn-sm"><i class="bi bi-backpack-fill"></i></span>&nbsp;Planilha&nbsp; </a>
                        
                                <a href="importar_listDoc.php?
                                idNucleo=<?=$disciplina['idNucleo']?>
                                &idTurma=<?=$disciplina['idTurma']?>
                                &idCurso=<?=$disciplina['idCursoCurriculo']?>
                                &idDisciplina=<?=$disciplina['idDisciplinaCurriculo']?>
                                &qtdAlunos=<?=$qtdAlunos?>" 
                                class="btn btn-outline-primary btn-sm"><i class="bi bi-backpack-fill"></i></span>Mat Estudo</a>
                      <?php   }   
                          
                       }else{ 
                           if( $privilegio=="opFatad" or $privilegio=="admFatad"){       
                            ?>
                        
                            <a href="matEscolarDistMatNucleo.php?
                                idNucleo=<?=$disciplina['idNucleo']?>
                                &idTurma=<?=$disciplina['idTurma']?>
                                &idCurso=<?=$disciplina['idCursoCurriculo']?>
                                &idDisciplina=<?=$disciplina['idDisciplinaCurriculo']?>" 
                                class="btn btn-success btn-sm"><i class="bi bi-backpphpack-fill"></i></span>Distribuir</a>                        
                        <?php 
                        }  
                      }                     
                    } 
                }
                ?>
                    </td>
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
                                    <input type="hidden" id="idNucleo" name="idNucleo" value="<?php echo $idNucleo; ?>">
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