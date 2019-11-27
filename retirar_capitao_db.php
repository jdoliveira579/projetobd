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

    require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno;
	}
    else
    {
        
        //tirar user na tabela de posicoes
        $sql = "DELETE FROM posicao WHERE posicao.jogador_utilizador_username='$username'";
        try{
            if ($connection->query($sql) === FALSE){
                $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                header('Location: principal.php');
            }
        } catch(Exception $e){
            echo $e->getMessage();
        }
                
        //tirar user na tabela de jogadores
        $sql = "DELETE FROM jogador WHERE jogador.utilizador_username='$username'";
        try{
            if ($connection->query($sql) === FALSE){
                $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                header('Location: principal.php');
            }
        } catch(Exception $e){
            echo $e->getMessage();
        }
                
        //tirar o número de jogadores da equipa
        $sql = "UPDATE equipa SET num_jogadores=num_jogadores-1 WHERE equipa.nome='$equi'";
        try{
            if ($connection->query($sql) === FALSE){
                $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                header('Location: principal.php');
            }
        } catch(Exception $e){
            echo $e->getMessage();
        }
            
        //verificar se equipa não tem jogadores
        $result = $connection->query("SELECT equipa.num_jogadores FROM equipa WHERE equipa.nome='$equi'");
        $row = mysqli_fetch_assoc($result);
        if($row['num_jogadores']==0){
            //se nao tem jogadores retirá-la das tabelas torneio_equipa e equipa
            $sql = "DELETE FROM torneio_equipa WHERE torneio_equipa.equipa_nome='$equi'";
            try{
                if ($connection->query($sql) === FALSE){
                    $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                    header('Location: principal.php');
                }
            } catch(Exception $e){
                echo $e->getMessage();
            }
            $sql = "DELETE FROM equipa WHERE equipa.nome='$equi'";
            try{
                if ($connection->query($sql) === FALSE){
                    $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
                    header('Location: principal.php');
                }
            } catch(Exception $e){
                echo $e->getMessage();
            }
            header('Location: principal.php');
        }
        else{
            header('Location: principal.php');
        }
    }
    
?>