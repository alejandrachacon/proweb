<?php
include_once dirname(__FILE__) . "/../config.php";

date_default_timezone_set('America/Bogota');

$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

if (mysqli_connect_errno())
{
  echo "Error en la conexión: " . mysqli_connect_error();
  exit();
}



function searchl($search_term)
{
	//variable usada para especificar cuántos resultados mostrar por página.
	$RESULTS_LIMIT=10;
	global $con;

	$sql = "SELECT isbn, autor, titulo, editorial, disponibles, total, url_imagen FROM libros WHERE total > 0 AND titulo LIKE '%".$search_term."%' ";
	$results = mysqli_query($con,$sql);
			 
	return $results;

}

function fullsearchl($titulo,$autor,$editorial,$disponibles){

	global $con;

	if($disponibles == 'si'){

		$sql = "SELECT isbn, autor, titulo, editorial, disponibles, total, url_imagen FROM libros WHERE (titulo LIKE '%".$titulo."%') AND (autor LIKE '%".$autor."%') AND (editorial LIKE '%".$editorial."%') AND (disponibles > 0 )";
	}
	else{

		$sql = "SELECT isbn, autor, titulo, editorial, disponibles, total, url_imagen FROM libros WHERE (titulo LIKE '%".$titulo."%') AND (autor LIKE '%".$autor."%') AND (editorial LIKE '%".$editorial."%') AND (disponibles = 0)";
	}


	$results = mysqli_query($con,$sql);

	return $results;


}



?>