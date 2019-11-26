<?php
    session_start();

    if( ($_POST['input_user_username'] == "") || ($_POST['input_user_password1'] == "") || ($_POST['input_user_password2'] == "")
    || ($_POST['input_user_email'] == "") || ($_POST['input_user_prinome'] == "") || ($_POST['input_user_ultnome'] == "")
    || ($_POST['input_user_cc'] == "")) {

  		$_SESSION['error_log'] = '<span style="color:red">Preencha todos os campos!</span>';
  		header('Location: registo.php');
  		exit();
  	}

    require_once "connect.php";
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno!=0){
  		echo "Error: ".$connection->connect_errno;
  	}
  	else{
        $username = $_POST['input_user_username'];
    		$password1 = $_POST['input_user_password1'];
        $password2 = $_POST['input_user_password2'];
        $email = $_POST['input_user_email'];
        $prinome = $_POST['input_user_prinome'];
        $ultnome = $_POST['input_user_ultnome'];
        $cc = $_POST['input_user_cc'];

        //save insterted data para não perder tudo só por se enganar
        $_SESSION['username'] = $_POST['input_user_username'];
        $_SESSION['password1'] = $_POST['input_user_password1'];
        $_SESSION['password2'] = $_POST['input_user_password2'];
        $_SESSION['email'] = $_POST['input_user_email'];
        $_SESSION['prinome'] = $_POST['input_user_prinome'];
        $_SESSION['ultnome'] = $_POST['input_user_ultnome'];
        $_SESSION['cc'] = $_POST['input_user_cc'];

        //verificar se os dados inseridos são válido
        if($_SERVER["REQUEST_METHOD"] == "POST"){
          //validation flag
          $flag_everything_OK = true;
          //length of login
          if ((strlen($username)<4) || (strlen($username)>20)){
            $flag_everything_OK = false;
            unset($_SESSION['username']);
            $_SESSION['error_log'] = '<span style="color:red">Username tem de ter entre 5 e 20 caracteres!</span>';
            header('Location: registo.php');
        		exit();
          }
          if (ctype_alnum($username) == false){
            $flag_everything_OK = false;
            unset($_SESSION['username']);
            $_SESSION['error_log'] = '<span style="color:red">Username só pode ter letras e números!</span>';
            header('Location: registo.php');
        		exit();
          }
          $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
          if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB!=$email)){
            $flag_everything_OK = false;
            unset($_SESSION['email']);
            $_SESSION['error_log'] = '<span style="color:red">Endereço de email incorreto!</span>';
            header('Location: registo.php');
        		exit();
          }
          //check password
          if ((strlen($password1)<6) || (strlen($password1)>20)){
            $flag_everything_OK = false;
            unset($_SESSION['password1']); unset($_SESSION['password2']);
            $_SESSION['error_log'] = '<span style="color:red">Password tem de ter entre 6 e 20 caracteres!</span>';
            header('Location: registo.php');
        		exit();
          }
          if ($password1 != $password2){
            $flag_everything_OK = false;
            unset($_SESSION['password1']); unset($_SESSION['password2']);
            $_SESSION['error_log'] = '<span style="color:red">Passwords não são iguais!</span>';
            header('Location: registo.php');
        		exit();
          }
          //$password_hash = password_hash($password1, PASSWORD_DEFAULT);
          $password_hash = $password1;
          //check name
          if ((strlen($prinome)<2)){
            $flag_everything_OK = false;
            unset($_SESSION['prinome']);
            $_SESSION['error_log'] = '<span style="color:red">Insira primeiro nome válido!</span>';
            header('Location: registo.php');
        		exit();
          }
          //check surname
          if ((strlen($ultnome)<2)){
            $flag_everything_OK = false;
            unset($_SESSION['ultnome']);
            $_SESSION['error_log'] = '<span style="color:red">Insira último nome válido!</span>';
            header('Location: registo.php');
        		exit();
          }
          //check cc
          if ((strlen($cc) == 9)){
            $flag_everything_OK = false;
            //unset($_SESSION['cc']);
            $_SESSION['error_log'] = '<span style="color:red">Cartão de cidadão deve ter 8 números!</span>';
            header('Location: registo.php');
        		exit();
          }
        }
        //check if username taken
        $result = $connection->query("SELECT username FROM utilizador WHERE username ='$username'");
        if (!$result) throw new Exception($connection->error);
          $count_logins = $result->num_rows;
          if($count_logins > 0){
            $flag_everything_OK = false;
            unset($_SESSION['username']);
            $_SESSION['error_log'] = '<span style="color:red">Username já utilizado!</span>';
            header('Location: registo.php');
        		exit();
          }
        //check if email exists
        $result = $connection->query("SELECT username FROM utilizador WHERE email ='$email'");
        if (!$result) throw new Exception($connection->error);
          $count_emails = $result->num_rows;
          if($count_emails > 0){
            $flag_everything_OK = false;
            unset($_SESSION['email']);
            $_SESSION['error_log'] = '<span style="color:red">Email já utilizado!</span>';
            header('Location: registo.php');
        		exit();
          }
        //check if username taken
        $result = $connection->query("SELECT username FROM utilizador WHERE cc ='$cc'");
        if (!$result) throw new Exception($connection->error);
          $count_ccs = $result->num_rows;
          if($count_ccs > 0){
            $flag_everything_OK = false;
            unset($_SESSION['cc']);
            $_SESSION['error_log'] = '<span style="color:red">Cartão de cidadão já utilizado!</span>';
            header('Location: registo.php');
        		exit();
          }
        //check se é o primeiro user
        $estado = 0;
        $result = $connection->query("SELECT username FROM utilizador");
        if (!$result) throw new Exception($connection->error);
          $count_usernames = $result->num_rows;
          if($count_usernames == 0){
            $estado = 1;
          }

        if ($flag_everything_OK == true){
          //Adicionar a base de dados
          $cc = (int)$cc;
          $saldo = 0.0;
          $sql = "INSERT INTO utilizador(username, password, cc, primeiro_nome, ultimo_nome, email, estado, saldo) VALUES ('$username','$password_hash',$cc,'$prinome','$ultnome','$email',$estado,$saldo)";
          try{
            if ($connection->query($sql) === TRUE){
              $_SESSION['logged'] = true;
              //limpar session
              unset($_SESSION['username']);
              unset($_SESSION['password1']);
              unset($_SESSION['password2']);
              unset($_SESSION['email']);
              unset($_SESSION['prinome']);
              unset($_SESSION['ultnome']);
              unset($_SESSION['cc']);
              header('Location: principal.php');
            }
            else{
              $_SESSION['error_log'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
              header('Location: registo.php');
            }
          } catch(Exception $e){
              echo $e->getMessage();
          }
        }
        $connection->close();
      }
?>
