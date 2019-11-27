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
    $torneio = sprintf('%s',$_GET['nome_torneio']);

    require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno;
	}
    else
    {
        $equipa = $_POST['equipa_input'];
        $posicao = $_POST['posicoes_input'];

        $result = $connection->query("SELECT equipa.nome FROM equipa WHERE equipa.nome ='$equipa'");
        if (!$result) throw new Exception($connection->error);
          $count_equipas = $result->num_rows;
          if($count_equipas > 0){
            $_SESSION['error_log'] = '<span style="color:red">Nome de equipa já utilizado!</span>';
            header('Location: criar_equipa.php?nome_torneio='.$torneio);
        	exit();
          }
          else{
            
            //insert equipa na tabela de equipas
            $sql = "INSERT INTO equipa(nome, num_jogadores, capitao) VALUES ('$equipa',1, '$username')";
            try{
                if ($connection->query($sql) === FALSE){
                  $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                  header('Location: criar_equipa.php?nome_torneio='.$torneio);
                }
            } catch(Exception $e){
                echo $e->getMessage();
            }
           
            //insert equipa na tabela torneio_equipa
            $sql = "INSERT INTO torneio_equipa(torneio_nome, equipa_nome) VALUES ('$torneio', '$equipa')";
            try{
                if ($connection->query($sql) === FALSE){
                  $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                  header('Location: criar_equipa.php?nome_torneio='.$torneio);
                }
            } catch(Exception $e){
                echo $e->getMessage();
            }
          
            //insert capitao na tabela jogador
            $sql = "INSERT INTO jogador(estatuto, equipa_nome, utilizador_username) VALUES ('titular', '$equipa', '$username')";
            try{
                if ($connection->query($sql) === FALSE){
                    $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                    header('Location: criar_equipa.php?nome_torneio='.$torneio);
                }
            } catch(Exception $e){
                echo $e->getMessage();
            }


            //insert capitao na tabela posicao
            $sql = "INSERT INTO posicao(nome, jogador_utilizador_username) VALUES ('$posicao', '$username')";
            try{
                if ($connection->query($sql) === TRUE){
                header('Location: principal.php');
                }
                else{
                $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                header('Location: criar_equipa.php?nome_torneio='.$torneio);
                }
            } catch(Exception $e){
                echo $e->getMessage();
            }
        }
    }
?>