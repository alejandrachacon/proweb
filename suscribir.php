<?php
session_start();

    include_once dirname(__FILE__) . "/eventos/eventos_crud.php";
    include_once dirname(__FILE__) . "/eventos/suscribir_crud.php";

    if(isset($_GET['subscribe']) && $_GET['subscribe']=='true')
    {
       if(crear_suscripcion($_SESSION['usuario'],$_GET['id'])){

          $msg = "<span style='color: green'>".$_SESSION['usuario']." ya estás inscrito en el evento! </span>";

       }
       else{
          $msg = "";
       }
    }
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
        echo $msg;
        $row = buscar_evento($_GET['id']); // buscar todos los eventos vigentes
      

        $html .= "<tr>";
        $html .= "<td>" . $row['id'] . "</td>";
        $html .= "<td>" . $row['nombre'] . "</td>";
        $html .= "<td>" . $row['informacion'] . "</td>";

        if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin') // Solo mostrar el botón de suscribir cuando el usuario tiene sesion iniciada
        {
          $html .= "<td>" . "<a href='suscribir.php?id=" . $row['id']."&subscribe=true'>Suscribir</a>" . "</td>";
        }
        else
        {
          $html .= "<td> Inicie sesión para suscribir </td>";
        }
        if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin') // Solo mostrar botón de actualizar/eliminar al admin
        {
          $html .= "<td>" . '<a href="actualizar.php?tipo=evento&id=' . $row['id'] . '">Actualizar</a>' . "</td>";
          $html .= "<td>" . '<a href="eliminar.php?tipo=evento&id=' . $row['id'] . '">Eliminar</a>' . "</td>";
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