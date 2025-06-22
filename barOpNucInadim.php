<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Iniciar a sessão // Supondo que 'idSessao' esteja definida na sessão 
$idSessao = isset($_SESSION['idSessao']) ? $_SESSION['idSessao'] : 'Valor padrão se não definido'; 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FATAD</title>

    
</head>
<body>
<nav class="menu-lateral">
    <ul class="menu">
        <li class="menu-item logo"> 
            <a href="#"> 
                <img src="./imagens/LogoFatadSF.png" width="35px" alt="Logo"> 
                <span>Centro Educacional Social Evangélico FATAD-ME / <?php echo $idSessao ?></span> 
                
            </a>
        </li>
        <li><a href="iniciar.php">Home</a></li> <!-- Link para home.php -->
        <li>
            <a href="#">Financeiro</a> <!-- Link para services.php -->
            <ul>
                <li><a href="opFinanceiraGestao.php">Mov Financeiro</a></li> <!-- Link para web-design.php -->
            </ul>
        </li>
        
        <li><a href="logout.php">Sair</a></li> <!-- Link para home.php -->
    </ul>
</nav>
<!-- <div class="content"> 
     <br><br>
    <h1>Paz do Senhor!</h1> 
    <div id="biblical-message" class="center"> 
        <img src="./imagens/feProtestante.jpg" alt="Imagem Bíblica" id="biblical-image"> 
        <br><br>
        <p id="biblical-text">"Mas o justo viverá da fé." - Rm 1:17b ARC</p> 
    </div> 
</div -->

</body>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.menu-lateral {
    width: 100%;
    background-color: #dd2b72;
    position: fixed;
    top: 0;
    z-index: 1000;
}

.menu {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: space-between; /* Distribui espaço entre logo e itens do menu */
    align-items: center;
    padding: 8px;
}

.menu-item.logo {
    display: flex;
    align-items: center;
    margin-right: auto;
}

.menu-item.logo a {
    display: flex;
    align-items: center;
}

.menu-item.logo img {
    margin-right: 10px; /* Ajuste o espaçamento conforme necessário */
    display: block;
}

.menu-item.logo span {
    font-size: 16px; /* Ajuste o tamanho da fonte conforme necessário */
    display: block;
}

.menu > li {
    position: relative;
}

.menu > li a {
    display: block;
    color: white;
    text-align: center;
    padding: 6px 10px; /* Ajuste o padding para deixar o menu mais fino */
    text-decoration: none;
}

.menu > li a:hover {
    background-color: #e07373;
}

.menu ul {
    display: none;
    position: absolute;
    top: 100%;
    right: 0; /* Alinha o submenu com a borda direita do item do menu principal */
    background-color: #dd2b72;
    min-width: 160px;
    z-index: 1001;
    flex-direction: column;
    list-style-type: none;
    padding: 0;
}

.menu ul li {
    float: none;
}

.menu ul li a {
    padding: 12px 16px;
}

.menu ul li a:hover {
    background-color: #e07373;
}

.menu li:hover > ul {
    display: flex;
}

.content {
    padding-top: 50px;
    position: relative;
    z-index: 999;
    text-align: center; /* Centraliza o conteúdo */
}

#biblical-message {
    margin-top: 20px;
}

#biblical-image {
    max-width: 100%;
    height: auto;
}

#biblical-text {
    font-size: 1.2em;
    margin-top: 10px;
}

    </style>
<!-- <script>
    function changeMessage() {
    // Exemplo de mensagens e imagens
    const messages = [
        {
            text: '"Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito, para que todo aquele que nele crê não pereça, mas tenha a vida eterna." - João 3:16',
            img: 'https://www.bible.com/images/logo.svg' // Link para a imagem
        },
        {
            text: '"Eu sou o caminho, a verdade e a vida. Ninguém vem ao Pai senão por mim." - João 14:6',
            img: 'https://www.bible.com/images/logo.svg' // Link para a imagem
        }
        // Adicione mais mensagens e imagens conforme necessário
    ];

    // Seleciona uma mensagem aleatória
    const message = messages[Math.floor(Math.random() * messages.length)];

    // Atualiza a mensagem e a imagem
    document.getElementById('biblical-text').innerText = message.text;
    document.getElementById('biblical-image').src = message.img;
}

</script> -->
</html>