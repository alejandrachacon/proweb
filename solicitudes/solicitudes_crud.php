<?php
include_once dirname(__FILE__) . "/../config.php";

date_default_timezone_set('America/Bogota');

$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}

// SELECT de todas las solicitudes que no se han aprobado/declinado (pendientes).
// Estas solicitudes tienen el estado de "solicitado"
function get_solicitudes_pendientes()
{
  global $con;
  $sql = "SELECT * FROM `solicitudes` WHERE estado='solicitado'";
  
  $result = mysqli_query($con, $sql);

  //mysqli_close($con);
  return $result;
}

// Retorna el equipo mas cercano a vencer
function get_equipo_mascercano_vencer($nombre)
{
  global $con;
  $sql = "SELECT * FROM `solicitudes` WHERE estado='prestado' AND `equipos_nombre`='$nombre' ORDER BY `fecha_vencimiento` ASC";

  $result = mysqli_query($con, $sql);
  if (mysqli_num_rows($result) <= 0)
  {
    return false;
  }

  $row = mysqli_fetch_array($result); // Tomar el primer resultado, que por el order by es el mas cercano a vencer
  return $row;
}

// Retorna el sala mas cercano a vencer
function get_sala_mascercano_vencer($nombre)
{
  global $con;
  $sql = "SELECT * FROM `solicitudes` WHERE estado <> 'rechazado' AND estado <> 'devuelto' AND estado <> 'solicitado' AND `sala_nombre`='$nombre' ORDER BY `fecha_vencimiento` ASC";

  $result = mysqli_query($con, $sql);
  if (!$result)
  {
    return false;
  }
  if (mysqli_num_rows($result) <= 0)
  {
    return false;
  }

  $row = mysqli_fetch_array($result); // Tomar el primer resultado, que por el order by es el mas cercano a vencer
  return $row;
}

// Retorna el libro mas cercano a vencer
function get_libro_mascercano_vencer($nombre)
{
  global $con;
  $sql = "SELECT * FROM `solicitudes` WHERE estado='prestado' AND `libros_isbn`='$nombre' ORDER BY `fecha_vencimiento` ASC";

  $result = mysqli_query($con, $sql);
  if (mysqli_num_rows($result) <= 0)
  {
    return false;
  }

  $row = mysqli_fetch_array($result); // Tomar el primer resultado, que por el order by es el mas cercano a vencer
  return $row;
}

// Retorna una solicitud dado su id, si no encuentra la solicitud retorna false
function get_solicitud_id($idSolicitud)
{
  global $con;
  $sql = "SELECT * FROM `solicitudes` WHERE estado='solicitado' AND id=$idSolicitud ORDER BY fecha_prestamo ASC";
  
  $result = mysqli_query($con, $sql);

  if (mysqli_num_rows($result) <= 0)
  {
    return false;
  }

  $row = mysqli_fetch_array($result); // Tomar el primer resultado
  
  return $row;
}

// Aprueba la solicitud de un equipo y actualiza el total de disponibles a disponibles-1
function aprobar_equipo($idSolicitud, $usuario, $tipo, $fechaVencimiento, $nombre, $reporteCada)
{
  global $con;
  
  $updateDisponibles = "UPDATE `equipos` SET `disponibles`=disponibles-1 WHERE `disponibles` > 0 AND `nombre`='$nombre' LIMIT 1";
  if (!mysqli_query($con, $updateDisponibles))
  {
    return false;
  }

  $updateSolicitud = "UPDATE `solicitudes` SET `estado`='prestado', `fecha_vencimiento`='$fechaVencimiento' WHERE id=$idSolicitud AND `equipos_nombre`='". $nombre . "' LIMIT 1";
  if (!mysqli_query($con, $updateSolicitud))
  {
    return false;
  }

  return true;
}

// Aprueba la solicitud de un libro y actualiza el total de disponibles a disponibles-1
function aprobar_libro($idSolicitud, $usuario, $tipo, $fechaVencimiento, $isbn, $reporteCada)
{
  global $con;
  
  $updateDisponibles = "UPDATE `libros` SET `disponibles`=disponibles-1 WHERE `disponibles` > 0 AND `isbn`='$isbn' LIMIT 1";
  if (!mysqli_query($con, $updateDisponibles))
  {
    return false;
  }

  $updateSolicitud = "UPDATE `solicitudes` SET `estado`='prestado', `fecha_vencimiento`='$fechaVencimiento' WHERE id=$idSolicitud LIMIT 1";
  if (!mysqli_query($con, $updateSolicitud))
  {
    return false;
  }

  return true;
}

