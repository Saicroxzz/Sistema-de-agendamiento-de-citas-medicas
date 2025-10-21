<?php
session_start();
require_once '../includes/config.php';

// Conexión a la base de datos
$mysqli = new mysqli($host, $user, $password, $dbname);

if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $mysqli->connect_error]);
    exit;
}

// Obtener datos del formulario
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$rol = $_POST['rol'];

// Validar datos
if (empty($id) || empty($nombre) || empty($correo) || empty($rol)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit;
}

// Preparar y ejecutar la consulta
$query = "UPDATE usuarios SET nombre = ?, correo = ?, rol = ? WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('sssi', $nombre, $correo, $rol, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el usuario: ' . $stmt->error]);
}

$stmt->close();
$mysqli->close();
?>
