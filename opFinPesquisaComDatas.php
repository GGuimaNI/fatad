<?php
session_start();
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
    <?php include('./index.html'); ?>
    <div class="container mt-5">
      <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: blue;">Pesquisar Movimentação Financeira
                        <!-- <a href="opFinanceiraGestao.php" class="btn btn-danger float-end">Voltar</a> -->
                        </h5>
                    </div>
                    <div class="card-body">
                        
                        <form action="opFinanceiraGestao.php" method="POST">
                            <div class="mb-3">
                              <label>Data Inicial</label>
                              <input type="date" placeholder ="Data inicial." required="" name="data1" autofocus class="form-control">
                            </div>
                            <div class="mb-3">
                              <label>Data Final</label>
                              <input type="date" placeholder ="Data final, ou deixe em branco." name="data2" class="form-control">
                            </div>
                            
                            <p style="color:red">A Data Final em branco assume data atual.</p>                            
                            <div class="mb-3">
                                  <button type="submit" name="pesquisa" class="btn btn-primary">Pesquisar</button>
                            </div>
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

  </body>
</html>
