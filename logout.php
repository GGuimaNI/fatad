<?php

// Configurações do banco de dados
$host = 'localhost';  // Geralmente localhost em ambientes locais
$username = 'root';  // Nome de usuário do MySQL
$password = 'CruGuaMys1634*';  // Senha do MySQL (ou deixe em branco se não houver senha)
$database = 'fatadgestao';  // Nome do banco de dados

date_default_timezone_set('America/Sao_Paulo');

// Caminho para salvar o backup
$backup_file = 'C:\backupFatad\backup' . $database . '_' . date("Y-m-d_H-i-s") . '.sql';

// Caminho completo para o mysqldump (ajustar conforme seu ambiente)
$mysqldumpPath = '"C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe"';  // Altere conforme seu ambiente

// Comando mysqldump para criar o backup
$command = "$mysqldumpPath --host=$host --user=$username --password=$password $database > $backup_file";

// Executa o comando mysqldump e captura saída e erros
exec($command . ' 2>&1', $output, $result);  // Captura a saída padrão e os erros

// Exibe a saída do comando para ver o que aconteceu
if ($result === 0) {
    echo "Backup criado com sucesso: " . $backup_file;
} else {
    echo "Erro ao criar o backup:\n";
    echo "Comando executado: $command\n";  // Mostra o comando executado
    echo "Saída do comando:\n";
    echo implode("\n", $output);  // Mostra detalhes do erro
}
session_start();
session_destroy();
header('Location:index.php');

?>

