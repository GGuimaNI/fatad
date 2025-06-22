<?php
session_start(); 
//A finalidade desta página é possibilitar a matrícula por Adm Núcleo,
//pela internet.  Esta página é idêntica a matricularGRUDcreate.php.  
//a única diferença e o script "js/alunoMatriculaNuc.js".  Os scripts também
//são iguais, com a diferença que este chama o selectTurmasSubcategoriaNuc.php
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$nomeAluno="";

// Verificar se o valor de $_SESSION['usuario'] está correto no início 
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
              <h5 style="color: blue;">Matricular Aluno (Adm Núcleo) </h5>
            </div>
              
            <div class="card-body">
                <form action="matricularGRUDacoes.php" method="POST">
                    <div class="mb-3">
                        <label>Aluno:</label>
                        <select   required="Clique para escolher aluno" name="idAluno" id="idAluno" class="form-control">
                            <option value="">Escolha Aluno</option>
                            <?php 
                            if($privilegio=="opNuc"){
                              $nucleo=$fg->findNucleoCpf($usuario);
                              foreach($nucleo as $row){
                                  $idNucleo=$row->idNucleo;
                                  break;
                              }
                              $rsAlunos = $fg->findAlunosNucleo($idNucleo);
                            }else{
                              $rsAlunos = $fg->findAluno();
                            }
                            
                            
                            foreach ($rsAlunos as $row) {
                                ?>
                                <option value="<?= $row->idAluno ?>"><?= $row->nomeAluno ?></option>                                                        
                             <?php 
                            } ?>
                        </select>
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
                      <button type= "submit" name="matricula_aluno" class="btn btn-primary">Salvar</button>
                </div>               
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="js/alunoMatriculaNuc.js"></script>
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