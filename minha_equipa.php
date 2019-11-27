<?php

	session_start();
	
	/*if (isset($_SESSION['logged']))
	{
		header('Location: minha_equipa.php');
		exit();
	}*/
    
    $equi = sprintf("%s", $_GET['nome_equipa']);
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Torneios</title>
    <link href ="./css/style.css" rel = "stylesheet" type = "text/css">
    <style>
        #torneios-table, #equipa-table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 60%;
        }

        #torneios-table td, #torneios-table th, #equipa-table td, #equipa-table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #torneios-table tr:nth-child(even), #equipa-table tr:nth-child(even) {background-color: #f2f2f2;}

        #torneios-table tr:hover, #equipa-table tr:hover {background-color: #ddd;}

        #torneios-table th, #equipa-table th {
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
    <div class="container-inicial">
        <div class="input-container">
	        <h1 class="titulo"><?php echo $equi ?></h1>
            <input type="button" class="submit_inicial ver_torneios" value="SAIR DA EQUIPA" onclick="gotoSairEquipa()" />
        </div>
    </div>


    <center>
<?php
	
	   require_once "connect.php";
        
        
        $connection = @new mysqli($host, $db_user, $db_password, $db_name);
		if ($connection->connect_errno != 0)
		{
			throw new Exception(mysqli_connect_errno());
		}        
        	
	              
    $result_equipa = $connection->query("SELECT equipa.nome as nome_equipa, equipa.num_jogadores as jogadores_equipa, equipa.capitao as capitao_equipa
                                         FROM equipa
                                         WHERE equipa.nome='$equi'");
    
    $result_jogadores = $connection->query("SELECT jogador.utilizador_username as utilizador, jogador.equipa_nome as equipa, jogador.estatuto as estatuto,
    										posicao.nome as posicao
											FROM jogador, equipa, posicao
											WHERE jogador.equipa_nome=equipa.nome AND jogador.utilizador_username=posicao.jogador_utilizador_username
                                            AND equipa.nome='$equi' 
											ORDER BY jogador.equipa_nome ASC, jogador.estatuto DESC");

    $result_jogos = $connection->query("SELECT jogo_data.equipa1 as equipa1, jogo_data.equipa2 as equipa2, jogo_data.golos1 as golos1, jogo_data.golos2 as golos2,	 															jogo_data.golos2 as golos2, 
    									jogo_data.data_dia_hora as dia_hora, jogo_data.campo_nome as campo
										FROM jogo_data
										WHERE jogo_data.equipa1='$equi' OR jogo_data.equipa2='$equi' 
										ORDER BY jogo_data.data_dia_hora");


    $num_of_results_jogadores = mysqli_num_rows($result_jogadores);
	$num_of_results_jogos = mysqli_num_rows($result_jogos);
        
  //$message = $num_of_results; 
  //echo "<script type='text/javascript'>alert('$message');</script>";
           
    if($result_equipa){

        echo '<h2><b class="titulo-lista">Minha Equipa</b></h2>
            <table id="equipa-table"> <!-- style="width:60%" >  -->
                <tr>     
                    <th style="display:none;">Equipa</th>
                    <th>Número de Jogadores</th> 
                    <th>Capitão</th>
                </tr>';
                
                $row = mysqli_fetch_assoc($result_equipa);
                        
                $nome_equipa = $row['nome_equipa'];
                $num_jogadores = $row['jogadores_equipa'];
                $capitao = $row['capitao_equipa'];
                                
                echo							
                '<tr>
                <td style="display:none;">'.$nome_equipa.'</td>
                <td>'.$num_jogadores.'</td>
                <td>'.$capitao.'</td>  
                </tr>';

        echo '</table>';
    }
    
    if($result_jogadores)
	{
                
        echo '<h2><b class="titulo-lista">Jogadores</b></h2>
            <table id="torneios-table"> <!-- style="width:70%" >  -->
                <tr>     
                    <th>Username</th>
                    <th>Estatuto</th>
                    <th>Posição</th>
                </tr>';

                

        for ($i = 1; $i <= $num_of_results_jogadores; $i++) 
        {
							
              $row = mysqli_fetch_assoc($result_jogadores);
            
		      $username = $row['utilizador'];
		      $estatuto = $row['estatuto'];
		      $posicao = $row['posicao'];
							
            echo							
            '<tr>
            <td>'.$username.'</td>
            <td>'.$estatuto.'</td>
            <td>'.$posicao.'</td>   
            </tr>'; 
        }
        
      echo '</table>';    

    }

    if($result_jogos)
	{
                
        echo '<h2><b class="titulo-lista">Jogos</b></h2>
            <table id="torneios-table"> <!-- style="width:70%" >  -->
                <tr>      
                    <th>Equipa 1</th>
                    <th>Resultado</th>
                    <th>Equipa 2</th> 
                    <th>Data</th>
                    <th>Campo</th>
                </tr>';

                

        for ($i = 1; $i <= $num_of_results_jogos; $i++) 
        {
							
              $row = mysqli_fetch_assoc($result_jogos);
            
              $equipa1 = $row['equipa1'];
              $resultado = sprintf('%s - %s',$row['golos1'],$row['golos2']);
              $equipa2 = $row['equipa2'];
              $data = $row['dia_hora'];
              $campo = $row['campo'];

            echo							
            '<tr>
            <td>'.$equipa1.'</td>
            <td>'.$resultado.'</td>
            <td>'.$equipa2.'</td>
            <td>'.$data.'</td>
            <td>'.$campo.'</td>   
            </tr>'; 
        }
        
      echo '</table>';    

    }
       
?>
 </center>
 <center><input class="submit_inicial voltar" type="button" onclick="location.replace('principal.php');" value="Voltar" /></center> 
 </body>  
	
</html>   