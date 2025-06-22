<?php
session_start();
// var_dump($_SESSION);
// var_dump($_POST);

require './config.php';
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
  $idCurso = filter_input(INPUT_POST, 'idCurso', FILTER_VALIDATE_INT);
  include_once './fatadgestaoControler.php';
  $fg = new fatadgestaoControler;
  $cursoNivel="";
  $cursos=$fg->findCursoEspecifico($idCurso);
  foreach($cursos as $row){
    $cursoNivel=$row->nomeCurso;
    break;
  }

try {
    $pdo = new Config();
    
    $sql = "SELECT c.*, d.*
            FROM tb_curriculo_disciplinar AS cd
            JOIN tb_cursos AS c ON cd.idCursoCurriculo = c.idCurso
            JOIN tb_disciplinas AS d ON cd.idDisciplinaCurriculo = d.idDisciplina
            WHERE c.idCurso = :idCurso ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);



} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
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
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 style="color: blue;">Documentos Vinculados ao Curso: <?=$cursoNivel?></h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>CÓDIGO</th>
                                    <th>DISCIPLINA</th>
                                    <th>NÍVEL</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="conteudoTabela">
                  <?php
                  // Carrega os registros ao abrir a página

                  if ($resultados) {
                    foreach ($resultados as $row) { ?>
                        <tr>
                        <td><?=$row['idDisciplina']?></td>
                        <td><?=$row['codigoDisciplina']?></td>
                        <td><?=$row['nomeDisciplina']?></td>
                        <td><?=$row['nivelCurso']?></td>
                        <td>
                            <a href='mntDocumentosListar.php
                            ?idCurso=<?=$row['idCurso']?>
                            &idDisciplina=<?=$row['idDisciplina']?>
                            &nivelCurso=<?=$row['nivelCurso']?>
                            &nomeDisciplina=<?=$row['nomeDisciplina']?>' 
                            class='btn btn-warning btn-sm'><span class='bi-eye-fill'></span> Documentos</a>
                        </td>
                        </tr>
                    <?php
                    }
                } else {
                    $tabela = "<tr><td colspan='5'>Nenhum curso encontrado.</td></tr>";
                }
                                    
                  ?>
                  
                 
          
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
    
    
</body>
</html>
