<?php

    session_start();
    
    /*if (!isset($_SESSION['logged']))
    {
        header('Location: torneio.php');
        exit();
    }*/
    $torneio = sprintf('%s',$_GET['nome_torneio']);
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
        <center><b class="titulo">Criar equipa em <?php echo $torneio ?></b><br /><br /></center>
        <center><div class="container-login">
                    <form action="equipa_to_database.php?nome_torneio=<?php echo $torneio ?>" method="post">
                        <input class="text_login" type="text" placeholder="Nome da Equipa" name="equipa_input" align = "center"/>
                        
                        <select name="posicoes_input" style="width:100%;">                                                          
                                <option value="GR">GR</option>
                                <option value="DC">DC</option>
                                <option value="DD">DD</option>
                                <option value="DE">DE</option>
                                <option value="MC">MC</option>
                                <option value="MD">MD</option>
                                <option value="ME">ME</option>
                                <option value="PL">PL</option>
                        </select>

                        <input type="submit" class="submit_login" value="CONFIRMAR" />
                        
                    </form>
                    <input class="submit_login" type="button" value="VOLTAR" align="center" style="width:100%" 
                           onclick="returntoTorneio()" />    
                </div>
        </center>
        
    </body>
</html>

<script type="text/javascript">

    function returntoTorneio(){
        nome_torneio="<?php echo $torneio ?>";
        location.replace("torneio_logged.php?nome_torneio="+nome_torneio+"&estado=Em espera");
    }
</script>