<?php
include_once './fatadgestaoControler.php';
include_once './alunosModelClass.php';
$fg = new fatadgestaoControler;
$am= new alunosModelClass;
$idCurso="";
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
        .pesquisarAlunoCboList{    
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
            max-width: 300px;
            color: #fff;
            border: none;
            padding: 5px 50px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        input[type='submit']:hover{
            background-color: #3c8c41;
        }
        
        
    </style>
    

    
    <body>
        <br><br>
        <nav>
            <div class="logo">
                <h1>                
                    <a><img src="./imagens/LogoFatadSF.png" width="30px" height="alt"></a>              
                    Escolha um Aluno
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
        <div class="container-fluid" id="perfil" style="background-color: transparent"> 
            <div class="height-nav"></div>
            <br><br><br><br><br>
            
            <form class="pesquisarAlunoCboList" method="GET" action="listarAlunoNota.php" >
                <label>Tecle a primeira letra do nome do aluno:</label> <br>
                <!-- Inicio-->
                    <div id="collapse5" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="form-group">                                               
                                <select autofocus  required="Clique para escolher aluno" multiplus size =10 name="idAluno" id="idAluno" class="form-control">
                                    
                                    <?php $rsCursos = $fg->findAlunoCurso();
                                    foreach ($rsCursos as $row) {
                                    ?>
                                        <option  value="<?= $row->idAluno ?>"><?= $row->nomeAluno.' ('.$row->idCurso.'-'.$row->nomeCurso.')' ?></option>                                                        
                                        
                                    <?php 
                                        $idCurso=$row->idCurso;
                                    } ?>
                                </select>
                                
                            </div>
                        </div>
                    </div>
                <!-- Fim-->
                
                
                <div class="col-md-12">
                    <input type="hidden" id="idcurso" name="idcurso" value="<?php echo $idCurso; ?>">
                    <input type="submit" name="btn" id="apostila" class="btn btn-primary" value="Avaliar Escolhido"> 
                </div>
                <?php 
                if (empty($_GET['idAluno'])){
                    exit();
                } ?>
                
            </form>
        </div>
        
    <script src="js/jquery356min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="js/cursoDisciplina.js"></script>

    <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
        integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        
    </body>   
</html>
