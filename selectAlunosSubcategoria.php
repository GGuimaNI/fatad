<?php
include("includes/conn.php");

$categoria = $_GET['idTurma'];
$selected = isset($_GET['selected']) ? $_GET['selected'] : null;

$query = $conn->prepare("SELECT distinct ha.idAluno, ha.idCurso,  ha.idNucleo,ha.idTurma,
            (SELECT  nomeAluno FROM tb_aluno where idAluno=ha.idAluno) as nomeAluno, 
            (SELECT  nomeCurso FROM tb_cursos where idCurso=ha.idCurso) as nomeCurso 
            FROM tb_historico_aluno as ha 
            WHERE idTurma=:categoria_id 
            ORDER BY nomeAluno");

$data = ['categoria_id' => $categoria];
$query->execute($data);

$registros = $query->fetchAll(PDO::FETCH_ASSOC);

echo '<option value="">Tecle a primeira letra</option>';

foreach($registros as $option) {
    $check = '';
    if($selected == $option['id']) {
        $check = 'selected';
    }
?>
    <option value="<?php echo $option['idAluno']?>" <?php echo $check; ?>><?php echo $option['nomeAluno']?></option>
<?php
}

