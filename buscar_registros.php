<?php
include_once("./config.php");
$pdo=new config;

$filtro = filter_input(INPUT_POST, 'filtro', FILTER_SANITIZE_SPECIAL_CHARS);
$pagina = filter_input(INPUT_POST, 'pagina', FILTER_VALIDATE_INT) ?: 1; // Página padrão é 1

// Define o número de registros por página
$limite = 20;
$offset = ($pagina - 1) * $limite;

// Conta o número total de registros para a paginação, aplicando o filtro
$sqlCount = "SELECT COUNT(*) as total
             FROM tb_downdocs AS d
             JOIN tb_cursos AS c ON d.idCurso = c.idCurso
             JOIN tb_disciplinas AS di ON d.idDisciplina = di.idDisciplina
             WHERE di.nomeDisciplina LIKE :filtro
             OR d.nomeArq LIKE :filtro
             OR di.codigoDisciplina LIKE :filtro
             OR c.nivelCurso LIKE :filtro";

$stmtCount = $pdo->prepare($sqlCount);
$filtroCount = '%' . $filtro . '%';
$stmtCount->bindParam(':filtro', $filtroCount, PDO::PARAM_STR);
$stmtCount->execute();
$totalRegistros = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];
$totalPaginas = ceil($totalRegistros / $limite);

// Prepara a consulta SQL para buscar os registros, aplicando o filtro e a paginação
$sql = "SELECT d.*, c.*, di.*
        FROM tb_downdocs AS d
        JOIN tb_cursos AS c ON d.idCurso = c.idCurso
        JOIN tb_disciplinas AS di ON d.idDisciplina = di.idDisciplina
        WHERE di.nomeDisciplina LIKE :filtro
        OR d.nomeArq LIKE :filtro
        OR di.codigoDisciplina LIKE :filtro
        OR c.nivelCurso LIKE :filtro
        ORDER BY c.idCurso, di.idDisciplina
        LIMIT :limite OFFSET :offset";

$stmt = $pdo->prepare($sql);
$filtroData = '%' . $filtro . '%';
$stmt->bindParam(':filtro', $filtroData, PDO::PARAM_STR);
$stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gera as linhas da tabela dinamicamente
$tabela = "";
if ($resultados) {
    foreach ($resultados as $row) {
        $tabela .= "<tr>";
        $tabela .= "<td>{$row['idDowndocs']}</td>";
        $tabela .= "<td>{$row['codigoDisciplina']}</td>";
        $tabela .= "<td>{$row['nivelCurso']}</td>";
        $tabela .= "<td>{$row['nomeDisciplina']}</td>";
        $tabela .= "<td>{$row['nomeArq']}</td>";
        $tabela .= "<td>
                        <a href='downloadLivro.php?idDowndocs={$row['idDowndocs']}' target='_blank' class='btn btn-secondary btn-sm'>
                            <span class='bi-eye-fill'></span> Visualizar
                        </a>
                        <a href='importar_arquivos_excluir.php
                        ?idDowndocs={$row['idDowndocs']}
                        &idCurso={$row['idCurso']}
                        &idDisciplina={$row['idDisciplina']} 
                        &nomeArq={$row['nomeArq']}'
                        class='btn btn-warning btn-sm'>
                            <span class='bi bi-trash'></span> Excluir
                        </a>
                    </td>";
        $tabela .= "</tr>";
    }
} else {
    $tabela .= "<tr><td colspan='5'>Nenhum registro encontrado.</td></tr>";
}

// Gera a navegação de paginação
$paginacao = "";
for ($i = 1; $i <= $totalPaginas; $i++) {
    $paginacao .= "<li class='page-item'><a class='page-link' href='#' data-pagina='$i'>$i</a></li>";
}

// Retorna a tabela e a paginação em formato JSON
echo json_encode(['tabela' => $tabela, 'paginacao' => $paginacao]);
?>
