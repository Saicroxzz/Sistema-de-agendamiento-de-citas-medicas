<?php
session_start();
require '../includes/config.php';

header('Content-Type: application/json');

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para realizar esta acción.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : '';
    $razon_cancelacion = isset($_POST['razon_cancelacion']) ? trim($_POST['razon_cancelacion']) : '';

    // Verifica si se han recibido los datos correctamente
    if (empty($appointment_id) || empty($razon_cancelacion)) {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
        exit();
    }

    // Obtener el ID del usuario autenticado
    $user_id = $_SESSION['user_id'];

    // Verificar si la cita pertenece al usuario
    $query = "SELECT id FROM citas WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $appointment_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo json_encode(['status' => 'error', 'message' => 'No se encontró la cita o no tienes permiso para cancelarla.']);
        exit();
    }

    // Actualizar el estado de la cita a 'cancelada' y guardar la razón
    $update_query = "UPDATE citas SET estado = 'cancelada', razon_cancelacion = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $razon_cancelacion, $appointment_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Cita cancelada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al cancelar la cita. Por favor, inténtalo de nuevo.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
}

$conn->close();
?>
