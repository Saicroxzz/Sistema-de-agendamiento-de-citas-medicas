<?php
include('config.php');

// Obtener la fecha enviada por el método GET
if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];

    // Consulta para obtener los horarios disponibles de la tabla `disponibilidad_citas`
    $query = $conn->prepare("SELECT hora_inicio, hora_fin FROM disponibilidad_citas WHERE fecha = ? AND disponible = 1");
    $query->bind_param('s', $fecha);
    $query->execute();
    $result = $query->get_result();

    $horarios = array();
    while ($row = $result->fetch_assoc()) {
        $horarios[] = array(
            'hora_inicio' => $row['hora_inicio'],
            'hora_fin' => $row['hora_fin']
        );
    }

    // Devolver los horarios disponibles en formato JSON
    echo json_encode($horarios);
}
?>
