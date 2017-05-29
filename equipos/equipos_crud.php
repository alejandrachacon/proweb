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
  $sql = "SELECT nombre, fabricante, disponibles, total, url_imagen FROM equipos";
  
  $result = mysqli_query($con, $sql);

  //mysqli_close($con);
  return $result;
}

// Selecciona el primer equipo disponible con el fabricante y nombre (referencia) especificados
// Return: numero de serie del equipo o false si no hay ninguno disponible
function get_equipo_disponible($fabricante, $nombre)
{
  global $con;
  $sql = "SELECT serie FROM equipos WHERE disponibles > 0 AND fabricante = '$fabricante' AND nombre = '$nombre'";
  
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
// TODO: mover a solicitudes/solicitudes_crud.php
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
  $sql = "INSERT INTO `solicitudes`(`usuario`, `equipos_nombre`, `tipo`, `fecha_prestamo`,`estado`) VALUES ('$usuario', '" . $nombre . "', 'equipo', '$fechaPrestamo', 'solicitado')";
  
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;

}

function crear_equipo($fabricante, $nombre, $serie, $disponibles, $fileName)
{
  global $con;
  $sql = "INSERT INTO `equipos`(`nombre`, `serie`, `fabricante`, `disponibles`, `total`, `url_imagen`) VALUES ('$nombre', $serie, '$fabricante', $disponibles, $disponibles, '$fileName')";
  
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;
}

?>