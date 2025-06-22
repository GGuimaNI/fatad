<?php
session_start();
include_once './fatadgestaoControler.php';
include_once './config.php';
$fg=new fatadgestaoControler;
$idNucleo = 0;
if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="Visitante"){
        include('./barVisitante.html');    
    }else { 
    echo 'Sessão não iniciada ou privilégio não definido.'; 
    // Redirecionar para a página de login ou mostrar uma mensagem de erro 
    header('Location: login.php'); exit(); 
}
}
?>

<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <br>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card"> 
            <div class="card-header">

            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']); // Limpa a mensagem após exibição
            }
            $pdo = new Config();
            $query = "SELECT cpfUsuario,nomeUsuario,emailUsuario,telZapUsuario FROM tb_usuarios WHERE cpfUsuario = :cpfUsuario";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':cpfUsuario', $usuario, PDO::PARAM_STR);
                $stmt->execute();
                $usuario=$stmt->fetch();
                // var_dump($usuario);

            ?>              
            </div>
            <div class="card-header">
                        <h4 style="color: blue;">Cadastro
                        <a href="iniciar.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
            </div>
            <div class="card-body">
                
              <form action="alunosGRUDacoes.php" method="POST">
                <div class="mb-3">
                    <br>
                    <p style="color: red;"><label>Informações Necessárias (obrigatórias)</label></p>
                </div>

                <div class="mb-3">
                  <label>Nome:</label>
                  <input type="text" name="nomeAluno" value="<?= isset($usuario['nomeUsuario']) ? htmlspecialchars($usuario['nomeUsuario']) : '' ?>" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label>CPF:</label>
                    <?php $cpfLimpo = isset($usuario['cpfUsuario']) ? preg_replace('/[^0-9]/', '', $usuario['cpfUsuario']) : ''; ?>
                    <input type="number" name="cpfAluno" 
                    value="<?= htmlspecialchars($cpfLimpo) ?>" class="form-control" readonly>                            
                </div> 
                <div class="mb-3">
                    <label>E-mail:</label>
                    <input type="email" name="emailAluno" value="<?= isset($usuario['emailUsuario']) ? htmlspecialchars($usuario['emailUsuario']) : '' ?>" class="form-control" readonly>
                </div>  
                <div class="mb-3">
                    <label>Telefone:</label>
                    <input type="tel"    name="telZap" value="<?= isset($usuario['telZapUsuario']) ? htmlspecialchars($usuario['telZapUsuario']) : '' ?>" class="form-control" readonly>
                </div> 
                <div class="mb-3">
                    <label>Data de Nascimento:</label>
                    <input type="date" required="" placeholder="dd/mm/AAAA" name="dtNasc" id="dtNasc" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Escolaridade:
                    <label>
                    </label>
                        <input type="radio" required="" name="escolaridade" value="Fundamental"> Fundamental
                    </label>
                    <label>
                        <input type="radio" required="" name="escolaridade" value="Médio"> Médio
                    </label>
                    <label>
                        <input type="radio" required="" name="escolaridade" value="Superior"> Superior
                    </label>
                </div>

                <div class="mb-3">
                    <br>
                    <p style="color: red;"><label>Informações complementares (poderão ser preenchidas posteriormente)</label></p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Instituição onde estudou:</label>
                    <input type="text" name="instEnsino" class="form-control" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Data Início:</label>
                    <input type="date" placeholder="dd/mm/AAAA" name="dtIniEstudo" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Data Conclusão:</label>
                    <input type="date" placeholder="dd/mm/AAAA" name="dtTerEstudo" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Identidade:</label>
                    <input type="text" placeholder="Acrescente o expedidor" name="idtAluno" id="idtAluno" class="form-control">
                </div>   
 
                <div class="mb-3">
                    <label>Natural de:</label>
                    <input type="text" placeholder="Insira Cidade-UF"  name="cidadeNatal" id="cidadeNatal" class="form-control">
                </div>
                  
                <div class="mb-3">
                    <label>Nome Pai:</label>
                    <input type="text"  placeholder="Nome do pai completo" name="nomePai" id="nomePaiAluno" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Nome Mãe:</label>
                    <input type="text"  placeholder="Nome da mãe completo" name="nomeMae" id="nomeMaeAluno" class="form-control">
                </div> 
                <div class="mb-3">
                    <label>Estado Civil:</label>
                    <label>
                    <input type="radio"  name="estadoCivil" value="Casado(a)"> Casado(a)
                    </label>
                    <label>
                    <input type="radio"  name="estadoCivil" value="Solteiro(a)"> Solteiro(a)
                    </label><label>
                    <input type="radio"  name="estadoCivil" value="Viúvo(a)"> Viúvo(a)
                    </label><label>
                    <input type="radio"  name="estadoCivil" value="Divorciado(a)"> Divorciado(a)
                    </label><label>
                    <input type="radio"  name="estadoCivil" value="União Estável"> União Estável
                    </label>
                </div> 
                <div class="mb-3">
                    <label>Nome do Cônjuge:</label>
                    <input type="text"  placeholder="Deixe em branco, se for o caso" name="nomeConjuge" class="form-control">
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
                    <p style="color: blue;"><label>Informações Sobre Experiência Cristã</label></p>
                </div>
                <div class="mb-3">
                    <label class="form-label">Igreja onde é membro:</label>
                    <input type="text" name="instIgreja" class="form-control" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Endereço da Igreja:</label>
                    <input type="text" name="endIgreja" class="form-control" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nome do Pastor:</label>
                    <input type="text" name="nomePastor" class="form-control" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Seu Cargo ou Função na Igreja:</label>
                    <input type="text" name="cargoFuncao" class="form-control" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Observações Julgadas Necessárias:</label>
                    <textarea rows="4" style="width: 100%;" placeholder="Tamanho máximo de 400 caracteres" name="obs" class="form-control" maxlength="400" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                  <button type="submit" name="cadastro_aluno" class="btn btn-primary">Salvar</button>
                </div>
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

    </script>
    <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="js/cep.js"></script>
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