// Aprueba la solicitud de una sala y actualiza el total de disponibles a disponibles-1
function aprobar_sala($idSolicitud, $usuario, $tipo, $fechaVencimiento, $nombre)
{
  global $con;
  
  $updateDisponibles = "UPDATE `sala` SET `disponible`=0 WHERE `disponible`=1 AND `nombre`='$nombre' LIMIT 1";
  if (!mysqli_query($con, $updateDisponibles))
  {
    return false;
  }

  $updateSolicitud = "UPDATE `solicitudes` SET `estado`='prestado', `fecha_vencimiento`='$fechaVencimiento' WHERE id=$idSolicitud AND `sala_nombre`='". $nombre . "' LIMIT 1";
  if (!mysqli_query($con, $updateSolicitud))
  {
    return false;
  }

  return true;
}

// Declina la solicitud de un equipo
function declinar_solicitud($idSolicitud)
{
  global $con;

  $updateSolicitud = "UPDATE `solicitudes` SET `estado`='rechazado' WHERE id=$idSolicitud LIMIT 1";
  if (!mysqli_query($con, $updateSolicitud))
  {
    return false;
  }

  return true;
}

// Busca todos los items que un usuario tiene prestado (eg. no tienen el estado "devuelto" o )
function get_prestados_usuario($usuario, $tipo)
{
  global $con;

  $sql = "SELECT * FROM `solicitudes` WHERE estado <> 'devuelto' AND estado <> 'rechazado' AND estado <> 'solicitado' AND usuario='$usuario' AND tipo='$tipo' ORDER BY fecha_prestamo ASC";
  
  $result = mysqli_query($con, $sql);

  if (!$result)
  {
    return false;
  }

  return $result;
}

//$solicitud = buscar_solicitud($_POST['usuario'], $_POST['tipo'], $_POST['item']);
function buscar_solicitud($usuario, $tipo, $item)
{
  global $con;
  $sql = "";
  if ($tipo == 'equipo')
  {
    $sql = "SELECT * FROM solicitudes WHERE usuario='$usuario' AND tipo='$tipo' AND equipos_nombre='$item' AND estado <> 'rechazado' AND estado <> 'devuelto'";
  }
  else if ($tipo == 'libro')
  {
    $sql = "SELECT * FROM solicitudes WHERE usuario='$usuario' AND tipo='$tipo' AND libros_isbn='$item'";
  }
  else if ($tipo == 'sala')
  {
    $sql = "SELECT * FROM solicitudes WHERE usuario='$usuario' AND tipo='$tipo' AND sala_nombre='$item'";
  }

  $result = mysqli_query($con, $sql);
  if (!$result)
  {
    return false;
  }

  $row = mysqli_fetch_array($result);
  return $row;
}

function get_libro_disponible($isbn)
{
  global $con;
  $sql = "SELECT isbn FROM libros WHERE disponibles > 0 AND isbn='$isbn'";
  
  $result = mysqli_query($con, $sql);

  // Sino encuentra nada retornar false
  if (mysqli_num_rows($result) <= 0)
  {
    return false;
  }

  $row = mysqli_fetch_array($result); // Coger el primer resultado

  return $row;
}

function solicitar_libro($usuario, $isbn, $fechaPrestamo)
{
  global $con;
  $disponible = get_libro_disponible($isbn);

  // Si no encontró ningun libro disponible, retornar
  if (!$disponible)
  {
    return false;
  }

  $fechaPrestamo .= ":00";
  $sql = "INSERT INTO `solicitudes`(`usuario`, `libros_isbn`, `tipo`, `fecha_prestamo`,`estado`) VALUES ('$usuario', '$isbn', 'libro', '$fechaPrestamo', 'solicitado')";
  
  if (!mysqli_query($con, $sql))
  {
    return false;
  }

  return true;
}

?>