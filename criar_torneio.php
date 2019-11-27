<?php

    session_start();
    
    /*if (!isset($_SESSION['logged']))
    {
        header('Location: torneio.php');
        exit();
    }*/
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Torneios</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css">
        <style>
            #torneio-table{
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            #torneio-table td, #torneio-table th{
                border: 1px solid #ddd;
                padding: 8px;
            }

            #torneio-table tr:nth-child(even){background-color: #f2f2f2;}

            #torneio-table tr:hover{background-color: #ddd;}

            #torneio-table th{
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #008CBA;
                color: white;
            }
        </style> 
        <script src="./scripts/projeto.js"></script>
    </head>

    <body>
        <center><b class="titulo">Criar Torneio</b><br /><br /></center>
        <center><div class="container-login">
                    <form action="torneio_to_database.php" method="post">
                        
                        <label for="Nome">Nome: </label>
                        <input class="text_login" type="text" placeholder="Nome do Torneio" name="nome_input" align = "center" value="<?php if(isset($_SESSION['nome_input']))echo $_SESSION['nome_input']?>"/>

                        <label for="Data Inicio">Data Início: </label>
                        <input class="text_login" type="date" placeholder="Início" name="data_inicio_input" value="<?php if(isset($_SESSION['data_inicio_input']))echo $_SESSION['data_inicio_input']?>" />
                        
                        <label for="Data Fim">Data Fim: </label>
                        <input class="text_login" type="date" placeholder="Fim" name="data_fim_input" value="<?php if(isset($_SESSION['data_fim_input']))echo $_SESSION['data_fim_input']?>" />

                        <label for="Horas">Horas(Início - Fim): </label><br>
                        <input class="text_login" type="text" placeholder="eg. 20h" name="hora_inicio_input" style="width: 46%;" value="<?php if(isset($_SESSION['hora_inicio_input']))echo $_SESSION['hora_inicio_input']?>" />
                        <b> - </b>
                        <input class="text_login" type="text" placeholder="eg. 22h:30min" name="hora_fim_input" style="width: 46%;" value="<?php if(isset($_SESSION['hora_fim_input']))echo $_SESSION['hora_fim_input']?>" /><br>

                        <label for="Dias">Dias: </label>
                        <input class="text_login" type="text" placeholder="eg. Segunda,Terça,Quinta" name="dias_input" align = "center" value="<?php if(isset($_SESSION['dias_input']))echo $_SESSION['dias_input']?>" />

                        <label for="Campos">Campos: </label>
                        <input class="text_login" type="text" placeholder="eg. campo1,campo2,campo3" name="campos_input" align = "center" value="<?php if(isset($_SESSION['campos_input']))echo $_SESSION['campos_input']?>" />
                        <?php
                            if(isset($_SESSION['error_log'])){
                                echo $_SESSION['error_log'];
                                unset($_SESSION['error_log']);
                            }
                        ?>
                        <input type="submit" class="submit_login" value="CONFIRMAR" />
                        
                    </form>
                    <input class="submit_login" type="button" value="VOLTAR" align="center" style="width:100%" 
                           onclick="location.replace('principal.php');" />    
                </div>
        </center>
        
    </body>
</html>

<script type="text/javascript">

    function returntoPr(){
        nome_torneio="<?php echo $torneio ?>";
        location.replace("torneio_logged.php?nome_torneio="+nome_torneio+"&estado=Em espera");
    }
</script>