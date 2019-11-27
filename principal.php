<?php
  session_start();
	/*if (!(isset($_SESSION['logged'])) && !($_SESSION['logged']==true)){
        header('Location: login.php');
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
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Torneios</title>
  <link rel="stylesheet" type="text/css" href="./css/style.css">
  <style>
    #torneios-table, #minha-equipa-table{
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
    }

    #torneios-table td, #torneios-table th, #minha-equipa-table td, #minha-equipa-table th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #torneios-table tr:nth-child(even), #minha-equipa-table tr:nth-child(even) {background-color: #f2f2f2;}

    #torneios-table tr:hover, #minha-equipa-table  tr:hover {background-color: #ddd;}

    #torneios-table th, #minha-equipa-table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #008CBA;
        color: white;
        
    }


  </style>
  <script src="./scripts/projeto.js"></script>
</head>

<body>
  <div class="container-inicial">
    <div class="input-container">
      <h1 class="titulo">MENU PRINCIPAL</h1>
      <input class="submit_inicial minhas_informações" type="button" value="MINHAS INFORMAÇÕES" onclick="location.replace('minhas_informações.php');" />
      <?php
        require_once "connect.php";
        
        $connection = @new mysqli($host, $db_user, $db_password, $db_name);
		    if ($connection->connect_errno != 0)
		    {
			    throw new Exception(mysqli_connect_errno());
        }

        $result_admin = $connection->query("SELECT username 
                                            FROM utilizador
                                            WHERE estado='1' 
                                            AND username='".$username."'");

        $num_of_results_admin = mysqli_num_rows($result_admin);
        
        if($num_of_results_admin>0){
          echo '<input class="submit_inicial gerir_utilizadores" type="button" value="GERIR UTILIZADORES" onclick="location.replace("gerir_utilizadores.php");" >';
        }

      ?>
      <input class="submit_inicial ver_torneios" type="button" value="VER TORNEIOS" onclick="location.replace('informações_torneios_logged.php');" />
    </div>
  </div>

  <?php
      require_once "connect.php";

      $connection = @new mysqli($host, $db_user, $db_password, $db_name);
      if ($connection->connect_errno != 0){
      throw new Exception(mysqli_connect_errno());
      }

      $result_equipa = $connection->query("SELECT equipa.nome, equipa.num_jogadores, equipa.capitao, jogador.estatuto
                                           FROM jogador, equipa
                                           WHERE equipa.nome=jogador.equipa_nome
                                           AND jogador.utilizador_username='".$username."'");
    
      $num_of_result_equipa = mysqli_num_rows($result_equipa);
      
      $nome=" ";
      
      if($num_of_result_equipa>0){
        
        echo '<h2><b class="titulo-lista">MINHA EQUIPA</b></h2>
          <table id="minha-equipa-table">
              <tr>
                <th>Minha Equipa</th>
                <th>Número de Jogadores</th>
                <th>Capitão</th>
                <th>Meu Estatuto</th>
                <th>Ver Equipa</th>
              </tr>   
              <tr><td></td></tr>';
              
        $row = mysqli_fetch_assoc($result_equipa);

        $nome = $row['nome'];
        $num_jogadores = $row['num_jogadores'];
        $capitao = $row['capitao'];
        $estatuto = $row['estatuto'];

          echo'<tr>
          <td>'.$nome.'</td>
          <td>'.$num_jogadores.'</td>
          <td>'.$capitao.'</td>
          <td>'.$estatuto.'</td>
          <td><input class="submit_inicial ver_equipas" type="button" value="VER EQUIPA" align="center" style="width:100%" onclick="gotoVerEquipa(this)"></td>
          </tr>';

        echo '</table>';

      }
      else {
        echo '<table id="minha-equipa-table">
                <tr>
                  <th>Minha Equipa</th>
                  <th>Número de Jogadores</th>
                  <th>Capitão</th>
                  <th>Meu Estatuto</th>
                  <th>Ver Equipa</th>
                </tr>   
                <tr><td></td><td></td><td></td><td></td><td></td></tr>
              </table>';
      }
      
      $result_torneios_gestor = $connection->query("SELECT t1.inicio_torneio, upper(t1.nome) as nome, (SELECT COUNT(torneio_equipa.torneio_nome) FROM torneio_equipa, (SELECT torneio.nome FROM torneio WHERE torneio.utilizador_username='$username') meu_torneio
                                                                                                       WHERE torneio_equipa.torneio_nome = meu_torneio.nome) as num, t1.data_inicio, t1.data_fim, t1.dias, t1.horas, b1.campos
                                                    FROM torneio t1, (SELECT torneio_nome, GROUP_CONCAT(nome SEPARATOR ', ') campos FROM campo_torneio ct, campo c WHERE ct.campo_nome = c.nome GROUP BY torneio_nome) b1
                                                    WHERE b1.torneio_nome = t1.nome AND t1.utilizador_username = '$username'");
                                            
                                              
      $result_torneios = $connection->query("SELECT t2.inicio_torneio, upper(t2.nome) as nome, a2.num, t2.data_inicio, t2.data_fim, t2.dias, t2.horas, b2.campos
                                             FROM torneio t2, (SELECT COUNT(equipa_nome) num, equipa_nome, torneio_nome FROM torneio_equipa GROUP BY torneio_nome) a2,
                                                  (SELECT torneio_nome, GROUP_CONCAT(nome SEPARATOR ', ') campos FROM campo_torneio ct, campo c WHERE ct.campo_nome = c.nome GROUP BY torneio_nome) b2,
                                                  (SELECT equipa_nome FROM jogador WHERE jogador.utilizador_username like '$username') j2
                                             WHERE t2.nome = a2.torneio_nome AND b2.torneio_nome = t2.nome AND j2.equipa_nome = a2.equipa_nome");                                                                            
      
      $num_of_results_torneios_gestor = mysqli_num_rows($result_torneios_gestor);
      $num_of_results_torneios = mysqli_num_rows($result_torneios);
      
      if($num_of_results_torneios>0){
        
        echo '<h2><b class="titulo-lista">MEU TORNEIO</b></h2>
            <table id="torneios-table">
              <tr>
                <th>Estado</th>
                <th>Meu Torneio</th>
                <th>N equipas</th>
                <th>Inicio</th>
                <th>Fim</th>
                <th>Dias da semana</th>
                <th>Horas</th>
                <th>Campos</th>
                <th>Mais</th>
              </tr>   
              <tr><td></td></tr>';
              
        for ($i = 1; $i <= $num_of_results_torneios; $i++){
          
          $row = mysqli_fetch_assoc($result_torneios);

          if($row['inicio_torneio'] == 1){
            $inicio = 'A decorrer';
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
          <td>'.$inicio.'</td>
          <td>'.$nome.'</td>
          <td>'.$num.'</td>
          <td>'.$d_inicio.'</td>
          <td>'.$d_fim.'</td>
          <td>'.$dias.'</td>
          <td>'.$horas.'</td>
          <td>'.$campos.'</td>
          <td><input class="submit_inicial ver_equipas" type="button" value="VER TORNEIO" align="center" style="width:100%" onclick="gotoMeuTorneio(this)"></td>
          </tr>';
        }

        echo '</table>';

      }
      else {
        echo '<table id="torneios-table">
                <tr>
                  <th>Estado</th>
                  <th>Meu Torneio</th>
                  <th>N equipas</th>
                  <th>Inicio</th>
                  <th>Fim</th>
                  <th>Dias da semana</th>
                  <th>Horas</th>
                  <th>Campos</th>
                  <th>Mais</th>
                </tr>   
                <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
              </table>';
      }

      if($num_of_results_torneios_gestor>0){
        
        echo '<h2><b class="titulo-lista">TORNEIOS A GERIR</b></h2>
        <table id="torneios-table">
              <tr>
                <th>Estado</th>
                <th>Meu Torneio</th>
                <th>N equipas</th>
                <th>Inicio</th>
                <th>Fim</th>
                <th>Dias da semana</th>
                <th>Horas</th>
                <th>Campos</th>
                <th>Mais</th>
              </tr>   
              <tr><td></td></tr>';
              
        for ($i = 1; $i <= $num_of_results_torneios_gestor; $i++){
          
          $row = mysqli_fetch_assoc($result_torneios_gestor);

          if($row['inicio_torneio'] == 1){
            $inicio = 'A decorrer';
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
          <td>'.$inicio.'</td>
          <td>'.$nome.'</td>
          <td>'.$num.'</td>
          <td>'.$d_inicio.'</td>
          <td>'.$d_fim.'</td>
          <td>'.$dias.'</td>
          <td>'.$horas.'</td>
          <td>'.$campos.'</td>
          <td><input class="submit_inicial ver_equipas" type="button" value="VER TORNEIO" align="center" style="width:100%" onclick="gotoMeuTorneio(this)"></td>
          </tr>';
        }

        echo '</table>';

      }
      else {
        echo '<table id="torneios-table">
                <tr>
                  <th>Estado</th>
                  <th>Meu Torneio</th>
                  <th>N equipas</th>
                  <th>Inicio</th>
                  <th>Fim</th>
                  <th>Dias da semana</th>
                  <th>Horas</th>
                  <th>Campos</th>
                  <th>Mais</th>
                </tr>   
                <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
              </table>';
      }

    ?>

  <center><div>
    <input class="submit_inicial ver_equipas" type="button" value="CRIAR TORNEIO" align="center" style="width:40%" onclick="location.replace('criar_torneio.php');" />
    <form action="logout.php" method="post">
      <input class="submit_inicial" type="submit" value="LOGOUT" align = "center"/>
    </form>
  </div></center>
</body>
</html>