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
    #torneios-table, #info-table{
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 98%;
    }
    #torneios-table td, #torneios-table th {
        border: 1px solid #ddd;
        padding: 8px;
    }
    #info-table td, #info-table th {
      padding: 2px;
      color: #008CBA;
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
        <h1 class="titulo">TORNEIOS</h1>
        <form action="inicial.php" method="post">
          <div>
              <input class="submit_inicial registo_inicial" type="submit" value="VOLTAR" align="center"/>
          </div>
        </form>
      </div>
  </div>

  <table id="torneios-table">
      <tr>
        <th>Inicio</th>
        <th>Fim</th>
        <th>Estado</th>
        <th>Nome</th>
        <th>N equipas</th>
        <th>Dias da semana</th>
        <th>Horas</th>
        <th>Campo</th>
        <th></th>
      </tr>

      <tr>
          <td>
              <?php
                  require_once "connect.php";

                  $connection = @new mysqli($host, $db_user, $db_password, $db_name);
                  if ($connection->connect_errno != 0){
                			throw new Exception(mysqli_connect_errno());
              		}

                  $result = $connection->query("SELECT t.inicio_torneio, upper(t.nome) as nome, a.num, t.data_inicio, t.data_fim, t.dias, t.horas, b.campos
                                  FROM torneio t, (SELECT COUNT(equipa_nome) num, torneio_nome FROM torneio_equipa GROUP BY torneio_nome) a,
                                  (SELECT torneio_nome, GROUP_CONCAT(nome SEPARATOR ', ') campos FROM campo_torneio ct, campo c WHERE ct.campo_nome = c.nome GROUP BY torneio_nome) b
                                  WHERE t.nome = a.torneio_nome and b.torneio_nome = t.nome ORDER BY t.data_inicio, t.data_fim, t.inicio_torneio");

                  $num_of_results = mysqli_num_rows($result);

                  if($num_of_results>0){
                        for ($i = 1; $i <= $num_of_results; $i++){
                          $row = mysqli_fetch_assoc($result);

                          if($row['inicio_torneio'] == 0){
                            $inicio = 'Pronto';
                          }
                          else{
                            $inicio = 'Em espera';
                          }
                		      $nome = $row['nome'];
                          $num = $row['num'];
                		      $d_inicio = $row['data_inicio'];
                		      $d_fim = $row['data_fim'];
                          $dias = $row['dias'];
                          $horas = $row['horas'];
                          $campos = $row['campos'];

                          echo'<tr>
                            <td>'.$d_inicio.'</td>
                            <td>'.$d_fim.'</td>
                            <td>'.$inicio.'</td>
                            <td>'.$nome.'</td>
                            <td>'.$num.'</td>
                            <td>'.$dias.'</td>
                            <td>'.$horas.'</td>
                            <td>'.$campos.'</td>
                            <td><form action="vertorneio.php?torneio='.$nome.'" method="post">
                              <input class="submit_inicial ver_equipas" type="submit" value="VER MAIS" align="center"/>
                            </form></td>
                            </tr>';
                        }

                      echo '</table>';

                    }
                    else
                    {
                        echo "Não há torneios";
                    }

                ?>
          </td>
      </tr>

</body>
</html>
