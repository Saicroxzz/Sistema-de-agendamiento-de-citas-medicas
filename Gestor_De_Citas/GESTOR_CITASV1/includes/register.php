<?php
require_once '../includes/config.php'; // Incluye la configuración de la base de datos

// Verificar si los datos del formulario se enviaron
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $genero = $_POST['genero'];
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);

    // Validación del correo institucionals
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@unitropico\.edu\.co$/", $correo)) {
        die("Error: El correo debe ser del dominio '@unitropico.edu.co'"); // poner fina institucional
    }

    // Validación de campos vacíos
    if (empty($nombre) || empty($apellido) || empty($telefono) || empty($correo) || empty($password)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Hash de la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Rol predeterminado para el usuario
    $rol = 'usuario';

    // Preparar la consulta para insertar el usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, genero, telefono, correo, password, rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Verificar si la preparación de la consulta falló
    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }

    // Asociar los parámetros a la consulta
    $stmt->bind_param("sssssss", $nombre, $apellido, $genero, $telefono, $correo, $hashed_password, $rol);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página de login después de un registro exitoso
        header("Location: ../includes/login.php");
        exit();
    } else {
        echo "Error al registrar usuario: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Error: Solicitud inválida.";
}
?>
