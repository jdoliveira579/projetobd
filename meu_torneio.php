<?php

	session_start();
	
	if (!isset($_SESSION['logged']))
	{
		header('Location: torneio.php');
		exit();
	}
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    
	<title>Entrar Torneio</title>
<!--	<link href ="bootstrap.css" rel = "stylesheet" type = "text/css"> -->
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <script src="./scripts/projeto.js"></script>
    <style>
        #torneios-table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #torneios-table td, #torneios-table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #torneios-table tr:nth-child(even){background-color: #f2f2f2;}

        #torneios-table tr:hover {background-color: #ddd;}

        #torneios-table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #008CBA;
            color: white;
        }
  </style>  
</head>

<body>
	<br /><br />
	<br /><br />
	<center><b class="titulo">TORNEIO</b><br /><br /></center>
	<br><br/>

    <center>
<?php
	
	    require_once "connect.php";
        
        
        $connection = @new mysqli($host, $db_user, $db_password, $db_name);
		if ($connection->connect_errno != 0)
		{
			throw new Exception(mysqli_connect_errno());
        }
	       
    // filter by nome:        
    $result_torneio = $connection->query("SELECT torneio.nome as nome, COUNT(torneio_equipa.torneio_nome) as num_equipas, torneio.inicio_torneio as estado, 
                                          torneio.utilizador_username as gestor_torneio
                                           FROM torneio, torneio_equipa
                                           WHERE torneio_equipa.torneio_nome=torneio.nome
                                           AND torneio.nome='".$_GET['nome_torneio']."'");
                                           
    $result_equipas = $connection->query("SELECT equipa.nome as nome_equipa, equipa.num_jogadores as num_jogadores, equipa.capitao as capitao
                                          FROM torneio_equipa, equipa
                                          WHERE equipa.nome=torneio_equipa.equipa_nome
                                          AND torneio_equipa.torneio_nome='".$_GET['nome_torneio']."'");  

    $result_jogos = $connection->query("SELECT jogo_data.equipa1 as equipa1, jogo_data.equipa2 as equipa2, jogo_data.golos1 as golos1,
                                          jogo_data.golos2 as golos2, jogo_data.data_dia_hora as data_dia_hora, jogo_data.campo_nome as campo
                                          FROM jogo_data
                                          WHERE jogo_data.torneio_nome='".$_GET['nome_torneio']."'");

    $num_of_results_equipas = mysqli_num_rows($result_equipas);
    $num_of_results_jogos = mysqli_num_rows($result_jogos);

    //$message = $num_of_results; 
    //echo "<script type='text/javascript'>alert('$message');</script>";         

	if($result_torneio)
	{
                
        echo '<h2><b class="titulo-lista">TORNEIO</b></h2>
            <table id="torneios-table"> <!-- style="width:70%" >  -->
                <tr>     
                    <th>Nome</th>
                    <th>Número de Equipas</th> 
                    <th>Gestor</th>
                    <th>Estado</th>
                </tr>';   
            
            $row = mysqli_fetch_assoc($result_torneio);

            $nome = $row['nome'];
		    $num_equipas = $row['num_equipas'];
		    $gestor_torneio = $row['gestor_torneio'];
            $estado = $row['estado'];
            
            if($estado == 1){
                $estado = "Começado";
            }
            else{
                $estado = "Por começar";
            }
							
            echo							
            '<tr>
            <td>'.$nome.'</td>
            <td>'.$num_equipas.'</td>
            <td>'.$gestor_torneio.'</td>
            <td>'.$estado.'</td>   
            </tr>'; 

        echo '</table>';    

    }
    else {
        echo '<h2><b class="titulo-lista">TORNEIO</b></h2>
            <table id="torneios-table"> <!-- style="width:70%" >  -->
                <tr>     
                    <th>Nome</th>
                    <th>Número de Equipas</th> 
                    <th>Gestor</th>
                    <th>Estado</th>
                </tr>';   
        echo '</table>'; 
    }

    if($result_equipas)
	{
                
        echo '<h2><b class="titulo-lista">EQUIPAS</b></h2>
            <table id="torneios-table"> <!-- style="width:70%" >  -->
                <tr>     
                    <th>Nome</th>
                    <th>Número de Jogadores</th> 
                    <th>Capitão</th>
                </tr>';   
            
        for ($i = 1; $i <= $num_of_results_equipas; $i++) 
        {
							
            $row = mysqli_fetch_assoc($result_equipas);
            
		    $nome_equipa = $row['nome_equipa'];
		    $num_jogadores = $row['num_jogadores'];
		    $capitao = $row['capitao'];
							
            echo							
            '<tr>
            <td>'.$nome_equipa.'</td>
            <td>'.$num_jogadores.'</td>
            <td>'.$capitao.'</td>    
            </tr>'; 
        } 

        echo '</table>';    
    }
    else {
        echo '<h2><b class="titulo-lista">EQUIPAS</b></h2>
            <table id="torneios-table"> <!-- style="width:70%" >  -->
                <tr>     
                    <th>Nome</th>
                    <th>Número de Jogadores</th> 
                    <th>Capitão</th>
                </tr>';    
        echo '</table>'; 
    }
    
    if($result_jogos)
	{
                
        echo '<h2><b class="titulo-lista">JOGOS</b></h2>
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
            $equipa2 = $row['equipa2'];
            $golos = sprintf("%s - %s", $row['golos1'], $row['golos2']);
		    $data_dia_hora = $row['data_dia_hora'];
		    $campo = $row['campo'];
							
            echo							
            '<tr>
            <td>'.$equipa1.'</td>
            <td>'.$golos.'</td>
            <td>'.$equipa2.'</td>
            <td>'.$data_dia_hora.'</td>
            <td>'.$campo.'</td>    
            </tr>'; 
        } 

        echo '</table>';    

    }
    else {
        echo '<h2><b class="titulo-lista">JOGOS</b></h2>
            <table id="torneios-table"> <!-- style="width:70%" >  -->
                <tr>     
                    <th>Equipa 1</th>
                    <th>Resultado</th> 
                    <th>Equipa 2</th>
                    <th>Data</th>
                    <th>Campo</th>
                </tr>';  
        echo '</table>'; 
    }   
?>
 </center>
 <center><input class="submit_inicial voltar" type="button" onclick="location.replace('principal.php');" value="Voltar" /></center> 

 </body>  
	
</html> 