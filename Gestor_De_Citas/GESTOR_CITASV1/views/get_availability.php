<?php
session_start();
require_once '../includes/config.php';

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'administrador') {
    http_response_code(403);
    exit();
}

// Conexión a la base de datos
$mysqli = new mysqli($host, $user, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Obtener disponibilidad
$query = "SELECT id, fecha, hora_inicio, hora_fin FROM disponibilidad_citas";
$result = $mysqli->query($query);

if (!$result) {
    die("Error: " . $mysqli->error);
}

// Generar tabla de disponibilidad
while ($row = $result->fetch_assoc()):
?>
    <tr>
        <td><?php echo htmlspecialchars($row['id']); ?></td>
        <td><?php echo htmlspecialchars($row['fecha']); ?></td>
        <td><?php echo htmlspecialchars($row['hora_inicio']); ?></td>
        <td><?php echo htmlspecialchars($row['hora_fin']); ?></td>
        <td>
            <button class="btn btn-danger btn-sm delete-availability" data-id="<?php echo htmlspecialchars($row['id']); ?>">Eliminar</button>
        </td>
    </tr>
<?php endwhile; ?>
