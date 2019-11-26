<?php
  session_start();
	/*if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true)){
        header('Location: principal.php');
        exit();
	}*/
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Torneios</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <br /><br />
    <center><div class="container-login">
    	<form action="login_to_database.php" method="post">
    		<input class="text_login" type="text" placeholder="Username" name="input_user_username" align = "center" value="<?php if(isset($_SESSION['user_password']))echo $_SESSION['user_username']?>"/>
        <input class="text_login" type="password" placeholder="Password" name="input_user_password" align="center" value="<?php if(isset($_SESSION['user_password']))echo $_SESSION['user_password']?>"/>

        <br /><?php
          if(isset($_SESSION['error_log'])){
        		echo $_SESSION['error_log'];
        		unset($_SESSION['error_log']);
      	}?><br />

    		<input class="submit_login" type="submit" value="LOGIN" align="center" style="width:100%"/>
    	</form>
      <form action="registo.php" method="post">
        <input class="submit_login" type="submit" value="REGISTO" align="center" style="width:100%"/>
      </form>
      <form action="inicial.php" method="post">
    		<input class="submit_login" type="submit" value="VOLTAR" align="center" style="width:100%"/>
    	</form>
    </div></center>

</body>
</html>
