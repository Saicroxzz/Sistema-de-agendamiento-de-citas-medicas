<?php
require 'config.php'; // Asegúrate de requerir la conexión a la base de datos si es necesario

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['fecha']) && isset($_POST['hora_inicio']) && isset($_POST['hora_fin']) && isset($_POST['disponible'])) {
        $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
        $fecha = htmlspecialchars($_POST['fecha'], ENT_QUOTES, 'UTF-8');
        $hora_inicio = htmlspecialchars($_POST['hora_inicio'], ENT_QUOTES, 'UTF-8');
        $hora_fin = htmlspecialchars($_POST['hora_fin'], ENT_QUOTES, 'UTF-8');
        $disponible = htmlspecialchars($_POST['disponible'], ENT_QUOTES, 'UTF-8');

        // Prepara la consulta SQL
        $query = $conn->prepare("UPDATE disponibilidad_citas SET fecha = ?, hora_inicio = ?, hora_fin = ?, disponible = ? WHERE id = ?");
        $query->bind_param('ssssi', $fecha, $hora_inicio, $hora_fin, $disponible, $id);

        // Ejecuta la consulta
        if ($query->execute()) {
            // Si es exitoso, devuelve una respuesta JSON
            echo json_encode(['status' => 'success']);
        } else {
            // Si falla, devuelve una respuesta de error
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar disponibilidad']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Solicitud inválida']);
}
?>
