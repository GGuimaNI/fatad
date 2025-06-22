<?php
session_start();
require './conexao.php';
include_once 'fatadgestaoControler.php';
$fg=new fatadgestaoControler;
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
    <title>Núcleos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <br>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5 style="color: blue;">Editar Núcleo
                <!-- <a href="nucleosGRUD.php" class="btn btn-danger btn-sm float-end">Voltar</a> -->
              </h4>
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
                  <label>Nome do Núcleo</label>
                  <input type="text" name="descNucleo"  value="<?=$nucleo['descNucleo']?>"  class="form-control">
                </div>
                <div class="mb-3">
                    <label>Número Núcleo (repita o número, mesmo que conste do nome.)</label>
                    <input type="number" name="nrNucleo" class="form-control" value="<?php
                        if (isset($nucleo['nrNucleo']) && $nucleo['nrNucleo'] !== null) {
                            echo $nucleo['nrNucleo'];
                        } else {
                            echo 'Preencha, se for o caso.';
                        }
                    ?>">
                </div>


                <div class="mb-3">
                  <label>CPF do Responsável:</label>
                  <select name="cpfResp" id="cpfResp" class="form-control" onchange="preencheDados()">
                    <option value="">Selecione</option>
                    <?php
                    //$result=$fg->listUsuario();

                    // Busca os dados da tabela 
                    $sql = "Select cpfUsuario as cpf, nomeUsuario as nome, emailUsuario as email FROM tb_usuarios"; 
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $selected = $row["cpf"] == $nucleo["cpfResp"] ? 'selected' : '';

                            echo "<option value=\"" . $row["cpf"] . ";" . $row["nome"] . ";" . $row["email"] . "\" $selected>" . $row["cpf"] . "</option>";
                          }
                    } else {
                        echo "<option value=\"\">Nenhuma opção disponível</option>";
                    }
                    ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label>Nome do Responsável:</label>
                  <input type="text" required="" name="nomeRespNucleo" id="nomeRespNucleo" class="form-control">
                </div>
                <!-- Para guardar o cpf, visto que o select tem outros valores agregados--> 
                <input type="hidden" name="cpf" id="cpf" class="form-control"> 
                
                <div class="mb-3">
                    <label>E-mail:</label>
                    <input type="email" required="" name="email" id="emailRespNucleo" class="form-control">
                </div>  

                <div class="mb-3">
                    <label>Perfifil do Núcleo:&nbsp;&nbsp;&nbsp;</label>
                    <?php 
                    if($nucleo['perfil']=="Núcleo"){
                    ?>
                        <input type="radio" required="" name="opcao" value="Núcleo" checked > Núcleo &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="opcao" value="FATAD"> FATAD       
                    <?php 
                    }else{
                    ?>
                        <input type="radio" required="" name="opcao" value="Núcleo"  > Núcleo &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="opcao" value="FATAD" checked> FATAD  
                    <?php            
                    }
                    ?>     
                </div>    
                  
                <div class="mb-3">
                    <label>Telefone:</label>
                    <input type="number" required="" placeholder="Apenas números, com DDD" name="telZap" id="telZap"  value="<?=$nucleo['telZap']?>"  class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Endereço:</label>
                    <input type="text" required="" name="enderecoNucleo" id="enderecoNucleo" value="<?=$nucleo['enderecoNucleo']?>"  class="form-control">
                </div>                 
                 <div class="mb-3">
                    <label class="form-label">Cidade-UF:</label>
                    <input type="text" name="cidadeUF" id="cidadeUF" value="<?=$nucleo['cidadeUF']?>"  class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">CEP:</label>
                    <input type="text" required="" name="cep" id="cep" value="<?=$nucleo['cep']?>" class="form-control">
                </div>
                       
                <input type="hidden" id="idNucleo" name="idNucleo" value="<?=$nucleo['idNucleo']?>">
                
                <div class="mb-3">
                  <button type="submit" name="update_nucleo" class="btn btn-primary btn-sm">Salvar</button>
                </div>

                <?php
                    } else {
                        echo "<h5>Núcleo não possui Coordenador.  Utilize o menu Administração para atribuir um.</h5>";
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

    function preencheDados() {
            const select = document.getElementById("cpfResp");
            const selectedOption = select.options[select.selectedIndex].value;
            const [cpf, nome, email] = selectedOption.split(";");
            
            document.getElementById("cpf").value = cpf;
            document.getElementById("nomeRespNucleo").value = nome;
            document.getElementById("emailRespNucleo").value = email;
        }
        // Função para preencher os dados ao carregar a página
        window.onload = function() {
            preencheDados();
        };

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