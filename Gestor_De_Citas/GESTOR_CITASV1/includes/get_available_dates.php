<?php
require 'config.php';

$query = "SELECT DISTINCT fecha FROM disponibilidad_citas WHERE disponible = 1";
$result = $conn->query($query);

$dates = [];
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['fecha'];
}

header('Content-Type: application/json');
echo json_encode($dates);
?>
