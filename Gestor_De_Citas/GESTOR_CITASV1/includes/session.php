<?php
$host = 'localhost';   // O la dirección de tu servidor MySQL
$user = 'root';        // Usuario de MySQL
$password = '';        // Contraseña de MySQL
$database = 'gestor_citas'; // Nombre de la base de datos

$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Error en la conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
?>