<?php
$base_dir = "updocs/";
$usuario = 'usuario123'; // Substitua pelo identificador real do usuário
$target_dir = $base_dir . $usuario . "/";
$uploadOk = 1;

// Cria o diretório do usuário, se não existir
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (isset($_POST["submit"])) {
    foreach ($_FILES["filesToUpload"]["name"] as $key => $name) {
        $target_file = $target_dir . basename($name);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Verifica se o arquivo já existe
        if (file_exists($target_file)) {
            echo "Desculpe, o arquivo " . $name . " já existe.<br>";
            $uploadOk = 0;
        }

        // Verifica o tamanho do arquivo
        if ($_FILES["filesToUpload"]["size"][$key] > 500000) { // 500 KB
            echo "Desculpe, o arquivo " . $name . " é muito grande.<br>";
            $uploadOk = 0;
        }

        // Permite apenas certos formatos de arquivo
        if (!in_array($fileType, ["jpg", "jpeg", "png", "gif", "pdf"])) {
            echo "Desculpe, apenas arquivos JPG, JPEG, PNG, GIF e PDF são permitidos.<br>";
            $uploadOk = 0;
        }

        // Verifica se $uploadOk foi setado como 0 por algum erro
        if ($uploadOk == 0) {
            echo "Desculpe, o arquivo " . $name . " não foi enviado.<br>";
        // Se tudo estiver ok, tenta fazer o upload do arquivo
        } else {
            if (move_uploaded_file($_FILES["filesToUpload"]["tmp_name"][$key], $target_file)) {
                echo "O arquivo " . htmlspecialchars($name) . " foi enviado com sucesso para " . $target_dir . ".<br>";
            } else {
                echo "Desculpe, houve um erro ao enviar o arquivo " . $name . ".<br>";
            }
        }
    }
}
?>
