<?php

	session_start();
	
	/*if (!isset($_SESSION['logged']))
	{
		header('Location: informações_torneios.php');
		exit();
    }*/
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
	<title>Informações Torneios</title>
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
	<center><b class="titulo">INFORMAÇÕES TORNEIOS</b><br /><br /></center>
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
    $result_torneios = $connection->query("SELECT t.inicio_torneio, t.nome, a.num, t.data_inicio, t.data_fim, t.dias, t.horas, b.campos
                                  FROM torneio t, (SELECT COUNT(equipa_nome) num, torneio_nome FROM torneio_equipa GROUP BY torneio_nome) a,
                                  (SELECT torneio_nome, GROUP_CONCAT(nome SEPARATOR ', ') campos FROM campo_torneio ct, campo c WHERE ct.campo_nome = c.nome GROUP BY torneio_nome) b
                                  WHERE t.nome = a.torneio_nome and b.torneio_nome = t.nome");
    
    $num_of_results_torneios = mysqli_num_rows($result_torneios);
        
  //$message = $num_of_results; 
  //echo "<script type='text/javascript'>alert('$message');</script>";
           
        
	if($result_torneios)
	{
                
        echo '<table id="torneios-table"> <!-- style="width:70%" >  -->
                <tr>     
                    <th>Estado</th>
                    <th>Nome</th>
                    <th>Número de equipas</th> 
                    <th>Data (Início-Fim)</th>
                    <th>Dias</th>
                    <th>Horas</th>
                    <th>Campos</th>
                </tr>';   

        for ($i = 1; $i <= $num_of_results_torneios; $i++) 
        {
							
            $row = mysqli_fetch_assoc($result_torneios);
            
            $estado = $row['inicio_torneio'];
            $nome_torneio = $row['nome'];
            $num_equipas = $row['num'];
            $datas = sprintf('%s - %s',$row['data_inicio'],$row['data_fim']);
            $dias = $row['dias'];
            $horas = $row['horas'];
            $campos = $row['campos'];

            
            if($estado == 1){
                $estado = "A decorrer";
            }
            else{
                $estado = "Em espera";
            }
							
            echo							
            '<tr>
            <td>'.$estado.'</td>
            <td>'.$nome_torneio.'</td>
            <td>'.$num_equipas.'</td>
            <td>'.$datas.'</td>
            <td>'.$dias.'</td>
            <td>'.$horas.'</td>
            <td>'.$campos.'</td>
            <td><input type="button" class="submit_inicial ver_equipas" value="VER TORNEIO" align="center" style="width:100%" onclick="gotoTorneioLogged(this)"></td>       
            </tr>'; 
        }

        echo '</table>';    

    }
    else
    {
        
        echo 'Não há torneios!';
    }
       
?>
 </center>
 <center><input class="submit_inicial voltar" type="button" onclick="location.replace('principal.php');" value="Voltar" /></center> 
 </body>  
	
</html>   