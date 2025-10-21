<?php
// includes/config.php

$host = 'localhost';
$dbname = 'gestor_citas';
$user = 'root';  // Cambia si tienes otro usuario
$password = '';  // Cambia si tienes otra contraseña

// Conectar a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>