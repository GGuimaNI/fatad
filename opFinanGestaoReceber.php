<?php
session_start();
require './conexao.php';
require './config.php';
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

    <br><br>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                
                <div class="card">
                    
                    <div class="card-header"> 
                        <tr>
                            <td><h5 style="color: blue;">Gestão Financeira - Receber<h/5></td>
                            <td>
                                  <a href="opFinanceiraGestao.php" class="btn btn-danger btn-sm float-end">Voltar</a>
                            </td>
                        </tr>
                    </div>
                   
                    <form action="opFinanceirasAcoes.php" method="POST">
                    <?php

                    $pdo=new Config;
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $perfil = trim($_GET['perfil'] ?? '');
                        $id = trim($_GET['id'] ?? '');
                        $idTurma = trim($_GET['idTurma'] ?? '');
                        $idCurso = trim($_GET['idCurso'] ?? '');
                        $idDisciplina = trim($_GET['idDisciplina'] ?? '');

                        // Conectando com PDO (certifique-se de já ter sua conexão $pdo configurada)

                        if ($perfil === 'FATAD') {
                            $sql = "SELECT f.*, a.nomeAluno AS nomeResponsavel
                                    FROM tb_op_financeira AS f
                                    JOIN tb_aluno AS a ON a.cpfAluno = f.idResp
                                    WHERE f.idMaterial = :idDisciplina 
                                    AND f.idTurma = :idTurma 
                                    AND f.idResp = :idResp";
                        } else {
                            $sql = "SELECT f.*, n.nomeRespNucleo AS nomeResponsavel, n.descNucleo
                                    FROM tb_op_financeira AS f
                                    JOIN tb_nucleofatad AS n ON n.cpfResp = f.idResp AND n.perfil = 'Núcleo'
                                    WHERE f.idMaterial = :idDisciplina 
                                    AND f.idTurma = :idTurma 
                                    AND f.idResp = :idResp";
                        }

                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            'idDisciplina' => $idDisciplina,
                            'idTurma' => $idTurma,
                            'idResp' => $id
                        ]);

                        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (!empty($itens)) {
                            foreach ($itens as $item) {
                                $valorMaterial=$item['valorUnitario'];
                                $valorEncadernacao=$item['valorEncadernacao'];
                                $frete=$item['frete'];
                       
                        ?>
                                
                            <div class="mb-3">
                              <label>Descrição do Material e destinação:</label>
                              <p class="form-control">
                                  <?=$item['descOp'],' -  Destinado para:   '. $item['nomeResponsavel'];?>
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
                                <td>R$ <?= number_format($valorMaterial+$valorEncadernacao+$frete, 2, ',', '.'); ?></td>
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
                                <input type="hidden" name="perfil" value="<?=$perfil?>" class="form-control">
                                <input type="hidden" name="idCurso" value="<?=$idCurso?>" class="form-control">
                                <input type="hidden" name="id" value="<?=$id?>" class="form-control">
                                <input type="hidden" name="idDisciplina" value="<?=$idDisciplina?>" class="form-control">
                                <input type="hidden" name="idOp" value="<?=$item['idOp']?>" class="form-control">                           
                        <?php
                            }
                        }
                        ?>
                        <div class="mb-3">
                          <button type="submit" name="OpFinanGestaoReceber" class="btn btn-primary">Salvar</button>
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