<?php 
session_start(); 
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$inadimplencia=0;
$idAluno=0;
$cpf=0;

//Para evitar que a página regarrege do cache. 
//Esta página recarrega a cada 5 min (linha 60)
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");

 if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    $idSessao=$_SESSION['idSessao'];

    // Adicionar lógica baseada no privilégio do usuário 
    if($privilegio=="Visitante"){
        include('./barVisitante.html');    
    }else{ 
    echo 'Sessão não iniciada ou privilégio não definido.'; 
        // Redirecionar para a página de login ou mostrar uma mensagem de erro 
        header('Location: logout.php'); exit(); 
    } 
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bem-vindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- //para recarregar a página a cada 5 min -->
    <meta http-equiv="refresh" content="300">

    <title>Mensagem Bíblica do Dia</title>
</head>
<body class="home-page"> <!-- Adicionada classe específica da página -->
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']); // Limpa a mensagem após exibição
    } 
    ?>

    <?php $telZap="5561999962550"; ?>
    <div class="Visitante" style="color: red; text-align: center;">
        <h2><?php echo "<p>Bem-vindo usuário " . htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') . "!</p>"; ?> </h2>       
        <p>Para mais informações, se for o caso, ligue para +55 (61) 9 9996-2550 ou clique no botão  
        <button class="whatsapp-button" onclick="window.open('https://api.whatsapp.com/send?phone=<?php echo $telZap; ?>&text=Olá, gostaria de mais informações sobre os cursos FATAD!', '_blank')">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
        </button>
        e faça contatato pelo WhatsApp.
    </p>
    </div>
    <?php  
    $rAluno=$fg->findAlunoCpf($usuario);
    if (!empty($rAluno)) {
        $idAluno = $rAluno[0]->idAluno;
        $cpf=$rAluno[0]->cpfAluno; 
    }
    ?>
    <input type="hidden" name="idAluno" value="<?php echo $idAluno; ?>">    
    <?php 
        echo "<table>";
        //Título da tabela
        echo "<tr><th>Passos necessários para sua inscrição e matrícula.</th><th>Ação</th></tr>";
        //Linha Criar Ficha de Inscrição ou Editar Ficha
        echo "<tr><td>Preencha sua <strong><span style='color: blue;'>Ficha de Inscrição e Matrícula</span></strong> clicando no botão ao lado.</td>";  
        $textoBotao = $fg->findAlunoCpf($usuario) ? "Editar" : "Preencher";
        echo "<td><form action='cadastroAlunoEdit.php' method='get'><input type='submit' value='$textoBotao'></form></td>";
        echo "</tr>";

        //Linha do Imprimir Ficha
        $desabilitado = ($fg->findAlunoCpf($usuario)) ? "" : "disabled";
            echo "<td><strong><span style='color: blue;'>Verifique</span></strong> como está tua Ficha de Inscrição. Por enquanto ela pode ficar assim, mas ela deverá ser completada posteriormente, pois dela sairão os dados para teu Diploma de Conclusão.</td>";  
        echo "<td><form action='cadastroAlunoPDF.php' method='post'>
            <input type='hidden' name='idAluno' value='" . $idAluno . "'>
            <input type='submit' value='Visualizar' $desabilitado  
                " . ($desabilitado ? "style='background-color: #ccc; color: #666; cursor: not-allowed; opacity: 0.6; border: 1px solid #999;'" : "") . "></form></td></tr>";
            echo "</tr>";
        
        //Linha Botão Documentos
        echo "<td>
                Recomendamos, também, conforme for o caso, o envio de copia dos doscumentos abaixo:<br>
                <strong><span style='color: blue;'>-Curso Avançado</span></strong>: CPF, RG, Comprovante Residência, Histórico Escolar, Cert Conclusão 2ºGrau.<br>
                <strong><span style='color: blue;'>-Curso Médio</span></strong>: CPF, RG, Comprovante Residência.<br>
                <strong><span style='color: blue;'>-Curso Básico</span></strong>: CPF, RG, Comprovante Residência.
                </td>";
                        
        // botão
                    echo "<td><form action='cadastroListDoc.php?' method='post'>
                    <input type='hidden' name='idAluno' value='" . $idAluno . "'>
                    <input type='hidden' name='cpf' value='" . $cpf . "'>

                    <input type='submit' value='Enviar Documentos' $desabilitado  
                        " . ($desabilitado ? "style='background-color: #ccc; color: #666; cursor: not-allowed; opacity: 0.6; border: 1px solid #999;'" : "") . ">
                    </form></td>";
      
        echo "</tr>";
        echo "</table>";
        
    ?>
    



    <script>
        const verses = [
            'john 3:16', 'psalm 23:1', 'philippians 4:13', 'genesis 1:1','psalm 119:105',
            'proverbs 3:5', 'romans 8:28','psalm 91:1', 'proverbs 24:10','joshua 1:9', 
            'matthew 6:33', 'philippians 4:5', 'romans 12:2','john 11:40',
            'proverbs 23:18','proverbs 23:9','psalm 46:1','philippians 4:6'
        ];

        function fetchRandomBiblicalMessage() {
            const randomVerse = verses[Math.floor(Math.random() * verses.length)];

            fetch(`https://bible-api.com/${randomVerse}?translation=almeida`)
                .then(response => response.json())
                .then(data => {
                    const messageContainer = document.getElementById('biblicalMessage');
                    const message = data.text + " (" + data.reference + ")";
                    messageContainer.innerHTML = message;
                })
                .catch(error => {
                    console.error("Erro ao buscar a mensagem bíblica:", error);
                    document.getElementById('biblicalMessage').innerHTML = "Erro ao carregar a mensagem. Detalhes: " + error.message;
                });
        }

        window.onload = fetchRandomBiblicalMessage;
    </script>
</body>


<style>
        body {
            font-family: Arial, sans-serif;
            background-size: cover;
            text-align: center;
            padding-top: 80px; /* Adicionado para evitar que o menu sobreponha o conteúdo */
        }
        
        .menu-bar {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            position: fixed !important; /* Fixar a barra de menu no topo */
            top: 0 !important; /* Garantir que a barra de menu fique no topo */
            left: 0 !important;
            background-color: rgba(255, 255, 255, 0.9); /* Adicionar um fundo translúcido, se necessário */
        }
        
        .message {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .bible-image {
            width: 200px; /* Ajuste o tamanho conforme necessário */
            margin: 0 auto 20px auto; /* Centralizar a imagem */
            display: block;
        }
    </style>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            text-align: left;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        th {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-size: 18px;
        }
        td {
            border: 1px solid #dee2e6;
            padding: 10px;
            font-size: 15px;
        }
        input[type='submit'] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 10px;
            width: 130px;
            font-size: 12px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;

        }
        input[type='submit']:hover {
            background-color: #218838;
        }
    </style>
    <style>
        .whatsapp-button {
        display: inline-block;
        background-color: transparent;
        border: none;
        cursor: pointer;
        }

        .whatsapp-button img {
            width:28px; /* Ajuste o tamanho do ícone */
            transition: transform 0.3s ease;
        }

        .whatsapp-button:hover img {
            transform: scale(1.1);
        }
    </style>

</html>
