<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Lista de Arquivos</title>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Documentos do Aluno</h2>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nome do Arquivo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $usuario = 'usuario123'; // Substitua pelo identificador real do usuário (por exemplo, $usuario = $_SESSION['usuario'];)
                $dir = "updocs/$usuario/";
                
                if (is_dir($dir)) {
                    if ($dh = opendir($dir)) {
                        while (($file = readdir($dh)) !== false) {
                            if ($file != "." && $file != "..") {
                                echo "<tr>";
                                echo "<td>$file</td>";
                                echo "<td><a href='viewFileAluno.php?file=$file&user=$usuario' class='btn btn-primary btn-sm'>Visualizar</a></td>";
                                echo "</tr>";
                            }
                        }
                        closedir($dh);
                    }
                } else {
                    echo "<tr><td colspan='2'>Nenhum documento encontrado para o usuário.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
