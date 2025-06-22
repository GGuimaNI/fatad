<?php
include("includes/conn.php");

$categoria = $_GET['categoria'];
$selected = isset($_GET['selected']) ? $_GET['selected'] : null;

$query = $conn->prepare("SELECT idDisciplina, nomeDisciplina
    FROM tb_disciplinas
    WHERE idCurso=:categoria_id
    ORDER BY nomeDisciplina ASC");

$data = ['categoria_id' => $categoria];
$query->execute($data);

$registros = $query->fetchAll(PDO::FETCH_ASSOC);

echo '<option value="">Selecione uma disciplina</option>';

foreach($registros as $option) {
    $check = '';
    if($selected == $option['id']) {
        $check = 'selected';
    }
?>
    <option value="<?php echo $option['idDisciplina']?>" <?php echo $check; ?>><?php echo $option['nomeDisciplina']?></option>
<?php
}