<?php
session_start();
include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;
$idNucleo = 0;
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
    <title>Cadastrar Aluno</title>
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
            ?>              
            <h4 style="color: blue;">Cadastrar Aluno </h4>
            </div>
            <div class="card-body">
              <form action="alunosGRUDacoes.php" method="POST">
                <?php 
                if($privilegio=="opFatad" OR $privilegio=="admFatad"){ 
                ?>
                    <div class="mb-3">
                        <label>Incluir para o Núcleo:</label>
                        <select name="nucleo" class="form-control">
                        <?php

                        $nucleo = $fg->findNucleo($_SESSION['usuario']);

                        if ($nucleo > 0) { // Se não houver valor válido, preencher com a função findNucleo()
                            $todosNucleos = $fg->findNucleo();
                            foreach ($todosNucleos as $nucleoItem) {
                                echo "<option value='{$nucleoItem->idNucleo}'>{$nucleoItem->descNucleo}</option>";
                            }
                        }
                        ?>
                        </select>
                    </div>
                <?php
                }
                
                ?>
                <div class="mb-3">
                  <label>Nome:</label>
                  <input type="text" name="nomeAluno" class="form-control">
                </div>
                <div class="mb-3">
                    <label>CPF:</label>
                    <input type="number" required="" placeholder="Apenas números" name="cpfAluno" id="cpfAluno" class="form-control">
                </div> 
                <div class="mb-3">
                    <label>Identidade:</label>
                    <input type="text" required="" placeholder="Acrescente o expedidor" name="idtAluno" id="idtAluno" class="form-control">
                </div>   
 
                <div class="mb-3">
                    <label>Natural de:</label>
                    <input type="text" placeholder="Insira Cidade-UF" required="" name="cidadeNatal" id="cidadeNatal" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Data de Nascimento:</label>
                    <input type="date" required="" placeholder="dd/mm/AAAA" name="dtNasc" id="dtNasc" class="form-control">
                </div>  
                <div class="mb-3">
                    <label>Nome Pai:</label>
                    <input type="text" required="" placeholder="Nome do pai completo" name="nomePai" id="nomePaiAluno" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Nome Mãe:</label>
                    <input type="text" required="" placeholder="Nome da mãe completo" name="nomeMae" id="nomeMaeAluno" class="form-control">
                </div> 

                <div class="mb-3">
                    <label>Estado Civil:</label>&nbsp;&nbsp;&nbsp;
                    <label>
                    <input type="radio" required="" name="estadoCivil" value="Casado(a)"> Casado(a)&nbsp;&nbsp;&nbsp;
                    </label>
                    <label>
                    <input type="radio" required="" name="estadoCivil" value="Solteiro(a)"> Solteiro(a)&nbsp;&nbsp;&nbsp;
                    </label><label>
                    <input type="radio" required="" name="estadoCivil" value="Viúvo(a)"> Viúvo(a)&nbsp;&nbsp;&nbsp;
                    </label><label>
                    <input type="radio" required="" name="estadoCivil" value="Divorciado(a)"> Divorciado(a)&nbsp;&nbsp;&nbsp;
                    </label><label>
                    <input type="radio" required="" name="estadoCivil" value="União Estável"> União Estável
                    </label>
                </div> 
                <div class="mb-3">
                    <label>Nome do Cônjuge:</label>
                    <input type="text"  placeholder="Deixe em branco, se for o caso" name="nomeConjuge" class="form-control">
                </div>
                <div class="mb-3">
                    <label>E-mail:</label>
                    <input type="email" required="" placeholder="Melhor e-mail" name="emailAluno" id="emailAluno" class="form-control">
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
                <!--informações acadêmicas -->
                <div class="mb-3">
                    <p style="color: blue;"><label>Informações Sobre Escolaridade</label></p>
                </div>
                <div class="mb-3">
                    <label>Escolaridade:&nbsp;&nbsp;&nbsp;
                    <label>
                    </label>
                        <input type="radio" name="escolaridade" value="Fundamental"> Fundamental&nbsp;&nbsp;&nbsp;
                    </label>
                    <label>
                        <input type="radio" name="escolaridade" value="Médio"> Médio&nbsp;&nbsp;&nbsp;
                    </label>
                    <label>
                        <input type="radio" name="escolaridade" value="Superior"> Superior
                    </label>
                </div>

                <!--Experiência cristã -->

                <div class="mb-3">
                    <label class="form-label">Instituição:</label>
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
  </body>
</html>