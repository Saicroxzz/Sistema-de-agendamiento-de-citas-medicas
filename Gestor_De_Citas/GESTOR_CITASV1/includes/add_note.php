<?php
session_start();
require '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cita_id = $_POST['cita_id'];
    $nota = mysqli_real_escape_string($conn, $_POST['note']);
    
    // Verifica que no esté vacío
    if (!empty($nota)) {
        // Inserta la nota en la tabla de notas o actualiza según sea el caso (asegúrate de tener una tabla de notas)
        $query = "INSERT INTO notas (cita_id, nota) VALUES ('$cita_id', '$nota')
                  ON DUPLICATE KEY UPDATE nota = '$nota'";
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Nota guardada correctamente.";
        } else {
            $_SESSION['message'] = "Error al guardar la nota.";
        }
    } else {
        $_SESSION['message'] = "La nota no puede estar vacía.";
    }
}

header('Location: consultorio.php');
exit();
?>
