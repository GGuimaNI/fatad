<?php
session_start();
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$nomeAluno="";
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
        <link rel="stylesheet" href="./css./styles.css">
    </head>
    
    


    
    <body>
        <nav>
            <div class="logo">
                <h1>                
                    <a><img src="./imagens/LogoFatadSF.png" width="30px" height="alt"></a>              
                    Matricular Aluno
                </h1>
            </div>
            <button onclick="abrirMenu()">&#9776;</button>
            <ul id="botoesMenu">
                <a href="./htmlAlunos.html">
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
            <form class="matricularAluno" method="post" action="matricularAluno.php" >
                    <label>Aluno:</label>
                    <div id="collapse5" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">                                               
                                <select   required="Clique para escolher aluno" name="idAluno" id="idAluno" class="form-control">
                                    <option value="">Escolha Aluno</option>
                                    <?php $rsAlunos = $fg->findAluno();
                                    foreach ($rsAlunos as $row) {
                                        ?>
                                        <option value="<?= $row->idAluno ?>"><?= $row->nomeAluno ?></option>                                                        
                                     <?php 
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <!-- Fim-->
                
                
                
                <label>Cursos:</label>
                <div id="collapse5" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="form-group">                                               
                            <select   required="" name="idCurso" id="idCurso" class="form-control">
                                <option value="">Escolha Curso</option>
                                <?php $rsCursos = $fg->findCursosNivel();
                                foreach ($rsCursos as $row) {
                                    ?>
                                    <option value="<?= $row->idCurso ?>"><?= $row->cursoNivel ?></option>                                                        
                                <?php 
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Fim-->
                
                <label>Data Matrícula:</label>
                <input type="date" required="Escreva ou selecione" placeholder="dd/mm/AAAA" name="dtMatricula" id="dtMatricula" />
               
                <label>Número Matrícula:</label>
                <input type="text" required="Informe número matrícula" placeholder="Escreva o número da matrícula" name="nrMatricula" id="nrMatricula" />
                
                <label>Locais Disponíveis:</label>
                <select multiple size="10" name="idTurma" id="idTurma" required>    
                    <option value="">Escolha um local</option>
                </select>
                <!-- Fim-->
                    <br>
                                
                
                <input type="submit" name="matricularAluno" id="matricularAluno" value="Matricular"/>
                <?php
                if(isset($_POST['idTurma'])){  
                    $idAluno=$_POST['idAluno'];
                    $idCurso=$_POST['idCurso'];
                    
                    $idTurma=$_POST['idTurma'];
                    $dtIniEstudo=$_POST['dtMatricula'];
                    $nrMatricula=$_POST['nrMatricula'];
                    $situacao="Matriculado";
                    
                    $rsCurriculo = $fg->findCurriculo($idCurso);
                    $rsNucleo = $fg->findNucleoEspecifico($idTurma);
                    $rsAluno = $fg->findAlunoEspecifico($idAluno);
                    
                     foreach ($rsAluno as $row) {
                        $nomeAluno=$row->nomeAluno;
                    }

                    foreach ($rsNucleo as $row) {
                        $idNucleo=$row->idNucleo;
                    }
                    
                    $sqlMatr= "INSERT INTO tb_matricula(nrMatricula,idAluno,idTurma,dtMatricula) " 
                                ."VALUES ('$nrMatricula','$idAluno','$idTurma','$dtIniEstudo')";
//                        var_dump($sqlMatr);
                    $resMatr= mysqli_query($conn, $sqlMatr);
                    if(!$resMatr){
                        echo 'Falha na matrícula.';
                    }else{
                        foreach ($rsCurriculo as $row) {
                            $idDisciplina=$row->idd;         
                            $sql = "INSERT INTO tb_historico_aluno(idAluno,idCurso,idNucleo,idTurma,dtIniEstudo,idDisciplina,situacao)"
                                                       . "VALUES ('$idAluno','$idCurso','$idNucleo','$idTurma','$dtIniEstudo','$idDisciplina','$situacao')";                                         
                            $result= mysqli_query($conn, $sql);
                            if(!$result){
                                echo 'Histórico de ".substr($nomeAluno,0,10)." teve problema. Verifique';  
                            }   
                        }
                        echo substr($nomeAluno,0,10).'... foi matriculado';
                    }
                }
                
                ?>
            </form>
        </div>

             <style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
        }
        .matricularAluno{    
            max-width: 250px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
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
     
        
    <script src="js/jquery356min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="js/alunoMatricula.js"></script>

    <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
        integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </body>   
</html>
