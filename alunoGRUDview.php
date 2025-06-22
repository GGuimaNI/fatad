<?php
session_start();
require './conexao.php';
if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opFatad"){
        include('./index.html');
    }elseif($privilegio=="admFatad"){
        include('./index.html');
    }elseif($privilegio=="opNuc"){
        include('./barOpNuc.php');
    }elseif($privilegio=="opAluno"){
        include('./barOpAluno.php');
    }elseif($privilegio=="Visitante"){
        include('./barVisitante.html');    
    } else { 
    echo 'Sessão não iniciada ou privilégio não definido.'; 
    // Redirecionar para a página de login ou mostrar uma mensagem de erro 
    header('Location: login.php'); exit(); 
  }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>    
    <div class="container mt-5">
    <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: blue;">Visualizar Aluno
                        <a href="alunosGRUD.php" class="btn btn-danger float-end">Voltar</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['idAluno'])) {
                            $aluno_id = mysqli_real_escape_string($conn, $_GET['idAluno']);
                            $sql = "SELECT *,(SELECT descNucleo FROM tb_nucleofatad 
                                              WHERE tb_nucleofatad.idNucleo=tb_aluno.idCadastro)as descNucleo 
                                    FROM tb_aluno WHERE idAluno='$aluno_id'";
                            $query = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($query) > 0) {
                              $aluno = mysqli_fetch_array($query);
                        ?>
                        <form action="">
                            <div class="mb-3">
                              <label>Vínculo do Aluno:</label>
                              <p class="form-control">
                                  <?=$aluno['descNucleo'];?>
                              </p> 
                            </div>
                            <div class="mb-3">
                              <label>Nome</label>
                              <p class="form-control">
                                  <?=$aluno['nomeAluno'];?>
                              </p> 
                            </div>
                            <div class="mb-3">
                                <label>CPF:</label>
                                <p class="form-control">
                                  <?=$aluno['cpfAluno'];?>
                                </p>
                                
                            </div> 
                            <div class="mb-3">
                                <label>Identidade:</label>
                                <?=$aluno['idtAluno'];?>
                            </div>   

                            <div class="mb-3">
                                <label>Natural de:</label>
                                <p class="form-control">
                                  <?=$aluno['cidadeNatAluno'];?>
                                </p>
                                
                            </div>
                            <div class="mb-3">
                                <label>Data de Nascimento:</label>
                                <p class="form-control">
                                  <?=date('d/m/Y', strtotime($aluno['dtNascAluno']))?>
                                </p>
                                
                            </div>  
                            <div class="mb-3">
                                <label>Nome Pai:</label>
                                <p class="form-control">
                                  <?=$aluno['nomePaiAluno'];?>
                                </p>
                                
                                
                            </div>
                            <div class="mb-3">
                                <label>Nome Mãe:</label>
                                <p class="form-control">
                                  <?=$aluno['nomeMaeAluno'];?>
                                </p>
                                
                                
                            </div> 
                            <div class="mb-3">
                                <label>E-mail:</label>
                                <p class="form-control">
                                  <?=$aluno['emailAluno'];?>
                                </p>
                                
                            <div class="mb-3">
                                <label>Telefone:</label>
                                <p class="form-control">
                                  <?=$aluno['telZapAluno'];?>
                                </p>
                                
                             </div> 

                            <div class="mb-3">
                                <label>CEP:</label>
                                <p class="form-control">
                                  <?=$aluno['cep'];?>
                                </p>
                                
                            </div>
                            <div class="mb-3">
                                <label>Endereço:</label>
                                <p class="form-control">
                                  <?=$aluno['enderecoAluno'];?>
                                </p>
                                
                            </div>
                            <div class="mb-3">
                                <label>Cidade-UF:</label>
                                <p class="form-control">
                                    <?=$aluno['cidadeMoradia'];?>
                                </p>
                                
                            </div>
                            

                        </form>
                        <?php
                        } else {
                            echo "<h5>Usuário não encontrado</h5>";
                          }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function buscaCep(cep){
            fetch('https://viacep.com.br/ws/'+cep+'/json/')
            .then(response => {
               if(!response.ok){
                        console.log("erro de conexao");
                        return;
               }
               return response.json();
            })
           .then(data => {
                   console.log(data);
                   txtRua.value = data.logradouro;
           })
           .catch(error => {
               console.log("Erro: ", error);
           });                         
       }
</script>

    <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="js/cep.js"></script>
  </body>
</html>