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
    #torneios-table{
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        padding-right: 10px;
        float: left;
        width: 100%;
    }
    #info-table, #jogos-table{
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }
    #classificacao-table{
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      float: left;
      width: 100%;
    }
    #torneios-table td, #torneios-table th, #classificacao-table td, #classificacao-table th, #jogos-table td, #jogos-table th{
        border: 1px solid #ddd;
        padding: 8px;
    }
    #info-table td, #info-table th {
      padding: 2px;
      color: #008CBA;
    }
    #torneios-table tr:nth-child(odd), #classificacao-table tr:nth-child(odd), #jogos-table tr:nth-child(odd){
      background-color: #f2f2f2;
    }

    #torneios-table th, #classificacao-table th, #jogos-table th{
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #008CBA;
        color: white;
    }
    .container {
        width: 98%;
        height: 50px;
        padding: 10px;
    }
    .one {
        width: 47%;
        height: 50px;
        float: left;
    }
    .two {
        margin-left: 55%;
        width: 45%;
        height: 50px;
    }
    .three {
        width: 100%;
        float: left;
    }
  </style>
</head>

<body>
  <div class="container-inicial">
      <div class="input-container">
        <?php
          $torneio = $_SERVER['QUERY_STRING'];
          $torneio = strtoupper(substr($torneio, 8));
          echo '<h1 class="titulo">'.$torneio.'</h1>';

          require_once "connect.php";

          $connection = @new mysqli($host, $db_user, $db_password, $db_name);
          if ($connection->connect_errno != 0){
              throw new Exception(mysqli_connect_errno());
          }

          $result_torneio = $connection->query("SELECT t.inicio_torneio, t.data_inicio, t.data_fim, t.dias, t.horas, b.campos
                          FROM torneio t, (SELECT torneio_nome, GROUP_CONCAT(nome SEPARATOR ', ') campos FROM campo_torneio ct, campo c WHERE ct.campo_nome = c.nome GROUP BY torneio_nome) b
                          WHERE b.torneio_nome = t.nome and t.nome = '".$torneio."';");

          $row = mysqli_fetch_assoc($result_torneio);

          $d_inicio = $row['data_inicio'];
          $d_fim = $row['data_fim'];
          $dias = $row['dias'];
          $horas = $row['horas'];
          $campos = $row['campos'];


        ?>
        <table id="info-table">
            <tr>
              <td><b>Data inicio:</b> <?php echo ''.$d_inicio.''?> </td>
            </tr>
            <tr>
              <td><b>Data fim:</b> <?php echo ''.$d_fim.''?> </td>
            </tr>
            <tr>
              <td><b>Dias de jogo:</b> <?php echo ''.$dias.''?> </td>
            </tr>
            <tr>
              <td><b>Horas de jogo:</b> <?php echo ''.$horas.''?> </td>
            </tr>
            <tr>
              <td><b>Campos:</b> <?php echo ''.$campos.''?></td>
            </tr>
        </table>
        <form action="torneios.php" method="post">
          <div>
              <input class="submit_inicial registo_inicial" type="submit" value="VOLTAR" align="center"/>
          </div>
        </form>
      </div>
  </div>

  <section class="container">
    <div class="one">
      <h1 class="titulo-lista">Equipas</h1>
      <table id="torneios-table">
          <tr>
            <th>Nome equipa</th>
            <th>Número jogadores</th>
          </tr>

          <tr>
              <td>
                  <?php
                      $result_equipas = $connection->query("SELECT e.nome, a.num FROM equipa e, torneio_equipa t,
                                                          (SELECT COUNT(utilizador_username) num, equipa_nome FROM jogador GROUP BY equipa_nome) a
                                                          WHERE e.nome = a.equipa_nome and e.nome = t.equipa_nome and t.torneio_nome = '".$torneio."';");

                      $num_of_results = mysqli_num_rows($result_equipas);

                      if($num_of_results>0){
                            for ($i = 1; $i <= $num_of_results; $i++){
                              $row = mysqli_fetch_assoc($result_equipas);

                    		      $nome = $row['nome'];
                              $num = $row['num'];

                              echo'<tr>
                                <td>'.$nome.'</td>
                                <td>'.$num.'</td>
                                </tr>';
                            }
                        }
                        else
                        {
                            echo "Não há equipas inscritas";
                        }

                    ?>
              </td>
          </tr>
        </table>
    </div>
    <div class="two">
      <h1 class="titulo-lista">Classificação</h1>
      <table id="classificacao-table">
          <tr>
            <th>Posição</th>
            <th>Equipa</th>
            <th>V</th>
            <th>E</th>
            <th>D</th>
            <th>GM</th>
            <th>GS</th>
            <th>P</th>
          </tr>
          <tr>
              <td>
                  <?php
                      $result = $connection->query("SELECT equipa_nome, vitorias, derrotas, empates, golos_marcados, golos_sofridos, pontos FROM classificacao c, torneio t
                        WHERE c.torneio_nome = t.nome AND t.nome = '".$torneio."' ORDER BY c.pontos, c.golos_marcados, c.golos_sofridos;");


                        $num_of_results = mysqli_num_rows($result);

                        if($num_of_results>0){
                              for ($i = 1; $i <= $num_of_results; $i++){
                                $row = mysqli_fetch_assoc($result);

                      		      $nome = $row['equipa_nome'];
                                $gm = $row['golos_marcados'];
                                $gs = $row['golos_sofridos'];
                                $v = $row['vitorias'];
                                $d = $row['derrotas'];
                                $e = $row['empates'];
                                $pontos = $row['pontos'];

                                echo'<tr>
                                  <td>'.$i.'</td>
                                  <td>'.$nome.'</td>
                                  <td>'.$v.'</td>
                                  <td>'.$e.'</td>
                                  <td>'.$d.'</td>
                                  <td>'.$gm.'</td>
                                  <td>'.$gs.'</td>
                                  <td>'.$pontos.'</td>
                                  </tr>';
                              }
                          }
                          else
                          {
                              echo "Não há equipas inscritas";
                          }

                      ?>
                </td>
            </tr>
          </table>
    </div>
  </section>

  <section class="container">
    <div class="three">
        <h1 class="titulo-lista">Jogos</h1>
        <table id="jogos-table">
            <tr>
              <th>Data</th>
              <th>Campo</th>
              <th>Equipa 1</th>
              <th>Resultado</th>
              <th>Equipa 2</th>
            </tr>
            <tr>
                <td>
                    <?php
                          $result_jogos = $connection->query("SELECT jogo_data.equipa1 as equipa1, jogo_data.equipa2 as equipa2, jogo_data.golos1 as golos1,
                                                              jogo_data.golos2 as golos2, jogo_data.data_dia_hora as data_dia_hora, jogo_data.campo_nome as campo
                                                              FROM jogo_data
                                                              WHERE jogo_data.torneio_nome='".$torneio."'
                                                              ORDER BY jogo_data.data_dia_hora");

                          $num_of_results = mysqli_num_rows($result_jogos);

                          if($num_of_results>0){
                                for ($i = 1; $i <= $num_of_results; $i++){
                                  $row = mysqli_fetch_assoc($result_jogos);

                        		      $equipa1 = $row['equipa1'];
                                  $equipa2 = $row['equipa2'];
                                  $golos = sprintf("%s - %s", $row['golos1'], $row['golos2']);
                      		        $data_dia_hora = $row['data_dia_hora'];
                      		        $campo = $row['campo'];

                                  echo'<tr>
                                      <td>'.$data_dia_hora.'</td>
                                      <td>'.$campo.'</td>
                                      <td>'.$equipa1.'</td>
                                      <td>'.$golos.'</td>
                                      <td>'.$equipa2.'</td>
                                    </tr>';
                                }
                            }
                            else
                            {
                                echo "Não há equipas inscritas";
                            }

                        ?>
                  </td>
              </tr>
            </table>
          </div>
      </section>
</body>
</html>
