<?php
  session_start();
	if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true)){
        header('Location: principal.php');
        exit();
	}
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

  <center><div class="container-registo">
    <form action="registo_to_database.php" method="post">
      <input class="text_login" type="text" placeholder="Username" name="input_user_username" align = "center" value="<?php if(isset($_SESSION['username']))echo $_SESSION['username']?>"/>
      <input class="text_login" type="password" placeholder="Password" name="input_user_password1" align = "center" value="<?php if(isset($_SESSION['password1']))echo $_SESSION['password1']?>"/>
      <input class="text_login" type="password" placeholder="Repetir password" name="input_user_password2" align = "center" value="<?php if(isset($_SESSION['password2']))echo $_SESSION['password2']?>"/>
      <input class="text_login" type="text" placeholder="Email" name="input_user_email" align = "center" value="<?php if(isset($_SESSION['email']))echo $_SESSION['email']?>"/>
      <input class="text_login" type="text" placeholder="Primerio nome" name="input_user_prinome" align = "center" value="<?php if(isset($_SESSION['prinome']))echo $_SESSION['prinome']?>"/>
      <input class="text_login" type="text" placeholder="Último nome" name="input_user_ultnome" align = "center" value="<?php if(isset($_SESSION['ultnome']))echo $_SESSION['ultnome']?>"/>
      <input class="text_login" type="text" placeholder="Número cartão cidadão" name="input_user_cc" align = "center"/ value="<?php if(isset($_SESSION['cc']))echo $_SESSION['cc']?>">
      <?php
        if(isset($_SESSION['error_log'])){
      		echo $_SESSION['error_log'];
      		unset($_SESSION['error_log']);
    	}
    	?>
      <input class="submit_login" type="submit" value="REGISTAR" align = "center" style="width:100%"/>
    </form>

    <form action="inicial.php" method="post">
  		<input class="submit_login" type="submit" value="VOLTAR" align="center" style="width:100%"/>
  	</form>
  </div></center>
</body>
</html>
