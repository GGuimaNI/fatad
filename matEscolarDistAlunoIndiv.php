<?php
session_start();
require './conexao.php';
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

if (isset($_SESSION['usuario_autenticado'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario = $_SESSION['usuario'];
  // Adicione lógica baseada no privilégio do usuário 
  if ($privilegio == "opAluno") {
        include('./barOpAluno.php');
  } else { 
        echo 'Sessão não iniciada ou privilégio não definido.'; 
        // Redirecionar para a página de login ou mostrar uma mensagem de erro 
        header('Location: logout.php'); 
        exit(); 
} 
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Material Escolar Aluno Individual</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  </head>
  
  <body>
      
    <?php 
   
    $idTurma = rtrim($_GET['idTurma']); 
    $idCurso =  rtrim($_GET['idCurso']);
    $idAluno =  rtrim($_GET['idAluno']);
    $cpf =  rtrim($_GET['cpf']);
    $idDisciplina = rtrim($_GET['idDisciplina']);
    ?>
<br><br>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <tr>
                            <td><h5 style="color: blue;">Solicitar Material
                              <a href="matEscolarDistribuirIndiv.php?
                                 idAluno=<?=$idAluno;?>
                                 &cpf=<?=$cpf;?>
                                 &idTurma=<?=$idTurma;?>
                                 &idCurso=<?=$idCurso;?>
                                 &idDisciplina=<?=$idDisciplina?>" 
                                 class="btn btn-danger btn-sm float-end">Voltar</a></h5>
                            </td>
                        </tr>
                    </div>
    
                    <form action="opFinanceirasAcoes.php" method="POST" enctype="multipart/form-data">
                        <?php
                        // Preparar a conexão PDO
                        $pdo = new Config;

                        // Definir a consulta SQL com parâmetros
                        $sql = "SELECT DISTINCT h.idHistorico,h.idAluno,h.idCurso,h.idNucleo,h.idTurma , d.codigoDisciplina,d.nomeDisciplina, a.idAluno,a.nomeAluno,a.cpfAluno, m.opcao,m.valorMaterial,m.valorEncadernacao,m.frete,
                                    (SELECT dtPagamento FROM fatadgestao.tb_op_financeira 
                                    WHERE idMaterial = h.idDisciplina 
                                    AND idResp = a.cpfAluno 
                                    AND perfil = 'FATAD') AS dtPagamento 
                                FROM tb_historico_aluno AS h 
                                JOIN tb_disciplinas AS d ON h.idDisciplina = d.idDisciplina 
                                JOIN tb_aluno AS a ON h.idAluno = a.idAluno 
                                JOIN tb_matricula AS m ON h.idAluno = m.idAluno  
                                WHERE a.cpfAluno = :cpf 
                                AND h.idTurma = :idTurma 
                                AND h.idCurso = :idCurso 
                                AND d.idDisciplina = :idDisciplina";

                        // Preparar e executar a consulta
                        $statement = $pdo->prepare($sql);
                        $statement->execute([
                            ':cpf' => $cpf,
                            ':idTurma' => $idTurma,
                            ':idCurso' => $idCurso,
                            ':idDisciplina' => $idDisciplina
                        ]);

                        // Buscar resultados
                        $itens = $statement->fetchAll(PDO::FETCH_ASSOC);

                        if ($itens) {
                            foreach ($itens as $item) { 
                                $valorMaterial=$fg->normalizarValor($item['valorMaterial']);
                                if($item['opcao']==1){
                                    $valorEncadernacao=$fg->normalizarValor($item['valorEncadernacao']);
                                    $frete=$fg->normalizarValor($item['frete']);
                                }else{
                                    $valorEncadernacao=0;
                                    $frete=0;
                                }
                    ?>  
                    <div class="form-group">
                        <div class="mb-3">
                            <label>Descrição do Material e destinação:</label>
                            <p class="form-control">
                                <?= $item['nomeDisciplina'] . ' - Destinado para: ' . $item['nomeAluno']; ?>
                                <input type="hidden" required name="descOp" value="<?= $item['nomeDisciplina'] ?>" class="form-control">
                            </p> 
                        </div>

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
                                <td>R$ <?= number_format($item['valorMaterial']+$item['valorEncadernacao']+$item['frete'], 2, ',', '.'); ?></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    <?php
                            }
                        }
                    ?>

                    <!-- Campo de upload de arquivo -->
                    <div class="mb-3">
                        <label for="documento">Envie seu Recibo de Depósito:</label>
                        <input type="file" required="" name="documento" id="documento" class="form-control">
                    </div>
                    <input type="hidden" name="qtdMat" value="1" class="form-control">
                                <input type="hidden" name="pagamento" value="<?=$item['opcao']?>" class="form-control">
                                <input type="hidden" name="valorUnit" value="<?=$item['valorMaterial']?>" class="form-control">
                                <input type="hidden" name="valorEncadernacao" value="<?=$item['valorEncadernacao']?>" class="form-control">
                                <input type="hidden" name="frete" value="<?=$item['frete']?>" class="form-control">
                                <input type="hidden" name="nomeResp" value="<?=$item['nomeAluno']?>" class="form-control">
                                <input type="hidden" name="descOp" value="<?=$item['nomeDisciplina']?>" class="form-control">
                                <input type="hidden" name="idResponsavel" value="<?=$item['cpfAluno']?>" class="form-control">
                                <input type="hidden" name="perfil" value="FATAD" class="form-control">
                                <input type="hidden" name="tipoOp" value="P" class="form-control">
                                <input type="hidden" name="idTurma" value="<?=$idTurma?>" class="form-control">
                                <input type="hidden" name="idCurso" value="<?=$idCurso?>" class="form-control">
                                <input type="hidden" name="idAluno" value="<?=$idAluno?>" class="form-control">
                                <input type="hidden" name="idDisciplina" value="<?=$idDisciplina?>" class="form-control">

                    <div class="mb-3">
                        <button type="submit" name="OpFinanAluno" class="btn btn-primary btn-sm">Salvar</button>
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

