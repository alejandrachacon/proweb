<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
body {margin:0;}

.topnav {
  overflow: hidden;
  background-color: #333;
}

.topnav a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
    background-color: #4CAF50;
    color: white;
}
</style>
</head>
<body>


<div class="topnav">
  <a class="active" href="index.php">Perfil</a>
  <a href="listado.php">Listado</a>
</div>

<?php
      
?>

<div style="padding-left:16px">
	<br>
	<br>
   	<div class="w3-card-4">
	  <div class="w3-container w3-green">
	    <?php
          echo "<h2> Bienvenido ".$_COOKIE["usuario"]." </h2>"
      ?>
   </div>
</div>
</div>

</body>
</html>