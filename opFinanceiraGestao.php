<?php
session_start();
require './conexao.php';

include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

if (isset($_SESSION['usuario_autenticado'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  $idSessao=$_SESSION['idSessao'];

  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opFatad"){
      include('./index.html');
  }elseif($privilegio=="admFatad"){

  }elseif($privilegio=="opNuc"){
          include('./barOpNuc.php');
  }elseif($privilegio=="opAluno"){
          include('./barOpAluno.php');
  }else{
      //visitante
      include('./barVisitante.html');    
  }
} else { 
  echo 'Sessão não iniciada ou privilégio não definido.'; 
  // Redirecionar para a página de login ou mostrar uma mensagem de erro 
  header('Location: login.php'); exit(); 
} 
//var_dump($_SESSION);

$argumento="dtContrato>= date_sub(now(), interval 6 MONTH)";
$opVoltar=0;

$formVals = array_count_values($_POST);
//$key = key($formVals);

//Testando botão mais arg1
if(count($formVals) == 2){
  $opVoltar=2;
   $argumento="dtContrato  between '".$_POST['data1']."' AND '".date('Y-m-d')."'";
}
//Testando botão mais arg1 e arg2
if(count($formVals) > 2){
  $opVoltar=2;
   $argumento="dtContrato  between '".$_POST['data1']."' AND '".$_POST['data2']."'";
}
if($_GET){
    $opVoltar=1;
     $argumento=" dtPagamento IS NULL ";
}
// var_dump($_GET);
// var_dump($_POST);
// var_dump($argumento);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style></style>
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
                       <?php if($opVoltar==1){ ?> 
                          <h5 style="color:blue;">A Receber 
                       <?php }elseif($opVoltar==2) { ?>
                          <h5 style="color:blue;">Mov Financeira Período Escolhido  
                       <?php }elseif($opVoltar==0) { ?>
                            <h5 style="color:blue;">Mov Financeira (últimos 6 mêses) 
                       <?php } ?>       
                       <a href="opFinPesquisaComDatas.php" class="btn btn-link"><span class="bi bi-search"></span>&nbsp;Escolher Data</a> 
                        <a href="opFinanceiraGestao.php?par=0" class="btn btn-link"><span class="bi bi-search"></span>&nbsp;Não Pagos</a>
                        <!-- <?php if($opVoltar==1){ ?>
                                <a href="opFinanceiraGestao.php" class="btn btn-danger float-end">Voltar</a>
                        <?php } ?> -->
                        </h5>
                    </td>
                </tr>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Data</th>
                    <th>Responsável (Tel)</th>
                    <th>Contato</center></th>
                    <th><center>Valor</center></th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  
                  if($privilegio=="opNuc"){
                    $sql = "SELECT * FROM qry_opfinanceirogestaonucleo "
                    . "WHERE cpfResp='".$usuario."' AND ".$argumento;
                  }elseif($privilegio=="opFatad" || $privilegio=="admFatad"){
                    $sql = "SELECT * FROM qry_opfinanceirogestaonucleo "
                    . "WHERE ".$argumento;
                  }elseif($privilegio=="opAluno"){
                    $sql = "SELECT * FROM qry_opfinanceirogestaoaluno "
                    . "WHERE idResp='".$usuario."' AND ".$argumento;
                  }
                  
                  $nucleos = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($nucleos) > 0) {
                    foreach($nucleos as $nucleo) {
                  ?>
                  <tr>
                    <td><?=date('d/m/Y', strtotime($nucleo['dtContrato']))?></td>
                    <td><?=$nucleo['nomeResponsavel']?> </td>                 
                    <td><?=$nucleo['telZap']?></td>                 
                    <td><center><?=$nucleo['valorTotal']?></center></td>
                             
                    <td>
                      
                      <a href="opFinanceiraVisualizar.php?
                         idOp=<?=$nucleo['idOp']?>
                         &perfil=<?=$nucleo['perfil']?>"  
                         class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></i></span>&nbsp;Vista</a>
                      <?php 
//                        $dtPagOk=date('d/m/Y', strtotime($disciplina['dtPagamento']));
    
                            if (is_null($nucleo['dtPagamento'])
                               || $nucleo['dtPagamento']=='0000-00-00'
                               || $nucleo['dtPagamento']=='' ){  
                                
                              if($privilegio=="opFatad" || $privilegio=="admFatad"){
                            ?>
                              <a href="opFinanGestaoReceber.php?
                              id=<?=$nucleo['idResp']?>
                              &perfil=<?=$nucleo['perfil']?>
                              &idCurso=<?=$nucleo['idCurso']?>
                              &idTurma=<?=$nucleo['idTurma']?>
                              &idDisciplina=<?=$nucleo['idMaterial']?>
                              " class="btn btn-outline-danger btn-sm bi bi-calculator"></span>&nbsp;Receber</a> 


                            <?php 
                              }else{ ?>
                                <!-- Botão que aciona o modal -->
                                <button type="button" class="btn btn-outline-danger btn-sm bi bi-ui-checks" data-bs-toggle="modal" data-bs-target="#meuModal">
                                    &nbsp;&nbsp;Débito&nbsp;
                                </button                              
                                <?php
                              }
                            } else { ?>
                            <a href="rptOpFinanceirasReciboNucleo.php?
                               idOp=<?=$nucleo['idOp']?> 
                               &perfil=<?=$nucleo['perfil']?>"
                               class="btn btn-outline-primary btn-sm bi bi-printer"></span>&nbsp;&nbsp;Recibo&nbsp;</a>
                             <?php
                            }
                ?>
                    </td>
                  </tr>
                  <?php
                  }
                 } 
    
