<?php

    session_start();
    
    /*if (!isset($_SESSION['logged']))
    {
        header('Location: torneio.php');
        exit();
    }*/
    $equi =sprintf('%s',$_GET['nome_equipa']);
    $posicoes = array( "GR", "DC", "DD", "DE", "MC", "MD", "ME", "PL");
    $num_posicoes = count($posicoes);
    $num_jogadores_equipa = $_GET['num_jogadores_equipa'];
    $max_def=6;
    $max_med=6;
    $max_pl=4;
    $max_gr=2;
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
                    <form action="jogador_to_equipa_db.php?nome_equipa=<?php echo $equi ?>" method="post">
                        <select name="posicoes_input" style="width:100%;">
                            <?php 
                                require_once "connect.php";
        
                                $connection = @new mysqli($host, $db_user, $db_password, $db_name);
                                if ($connection->connect_errno != 0)
                                {
                                    throw new Exception(mysqli_connect_errno());
                                }
                                
                                //Tabela número de posições ocupadas na equipa        
                                $result_posicoes = $connection->query("SELECT posicao.nome, COUNT(posicao.nome) AS posicao_jogadores
                                                                       FROM (SELECT jogador.utilizador_username AS jogadores FROM jogador WHERE jogador.equipa_nome='$equi') equip_jogadores,
                                                                            posicao
                                                                       WHERE equip_jogadores.jogadores = posicao.jogador_utilizador_username
                                                                       GROUP BY posicao.nome
                                                                       ORDER BY posicao.nome");
                                
                                $num_of_results_posicoes = mysqli_num_rows($result_posicoes);

                                $result_defesas = $connection->query("SELECT posicao.nome
                                                                      FROM (SELECT jogador.utilizador_username AS jogadores FROM jogador WHERE jogador.equipa_nome='$equi') equip_jogadores,
                                                                           posicao
                                                                      WHERE equip_jogadores.jogadores = posicao.jogador_utilizador_username
                                                                      AND posicao.nome LIKE 'D%'");
                                
                                $result_medios = $connection->query("SELECT posicao.nome
                                                                     FROM (SELECT jogador.utilizador_username AS jogadores FROM jogador WHERE jogador.equipa_nome='$equi') equip_jogadores,
                                                                           posicao
                                                                     WHERE equip_jogadores.jogadores = posicao.jogador_utilizador_username
                                                                     AND posicao.nome LIKE 'M%'");
                                
                                $result_avancados = $connection->query("SELECT posicao.nome
                                                                        FROM (SELECT jogador.utilizador_username AS jogadores FROM jogador WHERE jogador.equipa_nome='$equi') equip_jogadores,
                                                                             posicao
                                                                        WHERE equip_jogadores.jogadores = posicao.jogador_utilizador_username
                                                                        AND posicao.nome LIKE 'P%'");
                                
                                $num_of_defesas = mysqli_num_rows($result_defesas);
                                $num_of_medios = mysqli_num_rows($result_medios);
                                $num_of_avancados = mysqli_num_rows($result_avancados);

                                if($num_of_defesas==$max_def){
                                    $max_med=5;
                                    $max_pl=3;
                                }
                                else if($num_of_medios==$max_med){
                                    $max_def=5;
                                    $max_pl=3;
                                }
                                else if($num_of_avancados==$max_pl){
                                    $max_def=5;
                                    $max_med=5;
                                }

                                    while($row = mysqli_fetch_assoc($result_posicoes)){
                                        $posicoes_jogadores[$row['nome']] = $row['posicao_jogadores'];
                                    }

                                for($pos = 0; $pos < $num_posicoes; $pos++){
                                    foreach($posicoes_jogadores as $posicao => $posicao_jogadores) 
                                    {                           

                                        if(strcmp($posicao, $posicoes[$pos])==0){
                                                
                                            if(strcmp($posicao, "GR")==0 && $posicao_jogadores==$max_gr){
                                                echo '<option value="'.$posicao.'" disabled>'.$posicao.'</option>';
                                            }
                                            
                                            else if(strcmp($posicao, "PL")==0 && $num_of_avancados == $max_pl){
                                                echo '<option value="'.$posicao.'" disabled>'.$posicao.'</option>';
                                            }
                                            
                                            else if((strcmp($posicao, "DC")==0 || strcmp($posicao, "DD")==0 || strcmp($posicao, "DE")==0) && $num_of_defesas == $max_def){
                                                echo '<option value="'.$posicao.'" disabled>'.$posicao.'</option>';
                                            }
                                            
                                            else if((strcmp($posicao, "MC")==0 || strcmp($posicao, "MD")==0 || strcmp($posicao, "ME")==0) && $num_of_medios == $max_med){
                                                echo '<option value="'.$posicao.'" disabled>'.$posicao.'</option>';
                                            }

                                            else{
                                                echo '<option value="'.$posicao.'" >'.$posicao.'</option>';
                                            }
                                            break;
                                        }
                                    }
                                    if($i>$num_of_results_posicoes){
                                        echo '<option value="'.$posicoes[$pos].'" >'.$posicoes[$pos].'</option>';
                                    }
                                }    
                            ?>
                        </select>

                        <input type="submit" class="submit_login" value="CONFIRMAR" />
                        
                    </form>
                    <input class="submit_login" type="button" value="VOLTAR" align="center" style="width:100%" 
                           onclick="returntoInfoEquipa()" />       
                </div>
        </center>
        
    </body>
</html>
<script type="text/javascript">

    function returntoInfoEquipa(){
        nome_equipa="<?php echo $equi ?>";
        location.replace("info_equipa.php?nome_equipa="+nome_equipa+"&validation=0&estado=Em espera");
    }
</script>