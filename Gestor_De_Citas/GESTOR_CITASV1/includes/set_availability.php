<?php
session_start();
require '../includes/config.php';

// Verificar si el usuario es un doctor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    echo "Acceso denegado";
    exit();
}

$doctor_id = $_SESSION['user_id'];  // Obtener el ID del doctor desde la sesión
$fecha = $_POST['fecha'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];

// Insertar los horarios en la base de datos para el doctor
$query = $conn->prepare("INSERT INTO disponibilidad_citas (doctor_id, fecha, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)");
$query->bind_param("isss", $doctor_id, $fecha, $hora_inicio, $hora_fin);

if ($query->execute()) {
    echo "Horario habilitado con éxito";
} else {
    echo "Error al habilitar el horario";
}
?>
