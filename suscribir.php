<?php
session_start();

    include_once dirname(__FILE__) . "/eventos/eventos_crud.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="topnav">
  <?php
    if (!isset($_SESSION['rol']))
    {
      echo '<a href="index.php">Inicio</a>';
    }
    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin')
    {
        echo '<a href="mensajes.php">Mensajes</a>';
        echo '<a href="reportes.php">Reportes</a>';
    }
  ?>
  <a class="active" href="eventos.php">Eventos</a>
  <a href="salas.php">Salas</a>
  <a href="equipos.php">Equipos</a>
  <a href="libros.php">Libros</a>
  <?php
    if (isset($_SESSION['rol']))
    {
      echo '<a href="logout.php">Log out</a>';
    }
  ?>
</div>

<div style="padding-left:16px">
	<br>
	<br>
<br>

  <table class="w3-table-all w3-centered">
  <tr>
    <th>Id</th>
    <th>Nombre</th>
    <th>Información</th>
    <th>Suscribir</th>

  </tr>
  <?php

    $html = "";

        $row = buscar_evento($_GET['id']); // buscar todos los eventos vigentes
      

        $html .= "<tr>";
        $html .= "<td>" . $row['id'] . "</td>";
        $html .= "<td>" . $row['nombre'] . "</td>";
        $html .= "<td>" . $row['informacion'] . "</td>";

        if (isset($_SESSION['rol']) ) // Solo mostrar el botón de suscribir cuando el usuario tiene sesion iniciada
        {
          $html .= "<td>" . "<a href='/eventos/suscribir.php?id=" . $row['id']."'>Suscribir</a>" . "</td>";
        }
        else
        {
          $html .= "<td></td>";
        }
        
        $html .= "</tr>";
      
    

    echo $html;
  ?>  
  </table>
  <br>

  <br><br>
</div>

</body>
</html>