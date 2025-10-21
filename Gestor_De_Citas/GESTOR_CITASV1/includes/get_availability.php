<?php
require 'config.php';

$query = "SELECT fecha, hora_inicio FROM disponibilidad_citas WHERE disponible = 1";
$result = $conn->query($query);

$availability = [];
while ($row = $result->fetch_assoc()) {
    $row['fecha'] = strftime('%A %d de %B %Y', strtotime($row['fecha']));
    $availability[] = $row;
}

header('Content-Type: application/json');
echo json_encode($availability);
?>
