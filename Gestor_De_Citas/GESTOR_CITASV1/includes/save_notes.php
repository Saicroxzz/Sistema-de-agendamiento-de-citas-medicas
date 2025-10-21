<?php
include('config.php');

// Verificar si se enviaron los datos del formulario
if (isset($_POST['appointment_id']) && isset($_POST['notes'])) {
    $appointment_id = $_POST['appointment_id'];
    $notes = $_POST['notes'];

    // Actualizar la tabla `citas` para guardar las notas del doctor
    $query = $conn->prepare("UPDATE citas SET notas_doctor = ? WHERE id = ?");
    $query->bind_param('si', $notes, $appointment_id);

    if ($query->execute()) {
        echo "Notas guardadas correctamente.";
    } else {
        echo "Error al guardar las notas.";
    }
} else {
    echo "Datos incompletos.";
}
?>
