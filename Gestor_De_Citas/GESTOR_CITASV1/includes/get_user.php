<?php
session_start();
require_once '../includes/config.php';

// Conexión a la base de datos
$mysqli = new mysqli($host, $user, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Obtener usuarios
$query = "SELECT id, nombre, correo, rol FROM usuarios";
$result = $mysqli->query($query);

if (!$result) {
    die("Error: " . $mysqli->error);
}

// Generar HTML para la tabla de usuarios
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
    echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
    echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
    echo "<td>";
    echo "<button class='btn btn-primary btn-sm edit-user-btn' data-toggle='modal' data-target='#editUserModal'
            data-id='" . htmlspecialchars($row['id']) . "'
            data-nombre='" . htmlspecialchars($row['nombre']) . "'
            data-correo='" . htmlspecialchars($row['correo']) . "'
            data-rol='" . htmlspecialchars($row['rol']) . "'
            style='background-color: #046D4D;'>Editar</button>";
    echo "<button class='btn btn-danger btn-sm delete-user-btn ml-2' data-id='" . htmlspecialchars($row['id']) . "' style='background-color: #d9534f;'>Eliminar</button>";
    echo "</td>";
    echo "</tr>";
}

$mysqli->close();
?>
