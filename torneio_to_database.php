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

    $nome_torneio = $_POST['nome_input'];
    $data_inicio = $_POST['data_inicio_input'];
    $data_fim = $_POST['data_fim_input'];
    $hora_inicio = $_POST['hora_inicio_input'];
    $hora_fim = $_POST['hora_fim_input'];
    $dias = $_POST['dias_input'];
    $campos = $_POST['campos_input'];

    $horas = sprintf("%s-%s", $hora_inicio, $hora_fim);

    $dias_semana = array("Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado");
    $dias_escolhidos = explode(",", $dias);
    $campos_escolhidos = explode(",", $campos);

    //save insterted data para não perder tudo só por se enganar
    $_SESSION['nome_input'] = $_POST['nome_input'];
    $_SESSION['data_inicio_input'] = $_POST['data_inicio_input'];
    $_SESSION['data_fim_input'] = $_POST['data_fim_input'];
    $_SESSION['hora_inicio_input'] = $_POST['hora_inicio_input'];
    $_SESSION['hora_fim_input'] = $_POST['hora_fim_input'];
    $_SESSION['dias_input'] = $_POST['dias_input'];
    $_SESSION['campos_input'] = $_POST['campos_input'];

    if(strlen($nome_torneio)<4){
        unset($_SESSION['nome_input']);
        $_SESSION['error_log'] = '<span style="color:red">Nome do torneio deve ter pelo menos 4 caracteres!</span>';
        header('Location: criar_torneio.php');
        exit();
    }
    if($data_inicio >= $data_fim){
        unset($_SESSION['data_inicio_input']);
        unset($_SESSION['data_fim_input']);
        $_SESSION['error_log'] = '<span style="color:red">Data inicial tem de ser antes da data final!</span>';
        header('Location: criar_torneio.php');
        exit();
    }
    if($data_inicio <= date("Y-m-d")){
        unset($_SESSION['data_inicio_input']);
        unset($_SESSION['data_fim_input']);
        $_SESSION['error_log'] = '<span style="color:red">Data inicial tem de ser superior à atual!</span>';
        header('Location: criar_torneio.php');
        exit();
    }
    if($hora_fim <= $hora_inicio){
        unset($_SESSION['hora_inicio_input']);
        unset($_SESSION['hora_fim_input']);
        $_SESSION['error_log'] = '<span style="color:red">Hora inicial tem de ser inferior a hora final!</span>';
        header('Location: criar_torneio.php');
        exit();
    }
    for($i=0; $i < count($dias_escolhidos); $i++){
        if(!in_array($dias_escolhidos[$i], $dias_semana)){
            unset($_SESSION['dias_input']);
            $_SESSION['error_log'] = '<span style="color:red">Dia '.$dias_escolhidos[$i].' inválido! (eg. Segunda,Quarta,Sexta)</span>';
            header('Location: criar_torneio.php');
            exit();
        }
    }

    require_once "connect.php";
	$connection = @new mysqli($host, $db_user, $db_password, $db_name);

	if ($connection->connect_errno!=0)
	{
		echo "Error: ".$connection->connect_errno;
	}
    else
    {
        $result_campos = $connection->query("SELECT campo.nome FROM campo");

        $campos_existentes = array();

        while($row = mysqli_fetch_assoc($result_campos)){
            $campos_existentes[] = $row['nome'];
        }

        for($i=0; $i < count($campos_escolhidos); $i++){
            if(!in_array($campos_escolhidos[$i], $campos_existentes)){
                unset($_SESSION['campos_input']);
                $_SESSION['error_log'] = '<span style="color:red">Campo '.$campos_escolhidos[$i].' inválido! (eg. campo1,campo2,campo3)</span>';
                header('Location: criar_torneio.php');
                exit();
            }
        }

        // inserir torneio na tabela torneio
        $sql_torneio = $connection->query("INSERT INTO torneio(nome, dias, horas, data_inicio, data_fim, inicio_torneio, utilizador_username) VALUES ('$nome_torneio', '$dias', '$horas','$data_inicio', '$data_fim', 0, '$username')");
        try{
            if ($sql_torneio === FALSE){
                $_SESSION['error_log'] = "Error: " . $sql_torneio . "<br>" . mysqli_error($connection);
                header('Location: criar_torneio.php');
            }
        } catch(Exception $e){
            echo $e->getMessage();
        }

        for($i=0; $i<count($campos_escolhidos); $i++){
            $sql_torneio_campo = $connection->query("INSERT INTO campo_torneio VALUES ('$campos_escolhidos[$i]','$nome_torneio')");
            try{
                if ($sql_torneio_campo === FALSE){
                    $_SESSION['error_log'] = "Error: " . $sql_torneio_campo . "<br>" . mysqli_error($connection);
                    header('Location: criar_torneio.php');
                }
                else{
                    unset($_SESSION['nome_input']);
                    unset($_SESSION['data_inicio_input']);
                    unset($_SESSION['data_fim_input']);
                    unset($_SESSION['hora_inicio_input']);
                    unset($_SESSION['hora_fim_input']);
                    unset($_SESSION['dias_input']);
                    unset($_SESSION['campos_input']);
                    header('Location: principal.php');
                }
            } catch(Exception $e){
                echo $e->getMessage();
            }
        }

    }
?>