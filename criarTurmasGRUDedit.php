<?php
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
require './conexao.php';



?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar Turmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <?php
    include('index.html'); 
    
    if (isset($_GET['idTurma'])) {
        $idTurma = mysqli_real_escape_string($conn, $_GET['idTurma']);
        $idCurso = mysqli_real_escape_string($conn, $_GET['idCurso']);
        
        $sql = "SELECT *, "
            ."(SELECT descNucleo FROM tb_nucleofatad WHERE idNucleo=t.idNucleo) as descNucleo, "
            ."(SELECT nomeCurso FROM tb_cursos WHERE idCurso=t.idCursoCurriculo) as nomeCurso "
            ."FROM tb_turma as t "
            ."WHERE ativo=0 and idturma='$idTurma'";    

        $query = mysqli_query($conn, $sql);
        if (mysqli_num_rows($query) > 0) {
          $rowtur = mysqli_fetch_array($query);
        }
    }
    ?> 
      
     <br> 
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <h5 style="color: blue" >Editar Turma
                <a href="criarTurmasGRUD.php" class="btn btn-danger float-end">Voltar</a>
              </h5>
            </div>
            <div class="card-body">
              <form action="criarTurmasGRUDacoes.php" method="POST">
                  
                <div class="mb-3">
                    <label>Curso:</label>
                    <p class="form-control">
                      <?=$rowtur['nomeCurso'];?>
                    </p>
                </div>  
                <div class="mb-3">
                    <label>Núcleo:</label>
                    <p class="form-control">
                      <?=$rowtur['descNucleo'];?>
                    </p>
                </div>    
                <div class="mb-3">
                    <label>Data de Início:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input type="date" required="" placeholder="dd/mm/AAAA" name="dtIni" id="dtIni" value="<?=date('Y-m-d', strtotime($rowtur['dtInicioCurso']))?>" class="form-control">
                </div>   
 
                <div class="mb-3">
                <lbel>Data de Término:</label>
                    <input type="date" required="" placeholder="dd/mm/AAAA" name="dtTer" id="dtTer" value="<?=date('Y-m-d', strtotime($rowtur['dtInicioCurso']))?>" class="form-control">
                </div>
     
                <input type="hidden" id="idTurma" name="idTurma" value=<?=$idTurma?> />
                <div class="mb-3">
                  <button type="submit" name="update_curso" class="btn btn-primary">Salvar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script> src="js/code.jquery.com_jquery-3.7.0.min.js"</script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="js/cep.js"></script>
  </body>
</html>