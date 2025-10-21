<?php
require_once '../includes/config.php';

if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];

    // Consulta para obtener las horas disponibles de la fecha seleccionada
    $sql = "SELECT hora_inicio, hora_fin FROM disponibilidad_citas WHERE fecha = ? AND disponible = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $fecha);
    $stmt->execute();
    $result = $stmt->get_result();
    $horas_disponibles = $result->fetch_all(MYSQLI_ASSOC);

    // Retornar los resultados como JSON
    echo json_encode($horas_disponibles);
    exit();
} else {
    echo json_encode([]);
}
