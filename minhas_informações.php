<?php

	session_start();
	
	/*if (isset($_SESSION['logged']))
	{
		header('Location: minhas_informações.php');
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

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    
	<title>Torneios</title>
<!--	<link href ="bootstrap.css" rel = "stylesheet" type = "text/css"> -->
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <style>
        #info-table {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 60%;
        }

        #info-table td, #info-table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #info-table tr:nth-child(even){background-color: #f2f2f2;}

        #info-table tr:hover {background-color: #ddd;}

        #info-table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #008CBA;
            color: white;
        }

        #minhas_informacoes {
            font-size: 50px;
        }
    </style>  
</head>

<body>
	<br /><br />
	<br /><br />
	<center><b class="titulo" id="minhas_informacoes">MINHAS INFORMAÇÕES</b><br /><br /></center>
	<br><br/>
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
    $result = $connection->query("SELECT utilizador.username as username, utilizador.cc as cc, utilizador.primeiro_nome as primeiro_nome, utilizador.ultimo_nome as ultimo_nome, 
                                         utilizador.email as email, utilizador.saldo as saldo
								  FROM utilizador
								  WHERE utilizador.username='".$username."'");
	
        
  //$message = $num_of_results; 
//   echo "<script type='text/javascript'>alert('$message');</script>";
           
        
	if($result)
	{
                
        echo '<table id="info-table"> <!-- style="width:70%" >  -->';
                
                $row = mysqli_fetch_assoc($result);
                $username = $row['username'];
		        $cc = $row['cc'];
		        $nome = sprintf('%s %s',$row['primeiro_nome'],$row['ultimo_nome']);
		        $email = $row['email'];
                $saldo = $row['saldo'];
        
        echo    '<tr>
                    <th>Username: </th>
                    <td>'.$username.'</td>
                <tr>    
                <tr>    
                    <th>CC: </th>
                    <td>'.$cc.'</td>
                <tr>    
                    <tr>    
                    <th>Nome: </th>
                    <td>'.$nome.'</td>
                <tr>    
                    <tr>    
                    <th>E-mail: </th>
                    <td>'.$email.'</td> 
                <tr>   
                <tr>
                    <th>Saldo: </th>
                    <td>'.$saldo.'</td>							              
                <tr>';
        echo '</table>';    

    }
    else
    {
        
        echo 'Nenhum jogador encontrado!';
    }
       
?>
 </center>
 <center><input class="submit_inicial voltar" type="button" onclick="location.replace('principal.php');" value="Voltar" /></center> 
</body>  
	
</html>   