//                <!--Início de tratamento para alunos-->
        if($privilegio=="opFatad" || $privilegio=="admFatad"){

                  $sql = "SELECT * FROM qry_opfinanceirogestaoaluno "
                              . "WHERE ".$argumento;

                  $alunos = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($alunos) > 0) {
                    foreach($alunos as $aluno) {
                  ?>
                  <tr>
                    <td><?=date('d/m/Y', strtotime($aluno['dtContrato']))?></td>
                    <td><?=$aluno['nomeResponsavel']?></td>
                    <td><?=$aluno['telZap']?></td>
                    <td><center><?=$aluno['valorTotal']?></center></td>
                             
                    <td>
                      
                      <a href="opFinanceiraVisualizar.php?
                         idOp=<?=$aluno['idOp']?>
                         &perfil=<?=$aluno['perfil']?>"  
                         class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></i></span>&nbsp;Vista</a>
                      <?php 
//                        $dtPagOk=date('d/m/Y', strtotime($disciplina['dtPagamento']));

//                            if($disciplina['idOp']>0){
                            if (is_null($aluno['dtPagamento'])
                               || $aluno['dtPagamento']=='0000-00-00'
                               || $aluno['dtPagamento']=='' ){                                 
                            ?>
                             <a href="opFinanGestaoReceber.php?
                             id=<?=$aluno['idResp']?>
                             &perfil=<?=$aluno['perfil']?>
                             &idCurso=<?=$aluno['idCurso']?>
                             &idTurma=<?=$aluno['idTurma']?>
                             &idDisciplina=<?=$aluno['idMaterial']?>
                              " class="btn btn-outline-danger btn-sm bi bi-calculator"></span>&nbsp;Receber</a> 


                            <?php 
                            } else { ?>
                            <a href="rptOpFinanceirasReciboNucleo.php?
                               idOp=<?=$aluno['idOp']?> 
                               &perfil=<?=$aluno['perfil']?>"
                               class="btn btn-outline-primary" btn-sm"><i class="bi bi-printer"></i></span>&nbsp;&nbsp;Recibo&nbsp;</a>
                             <?php
                            }
                ?>
                    </td>
                  </tr>
                  <?php
                  }
                  }  
                } 
                 ?> 
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="meuModal" tabindex="-1" aria-labelledby="meuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color: red;" class="modal-title" id="meuModalLabel">Aviso de Débito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Até o presente momento esta operação consta como em débito.  Caso já tenha sido quitado, favor comunicar à FATAD.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <!-- <button type="button" class="btn btn-primary">Salvar mudanças</button> -->
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