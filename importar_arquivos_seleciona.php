<?php
session_start();
include_once("conexao.php");
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

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
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Arquivos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<br><br>
    <div class="container mt-4">
        <?php 
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']); // Limpa a mensagem após exibição
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: blue;">Carregar Material Didático</h5>
                    </div>
                    <div class="card-body">
                        <form action="importar_arquivos.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="idCurso">Escolha Curso:</label>
                                <select required name="idCurso" id="idCurso" class="form-control">
                                    <option value="">Escolha Curso</option>
                                    <?php
                                    $rsCursos = $fg->findCursosNivel();
                                    foreach ($rsCursos as $row) {
                                        echo "<option value='{$row->idCurso}'>{$row->cursoNivel}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="idDisciplina">Escolha Disciplina:</label>
                                <select required name="idDisciplina" id="idDisciplina" class="form-control">
                                    <option value="">Escolha Disciplina</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="folderInput">Escolha uma pasta:</label>
                                <input type="file" id="folderInput" name="folder[]" webkitdirectory multiple class="form-control">
                            </div>
                            <button type="submit" class="btn btn-success mt-3">Importar Arquivos</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#idCurso').on('change', function() {
                var idCurso = $(this).val();
                if (idCurso) {
                    $.ajax({
                        type: 'POST',
                        url: 'importar_get_disciplinas.php',
                        data: 'idCurso=' + idCurso,
                        success: function(html) {
                            $('#idDisciplina').html(html);
                        }
                    });
                } else {
                    $('#idDisciplina').html('<option value="">Escolha Disciplina</option>');
                }
            });
        });
    </script>
</body>
<style>
    body {
        font-family: Arial, sans-serif;
        background-size: cover;
    }
    .form-group label {
        display: block;
        margin-bottom: 3px;
        font-weight: bold;
    }
    .card {
        margin-top: 10px;
    }
    .btn-success {
        background-color: #4caf50;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }
    .btn-success:hover {
        background-color: #3c8c41;
    }
</style>
</html>


