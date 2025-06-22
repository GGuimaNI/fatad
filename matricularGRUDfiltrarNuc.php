<?php
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$nomeAluno="";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Filtrado</title>
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
              <h5 style="color: blue;">Matriculados Por Núcleo
                <a href="matricularGRUD.php" class="btn btn-danger float-end">Voltar</a>
              </h4>
            </div>
              
            <div class="card-body">
                <form action="" method="POST">
                    
                         
                    <div class="mb-3">
                        <label>Núcleo:</label>
                        <select   required="" name="idNucleo" id="idNucleo" class="form-control">
                            <option value="">Escolha o Núcleo</option>
                            <?php $rsCursos = $fg->findNucleo();
                            foreach ($rsCursos as $row) {
                                ?>
                                <option value="<?= $row->idNucleo ?>"><?= $row->descNucleo ?></option>                                                        
                            <?php 
                            } ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label>Lista de Alunos Matriculados:</label>
                        <select multiple size="10" name="idAluno" id="idAluno" required class="form-control">   
                            <option value=""></option>
                        </select>
                    </div>
                    
                    <div class="mb-3"> 
                      <label class="inline-label">Número de registros: </label> 
                      <span id="total_linhas" class="inline-value">0</span> 
                    </div>
     
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
      
    <script>
        document.getElementById('idNucleo').addEventListener('change', function() {
            var idNucleo = this.value;

            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState == XMLHttpRequest.DONE) {
                    if (xhr.status == 200) {
                        document.getElementById('total_linhas').textContent = xhr.responseText;
                    }
                }
            };

            xhr.open('GET', 'consultar_total_linhas.php?idNucleo=' + idNucleo, true);
            xhr.send();
        });

    </script>

    
    <script src="js/alunoMatNuc.js"></script>
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