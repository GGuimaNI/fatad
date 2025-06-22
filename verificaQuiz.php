<?php
$respostasCorretas = [
  'p1' => 'c', // Brasília
  'p2' => 'c', // 27
  'p3' => 'd', // Júpiter
];

$pontuacao = 0;

foreach ($respostasCorretas as $pergunta => $correta) {
  if (isset($_POST[$pergunta]) && $_POST[$pergunta] === $correta) {
    $pontuacao++;
  }
}

echo "<h3>Você acertou $pontuacao de " . count($respostasCorretas) . " perguntas.</h3>";
?>