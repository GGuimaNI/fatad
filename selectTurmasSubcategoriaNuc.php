<?php
session_start(); 
include("includes/conn.php");

// Verificar se o valor de $_SESSION['usuario'] está correto no início 
if (isset($_SESSION['privilegio'])) { 
    $categoria=$_SESSION['usuario']; 
}else{
    exit();
}

$idCurso = $_GET['idCurso'];
$selected = isset($_GET['selected']) ? $_GET['selected'] : null;
$query = $conn->prepare("SELECT DISTINCT t.idTurma, t.nomeSala, CONCAT(n.descNucleo, ' (Sala: ', c.nomeCurso, ')') AS nomeNucleo, c.nivelCurso "
    ."FROM tb_turma AS t "
    ."JOIN tb_nucleofatad AS n ON t.idNucleo = n.idNucleo "
    ."JOIN tb_cursos AS c ON t.idCursoCurriculo = c.idCurso "
    ."WHERE t.ativo=0 and t.idCursoCurriculo=".$idCurso." and  n.cpfResp=:categoria_id "
    ."ORDER BY nomeNucleo");

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
    <option value="<?php echo $option['idTurma']?>" <?php echo $check; ?>><?php echo $option['nomeNucleo']?></option>
<?php
}

