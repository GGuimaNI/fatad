<?php
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
include("includes/conn.php");
$nmCurso="";
?>

<!<!DOCTYPE html>
    <html lang="pt-br">
    <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <title>FATADGestão</title>
        <!-- <link rel="stylesheet" href="./css/styles.css"> -->
    </head>

        <style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
            /*background: #f7f7f7;*/
        }
        .criarCurriculo{    
            max-width: 300px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 25px;
            box-shadow: 0px 5px rgba(0,0,0,10);
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
        input[type='password']{
            width: 100%;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            margin-bottom: 3px;
            font-size: 12px;
        }
        input[type='email'],
            input[type='select']{
            width: 35%;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            margin-bottom: 3px;
            font-size: 12px;
        }
        input[type='number']{
            width: 35%;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            margin-bottom: 3px;
            font-size: 12px;
        }
       
        
        input[type='submit']:hover{
            background-color: #3c8c41;
        }
    </style>
   
<body>

<?php include('./index.html'); ?>         
<br><br>
<div class="content" id="perfil" style="background-color: transparent"> 

    <div width="100%" height="100%" align="left">
        <form class="criarCurriculo" method="post" action="criarCurriculo.php">
        <center><h4 style="color: blue; ">Criar Currículo de Curso</h4></center>
        Escolher o Curso:
        <br>
        <select  name="categoria" id="categoria" required>
            <option value="">Selecione um curso</option>
            <?php
            $query = $conn->query("SELECT idCurso, nomeCurso FROM tb_Cursos");
            $registros = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach($registros as $option) {
                ?>
                    <option value="<?php echo $option['idCurso']?>"><?php echo $option['nomeCurso']?></option>
                <?php
            }
            ?>
        </select>
       

<!--        <br><br>
        Disciplinas do Curriculo:
        <br>
        <select multiple size="10" name="subcategoria" id="subcategoria" required>    
        <select name="subcategoria" id="subcategoria" required>
            <option value="">Selecione uma disciplina</option>
        </select>-->
        <br><br>
        <label class="form-label">Ao clicar em processar, o currículo será criado com as disciplinas destinadas a ele. <br><br> Se ele já existir, apenas será acrescentada nova(s) disciplina(s).</label>
        <br><br><br>
        <input type="submit" name="inserirCurriculo" id="inserirCurriculo" value="Processar"/>


        

        <?php
        if(isset($_POST['categoria'])){
             $idCurso=$_POST['categoria'];
             
                $curso=$fg->findCursoEspecifico($idCurso);
                foreach ($curso as $row) {
                    $nmCurso = $row->nomeCurso ;
                }
                
                $insert=$fg->criarCurriculoCurso($idCurso);
                 
                if(empty($insert)){
                    echo 'Currículo curso '.$nmCurso.' já estava atualizado';
                }else{
                    echo 'Currículo do curso '.$nmCurso.' foi atualizado';
                }        
        }
        ?>
        
        
    </form>
</div>
</div>

</form>

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