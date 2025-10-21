<?php
require '../includes/config.php';

$user_id = $_SESSION['user_id'];

// Obtener citas del usuario
$query = "SELECT id, fecha, hora_inicio, estado FROM citas WHERE usuario_id = ? AND fecha >= CURDATE()";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($appointments);
?>
