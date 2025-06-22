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
        <li><a href="iniciar.php">Home</a></li> 
        <li>
            <a href="#">Vida Escolar</a> 
            <ul>
            <li>
                <a href="matEscolarDistribuirIndiv.php">Material Estudo</a></li> 
                <li><a href="al_aluno_nota.php">Desempenho</a></li> 
                <li><a href="opFinanceiraGestaoAluno.php">Mov Financeiro</a></li> 
            </ul>
        </li>
        <li>
            <a href="#">Meus dados</a> 
            <ul>
            <li><a href="al_dados_gerais.php">Dados Gerais</a></li> 
            <li><a href="al_dados_complementares.php">Complementares</a></li> 
            <li><a href="al_troca_senha.php">Trocar Senha</a></li> 

            </ul>
        </li>
        
        <li><a href="logout.php">Sair</a></li> 
    </ul>
</nav>

</body>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.menu-lateral {
    width: 100%;
    background-color: #800000;
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
    background-color:#800000;
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

</html>