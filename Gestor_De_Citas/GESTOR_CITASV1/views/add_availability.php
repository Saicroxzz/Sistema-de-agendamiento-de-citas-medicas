<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=gestor_citas;charset=utf8mb4', 'root', ''); // Ajusta 'usuario' y 'contraseña'
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'] ?? '';
    $hora_inicio = $_POST['hora_inicio'] ?? '';
    $hora_fin = $_POST['hora_fin'] ?? '';

    if (empty($fecha) || empty($hora_inicio) || empty($hora_fin)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos.']);
        exit;
    }

    // Verifica que no exista disponibilidad para la misma fecha y hora
    $stmt = $pdo->prepare("SELECT * FROM disponibilidad_citas WHERE fecha = ? AND hora_inicio = ? AND hora_fin = ?");
    $stmt->execute([$fecha, $hora_inicio, $hora_fin]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Ya existe una disponibilidad para esta fecha y hora.']);
        exit;
    }

    // Insertar disponibilidad
    $stmt = $pdo->prepare("INSERT INTO disponibilidad_citas (fecha, hora_inicio, hora_fin, disponible) VALUES (?, ?, ?, 1)");
    if ($stmt->execute([$fecha, $hora_inicio, $hora_fin])) {
        echo json_encode(['status' => 'success', 'message' => 'Disponibilidad agregada con éxito.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al agregar disponibilidad.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>
