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

    $equi = sprintf('%s',$_GET['nome_equipa']);
    $posicao = $_POST['posicoes_input'];
    $estatuto = "titular";

    require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno;
	}
    else
    {
        

        $result_jogadores_posicao = $connection->query("SELECT posicao.nome
                                            FROM (SELECT jogador.utilizador_username AS jogadores FROM jogador WHERE jogador.equipa_nome='$equi') equip_jogadores,
                                                posicao
                                            WHERE equip_jogadores.jogadores = posicao.jogador_utilizador_username
                                            AND posicao.nome='$posicao'");
                                            
        $num_of_results_jogadores_posicao = mysqli_num_rows($result_jogadores_posicao);


        if($num_of_results_jogadores_posicao==2 && (strcmp($posicao, "PL")==0 || strcmp($posicao, "MC")==0 || strcmp($posicao, "DC")==0)){
            $estatuto = "suplente";
        }
        else if($num_of_results_jogadores_posicao==1){
            $estatuto = "suplente";
        }

        //inserir user na tabela de jogadores
        $sql = "INSERT INTO jogador(estatuto, equipa_nome, utilizador_username) VALUES ('$estatuto','$equi','$username')";
        try{
            if ($connection->query($sql) === TRUE){
                header('Location: principal.php');
            }
            else{
                $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                header('Location: principal.php');
            }
            } catch(Exception $e){
                echo $e->getMessage();
            }
            
        //inserir user na tabela de posicoes
        $sql = "INSERT INTO posicao(nome, jogador_utilizador_username) VALUES ('$posicao', '$username')";
        try{
            if ($connection->query($sql) === TRUE){
                header('Location: principal.php');
            }
            else{
                $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                header('Location: principal.php');
            }
        } catch(Exception $e){
            echo $e->getMessage();
            }
            
        //iterar o número de jogadores da equipa
        $sql = "UPDATE equipa SET num_jogadores=num_jogadores+1 WHERE equipa.nome='$equi'";
        try{
            if ($connection->query($sql) === TRUE){
                header('Location: principal.php');
            }
            else{
                $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                header('Location: principal.php');
            }
        } catch(Exception $e){
            echo $e->getMessage();
            }
                
    }
?>

