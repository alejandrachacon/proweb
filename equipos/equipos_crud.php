<?php
include_once dirname(__FILE__) . "/../config.php";

date_default_timezone_set('America/Bogota');

$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}

// SELECT de todos los equipos disponibles
function get_equipos()
{
  global $con;
  $sql = "SELECT nombre, fabricante, url_imagen, COUNT(*) as disponibles FROM equipos WHERE disponible = 1 GROUP BY fabricante, nombre";
  
  $result = mysqli_query($con, $sql);

  mysqli_close($con);
  return $result;
}

// Selecciona el primer equipo disponible con el fabricante y nombre (referencia) especificados
// Return: numero de serie del equipo o false si no hay ninguno disponible
function get_equipo_disponible($fabricante, $nombre)
{
  global $con;
  $sql = "SELECT serie FROM equipos WHERE disponible = 1 AND fabricante = '$fabricante' AND nombre = '$nombre'";
  
  $result = mysqli_query($con, $sql);

  // Sino encuentra nada retornar false
  if (mysqli_num_rows($result) <= 0)
  {
    return false;
  }

  $row = mysqli_fetch_array($result); // Coger el primer resultado

  return $row;
}

// Registrar una solicitud para pedir un equipo
function solicitar_equipo($usuario, $fabricante, $nombre, $fechaPrestamo)
{
  global $con;
  $serie = get_equipo_disponible($fabricante, $nombre);

  // Si no encontró ningun equipo disponible, retornar
  if (!$serie)
  {
    return false;
  }

  $fechaPrestamo .= ":00" ;
  $sql = "INSERT INTO `solicitudes`(`usuario`, `equipos_serie`, `tipo`, `fecha_prestamo`,`estado`) VALUES ('$usuario', " . $serie['serie'] . ", 'equipo', '$fechaPrestamo', 'solicitado')";
  
  if (!mysqli_query($con, $sql))
  {
    echo "insert error<br>";
    echo mysqli_error($con);
    return false;
  }


  /*$update = "UPDATE equipos SET disponible = 0 WHERE serie = " . $serie['serie'] . " LIMIT 1";

  if (!mysqli_query($con, $update))
  {
    echo "update error<br>";
    echo mysqli_error($con);
    return false;
  }*/

  return true;

}

?>