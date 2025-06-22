<?php
session_start();
require './conexao.php';
include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;
$fgN=new fatadgestaoControler;
$usuario=0;

if (isset($_SESSION['usuario_autenticado'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  //Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="Visitante"){
      //visitante      include('./barVisitante.html');  
  }elseif($privilegio=="opAluno"){
        include('./barOpAluno.php');          
  } else { 
  echo 'Sessão não iniciada ou privilégio não definido.'; 
  // Redirecionar para a página de login ou mostrar uma mensagem de erro 
  header('Location: logout.php'); exit(); 
} 
}
// var_dump($_GET);
// var_dump($_POST);
// var_dump($_SESSION);
// exit();
?>   

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <br>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- <?php
                    if (isset($_SESSION['message'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
                        unset($_SESSION['message']); // Limpa a mensagem após exibição
                    } 
                    ?> -->
                    <div class="card-header">
                        <h5 style="color: blue;">Cadastro
                        <a href="iniciar.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        $pdo = new Config();

                        // Busca os dados do aluno já está cadastrado ou não
                        // $cpfAluno=preg_replace('/[^0-9]/', '', $usuario);
                        if(isset($_GET['cpfUsuario'])){
                            //neste caso, o operador é opFatad ou opAdmin
                            $cpfAluno=preg_replace('/[^0-9]/', '', $_GET['cpfUsuario']);
                        }else{
                            //neste caso é o visitante quem está editando
                            $cpfAluno=preg_replace('/[^0-9]/', '', $usuario);
                        }
                        $query = "SELECT * FROM tb_aluno WHERE cpfAluno = :cpfAluno";
                
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':cpfAluno', $cpfAluno, PDO::PARAM_STR);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            $aluno=$stmt->fetch();
                        } else {
                            header('Location: cadastroAlunoCreate.php');
                            exit();
                        }  
                        
                        ?>
                            <form action="cadastroAcoes.php" method="POST">

                            <div class="mb-3">
                              <label>Nome</label>
                              <input type="text" name="nomeAluno" value="<?= isset($aluno['nomeAluno']) ? htmlspecialchars($aluno['nomeAluno']) : '' ?>" class="form-control" readonly>
                            </div>

                            <?php
                            $escolaridade = isset($aluno['escolaridade']) ? htmlspecialchars($aluno['escolaridade']) : '';
                            ?>
                            <div class="mb-3">
                                <label>Escolaridade:</label>
                                <label>
                                    <input type="radio" required="" neme= "escolaridade" value="Fundamental" <?= ($escolaridade == 'Fundamental') ? 'checked' : '' ?>> Fundamental
                                </label>
                                <label>
                                    <input type="radio" required="" name="escolaridade" value="Médio" <?= ($escolaridade == 'Médio') ? 'checked' : '' ?>> Médio
                                </label>
                                <label>
                                    <input type="radio"required=""  name="escolaridade" value="Superior" <?= ($escolaridade == 'Superior') ? 'checked' : '' ?>> Superior
                                </label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Instituição onde estudou:</label>
                                <input type="text" name="instEnsino" value="<?= isset($aluno['instEnsino']) ? htmlspecialchars($aluno['instEnsino']) : '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Data Início Curso:</label>
                                <input type="date" placeholder="dd/mm/AAAA" name="dtIniEstudo" value="<?= isset($aluno['dtIniEstudo']) ? htmlspecialchars($aluno['dtIniEstudo']) : '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Data Conclusão:</label>
                                <input type="date" placeholder="dd/mm/AAAA" name="dtTerEstudo" value="<?= isset($aluno['dtTerEstudo']) ? htmlspecialchars($aluno['dtTerEstudo']) : '' ?>" class="form-control">
                            </div>
                             
                            <div class="mb-3">
                                <label>Nome Pai:</label>
                                <input type="text" placeholder="Nome do pai completo" name="nomePai" id="nomePaiAluno" value="<?= isset($aluno['nomePaiAluno']) ? htmlspecialchars($aluno['nomePaiAluno']) : '' ?>" class="form-control">
                            </div>

                            <div class="mb-3">
                                <?php
                                // Supondo que $aluno seja um array associativo com os dados do aluno
                                $estadoCivil = isset($aluno['estadoCivil']) ? htmlspecialchars($aluno['estadoCivil']) : '';
                                ?>
                                <label>Estado Civil:</label>
                                <label>
                                <input type="radio" name="estadoCivil" value="Casado(a)" <?= ($estadoCivil == 'Casado(a)') ? 'checked' : '' ?>> Casado(a)
                                </label>
                                <label>
                                <input type="radio" name="estadoCivil" value="Solteiro(a)" <?= ($estadoCivil == 'Solteiro(a)') ? 'checked' : '' ?>> Solteiro(a)
                                </label><label>
                                <input type="radio" name="estadoCivil" value="Viúvo(a)" <?= ($estadoCivil == 'Viúvo(a)') ? 'checked' : '' ?>> Viúvo(a)
                                </label><label>
                                <input type="radio" name="estadoCivil" value="Divorciado(a)" <?= ($estadoCivil == 'Divorciado(a)') ? 'checked' : '' ?>>Divorciado(a)
                                </label><label>
                                <input type="radio" name="estadoCivil" value="União Estável" <?= ($estadoCivil == 'União Estável') ? 'checked' : '' ?>> União Estável
                                </label>
                            </div> 
                            <div class="mb-3">
                                <label>Nome do Cônjuge:</label>
                                <input type="text" name="nomeConjuge" value="<?= isset($aluno['nomeConjuge']) ? htmlspecialchars($aluno['nomeConjuge']) : '' ?>" class="form-control">
                            </div>

                            <!--Experiência cristã -->

                            <div class="mb-3">
                                <p style="color: blue;"><label>Informações Sobre Experiência Cristã</label></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Igreja onde é membro:</label>
                                <input type="text" name="instIgreja" value="<?= isset($aluno['instIgreja']) ? htmlspecialchars($aluno['instIgreja']) : '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Endereço da Igreja:</label>
                                <input type="text" name="endIgreja" value="<?= isset($aluno['endIgreja']) ? htmlspecialchars($aluno['endIgreja']) : '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nome do Pastor:</label>
                                <input type="text" name="nomePastor" value="<?= isset($aluno['nomePastor']) ? htmlspecialchars($aluno['nomePastor']) : '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Seu Cargo ou Função na Igreja:</label>
                                <input type="text" name="cargoFuncao" value="<?= isset($aluno['cargoFuncao']) ? htmlspecialchars($aluno['cargoFuncao']) : '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Observações Julgadas Necessárias:</label>
                                <textarea rows="4" style="width: 100%;" placeholder="Tamanho máximo de 200 caracteres" name="obs" class="form-control" maxlength="400">
                                    <?= isset($aluno['obs']) ? htmlspecialchars($aluno['obs']) : '' ?>
                                </textarea>                            
                            </div>
                           
                            <input type="hidden" id="idAluno" name="idAluno" value="<?= isset($aluno['idAluno']) ? htmlspecialchars($aluno['idAluno']) : '' ?>" class="form-control">

                            <div class="mb-3">
                              <button type="submit" name="updateAlunoCompl" class="btn btn-primary">Salvar</button>
                            </div>
                        </form>      
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

 
        <style>
            input[type="radio"] {
                margin-right: 5px;
            }
            label {
                font-size: 16px;
                margin-right: 15px;
                cursor: pointer;
            }
        </style>
  </body>
</html>