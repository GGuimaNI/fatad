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

if ($privilegio == "opNuc") {
    $nucleo = $fg->findNucleoCpf($usuario);
    foreach ($nucleo as $row) {
        $idNucleo = $row->idNucleo;
        break;
    }
    $sql = "SELECT * FROM tb_aluno WHERE idCadastro=$idNucleo AND nomeAluno LIKE '%$filtro%'";
} else {
    $sql = "SELECT * FROM tb_aluno WHERE nomeAluno LIKE '%$filtro%'";
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
        $tabela .= "<td>{$aluno['idAluno']}</td>";
        $tabela .= "<td>{$aluno['nomeAluno']}</td>";
        $tabela .= "<td>{$aluno['cpfAluno']}</td>";
        $tabela .= "<td>" . date('d/m/Y', strtotime($aluno['dtNascAluno'])) . "</td>";
        $tabela .= "<td>
                        <a href='alunoGRUDview.php?idAluno={$aluno['idAluno']}' class='btn btn-secondary btn-sm'><span class='bi-eye-fill'></span>Visualizar</a>
                        <a href='alunoGRUDedit.php?idAluno={$aluno['idAluno']}' class='btn btn-success btn-sm'><span class='bi-pencil-fill'></span>Editar</a>
                        <a href='alunoGRUDlistDoc.php?idAluno={$aluno['idAluno']}' class='btn btn-warning btn-sm'><span class='bi bi-archive'></span>Documentos</a>
                    </td>";
        $tabela .= "</tr>";
    }
} else {
    $tabela = "<tr><td colspan='5'>Nenhum aluno encontrado.</td></tr>";
}

echo json_encode(['tabela' => $tabela]);
?>
