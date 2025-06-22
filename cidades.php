<?php
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
?>
    <?php 
        $servername = "localhost";
        $username = "root";
        $dbname= "fatadgestao";
        $password="";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if($conn->connect_error){
            die("Connection failed:".$conn->connect_error);
        }
    ?>
    <head>
        <!--<title>Menu Responsivo</title>-->
        <title>FATAD Gestão</title>
        <link rel="stylesheet" href="./styles.css">
    </head>
    
    
        <style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
        }
        .cidades{    
            max-width: 300px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 25px;
            box-shadow: 0px 5px rgba(0,0,0,0.1);
        }
        legend{
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        label{
            display: block;
            margin-bottom: 3px;
            font-weight: normal;
        }
        input[type='text'],
        input[type='password'],
            input[type='email']{
            width: 100%;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            margin-bottom: 3px;
            font-size: 12px;
        }
        input[type='submit']{
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 5px 15px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        input[type='submit']:hover{
            background-color: #3c8c41;
        }
    </style>
    <script>
        const arrayHeight = document.getElementsByClassName('height-nav');
        const navHeight = document.getElementsByTagName('nav')[0].clientHeight;

        for (let navHeightObj of arrayHeight)
            navHeightObj.style.height = navHeight + 'px';

        function abrirMenu() {
            const botoesMenu = document.getElementById('botoesMenu');

            botoesMenu.className = botoesMenu.className.includes('responsivo') ? '' : 'responsivo'
        }
    </script>

    
    <body>
        <nav>
            <div class="logo">
                <h1>                
                    <a><img src="./imagens/LogoFatadSF.png" width="30px" height="alt"></a>              
                    FATAD Gestao
                </h1>
            </div>
            <button onclick="abrirMenu()">&#9776;</button>
            <ul id="botoesMenu">
                </a>
                <a href="./index.html">
                    <li>
                        <p>Menu</p>
                    </li>
                </a>
                <a href="./logout.php">
                    <li>
                        <p>Sair</p>
                    </li>
                </a>
            </ul>
        </nav>
        <div class="content" id="perfil" style="background-color: transparent"> 
            <div class="height-nav"></div>
            <br><br><br><br><br>
            <form class="cidades" method="post" action="cidades.php" >
                <label>Nome:</label>
                <input type="text" placeholder="Nome da cidade" required="" name="nomeCidade" id="nomeCidade"/>
                <br>
                <div id="collapse5" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">                                               
                                <select   required="" name="cidade" class="form-control">
                                    <option value="">Cidade Natal</option>
                                    <?php $rs = $fg->findCidadeUF();
                                    foreach ($rs as $row) {
                                        ?>
                                        <option value="<?= $row->idCidade ?>"><?= $row->cidadeUF  ?></option>                                                        
                                        
                                            <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                
                <?php
                if(isset($_POST['nomeCurso'])){
                    $nomeCurso=$_POST['nomeCurso'];
                    $nivelCurso=$_POST['nivelCurso'];
                    $sql = "INSERT INTO tb_cursos (nomeCurso,nivelCurso) "
                    ."VALUES ('$nomeCurso','$nivelCurso')";
//                    var_dump($sql);
                    $result= mysqli_query($conn, $sql);

                    if($result){
                        echo 'Curso foi cadastrado';
                    }else{
                        echo 'Não funcionou o cadastro';
                    }
                }
                 ?>
            </form>
        </div>
    </body>   
</html>

