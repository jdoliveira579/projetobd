<?php

	session_start();

	if($_POST['input_user_username'] == ""){
		$_SESSION['error_log'] = '<span style="color:red">Insira username!</span>';
		header('Location: login.php');
		exit();
	}

	require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno;
	}
	else
	{
		$username = $_POST['input_user_username'];
		$password = $_POST['input_user_password'];

		$username = htmlentities($username, ENT_QUOTES, "UTF-8");
		if ($result= @$connection->query(sprintf("SELECT username FROM utilizador WHERE username ='%s'", mysqli_real_escape_string($connection,$username)))){
			$count_users = $result->num_rows;
			if($count_users>0){
					if ($result= @$connection->query(sprintf("SELECT username, password, estado FROM utilizador WHERE username ='%s' AND password = '%s'", mysqli_real_escape_string($connection,$username), mysqli_real_escape_string($connection,$password)))){
						$count_users = $result->num_rows;
						if($count_users>0){
								$row = $result->fetch_assoc();

								$estado = $row['estado'];
								if($estado == 1){
									$_SESSION['logged'] = true;
									$_SESSION['user_username'] = $row['username'];
									$_SESSION['user_password'] = $row['password'];
									/*$_SESSION['user_name'] = $row['name'];
									$_SESSION['user_surname'] = $row['surname'];
									$_SESSION['user_email'] = $row['email'];*/

									unset($_SESSION['error_log']);
									$result->free_result();
									header('Location: principal.php');
								}
								else{
									$_SESSION['user_username'] = $username;
									$_SESSION['error_log'] = '<span style="color:red">Aguarde confirmação de um Admin!</span>';
									header('Location: login.php');
									exit();
								}
						}
						else{
							  $_SESSION['user_username'] = $username;
								$_SESSION['error_log'] = '<span style="color:red">Username e password não coincidem!</span>';
								header('Location: login.php');
								exit();
						}
					}
					else{
							$_SESSION['error_log'] = '<span style="color:red">Erro query!</span>';
							header('Location: login.php');
							exit();
					}
			}
			else{
				$_SESSION['error_log'] = '<span style="color:red">Username não foi encontrado!</span>';
				header('Location: login.php');
				exit();
			}
		}
		else{
			$_SESSION['error_log'] = '<span style="color:red">Erro query</span>';
			header('Location: login.php');
			exit();
		}

		$connection->close();
	}
?>
