<?php
require 'config.php';

// Verifica si el parámetro 'date' está presente en la solicitud
if (isset($_GET['date'])) {
    $fecha = $_GET['date'];

    // Consulta para obtener las horas disponibles en la fecha seleccionada
    $query = "SELECT hora_inicio FROM disponibilidad_citas WHERE fecha = ? AND disponible = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $fecha);
    $stmt->execute();
    $result = $stmt->get_result();

    $horas_disponibles = [];

    // Recolecta las horas disponibles
    while ($row = $result->fetch_assoc()) {
        $horas_disponibles[] = ['hora_inicio' => $row['hora_inicio']];
    }

    // Configura el tipo de contenido a JSON
    header('Content-Type: application/json');

    // Devuelve las horas disponibles en formato JSON
    echo json_encode($horas_disponibles);
} else {
    // Si no se proporciona una fecha, devuelve un error
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'No se ha proporcionado una fecha.']);
}
?>
