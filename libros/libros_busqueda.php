<?php
include_once dirname(__FILE__) . "/../config.php";

date_default_timezone_set('America/Bogota');

$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}



function search($search_term)
{
	//variable usada para especificar cuántos resultados mostrar por página.
	$RESULTS_LIMIT=10;

		global $con;

		  
			 $sql = "SELECT isbn,titulo, autor,editorial, paginas, disponibles,total, url_imagen FROM libros WHERE (isbn LIKE '%".$search_term."%' OR titulo LIKE '%".$search_term."%' OR disponibles LIKE '%".$search_term."%' OR editorial LIKE '%".$search_term."%'  ) ";
			 $results = mysqli_query($con,$sql);
			 
		return $results;

}



?>