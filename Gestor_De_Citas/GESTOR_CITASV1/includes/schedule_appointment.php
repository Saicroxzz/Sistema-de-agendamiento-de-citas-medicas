<?php
session_start();
require '../includes/config.php';

if (!isset($_SESSION['user_id'], $_POST['date'], $_POST['time'])) {
    echo "Error: Información faltante.";
    exit();
}

$user_id = $_SESSION['user_id'];
$date = $_POST['date'];
$time = $_POST['time'];

// Verificar si la hora sigue disponible
$check_query = "SELECT * FROM disponibilidad_citas WHERE fecha = ? AND hora_inicio = ? AND disponible = 1";
$stmt = $conn->prepare($check_query);
$stmt->bind_param('ss', $date, $time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Error: La hora seleccionada ya no está disponible.";
    exit();
}

// Insertar cita en la base de datos
$insert_query = "INSERT INTO citas (usuario_id, fecha, hora_inicio, estado) VALUES (?, ?, ?, 'pendiente')";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param('iss', $user_id, $date, $time);

if ($stmt->execute()) {
    // Marcar la hora como no disponible
    $update_query = "UPDATE disponibilidad_citas SET disponible = 0 WHERE fecha = ? AND hora_inicio = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ss', $date, $time);
    $update_stmt->execute();

    echo "Cita agendada con éxito.";
} else {
    echo "Error al agendar la cita: " . $conn->error;
}
?>
