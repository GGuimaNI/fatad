
<?php
//include_once './fatadgestaoControler.php';
//$fg = new fatadgestaoControler;
require './conexao.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Núcleos - Criar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <?php include('index.html'); ?>
    <br><br>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5 style="color: blue;">Incluir Núcleo
              </h4>
            </div>
            <div class="card-body">
                <form action="nucleosGRUDacoes.php" method="POST">
                
                <div class="mb-3">
                  <label>Nome do Núcleo:</label>
                  <input type="text" required="" name="descNucleo" class="form-control">
                </div>
                  
                <div class="mb-3">
                    <label>Número Núcleo (repita o número, mesmo que conste do nome.)</label>
                    <input type="number" required="" name="nrNucleo" class="form-control">
                </div>
              
                <div class="mb-3">
                    <label>Perfil do Núcleo:&nbsp;&nbsp;&nbsp;</label>
                    <input type="radio" name="opcao" value="Núcleo" checked> Núcleo 
                    <input type="radio" name="opcao" value="FATAD"> FATAD         
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
                            echo "<option value=\"" . $row["cpf"] . ";" . $row["nome"] . ";" . $row["email"] . "\">" . $row["cpf"] . "</option>";
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
                    <label>Telefone:</label>
                    <input type="number" required="" placeholder="Apenas números, com DDD" name="telZap" id="telZap" class="form-control">
                 </div>
                                  
                <div class="mb-3">
                    <label class="form-label">CEP:</label>
                    <input type="text" name="cep" id="cep" class="form-control" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nº:</label>
                    <input type="text" name="numero" id="numero" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Rua:</label>
                    <input type="text" name="rua" id="rua" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Complemento:</label>
                    <input type="text" placeholder="Alguma referência do endereço" name="complemento" id="complemento" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Bairro:</label>
                    <input type="text" name="bairro" id="bairro" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Cidade:</label>
                    <input type="text" name="cidade" id="cidade" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado:</label>
                    <input type="text" name="estado" id="estado" class="form-control" maxlength="2" class="form-control">
                </div>  
                    
                <div class="mb-3">
                  <button type="submit" name="cadastro_nucleo" class="btn btn-primary">Salvar</button>
                </div>
           
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js" integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="js/cep.js"></script>
  </body>
</html>
