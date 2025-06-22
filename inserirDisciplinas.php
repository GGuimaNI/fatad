<?php 
session_start(); 
if (isset($_SESSION['privilegio'])) { 
    $privilegio = $_SESSION['privilegio']; 
    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opFatad"){
        include('./index.html');
    }elseif($privilegio=="admFatad"){

    }elseif($privilegio=="admNuc"){

    }else{
        //visitante
        header('Location: iniciarVisitante.php');
    }
} else { 
    echo 'Sessão não iniciada ou privilégio não definido.'; 
    // Redirecionar para a página de login ou mostrar uma mensagem de erro 
    header('Location: login.php'); exit(); 
} 
   // var_dump($_SESSION);
include_once("conexao.php");
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$nivelCurso=0
?>

    <head>
        <!--<title>Menu Responsivo</title>-->
        <title>FATAD</title>
    </head>
    
    
        <style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
        }
        .inserirDisciplinas{    
            max-width: 400px;
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
            text-align: left;
        }

        input[type="text"], select { 
            width: 100%; 
            padding: 5px; 
            margin: 5px 0; 
            display: inline-block; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
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

    
    <body>
        <div class="content" id="perfil" style="background-color: transparent">          
            <form class="inserirDisciplinas" method="post" action="inserirDisciplinas.php" >
                <h4 style="color: blue;">Criando Disciplina para Currículo de Curso</h4>
                <label>Nome:</label>
                <input type="text" placeholder="Nome da disciplina" required="" name="nomeDisciplina" id="nomeDisciplina" autofocus/>
                <br>
                <label>Código da Disciplina:</label>
                <input type="text" placeholder="Utilize letras maiúsculas" required="" name="codigoDisciplina" id="codigoDisciplina"/>
                <br>
                <label>Do Curso de:</label>
                <!-- Inicio-->
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
                                $nivelCurso=$row->nivelCurso;
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
<!--                <input type="text" placeholder="Insira (Básico, Médio ou Avançado)" required="" name="nivelDisciplina" id="nivelDisciplina"/>-->
                <!--<br>-->
                <label>Créditos:</label>
                <input type="text" required="" placeholder="Valor do crédito da disciplina" name="creditoDisciplina" id="creditoDisciplina" />
                <br>
                <label>Carga Horária:</label>
                <input type="text" required="" placeholder="Carga horária da disciplina" name="cargaHorariaDisciplina" id="cargaHorariaDisciplina" />
                <br>
                <label>Valor da Apostila:</label>
                <input type="text" required="" placeholder="Qual o valor da apóstila" name="valorMatDisciplina" id="valorMatDisciplina" />
                <br>
                <input type="submit" name="inserirDisciplinas" id="inserirDisciplinas" value="Enviar"/>
                <?php
                if(isset($_POST['nomeDisciplina'])){
                    $nomeDisciplina=$_POST['nomeDisciplina'];
                    $codigoDisciplina=$_POST['codigoDisciplina'];
                    $idCurso=$_POST['idCurso'];
                    $creditoDisciplina=$_POST['creditoDisciplina'];
                    $cargaHorariaDisciplina=$_POST['cargaHorariaDisciplina'];
//                    $valorMatDisciplina=$_POST['valorMatDisciplina'];
                    //multiplicando por 100 para eliminar a virgula ou ponto, quando recuperar o dado terá que fazer o inverso
                    $valorMatDisciplina=$fg->brl2decimal($_POST['valorMatDisciplina'],2);
                    $sql = "INSERT INTO tb_disciplinas (nomeDisciplina,codigoDisciplina,idCurso,creditoDisciplina,cargaHorariaDisciplina,valorMatDisciplina) "
                    ."VALUES ('$nomeDisciplina','$codigoDisciplina','$idCurso','$creditoDisciplina','$cargaHorariaDisciplina','$valorMatDisciplina')";
//                    var_dump($valorMatDisciplina);
                    $result= mysqli_query($conn, $sql);

                    if($result){
                        echo substr($nomeDisciplina, 20)."... foi incluida";
                    }else{
                        echo 'Não funcionou o cadastro';
                    }
                }
                 ?>
            </form>
        </div>
    </body>   
</html>
