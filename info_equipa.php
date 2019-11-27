<?php

    session_start();
    
    /*if (!isset($_SESSION['logged']))
    {
        header('Location: torneio.php');
        exit();
    }*/
    $equi =sprintf('%s',$_GET['nome_equipa']);
    $validation = $_GET['validation'];
    $estado_torneio = sprintf("%s", $_GET['estado']);
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Torneios</title>
	<link rel="stylesheet" type="text/css" href="./css/style.css">
    <style>
        #jogadores-table, #jogos-table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #jogadores-table td, #jogadores-table th, #jogos-table td, #jogos-table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #jogadores-table tr:nth-child(even), #jogos-table tr:nth-child(even) {background-color: #f2f2f2;}

        #jogadores-table tr:hover, #jogos-table tr:hover {background-color: #ddd;}

        #jogadores-table th, #jogos-table th {
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
            <input type="button" class="submit_inicial ver_torneios" value="ENTRAR EM EQUIPA" onclick="gotoEntrarEquipa()" />
        </div>
    </div>
<?php
	
	   require_once "connect.php";
        
        
        $connection = @new mysqli($host, $db_user, $db_password, $db_name);
		if ($connection->connect_errno != 0)
		{
			throw new Exception(mysqli_connect_errno());
		}        
        	
	       
    //Tabela Jogadores        
    $result_jogadores = $connection->query("SELECT utilizador.username ,jogador.estatuto, posicao.nome as posicao, utilizador.primeiro_nome as pnome, utilizador.ultimo_nome as unome, equipa.nome as enome
    								FROM utilizador, jogador, equipa,posicao 
    								WHERE jogador.equipa_nome='$equi' AND jogador.utilizador_username=utilizador.username AND equipa.nome = '$equi'
                                    AND posicao.jogador_utilizador_username = utilizador.username");
        
	$num_of_results_jogadores = mysqli_num_rows($result_jogadores);
        
	if($num_of_results_jogadores>0)
	{
                
        echo '<h2><b class="titulo-lista">Jogadores</b></h2>
            <table id="jogadores-table"> <!-- style="width:70%">  -->
                <tr>
                    <th style="display:none;">empid</th>      
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Estatudo</th> 
                    <th>Posição</th>
                </tr>';
                

        for ($i = 1; $i <= $num_of_results_jogadores ; $i++) 
        {
							
            $row = mysqli_fetch_assoc($result_jogadores);
            $nome = sprintf('%s %s',$row['pnome'],$row['unome']);
		    $estatuto = $row['estatuto'];
		    $posicao = $row['posicao'];

			$aux = $row['username'];
			$aux2 = $row['enome'];
			$result_capitao = $connection->query("SELECT equipa.capitao FROM utilizador, equipa 
				WHERE equipa.capitao = '$aux' AND equipa.nome = '$aux2'");

    		$num_of_results_capitao = mysqli_num_rows($result_capitao);

    		if($num_of_results_capitao == 0) {$capitao = 'Jogador';}
    		else{
    			$capitao = 'Capitão';}   
							
            echo							
            '<tr>
            <td>'.$nome.'</td>
            <td>'.$capitao.'</td>
            <td>'.$estatuto.'</td>
            <td>'.$posicao.'</td>  
            </tr>'; 
        }
        
      echo '</table>';    

    }

    $result_jogos = $connection->query("SELECT jogo_data.data_dia_hora as data, jogo_data.campo_nome as campo, jogo_data.torneio_nome as tnome,
                                    jogo_data.equipa1 as equip1, jogo_data.equipa2 as equip2, 
                                    jogo_data.golos1 as g1, jogo_data.golos2 as g2
                                    FROM  jogo_data, jogo_data_equipa,equipa
                                    WHERE jogo_data.id = jogo_data_equipa.jogo_data_id 
                                    AND jogo_data_equipa.equipa_nome = equipa.nome AND equipa.nome = '$equi'");
                                            
	$num_of_results_jogos = mysqli_num_rows($result_jogos);

    //Tabela Datas de Jogos
    	if($num_of_results_jogos>0)
	{
                
        echo '<h2><b class="titulo-lista">Data de Jogos</b></h2>
            <table id="jogos-table"> <!-- style="width:70%">  -->
                <tr>
                    <th style="display:none;">empid</th>      
                    <th>Data</th>
                    <th>Campo</th> 
                    <th>Nome equipa adv.</th>
                    <th>Resultado</th>
                    <th>Torneio</th>
                </tr>';
                

        for ($i = 1; $i <= $num_of_results_jogos ; $i++) 
        {
            $row = mysqli_fetch_assoc($result_jogos);
			$g1 = $row['g1'];
			$g2 = $row['g2'];				
			$dia = $row['data'];
			$campo = $row['campo'];
			$e1 = $row['equip1'];
			$e2 = $row['equip2'];
			$torneio = $row['tnome'];
            
			if(strcmp($e1,$equi) == 0){
				$adv = sprintf('%s',$e2);
				if($g1 == null){$resultado = 'Jogo Não Realizado';}
				else if($g1<$g2){$resultado = sprintf('%s - %s Derrota',$g1,$g2);}
				else if($g1>$g2){$resultado = sprintf('%s - %s Vitoria',$g1,$g2);}
                else if($g1==$g2){$resultado = sprintf('%s - %s Empate',$g1,$g2);}
			}
			else if (strcmp($e1,$equi) != 0){
				$adv = sprintf('%s',$e1);
				if($g1 == null){$resultado = 'Jogo Não Realizado';}
				else if($g1<$g2){$resultado = sprintf('%s - %s Vitoria',$g1,$g2);}
				else if($g1>$g2){$resultado = sprintf('%s - %s Derrota',$g1,$g2);}
                else if($g1==$g2){$resultado = sprintf('%s - %s Empate',$g1,$g2);}

			}
			
            
            echo					
            '<tr>
            <td>'.$dia.'</td>
            <td>'.$campo.'</td>
            <td>'.$adv.'</td>
            <td>'.$resultado.'</td>
            <td>'.$torneio.'</td> 
            </tr>'; 
        }
        
      echo '</table>';

    }
    $novo = sprintf("torneio_logged.php?nome_torneio=%s",$torneio);
    echo '<br>';
?>

</center>
<center><input class="submit_inicial voltar" type="button" onclick="returntoTorneio()" value="Voltar" /></center>

</body>

</html>

<script type="text/javascript">
                function gotoEntrarEquipa(){
                    var validation = <?php echo $validation ?>;
                    var estado = "<?php echo $estado_torneio ?>";
                    var num_jogadores = "<?php echo $num_of_results_jogadores ?>";
                    if(validation > 0){
                        alert("APENAS PODE FAZER PARTE DE UMA EQUIPA!");
                    }
                    else if(num_jogadores==16){
                        alert("EQUIPA JÁ COMPLETA!\n(Máximo de 16 jogadores)");
                    }
                    else if(estado == "A decorrer"){
                        alert("TORNEIO JÁ A DECORRER!");
                    }
                    else{
                        var nome_equipa = "<?php echo $equi ?>";
                        location.replace("entrar_equipa.php?nome_equipa="+nome_equipa+"&num_jogadores_equipa="+num_jogadores);
                    }
                }

                function returntoTorneio(){
                    var nome_torneio=document.getElementById("jogos-table").rows[1].cells[4].innerHTML;
                    var estado = "<?php echo $estado_torneio ?>";
                    location.replace("torneio_logged.php?nome_torneio="+nome_torneio+"&estado="+estado);
                }
            </script>