<?php
session_start();
require './conexao.php';
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
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

//var_dump($_GET);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrega Material Escolar</title>
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
                            <td>
                                <h5 style="color: blue;"> Distribuir Material Núcleo
                                <a href="matEscolarNucleos.php" class="btn btn-danger btn-sm float-end">Voltar</a>
                                <h5>
                            </td>
                        </tr>
                    </div>
    
                    <form action="opFinanceirasAcoes.php" method="POST">
                    <?php
                        $turma = rtrim(mysqli_real_escape_string($conn, $_GET['idTurma'])); 
                        $idCurso = rtrim(mysqli_real_escape_string($conn, $_GET['idCurso']));
                        $idNucleo = rtrim(mysqli_real_escape_string($conn, $_GET['idNucleo']));
                        $idDisciplina = rtrim(mysqli_real_escape_string($conn, $_GET['idDisciplina']));
                        
                        $sql = "SELECT *, "
                                ."(SELECT  count(idAluno)   FROM tb_matricula where idTurma=t.idTurma) as qtdAlunos, "
                                ."(Select nomeRespNucleo From tb_nucleofatad where idNucleo=t.idNucleo) as nomeRespNucleo, "
                                ."(Select descNucleo From tb_nucleofatad where idNucleo=t.idNucleo) as descNucleo, "
                                ."(Select perfil From tb_nucleofatad where idNucleo=t.idNucleo) as perfil, "
                                ."(Select nomeDisciplina from tb_disciplinas where idDisciplina=c.idDisciplinaCurriculo) as nomeDisciplina, "
                                ."(Select valorMatDisciplina from tb_disciplinas where idDisciplina=c.idDisciplinaCurriculo) as valorMatDisciplina "
                                ."FROM tb_turma as t, tb_curriculo_disciplinar as c "
                                ."where idTurma=$turma and c.idCursoCurriculo=$idCurso and t.idNucleo=$idNucleo and c.idDisciplinaCurriculo=$idDisciplina";

                        $itens = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($itens) > 0) {
                            foreach ($itens as $item) {
                            ?> 
                            <div class="form-group">
                                
                            <div class="mb-3">
                              <label>Descrição do Material e destinação:</label>
                              <p class="form-control">
                                  <?=$item['nomeDisciplina'],' -  Destinado para:   '. $item['nomeRespNucleo'];?>
                                  <input type="hidden" required="" name="descOp" value="<?=$item['nomeDisciplina']?>" class="form-control">
                            </p> 
                            
                            <div class="mb-3">
                                <label>Quantidade X Preço Unitário = Valor (sem frete)</label>
                                <p class="form-control"> <?=$item['qtdAlunos'];?> X <?=$item['valorMatDisciplina']?> = R$  
                                <?= number_format($item['qtdAlunos']*$item['valorMatDisciplina'],2,',','.');?>
                            <div class="mb-3">
                                </p> 
                            </div>
                            <div class="mb-3">
                                <label>Frete:</label>
                                <input type="number" required="" placeholder="Valor em reais ou 0 (zero)." name="frete" value="" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Data da Opeação:</label>
                                <input type="date" required="" placeholder="dd/mm/AAAA" name="dtContrato" class="form-control">
                            </div> 
                            <div class="mb-3">
                                <label>Data do Pagamento:</label>
                                <input type="date" placeholder="dd/mm/AAAA" name="dtPagamento" class="form-control">
                            </div> 
                                <input type="hidden" name="qtdMat" value="<?=$item['qtdAlunos']?>" class="form-control">
                                <input type="hidden" name="valorUnit" value="<?=$item['valorMatDisciplina']?>" class="form-control">
                                <input type="hidden" name="nomeResp" value="<?=$item['nomeRespNucleo']?>" class="form-control">
                                <input type="hidden" name="descOp" value="<?=$item['nomeDisciplina']?>" class="form-control">
                                <input type="hidden" name="idResponsavel" value="<?=$item['idNucleo']?>" class="form-control">
                                <input type="hidden" name="perfil" value="<?=$item['perfil']?>" class="form-control">
                                <input type="hidden" name="tipoOp" value="R" class="form-control">
                                <input type="hidden" name="idTurma" value="<?=$turma?>" class="form-control">
                                <input type="hidden" name="idCurso" value="<?=$idCurso?>" class="form-control">
                                <input type="hidden" name="idNucleo" value="<?=$idNucleo?>" class="form-control">
                                <input type="hidden" name="idDisciplina" value="<?=$idDisciplina?>" class="form-control">
                            <?php
                            }
                        }
                        ?>
                        <div class="mb-3">
                          <button type="submit" name="OpFinanNucleo" class="btn btn-primary btn-sm">Salvar</button>
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