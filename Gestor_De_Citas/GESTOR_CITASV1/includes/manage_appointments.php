<?php
include('config.php');

// Verificar si se enviaron los datos del formulario
if (isset($_POST['id']) && isset($_POST['action'])) {
    $appointment_id = $_POST['id'];
    $action = $_POST['action'];

    // Cambiar el estado de la cita según la acción (aceptar o rechazar)
    if ($action === 'aceptar') {
        $estado = 'completada';
        $razon_cancelacion = null;  // No hay razón de cancelación al aceptar
    } elseif ($action === 'rechazar') {
        $estado = 'cancelada';
        $razon_cancelacion = isset($_POST['razon_cancelacion']) ? $_POST['razon_cancelacion'] : null;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida.']);
        exit();
    }

    // Actualizar el estado de la cita en la tabla `citas`
    $query = $conn->prepare("UPDATE citas SET estado = ?, razon_cancelacion = ? WHERE id = ?");
    $query->bind_param('ssi', $estado, $razon_cancelacion, $appointment_id);

    if ($query->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Cita $estado correctamente."]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al gestionar la cita.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
}
?>
