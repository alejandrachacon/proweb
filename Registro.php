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
  <a class="active" href="index.php">Inicio</a>
  <a href="eventos.php">Eventos</a>
  <a href="salas.php">Salas</a>
  <a href="equipos.php">Equipos</a>
  <a href="libros.php">Libros</a>
</div>

<div style="padding-left:16px">
	<br>
	<br>
   	<div class="w3-card-4">
	  <div class="w3-container w3-green">
	    <h2>Registrese en la plataforma</h2>
	  </div>
	  <form class="w3-container" action="/proweb/index.php">
	    <p>      
	    <label class="w3-text-green"><b>Nombre de Usuario</b></label>
	    <input class="w3-input w3-border w3-sand" name="username" type="text" required>
      </p>
      <p>      
      <label class="w3-text-green"><b>Email</b></label>
      <input class="w3-input w3-border w3-sand" name="email" type="text" required>
      </p>
      <p>      
      <label class="w3-text-green"><b>Confirmar Email</b></label>
      <input class="w3-input w3-border w3-sand" name="emailconfirm" type="text" required>
      </p>
	    <p>      
	    <label class="w3-text-green"><b>Contraseña</b></label>
	    <input class="w3-input w3-border w3-sand" name="password" type="text" required></p>
	    <p>
	    <button class="w3-btn w3-green" type="submit">Registrarse</button>
	    </p>
	  </form>
   </div>
</div>

</body>
</html>