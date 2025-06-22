<?php
session_start();
require './conexao.php';
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

// var_dump($_SESSION);
// var_dump($_POST);
// var_dump($_GET);
// exit();
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
    <title>Visualizar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  </head>
  
  <body>
      
    <?php 

    $idOp = $_GET['idOp'];
    $perfil = $_GET['perfil'];
    ?>
    <div class="container mt-4">
    
    <br><br>
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: blue">Visão Operação Financeira
                            <?php if($privilegio=='opAluno'){ ?>
                                <a href="opFinanceiraGestaoAluno.php" class="btn btn-danger btn-sm float-end">Voltar</a>
                            <?php } else { ?>
                                <a href="opFinanceiraGestao.php" class="btn btn-danger btn-sm float-end">Voltar</a>
                            <?php } ?>
                        </h5>
                    </div>
    
                    <form action="">
                    <?php
                        
                        if($perfil=='FATAD' || $perfil=='opAluno'){
                           $sql = "SELECT f.*, a.*
                                    FROM tb_op_financeira AS f
                                    JOIN tb_aluno AS a ON a.cpfAluno = f.idResp 
                                   WHERE  f.idOp=$idOp"; 
                        }else{
                           $sql = "SELECT * 
                                    FROM tb_op_financeira as f
                                    JOIN tb_nucleofatad as n ON  f.idResp=n.cpfResp 
                                 WHERE f.idOp=$idOp"; 
                        }
                        

                        $itens = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($itens) > 0) {
                            foreach ($itens as $item) {
                            ?> 
                            <div class="form-group">
                                
                            <div class="mb-3">
                              <label>Descrição do Material:</label>
                                <p class="form-control">
                                <?=$item['descOp']?>
                                </p> 
                            </div>
                            <?php if($perfil=='FATAD'){?>    
                            <div class="mb-3">
                              <label>Destinação:</label>
                                <p class="form-control">
                                   Aluno de FATAD-Sede
                                </p> 
                            </div>
                            <div class="mb-3">
                              <label>Responsabilidade:</label>
                                <p class="form-control">
                                    <?=$item['nomeAluno']?>
                                </p> 
                            </div>
                            <?php }else{ ?>
                              <div class="mb-3">
                              <label>Destinação:</label>
                                <p class="form-control">
                                <?= $item['descNucleo'];?> 
                                </p> 
                            </div>
                            <div class="mb-3">
                              <label>Responsabilidade:</label>
                                <p class="form-control">
                                    <?=$item['nomeRespNucleo']?>
                                </p> 
                            </div>   
                            <?php } ?>  
                                
                            <div class="mb-3">
                                <label>Quantidade X Preço Unitário + Encadernação + Frete = Valor Total</label>
                                    <p class="form-control"> <?=$item['qtdMat']?> X <?= number_format($item['valorUnitario'],2,',','.');?> +<?= number_format($item['valorEncadernacao'],2,',','.');?> + <?= number_format($item['frete'],2,',','.');?> = R$  <?= number_format($item['valorTotal'],2,',','.');?>
                                </p> 
                            </div>
                            <?php 
                            if (is_null($item['dtPagamento'])
                               OR $item['dtPagamento']=='0000-00-00'
                               OR $item['dtPagamento']=='' ){
                               $dtPagamento='Não pago ou não processado';          
                            }else{
                                $dtPagamento = date('d/m/Y', strtotime($item['dtPagamento']));                            
                            } ?>
                            <?php 
                            if (is_null($item['dtPagamento'])
                               OR $item['dtPagamento']=='0000-00-00'
                               OR $item['dtPagamento']=='' ){
                            ?>
                            <div class="mb-3">
                                <label>Data da Opeação - Data do Pagamento:</label>         
                                <p style="color:red" class="form-control">                            
                                   <?=date('d/m/Y', strtotime($item['dtContrato']));?> / <?=$dtPagamento;?> 
                                </p>   
                            </div> 
                            <?php }else{?>    
                            <div class="mb-3">
                                <label>Data da Opeação / Data do Pagamento:</label>         
                                <p style="color:blue" class="form-control">                            
                                 <?=date('d/m/Y', strtotime($item['dtContrato']));?> paga em   <?=$dtPagamento;?> 
                                </p>   
                            </div>    
                            <?php }
                            }
                        }
                        ?>
                        
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


