<?php
include_once dirname(__FILE__) . "/../config.php";

date_default_timezone_set('America/Bogota');

$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}

// Generar reporte
function crear_reporte($solicitud, $estado, $comentario)
{
  global $con;
  
  // Buscar informacion de la solicitud
  $sql = "SELECT estado, tipo, equipos_nombre, libros_isbn, sala_nombre FROM  solicitudes WHERE id=$solicitud";
  $tipo = "";
  $equipo = null;
  $libro = null;
  $sala = null;

  if ($result=mysqli_query($con, $sql))
  {
    $row = mysqli_fetch_array($result);
    // Si el ultimo estado era deteriorado entonces no se puede devolver  
    if ($row['estado'] == 'deteriorado' && $estado == 'devuelto')
    {
      return 'deteriorado';
    }
    
    $tipo = $row['tipo'];
    $equipo = $row['equipos_nombre'];
    $libro = $row['libros_isbn'];
    $sala = $row['sala_nombre'];
  }

  // Insertar el nuevo reporte
  $sql = "INSERT INTO `reportes`(`solicitudes_id`, `estado`, `comentarios`) VALUES ($solicitud, '$estado', '$comentario')";
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  // Actualizar el estado de la solicitud
  $sql = "UPDATE `solicitudes` SET `estado`='$estado' WHERE `id`=$solicitud LIMIT 1";
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  // Actualizar la cantidad de disponibles si se devolvio satisfactoriamente
  if ($estado == 'devuelto')
  {
    if ($tipo == 'equipo')
    {
      $sql = "UPDATE equipos SET disponibles=disponibles+1 WHERE nombre='$equipo'";
    }
    else if ($tipo == 'libro')
    {
      $sql = "UPDATE libros SET disponibles=disponibles+1 WHERE isbn='$libro'";
    }
    
    if (!mysqli_query($con, $sql))
    {
      return false;
    }
  }

  return true;
}

?>