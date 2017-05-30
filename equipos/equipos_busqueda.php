<?php
include_once dirname(__FILE__) . "/../config.php";

date_default_timezone_set('America/Bogota');

$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}



function searche($search_term)
{
	//variable usada para especificar cuántos resultados mostrar por página.
	$RESULTS_LIMIT=10;

		global $con;

		  
			 //$sql = "SELECT nombre, fabricante, disponibles, total, url_imagen FROM equipos WHERE (fabricante LIKE '%".$search_term."%' OR nombre LIKE '%".$search_term."%' OR disponibles LIKE '%".$search_term."%'  ) ";

			 // La busqueda basica solo busca por nombre
			 $sql = "SELECT nombre, fabricante, disponibles, total, url_imagen FROM equipos WHERE total > 0 AND nombre LIKE '%" . $search_term . "%'";
			 $results = mysqli_query($con,$sql);
			 
		return $results;

}

function fullsearche($nombre,$fabricante,$disponibles){

	global $con ;

	$sql = "SELECT nombre, fabricante, disponibles, total, url_imagen FROM equipos WHERE (nombre LIKE '%$nombre%') AND (fabricante LIKE '%$fabricante%') AND (disponibles LIKE '%$disponibles%')";

	$results = mysqli_query($con,$sql);

	return $results;


}



?>