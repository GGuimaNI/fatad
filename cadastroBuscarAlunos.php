<?php
require './conexao.php';
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

session_start();

$privilegio = $_SESSION['privilegio'] ?? null;
$usuario = $_SESSION['usuario'] ?? null;

$filtro = filter_input(INPUT_POST, 'filtro', FILTER_SANITIZE_SPECIAL_CHARS);
if (!$filtro) {
    $filtro = '';
}

if ($privilegio == "opFatad" OR $privilegio=="admFatad") {
    $sql = "SELECT * FROM tb_usuarios  
            WHERE varPrivilegio='Visitante' AND nomeUsuario LIKE '%$filtro%'";
}

$alunos = mysqli_query($conn, $sql);
if (!$alunos) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit();
}

$tabela = "";
if (mysqli_num_rows($alunos) > 0) {
    foreach ($alunos as $aluno) {
        $tabela .= "<tr>";
        $tabela .= "<td>{$aluno['idUsuario']}</td>";
        $tabela .= "<td>{$aluno['nomeUsuario']}</td>";
        $tabela .= "<td>{$aluno['cpfUsuario']}</td>";
        $tabela .= "<td>{$aluno['telZapUsuario']}</td>";

        $tabela .= "<td>
                        <a href='alunoGRUDview.php?idAluno={$aluno['idUsuario']}' class='btn btn-secondary btn-sm'><span class='bi-eye-fill'></span>Editar Usuario</a>
                        <a href='alunoGRUDedit.php?idAluno={$aluno['idUsuario']}' class='btn btn-success btn-sm'><span class='bi-pencil-fill'></span>Editar Aluno</a>
                        <a href='alunoGRUDlistDoc.php?idAluno={$aluno['idUsuario']}' class='btn btn-warning btn-sm'><span class='bi bi-archive'></span>Documentos</a>
                    </td>";
        $tabela .= "</tr>";
    }
} else {
    $tabela = "<tr><td colspan='5'>Nenhum aluno encontrado.</td></tr>";
}

echo json_encode(['tabela' => $tabela]);
?>
