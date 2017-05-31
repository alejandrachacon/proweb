<?php
include_once dirname(__FILE__) . "/../config.php";

date_default_timezone_set('America/Bogota');

$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}

// SELECT de todos las suscripciones
function get_suscripciones()
{
  global $con;
  $sql = "SELECT usuario, eventos_id FROM suscripciones ";
  
  $result = mysqli_query($con, $sql);

  return $result;
}

function crear_suscripcion($usuario, $eventos_id)
{
  global $con;
  $sql = "INSERT INTO `suscripciones`(`usuario`, `eventos_id`) VALUES ('$usuario', '$eventos_id')";
  
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;
}


?>