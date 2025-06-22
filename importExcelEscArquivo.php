<?php
session_start(); // Iniciar a sessão
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Excel csv e salvar no BD</title>
</head>
<body>
<?php include('./index.html'); ?>
<br><br>
    <div class="container mt-4">
      <?php include('mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
          <div class="card-header">
                <tr>
                    <td><h5 style="color: blue">Importar as notas de uma Planilha           
                       <a href="pesquisarAlunoCurso.php" class="btn btn-danger btn-sm float-end">Voltar</a> </h5>                  
                    </td>
                  </tr>
             </div>

            <?php
            // Apresentar a mensagem de erro ou sucesso
            if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <div class="card-body">
              <div class="mb-3">
                <!-- Formulario para enviar arquivo .csv -->
                <form method="POST" action="importExcelProcessar.php" enctype="multipart/form-data">
                    <div class="mb-3">
                         <label>Informe data realização da prova: </label>
                         <input type="date" required="" name="dtProva">
                    </div>
                    <label>Arquivo: </label>
                    <input type="file" required="" name="arquivo" id="arquivo" accept="text/csv"><br><br>
                    <div class="mb-3">
                        <input type="submit" class="btn btn-primary btn-sm" value="Processar"><br>
                    </div>
                </form>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>       
</body>
</html>