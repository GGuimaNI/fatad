<?php
// Conecta ao banco
include("/.config.php");

// Busca 5 perguntas aleatórias
$sql = "SELECT * FROM perguntas ORDER BY ";
$resultado = mysqli_query($conexao, $sql);
?>

<form method="post" action="verificaQuiz.php">
  <?php
  $numero = 1;
  while ($pergunta = mysqli_fetch_assoc($resultado)) {
    echo "<fieldset>";
    echo "<legend><strong>Pergunta {$numero}:</strong> {$pergunta['enunciado']}</legend>";

    foreach (['a','b','c','d','e'] as $letra) {
      $texto = $pergunta["alternativa_$letra"];
      $name = "p{$pergunta['id']}";
      $id = "p{$pergunta['id']}$letra";
      echo "<label><input type='radio' name='$name' value='$letra' id='$id'> $letra) $texto</label><br>";
    }

    echo "</fieldset><hr>";
    $numero++;
  }
  ?>
  <button type="submit">Enviar Respostas</button>
</form>


 