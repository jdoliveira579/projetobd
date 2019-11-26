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
  <style>
    #torneios-table {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 98%;
    }

    #torneios-table td, #torneios-table th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #torneios-table tr:nth-child(even){background-color: #f2f2f2;}

    #torneios-table tr:hover {background-color: #ddd;}

    #torneios-table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #008CBA;
        color: white;
    }
  </style>
</head>

<body>
  <div class="container-inicial">
      <div class="input-container">
        <h1 class="titulo">PROJETO</h1>
        <form action="login.php" method="post">
          <div>
              <input class="submit_inicial login_inicial" type="submit" value="LOGIN" align="center"/>
          </div>
        </form>

        <form action="registo.php" method="post">
          <div>
              <input class="submit_inicial registo_inicial" type="submit" value="REGISTAR" align="center"/>
          </div>
        </form>
      </div>
  </div>

  <div class="container-inicial">
      <div class="input-container">
        <form action="torneios.php" method="post">
          <div>
              <input class="submit_inicial button_normal" type="submit" value="TORNEIOS" align="center"/>
          </div>
        </form>

        <form action="equipas.php" method="post">
          <div>
              <input class="submit_inicial button_normal" type="submit" value="EQUIPAS" align="center"/>
          </div>
        </form>
      </div>
  </div>

  <div class="container-instrucoes">
    <ul>
      <li class="titulo-lista">Como participar num torneio?</li>
      <h6>asadasdasdas</h6>
      <li class="titulo-lista">Como se inscrever?</li>
    </ul>
  </div>
</body>
</html>
