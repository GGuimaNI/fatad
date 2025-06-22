<?php
session_start();
require './conexao.php';
include_once 'fatadgestaoControler.php';
$fg=new fatadgestaoControler;
//var_dump($_POST);
//var_dump($_GET);
if (isset($_SESSION['privilegio'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opFatad"){
      include('./index.html');
      $rsNucleos = $fg->findNucleo();
  }elseif($privilegio=="admFatad"){

  }elseif($privilegio=="opNuc"){
      include('./barOpNuc.php');
      $rsNucleos = $fg->findNucleoCpf($usuario);
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
    <title>Visualizar Núcleos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <br>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: blue;">Visualizar Núcleo
                        <a href="nucleosGRUD.php" class="btn btn-danger btn-sm float-end">Voltar</a>
                        </h5>
                    </div>
                    <div class="card-body">
                    <?php
                       if (isset($_GET['idNucleo'])) {
                           $nucleo_id = mysqli_real_escape_string($conn, $_GET['idNucleo']);
                           $sql = "SELECT * 
                                   FROM tb_nucleofatad AS n 
                                   JOIN tb_usuarios AS u ON n.cpfResp = u.cpfUsuario 
                                   WHERE idNucleo='$nucleo_id'";
                           $query = mysqli_query($conn, $sql);
                           if (mysqli_num_rows($query) > 0) {
                             $nucleo = mysqli_fetch_array($query);
                       ?>
                        <form action="nucleosGRUDacoes.php" method="POST">
                            <div class="mb-3">
                              <label>Núcleo:</label>
                              <p class="form-control">
                                  <?=$nucleo['descNucleo'];?>
                              </p> 
                            </div>
                            <div class="mb-3">
                                <label>Número Núcleo:</label>
                                <p class="form-control">
                                    <?php 
                                    if (isset($nucleo['nrNucleo']) && $nucleo['nrNucleo'] !== null) {
                                      echo $nucleo['nrNucleo'];
                                    } else {
                                        echo 'Valor não disponível';
                                    }
                                    ?>
                                </p>
                            </div>

                            <div class="mb-3">
                              <label>Responsável:</label>
                              <p class="form-control">
                                  <?=$nucleo['nomeRespNucleo'];?>
                              </p> 
                            </div>
                            <div class="mb-3">
                              <label>CPF:</label>
                              <p class="form-control">
                                  <?=$nucleo['cpfResp'];?>
                              </p> 
                            </div>
                            
                            <div class="mb-3">
                              <label>Endereço:</label>
                              <p class="form-control">
                                  <?=$nucleo['enderecoNucleo'];?>
                              </p> 
                            </div>
                            <div class="mb-3">
                              <label>Cidade-UF:</label>
                              <p class="form-control">
                                  <?=$nucleo['cidadeUF'];?>
                              </p> 
                            </div> 
                            <div class="mb-3">
                              <label>CEP:</label>
                              <p class="form-control">
                                  <?=$nucleo['cep'];?>
                              </p> 
                            </div>  
                            <div class="mb-3">
                              <label>Telefone:</label>
                              <p class="form-control">
                                  <?=$nucleo['telZap'];?>
                              </p> 
                            </div>    
                            <div class="mb-3">
                              <label>E-mail:</label>
                              <p class="form-control">
                                  <?=$nucleo['email'];?>
                              </p> 
                            </div>            

                        <?php
                            } else {
                                echo "<h5>Usuário não encontrado</h5>";
                              }
                            }
                        ?>
                         </form>
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