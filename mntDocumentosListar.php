<?php
session_start();
require './config.php';
$pdo= new config;



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

$idCurso = filter_input(INPUT_GET, 'idCurso', FILTER_VALIDATE_INT); 
$idDisciplina = filter_input(INPUT_GET, 'idDisciplina', FILTER_VALIDATE_INT); 
$nomeDisciplina = filter_input(INPUT_GET, 'nomeDisciplina', FILTER_SANITIZE_SPECIAL_CHARS); 
$nivelCurso = filter_input(INPUT_GET, 'nivelCurso', FILTER_SANITIZE_SPECIAL_CHARS); 
$DisciplinaNivel=$nomeDisciplina." / Nível: ".$nivelCurso;
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
                        <h5 style="color: blue;">Documentos Vinculados à Disciplina: <?= $DisciplinaNivel ?></h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID Doc</th>
                                    <th>Arquivo Físico</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="conteudoTabela">
                            <?php    
                            // Prepara a consulta SQL para buscar os registros, aplicando o filtro e a paginação
                                
                                $sql = "SELECT d.*, c.*, di.*
                                        FROM tb_downdocs AS d
                                        JOIN tb_cursos AS c ON d.idCurso = c.idCurso
                                        JOIN tb_disciplinas AS di ON d.idDisciplina = di.idDisciplina
                                        WHERE d.idDisciplina=:idDisciplina 
                                            AND D.idCurso=:idCurso 
                                        ORDER BY nomeArq ";

                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
                                $stmt->bindParam(':idDisciplina', $idDisciplina, PDO::PARAM_INT);
                                $stmt->execute();
                                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if ($resultados) {
                                    foreach ($resultados as $row) { ?>
                                        <tr>
                                        <td><?=$row['idDowndocs']?></td>
                                        <td><?=$row['nomeArq']?></td>
                                        <td>
                                        <a href="downloadLivro.php?
                                        idDowndocs=<?=$row['idDowndocs']
                                        ?>" target='_blank' class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>  
                                        <a href="mntDoc_arquivos_excluir.php?
                                            idDowndocs=<?=$row['idDowndocs']?>
                                            &idDisciplina=<?=$idDisciplina?>"  
                                            class="btn btn-warning btn-sm"><span class="bi bi-trash"></span>&nbsp;Excluir</a>
                                        </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    $tabela = "<tr><td colspan='5'>Nenhum curso encontrado.</td></tr>";
                                }
                            ?>
                            </tbody>
                        </table>
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
