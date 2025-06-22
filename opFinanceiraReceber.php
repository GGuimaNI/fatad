<?php
session_start();
require './conexao.php';
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

if (isset($_SESSION['usuario_autenticado'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  $idSessao=$_SESSION['idSessao'];

  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opFatad"){
        include('./index.html');
  }elseif($privilegio=="admFatad"){
        include('./index.html');
  }elseif($privilegio=="opNuc"){
      $rsNucleo=$fg->findNucleoCpf($usuario);
      foreach($rsNucleo as $row){
          $idNucleo=$row->idNucleo;
      }
      $rsInadimplencia=$fg->findInadimplencia($idNucleo);
      foreach($rsInadimplencia as $row){
        $inadimplencia=$row['total'];
      }
      if($inadimplencia>2){
          include('./barOpNucInadim.php');
      }else{
          include('./barOpNuc.php');
      }   
  }elseif($privilegio=="opAluno"){
     include('./barOpAluno.php');
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
    <title>Gestão Financeira</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  
  <body>
      
    <?php include('./index.html'); ?>
    
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                
                <div class="card">
                    
                    <div class="card-header"> 
                        <tr>
                            <td>Operação Financeira - RECEBIMENTO</td>
                            <td>
                                  <a href="matEscolarNucleos.php" class="btn btn-danger btn-sm float-end">Voltar</a>
                            </td>
                        </tr>
                    </div>
                   
                    <form action="opFinanceirasAcoes.php" method="POST">
                    <?php
                        $turma = mysqli_real_escape_string($conn, $_GET['idTurma']); 
                        $idCurso = mysqli_real_escape_string($conn, $_GET['idCurso']);
                        $idNucleo = mysqli_real_escape_string($conn, $_GET['idNucleo']);
                        $idDisciplina = mysqli_real_escape_string($conn, $_GET['idDisciplina']);
                        $sql = "SELECT *, "
                                ."(select nomeRespNucleo from tb_nucleofatad where idNucleo =idResp and perfil='Núcleo') as nomeResponsavel, "
                                ."(select descNucleo from tb_nucleofatad where idNucleo =idResp and perfil='Núcleo') as descNucleo "
                                ."FROM tb_op_financeira "
                                ."where idMaterial=$idDisciplina and  idTurma=$turma";

                        $itens = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($itens) > 0) {
                            foreach ($itens as $item) {
                            ?> 
                                
                            <div class="mb-3">
                              <label>Descrição do Material e destinação:</label>
                              <p class="form-control">
                                  <?=$item['descOp'],' -  Destinado para:   '. $item['nomeResponsavel'];?>
                            </p> 
                            
                            <div class="mb-3">
                                <label>Quantidade X Preço Unitário + Frete = Valor Total</label>
                                <p class="form-control"> <?=$item['qtdMat'];?> X <?=$item['valorUnitario']?> + <?=$item['frete'];?> = R$  
                                <?= number_format($item['qtdMat']*$item['valorUnitario']+$item['frete'],2,',','.');?>
                            <div class="mb-3">
                                </p> 
                            </div>
                            
                            <div class="mb-3">
                                <label>Data Distribuição Material: </label>
                                <p class="form-control">
                                   <?=date('d/m/Y', strtotime($item['dtContrato']))?>
                                </p> 
                            </div> 
                            <div class="mb-3">
                                <label>Data do Pagamento:</label>
                                <input type="date" required="" placeholder="dd/mm/AAAA" name="dtPagamento" class="form-control">
                            </div> 

                                <input type="hidden" name="qtdMat" value="<?=$item['qtdMat']?>" class="form-control"><input type="hidden" name="idTurma" value="<?=$turma?>" class="form-control">
                                <input type="hidden" name="idCurso" value="<?=$idCurso?>" class="form-control">
                                <input type="hidden" name="idNucleo" value="<?=$idNucleo?>" class="form-control">
                                <input type="hidden" name="idDisciplina" value="<?=$idDisciplina?>" class="form-control">
                                <input type="hidden" name="idOp" value="<?=$item['idOp']?>" class="form-control">                           
 <?php
                            }
                        }
                        ?>
                        <div class="mb-3">
                          <button type="submit" name="OpFinanNucleoReceber" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
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