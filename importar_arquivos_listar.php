<?php
session_start();
require './conexao.php';
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$argumento="";

if (isset($_SESSION['privilegio'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opFatad"){
      include('./index.html');
  }elseif($privilegio=="admFatad"){
        include('./index.html');
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
<body><br><br>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 style="color: blue;">Gerenciar Disciplinas e Documentos Vinculados</h5>
                        <input type="text" id="filtroTabela" class="form-control w-33" placeholder="Filtrar...">
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID Doc</th>
                                    <th>Código</th>
                                    <th>Nível Curso</th>
                                    <th>Disciplina</th>
                                    <th>Doc Físico</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="conteudoTabela">
                            <!-- Paginação será carregada dinamicamente via AJAX 
                                 conforme script abaixo com buscar_registros.php-->
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation">
                            <ul class="pagination" id="paginacaoTabela">
                                <!-- Paginação será carregada dinamicamente via AJAX 
                                 conforme script abaixo com buscar_registros.php-->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div><style>
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
        .custom-file-input {
            display: none;
        }
        .custom-file-label {
            display: inline-block;
            padding: 10px 20px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            font-size: 14px;
        }
        .custom-file-label:hover {
            background-color: #0056b3;
        }
        .w-33 {
            width: 33.3333%;
        }
        .pagination {
            justify-content: center;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            function carregarRegistros(filtro, pagina) {
                $.ajax({
                    type: 'POST',
                    url: 'buscar_registros.php',
                    data: { filtro: filtro, pagina: pagina },
                    success: function(data) {
                        var result = JSON.parse(data);
                        $('#conteudoTabela').html(result.tabela);
                        $('#paginacaoTabela').html(result.paginacao);
                    }
                });
            }

            // Carregar todos os registros ao carregar a página
            carregarRegistros('', 1);

            $('#filtroTabela').on('input', function() {
                var filtro = $(this).val();
                carregarRegistros(filtro, 1);
            });

            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                var pagina = $(this).data('pagina');
                var filtro = $('#filtroTabela').val();
                carregarRegistros(filtro, pagina);
            });
        });
    </script>
</body>
</html>
