<?php
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
require './conexao.php';
?>
    
    <head>
        <!--<title>Menu Responsivo</title>-->
        <title>FATAD Gestão</title>
        <link rel="stylesheet" href="./css./styles.css">
    </head>
    
    
        <style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
        }
        .criarTurma{    
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
        
        input[type='SELECT']{
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
                    Criando Turmas de Curso
                </h1>
            </div>
            <button onclick="abrirMenu()">&#9776;</button>
            <ul id="botoesMenu">
                <a href="./criarTurmasGRUD.php">
                    <li>
                        <p>Voltar</p>
                    </li>
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
            <form class="criarTurma" method="post" action="criarTurma.php" >
                <label>Curso:</label>
                <!-- Inicio-->
                    <div id="collapse5" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">                                               
                                <select   required="Clique para escolher um curso" name="curso" class="form-control">
                                    <option value="">Escolha Curso</option>
                                    <?php $rsCursos = $fg->findCursoTurma();
                                    foreach ($rsCursos as $row) {
                                        ?>
                                        <option value="<?= $row->idCurso ?>"><?= $row->nomeCurso ?></option>                                                        
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <br>
                <!-- Fim-->
               <?php
//                $carga=0;
//                $credito=0;
//                $valor=0;
//                ?>
                
                <label>Núcleo:</label>
                    <div id="collapse5" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group"> 
                                <select   required="Clique para escolher um núcleo" name="nucleo" class="form-control">
                                    <option value="">Escolha Núcleo</option>
                                    $id=0;
                                    <?php $rsNucleos = $fg->findNucleo();
                                    foreach ($rsNucleos as $row) {
                                    ?>
                                        <option value="<?= $row->idNucleo ?>"><?= $row->descNucleo ?></option>                                                        
<!--                                        $carga=$row->cargaHorariaDisciplina;
                                        $credito=$row->creditoDisciplina; 
                                        $valor=$row->valorMatDisciplina;-->
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <br>

                
<!--                <label>Nome da Sala:</label>
                <input type="text" placeholder="Uma sigla. Ex: TA1 para Teologia Avançada." required="" name="nomeSala" id="nomeSala"/>-->
                <!--<br>-->
                <label>Data de Início:</label>
                <input type="date" required="Escreva ou selecione" placeholder="dd/mm/AAAA" name="dtIni" id="dtIni" />
                <br><br>
                <label>Data de Término:</label>
                <input type="date" required="Escreva ou selecione" placeholder="dd/mm/AAAA" name="dtTer" id="dtTer" />
                <br><br>
                                
                
                <input type="submit" name="cadastrarCurso" id="cadastrarCurso" value="Enviar"/>
                <?php
                if(isset($_POST['curso'])){
                    $curso=$_POST['curso'];
                    $nucleo=$_POST['nucleo'];
//                    $nomeSala=$_POST['nomeSala'];
                    $dtIni=$_POST['dtIni'];
                    $dtTer=$_POST['dtTer'];
                    
                    $nmCurso=$fg->findCursoNucleo($nucleo,$curso);
                    
                    if($nmCurso){
                        echo 'Já existe turma para este Curso no núcleo.';
                        exit;
                    }else{
                        $sql = "INSERT INTO tb_turma(idNucleo, idCursoCurriculo, dtInicioCurso, dtTerminoCurso,ativo)"
                            ."VALUES ('$nucleo','$curso','$dtIni','$dtTer',0)" ;
                        $result= mysqli_query($conn, $sql);
                        if($result){
                            echo 'Turma foi criada com sucesso';
                        }else{
                            echo 'Não funcionou o cadastro da Turma';
                        }
                        // Fecha a conexão
                        mysqli_close($conn);
                    }
                }
                 ?>
            </form>
        </div>
    </body>   
</html>
