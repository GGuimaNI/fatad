<?php
session_start(); 

include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$nomeAluno="";

// Verificar se o valor de $_SESSION['usuario'] está correto no início 
if (isset($_SESSION['usuario_autenticado'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opFatad"){
      include('./index.html');
  }elseif($privilegio=="admFatad"){
      include('./index.html');
  }elseif($privilegio=="opNuc"){
       include('./barOpNuc.html');
  }else { 
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
    <title>Matricular</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
              
            <div class="card-header">
              <br>
              <h5 style="color: blue;">Matricular Aluno do Cadastro</h5>
            </div>
              
            <div class="card-body">
                <form action="cadastroMatricularAlunoPro.php" method="POST">
                    
                    <?php 
                        $cpf=$fg->getCpfId();
                        $alunos = $fg->findAlunoCpf($cpf);
                        foreach ($alunos as $aluno) {
                            $nomeAluno= $aluno->nomeAluno;
                            $idAluno=$aluno->idAluno;
                        }
                    ?>
                    <input type="text" hidden name="idAluno" id="idAluno" class="form-control"
                     value="<?= htmlspecialchars($idAluno ?? '') ?>">
                    <div class="mb-3">
                        <label for="nomeAluno">Nome do Aluno:</label>
                        <input type="text" name="nomeAluno" id="nomeAluno" class="form-control"
                             value="<?= htmlspecialchars($nomeAluno ?? '') ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Curso:</label>
                        <select   required="" name="idCurso" id="idCurso" class="form-control">
                            <option value="">Escolha Curso</option>
                            <?php 
                            if($privilegio=="opNuc"){
                              $rsCursos = $fg->findCursosNivelCpf($usuario);
                            }else{
                              $rsCursos = $fg->findCursosNivel();
                            }

                            foreach ($rsCursos as $row) {
                                ?>
                                <option value="<?= $row->idCurso ?>"><?= $row->cursoNivel ?></option>                                                        
                            <?php 
                            } ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label>Núcleo e Sala:</label>
                        <select multiple size="6" name="idTurma" id="idTurma" required class="form-control">   
                            <option value="">Escolha um local</option>
                        </select>
                    </div>
                     
                    <div class="mb-3">
                        <label>Data Matrícula:</label>
                        <input type="date" required="Escreva ou selecione" placeholder="dd/mm/AAAA" name="dtMatricula" id="dtMatricula" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Número Matrícula:</label>
                        <input type="text" required="Informe número matrícula" placeholder="Escreva o número da matrícula" name="nrMatricula" id="nrMatricula" class="form-control">
                    </div>
  
                    <div class="mb-3">
                        <label>Recebe Material Correios:&nbsp;&nbsp;&nbsp;</label>
                        <label>
                            <input type="radio" name="opcao" value="sim" required onclick="toggleCampos(true)"> Sim&nbsp;&nbsp;
                        </label>
                        <label>
                            <input type="radio" name="opcao" value="nao" required onclick="toggleCampos(false)"> Não
                        </label>
                    </div>
                    <div class="mb-3">
                        <label for="valorMaterial">Valor do Material:</label>
                        <input type="text" name="valorMaterial" class="form-control" placeholder="Ex: 99,90" required title="Informe o valor do material.">                    
                    </div>
                    <div class="mb-3">
                        <div id="camposAdicionais" style="display: none;">
                      <div class="mb-3">
                        <label for="encadernacao">Encadernação:</label>
                        <input type="text" name="encadernacao" class="form-control" placeholder="Ex: 49,90" required title="Informe o valor da encadernação.">
                      </div>
                      <div class="mb-3">
                        <label for="frete">Valor do Frete:</label>
                        <input type="text" name="frete" class="form-control" placeholder="Ex: 19,90" required title="Informe o valor do frete.">
                      </div>
                    </div>         
                
                <div class="mb-3">
                      <button type= "submit" name="matricula_aluno" class="btn btn-primary">Salvar</button>
                </div>               
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="js/alunoMatricula.js"></script>
    <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="js/cep.js"></script>

        <script>
            function toggleCampos(mostrar) {
                const container = document.getElementById("camposAdicionais");
                container.style.display = mostrar ? "block" : "none";

                const campos = container.querySelectorAll("input");

                campos.forEach(input => {
                    if (mostrar) {
                        input.removeAttribute("disabled");
                        input.setAttribute("required", "required");
                    } else {
                        input.removeAttribute("required");
                        input.setAttribute("disabled", "disabled");
                    }
                });
            }

            window.addEventListener("DOMContentLoaded", () => {
                // Se estiver em modo edição, ativa os campos caso a opção seja "sim"
                const opcaoSelecionada = document.querySelector('input[name="opcao"]:checked');
                toggleCampos(opcaoSelecionada && opcaoSelecionada.value === "sim");

                // Adiciona evento para reagir a cliques futuros
                const radios = document.querySelectorAll('input[name="opcao"]');
                radios.forEach(radio => {
                    radio.addEventListener("change", () => {
                        toggleCampos(radio.value === "sim");
                    });
                });
            });
        </script>

  </body>
</html>