<?php
session_start();
require './conexao.php';
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

if (isset($_SESSION['usuario_autenticado'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opFatad"){
      include('./index.html');
  }elseif($privilegio=="admFatad"){

  }elseif($privilegio=="opNuc"){
      include('./barOpNuc.php');
  }elseif($privilegio=='opAluno'){
      //visitante
      include('./barVisitante.html');    
  }else { 
  echo 'Sessão não iniciada ou privilégio não definido.'; 
  // Redirecionar para a página de login ou mostrar uma mensagem de erro 
  header('Location: login.php'); exit(); 
} 
}
// var_dump($_GET);
// exit();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Material Aluno Sede</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    
  
  </head>
  
  <body>
      
    <?php 
    $idTurma = mysqli_real_escape_string($conn, $_GET['idTurma']); 
    $idCurso = mysqli_real_escape_string($conn, $_GET['idCurso']);
    $idAluno = mysqli_real_escape_string($conn, $_GET['idAluno']);
    $cpf = mysqli_real_escape_string($conn, $_GET['cpf']);
    $idDisciplina = mysqli_real_escape_string($conn, $_GET['idDisciplina']);
    ?>
    
    <div class="container mt-4">
    <br><br>
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <tr>
                            <td><h5 style="color: blue;">Operação Financeira - RECEBIMENTO</td>
                            <td>
                              <!--<a href="matEscolarGRUDpesDisc.php" class="btn btn-link"><span class="bi bi-search"></span>&nbsp;Pesquisar</a>-->
                              <!--<button type="button" class="bi bi-search" class="btn btn-link">Pesquisar</button>-->
                              <a href="matEscolarAlunoDistribuir.php?
                                  idAluno=<?=$idAluno;?>
                                 &cpf=<?=$cpf;?>
                                 &idTurma=<?=$idTurma;?>
                                 &idCurso=<?=$idCurso;?>
                                 &idDisciplina=<?=$idDisciplina?>"
                                 class="btn btn-danger btn-sm float-end">Voltar</a>
                              <!--<a href="matEscolarGRUDcreate.php" class="btn btn-primary float-end">Cadastrar&nbsp;</a>-->
                            </td>
                        </tr>
                    </div>
    
                    <form action="opFinanceirasAcoes.php" method="POST">
                    <?php
                        $sql = "SELECT DISTINCT 
                                f.*, 
                                a.nomeAluno, 
                                m.idAluno, 
                                m.valorMaterial, 
                                m.valorEncadernacao, 
                                m.frete, 
                                m.opcao
                            FROM tb_op_financeira AS f
                            JOIN tb_aluno AS a ON f.idResp = a.cpfAluno
                            JOIN tb_matricula AS m ON m.idAluno = a.idAluno

                               WHERE perfil ='FATAD' 
                               AND f.idMaterial=$idDisciplina 
                               AND  m.idTurma=$idTurma 
                               AND idResp=$cpf";

                        $itens = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($itens) > 0) {
                            foreach ($itens as $item) {
                                $valorMaterial=$fg->normalizarValor($item['valorUnitario']);
                                if($item['opcao']==1){
                                    $valorEncadernacao=$fg->normalizarValor($item['valorEncadernacao']);
                                    $frete=$fg->normalizarValor($item['frete']);
                                }else{
                                    $valorEncadernacao=0;
                                    $frete=0;
                                }
                            ?> 
                                
                            <div class="mb-3">
                              <label>Descrição do Material e destinação:</label>
                              <p class="form-control">
                                  <?=$item['descOp'],' -  Destinado para:   '. $item['nomeAluno'];?>
                            </p> 
                            
                            <div class="mb-3">
                    <table class="table table-bordered table-hover mt-4">
                    <thead class="thead-light">
                        <tr>
                        <th>Valor do Material</th>
                        <th>Custo Encadernação</th>
                        <th>Custo Frete</th>
                        <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>R$ <?= number_format($valorMaterial, 2, ',', '.'); ?></td>
                        <td>R$ <?= number_format($valorEncadernacao, 2, ',', '.'); ?></td>
                        <td>R$ <?= number_format($frete, 2, ',', '.'); ?></td>
                        <td>R$ <?= number_format($item['valorUnitario']+$item['valorEncadernacao']+$item['frete'], 2, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                    </table>
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
                                

                                
                                <input type="hidden" name="qtdMat" value="<?=$item['qtdMat']?>" class="form-control">
                                <input type="hidden" name="idTurma" value="<?=$idTurma?>" class="form-control">
                                <input type="hidden" name="idCurso" value="<?=$idCurso?>" class="form-control">
                                <input type="hidden" name="idAluno" value="<?=$idAluno?>" class="form-control">
                                <input type="hidden" name="cpf" value="<?=$cpf?>" class="form-control">
                                <input type="hidden" name="idDisciplina" value="<?=$idDisciplina?>" class="form-control">
                                <input type="hidden" name="idOp" value="<?=$item['idOp']?>" class="form-control">                           
 <?php
                            }
                        }
                        ?>
                        <div class="mb-3">
                          <button type="submit" name="OpFinanAlunoReceber" class="btn btn-primary">Salvar</button>
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