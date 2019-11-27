<?php

    session_start();
    
    /*if (!isset($_SESSION['logged']))
    {
        header('Location: torneio.php');
        exit();
    }*/
    if (isset($_SESSION['username']))
	{
		$username = $_SESSION['username'];
    }
    else if(isset($_SESSION['user_username']))
    {
        $username = $_SESSION['user_username'];
    }
    $equi =sprintf('%s',$_GET['nome_equipa']);
    
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Torneios</title>
        <link rel="stylesheet" type="text/css" href="./css/style.css">
        <style>
            #jogadores-table{
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            #jogadores-table td, #jogadores-table th{
                border: 1px solid #ddd;
                padding: 8px;
            }

            #jogadores-table tr:nth-child(even){background-color: #f2f2f2;}

            #jogadores-table tr:hover{background-color: #ddd;}

            #jogadores-table th{
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
        <center><b class="titulo"><?php echo $equi ?></b><br /><br /></center>
        <center><div class="container-login">
                    <form action="retirar_capitao_db.php?nome_equipa=<?php echo $equi ?>" method="post">
                        <select name="posicoes_input" style="width:100%;">
                            <?php 
                                require_once "connect.php";
        
                                $connection = @new mysqli($host, $db_user, $db_password, $db_name);
                                if ($connection->connect_errno != 0)
                                {
                                    throw new Exception(mysqli_connect_errno());
                                }
                                
                                //Tabela número de posições ocupadas na equipa        
                                $result_jogadores = $connection->query("SELECT jogador.utilizador_username
                                                                        FROM jogador
                                                                        WHERE jogador.equipa.nome='$equi' AND jogador.utilizador_username<>'$username'");
                                
                                $num_of_results_jogadores = mysqli_num_rows($result_jogadores);

                                if($num_of_results_jogadores>0)
                                {
                                    for ($i = 1; $i <= $num_of_results_jogadores ; $i++) 
                                    {                           
                                        
                                        $row = mysqli_fetch_assoc($result_jogadores);
                                        $jogador=$row['utilizador_username'];
                                        echo '<option value="'.$jogador.'">'.$jogador.'</option>';

                                    }
                                }
                                
                            ?>
                        </select>

                        <input type="submit" class="submit_login" value="CONFIRMAR" />
                        
                    </form>
                    <input class="submit_login" type="button" value="VOLTAR" align="center" style="width:100%" 
                           onclick="returntoMinhaEquipa()" />    
                </div>
        </center>
        
    </body>
</html>
<script type="text/javascript">

    function returntoMinhaEquipa(){
        nome_equipa="<?php echo $equi ?>";
        location.replace("minha_equipa.php?nome_equipa="+nome_equipa);
    }
</script>