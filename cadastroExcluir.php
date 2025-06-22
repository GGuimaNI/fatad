<?php
session_start();
$cpf=$_GET['cpf'];
// var_dump($_GET);
// exit();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Excluir Visitantes</title>
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
                        <h5 style="color: blue;">Marque a(s) opção(ões) que serão incluídas na exclusão </h5>
                    </div>
                    <div class="card-body">
                        <form action="cadastroAcoes.php" method="POST">
                            <div class="mb-3">
                                <fieldset>
                                    <div class="form-check">
                                        <input type="checkbox" name="opcoes[]" value="opcao1" class="form-check-input" id="opcao1">
                                        <label class="form-check-label" for="opcao1">
                                            <strong>Excluir</strong> os dados de usuário. O usuário que preencheu a Ficha de Inscrição, se for excluído, perderá o acesso à aplicação.  Entretanto poderá ser matriculado em qualquer curso.  Se for necessário que ele mantenha o acesso, não marque esta opção.
                                        </label>
                                    </div><br>

                                    <div class="form-check">
                                        <input type="checkbox" name="opcoes[]" value="opcao2" class="form-check-input" id="opcao2">
                                        <label class="form-check-label" for="opcao2">
                                            <strong>Excluir</strong> os dados de aluno. Se o usuário preencheu a Ficha de Inscrição, mas vai ser coordenador de núcleo, e não pretende ser matriculado em nenhum curso, então ele pode ser excluído. Neste caso, marque esta opção.
                                        </label>
                                    </div><br>

                                    <div class="form-check">
                                        <input type="checkbox" name="opcoes[]" value="opcao3" class="form-check-input" id="opcao3">
                                        <label class="form-check-label" for="opcao3">
                                            <strong>Excluir</strong> os arquivos. Estes arquivos são vinculados ao CPF. Se a exclusão for total, Usuário e Aluno, então os arquivos devem ser marcados para exclusão também, do contrário ficarão inacessíveis pelo sistema. Se a pessoa cadastrada permanecer como aluno, ou como usuário, então não marque esta opção.
                                        </label>
                                    </div><br>
                                </fieldset>
                            </div>
                            <input type="hidden" id="cpf" name="cpf" value="<?= $cpf ? htmlspecialchars($cpf) : '' ?>" class="form-control">
                            <div class="mb-3">
                              <button type="submit" name="excluir3opcoes" class="btn btn-primary btn btn-danger btn-sm">Excluir</button>
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

        <script src="js/cep.js"></script>
        <style>
            .form-check-input {
                border: 2px solid red; /* Destaca a borda do checkbox */
            }
            
            .form-check-label {
                color: blue; /* Altera a cor do texto */
            }
            
            .form-check-label strong {
                color: red; /* Dá uma cor específica à palavra 'Excluir' */
                font-weight: bold;
            }
        </style>
  </body>
</html>