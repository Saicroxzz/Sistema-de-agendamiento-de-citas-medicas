<?php
require_once 'includes/config.php';

// Datos de prueba
$email = 'admin1@example.com';
$password = 'brownandy'; // Contraseña en texto plano
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = 'administrador'; // O 'usuario'
$nombre = 'Tomy';
$apellido = 'Shelby';

// Preparar y ejecutar la consulta
$stmt = $conn->prepare("INSERT INTO usuarios (correo, password, rol, nombre, apellido) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('sssss', $email, $hashed_password, $role, $nombre, $apellido);
$stmt->execute();

if ($stmt->error) {
    echo "Error: " . $stmt->error;
} else {
    echo "Usuario insertado correctamente.";
}

$stmt->close();
$conn->close();
?>
