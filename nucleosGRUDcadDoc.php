<?php

// Incluir a conexão com o banco de dados
include_once './config.php';
$pdo = new Config();
include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;
?>
<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
<body>

    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $nucleo_id = $_GET['idNucleo'];

    $nucleo=$fg->findDescNucleoEspecifico($nucleo_id);
    foreach($nucleo as $row){
      $nomeNucleo=$row['descNucleo'];
    }
    ?>

    <!-- Formulário para cadastrar múltiplos arquivos blob no banco de dados -->
    <?php include('index.html'); ?>
    <br><br>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h5 style="color: blue;">Documentos <?= $nomeNucleo; ?>
                <a href="nucleosGRUDlistDoc.php?idNucleo=<?=$nucleo_id?>" class="btn btn-danger float-end">Voltar</a>
              </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="nucleosGRUDacoes.php" enctype="multipart/form-data">

                    <input type="hidden" name="idNucleo" value="<?=$nucleo_id?>" ><br><br>
                    <div class="mb-3">
                        <label>Arquivo PDF, JPG, e JPEG: </label>
                        <input type="file"  required=""           name="files[]"     id="files" multiple accept=".pdf,.jpg,.jpeg,.bmp">

                      </div>
                    <input type="submit" name="CadArquivo" value="Enviar"><br><br>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
      
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
            

